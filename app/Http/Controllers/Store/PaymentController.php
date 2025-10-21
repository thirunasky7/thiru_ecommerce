<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use Exception;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    private $phonePeMerchantId;
    private $phonePeSaltKey;
    private $phonePeSaltIndex;
    private $phonePeBaseUrl;

    public function __construct()
    {
        $this->phonePeMerchantId = config('services.phonepe.merchant_id');
        $this->phonePeSaltKey = config('services.phonepe.salt_key');
        $this->phonePeSaltIndex = config('services.phonepe.salt_index');
        $this->phonePeBaseUrl = config('services.phonepe.base_url');
    }

    /**
     * Initiate UPI Payment
     */
    public function initiateUpiPayment(Request $request)
    {
        try {
            $request->validate([
                'upi_id' => 'required|regex:/^[a-zA-Z0-9.\-_]{2,49}@[a-zA-Z]{2,}$/',
                'amount' => 'required|numeric|min:1',
            ]);

            $user = auth()->user();
            $amount = $request->amount;
            $upiId = $request->upi_id;
            $orderId = $request->order_id ?? 'ORD' . time() . Str::random(6);

            // Create pending order
            $order = $this->createPendingOrder($orderId, $amount, $user);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'upi',
                'gateway_reference' => $orderId,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 'pending',
                'upi_id' => $upiId,
                'metadata' => json_encode([
                    'user_id' => $user->id,
                    'upi_id' => $upiId,
                    'initiated_at' => now()->toDateTimeString(),
                ]),
            ]);

            // Generate UPI payment URL
            $paymentUrl = $this->generateUpiPaymentUrl($amount, $orderId, $upiId);

            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'payment_url' => $paymentUrl,
                'message' => 'UPI payment initiated successfully'
            ]);

        } catch (Exception $e) {
            Log::error('UPI Payment Initiation Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate UPI payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate UPI Payment URL
     */
    private function generateUpiPaymentUrl($amount, $orderId, $upiId)
    {
        $merchantVpa = config('services.upi.merchant_vpa', 'your-merchant@upi');
        $merchantName = config('app.name', 'Your Store');
        
        // Standard UPI URL format
        $upiString = "upi://pay?pa={$merchantVpa}&pn=" . urlencode($merchantName) . 
                    "&am={$amount}&tid={$orderId}&tn=" . urlencode("Order Payment - {$orderId}") . 
                    "&cu=INR";
        
        return $upiString;
    }

    /**
     * Initiate PhonePe Payment
     */
    public function initiatePhonePePayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $user = auth()->user();
            $amount = $request->amount * 100; // Convert to paise
            $orderId = 'PP' . time() . Str::random(6);
            $callbackUrl = route('payment.phonepe.callback');
            $redirectUrl = route('payment.phonepe.redirect');

            // Create pending order
            $order = $this->createPendingOrder($orderId, $amount / 100, $user);

            // PhonePe payload
            $payload = [
                "merchantId" => $this->phonePeMerchantId,
                "merchantTransactionId" => $orderId,
                "merchantUserId" => "MUID" . $user->id,
                "amount" => $amount,
                "redirectUrl" => $redirectUrl,
                "redirectMode" => "REDIRECT",
                "callbackUrl" => $callbackUrl,
                "mobileNumber" => $user->phone,
                "paymentInstrument" => [
                    "type" => "PAY_PAGE"
                ]
            ];

            // Encode payload
            $base64Payload = base64_encode(json_encode($payload));
            
            // Generate checksum
            $checksum = hash('sha256', $base64Payload . '/pg/v1/pay' . $this->phonePeSaltKey) . '###' . $this->phonePeSaltIndex;

            // Make API call to PhonePe
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum,
            ])->post($this->phonePeBaseUrl . '/pg/v1/pay', [
                'request' => $base64Payload
            ]);

            $responseData = $response->json();

            if ($response->successful() && $responseData['success']) {
                // Create payment record
                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'phonepe',
                    'gateway_reference' => $orderId,
                    'amount' => $amount / 100,
                    'currency' => 'INR',
                    'status' => 'pending',
                    'metadata' => json_encode([
                        'phonepe_response' => $responseData,
                        'initiated_at' => now()->toDateTimeString(),
                    ]),
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $orderId,
                    'payment_url' => $responseData['data']['instrumentResponse']['redirectInfo']['url'],
                    'message' => 'PhonePe payment initiated successfully'
                ]);
            } else {
                throw new Exception($responseData['message'] ?? 'PhonePe payment initiation failed');
            }

        } catch (Exception $e) {
            Log::error('PhonePe Payment Initiation Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate PhonePe payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initiate Google Pay Payment
     */
    public function initiateGooglePayPayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $user = auth()->user();
            $amount = $request->amount;
            $orderId = 'GP' . time() . Str::random(6);

            // Create pending order
            $order = $this->createPendingOrder($orderId, $amount, $user);

            // For Google Pay, we'll use UPI deep link
            $merchantVpa = config('services.upi.merchant_vpa', 'your-merchant@okicici');
            $merchantName = config('app.name', 'Your Store');
            
            $googlePayUrl = "tez://upi/pay?pa={$merchantVpa}&pn=" . urlencode($merchantName) . 
                           "&am={$amount}&tid={$orderId}&tn=" . urlencode("Order Payment - {$orderId}") . 
                           "&cu=INR";

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'google_pay',
                'gateway_reference' => $orderId,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 'pending',
                'metadata' => json_encode([
                    'user_id' => $user->id,
                    'initiated_at' => now()->toDateTimeString(),
                    'google_pay_url' => $googlePayUrl,
                ]),
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'payment_url' => $googlePayUrl,
                'message' => 'Google Pay payment initiated successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Google Pay Payment Initiation Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate Google Pay payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check Payment Status
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string',
            ]);

            $orderId = $request->order_id;

            // Find payment record
            $payment = Payment::where('gateway_reference', $orderId)->first();

            if (!$payment) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment record not found'
                ]);
            }

            // If payment gateway is PhonePe, check status with their API
            if ($payment->payment_gateway === 'phonepe') {
                return $this->checkPhonePeStatus($payment);
            }

            // For UPI and Google Pay, we simulate status check
            // In production, you would integrate with your payment gateway's status API
            return $this->simulateUpiStatusCheck($payment);

        } catch (Exception $e) {
            Log::error('Payment Status Check Failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to check payment status'
            ], 500);
        }
    }

    /**
     * Check PhonePe Payment Status
     */
    private function checkPhonePeStatus($payment)
    {
        try {
            $orderId = $payment->gateway_reference;
            $checksum = hash('sha256', '/pg/v1/status/' . $this->phonePeMerchantId . '/' . $orderId . $this->phonePeSaltKey) . '###' . $this->phonePeSaltIndex;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum,
                'X-MERCHANT-ID' => $this->phonePeMerchantId,
            ])->get($this->phonePeBaseUrl . '/pg/v1/status/' . $this->phonePeMerchantId . '/' . $orderId);

            $responseData = $response->json();

            if ($response->successful()) {
                $transactionStatus = $responseData['success'] ? 'success' : 'failed';
                
                if ($transactionStatus === 'success') {
                    $this->updatePaymentSuccess($payment, $responseData);
                } else {
                    $this->updatePaymentFailed($payment, $responseData['message'] ?? 'Payment failed');
                }

                return response()->json([
                    'status' => $transactionStatus,
                    'message' => $responseData['message'] ?? 'Payment status updated'
                ]);
            } else {
                throw new Exception('Failed to check PhonePe status');
            }

        } catch (Exception $e) {
            Log::error('PhonePe Status Check Failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'pending',
                'message' => 'Status check in progress'
            ]);
        }
    }

    /**
     * Simulate UPI Status Check (for demo purposes)
     * In production, integrate with your UPI gateway's status API
     */
    private function simulateUpiStatusCheck($payment)
    {
        // Simulate payment processing - in real scenario, check with your payment gateway
        $initiatedTime = strtotime($payment->created_at);
        $currentTime = time();
        $timeDiff = $currentTime - $initiatedTime;

        // Simulate success after 30 seconds for demo
        if ($timeDiff > 30) {
            $this->updatePaymentSuccess($payment, ['simulated' => true]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Payment completed successfully'
            ]);
        }
        // Simulate failure after 60 seconds if still pending
        elseif ($timeDiff > 60) {
            $this->updatePaymentFailed($payment, 'Payment timeout');
            
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment timeout'
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Payment is being processed'
        ]);
    }

    /**
     * PhonePe Callback Handler
     */
    public function handlePhonePeCallback(Request $request)
    {
        try {
            $response = $request->all();
            Log::info('PhonePe Callback Received: ', $response);

            $transactionId = $response['data']['merchantTransactionId'] ?? null;
            $status = $response['success'] ? 'success' : 'failed';

            if (!$transactionId) {
                throw new Exception('Invalid callback data');
            }

            $payment = Payment::where('gateway_reference', $transactionId)->first();

            if (!$payment) {
                throw new Exception('Payment record not found');
            }

            if ($status === 'success') {
                $this->updatePaymentSuccess($payment, $response);
            } else {
                $this->updatePaymentFailed($payment, $response['message'] ?? 'Payment failed');
            }

            return response()->json(['success' => true]);

        } catch (Exception $e) {
            Log::error('PhonePe Callback Handling Failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * PhonePe Redirect Handler
     */
    public function handlePhonePeRedirect(Request $request)
    {
        try {
            $transactionId = $request->merchantTransactionId;
            $payment = Payment::where('gateway_reference', $transactionId)->first();

            if (!$payment) {
                return redirect()->route('payment.failed')->with('error', 'Payment record not found');
            }

            if ($payment->status === 'success') {
                return redirect()->route('order.success')->with('success', 'Payment completed successfully');
            } else {
                return redirect()->route('payment.failed')->with('error', 'Payment failed or is still processing');
            }

        } catch (Exception $e) {
            Log::error('PhonePe Redirect Handling Failed: ' . $e->getMessage());
            return redirect()->route('payment.failed')->with('error', 'Payment processing error');
        }
    }

    /**
     * Update Payment as Successful
     */
    private function updatePaymentSuccess($payment, $gatewayResponse = [])
    {
        DB::transaction(function () use ($payment, $gatewayResponse) {
            // Update payment status
            $payment->update([
                'status' => 'success',
                'gateway_response' => json_encode($gatewayResponse),
                'paid_at' => now(),
            ]);

            // Update order status
            $payment->order->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            // Clear user's cart
            if ($payment->order->user_id) {
                Cart::where('user_id', $payment->order->user_id)->delete();
            }

            // Log the successful payment
            Log::info("Payment successful for Order: {$payment->order->id}, Payment: {$payment->id}");
        });
    }

    /**
     * Update Payment as Failed
     */
    private function updatePaymentFailed($payment, $failureReason = '')
    {
        DB::transaction(function () use ($payment, $failureReason) {
            // Update payment status
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $failureReason,
                'failed_at' => now(),
            ]);

            // Update order status
            $payment->order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            // Log the failed payment
            Log::warning("Payment failed for Order: {$payment->order->id}, Reason: {$failureReason}");
        });
    }

    /**
     * Create Pending Order
     */
    private function createPendingOrder($orderId, $amount, $user)
    {
        // Get cart items
        $cartItems = $this->getCartItems($user);
        
        // Calculate totals
        $subtotal = $cartItems->sum('subtotal');
        $shipping = 0; // Calculate shipping based on address
        $tax = 0; // Calculate tax
        $total = $subtotal + $shipping + $tax;

        // Create order
        $order = Order::create([
            'order_number' => $orderId,
            'user_id' => $user->id,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => json_encode([]), // Add shipping address
            'billing_address' => json_encode([]), // Add billing address
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return $order;
    }

    /**
     * Get Cart Items for User
     */
    private function getCartItems($user)
    {
        // Implement your cart retrieval logic here
        // This should return the cart items with product details
        return collect([]); // Placeholder
    }

    /**
     * Payment Success Page
     */
   public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::with(['order.items.product.translation', 'order.items.product.thumbnail'])
                        ->where('gateway_reference', $orderId)
                        ->first();

        if (!$payment || $payment->status !== 'success') {
            return redirect()->route('payment.failed');
        }

        return view('themes.xylo.payment.success', [
            'payment' => $payment,
            'order' => $payment->order,
        ]);
    }

    /**
     * Payment Failed Page
     */
    public function paymentFailed(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::where('gateway_reference', $orderId)->first();

        return view('themes.xylo.payment.failed', [
            'payment' => $payment,
            'order' => $payment ? $payment->order : null,
        ]);
    }

}