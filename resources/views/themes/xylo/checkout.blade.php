@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
<style>
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .form-control.error, .form-select.error {
        border-color: #dc3545;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    .payment-method {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method:hover {
        border-color: #007bff;
    }
    .payment-method.selected {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .payment-method input[type="radio"] {
        margin-right: 10px;
    }
    .payment-icon {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }
    .payment-details {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    .payment-details.active {
        display: block;
    }
    .card-logos {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .card-logos img {
        height: 25px;
        opacity: 0.6;
    }
    .upi-logos {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .upi-logos img {
        height: 30px;
        border-radius: 5px;
    }
    .qr-code-container {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        margin-top: 15px;
    }
    .qr-code {
        max-width: 200px;
        margin: 0 auto;
    }
    .payment-success {
        color: #28a745;
        font-weight: bold;
    }
    .payment-pending {
        color: #ffc107;
        font-weight: bold;
    }
</style>
@endsection
@section('content')
    @php $currency = activeCurrency(); @endphp
    <section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn productinnerbanner">
        <div class="container h-100">
            <div class="row">
                <div class="col-md-4">
                    <div class="breadcrumbs">
                        <a href="#">Home Page</a> <i class="fa fa-angle-right"></i> <a href="#">Headphone</a> <i
                            class="fa fa-angle-right"></i> checkout
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cart-page pb-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf

                        <div class="shipping_info">
                            <h3 class="cart-heading">Shipping Information</h3>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           name="first_name" placeholder="First Name" 
                                           value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           name="last_name" placeholder="Last Name" 
                                           value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           name="address" placeholder="Address" 
                                           value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" class="form-control @error('suite_floor') is-invalid @enderror" 
                                           name="suite_floor" placeholder="Suit/Floor" 
                                           value="{{ old('suite_floor') }}">
                                    @error('suite_floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <select name="country" class="form-select @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        <option value="india" {{ old('country') == 'india' ? 'selected' : '' }}>India</option>
                                        <option value="usa" {{ old('country') == 'usa' ? 'selected' : '' }}>United States</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           name="city" placeholder="City" 
                                           value="{{ old('city') }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mt-3">
                                    <select name="state" class="form-select @error('state') is-invalid @enderror">
                                        <option value="">Select State</option>
                                        <option value="california" {{ old('state') == 'california' ? 'selected' : '' }}>California</option>
                                        <option value="new_york" {{ old('state') == 'new_york' ? 'selected' : '' }}>New York</option>
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mt-3">
                                    <input type="text" class="form-control @error('zipcode') is-invalid @enderror" 
                                           name="zipcode" placeholder="Zipcode" 
                                           value="{{ old('zipcode') }}">
                                    @error('zipcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>
                                    <input type="checkbox" name="use_as_billing" checked> Use as billing
                                </label>
                            </div>
                        </div>

                        <div class="shipping_info">
                            <h3 class="cart-heading mt-5">Contact Information</h3>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" placeholder="Email" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" placeholder="Phone" 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="shipping_info mt-5">
                            <h3 class="cart-heading">Payment Method</h3>
                            
                            <!-- UPI Payments (PhonePe & Google Pay) -->
                            <div class="payment-method" data-method="upi">
                                <label class="d-flex align-items-center" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="upi"
                                           {{ old('payment_method') == 'upi' ? 'checked' : '' }}>
                                    <i class="fas fa-mobile-alt payment-icon" style="color: #34c759;"></i>
                                    <div>
                                        <strong>UPI Payment</strong>
                                        <p class="mb-0 text-muted">Pay using UPI apps like PhonePe, Google Pay, etc.</p>
                                    </div>
                                </label>
                                <div class="payment-details" id="upi-details">
                                    <div class="upi-logos">
                                        <img src="https://logos-download.com/wp-content/uploads/2021/01/PhonePe_Logo.png" alt="PhonePe">
                                        <img src="https://logos-download.com/wp-content/uploads/2020/06/Google_Pay_Logo.png" alt="Google Pay">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Paytm_logo.png/640px-Paytm_logo.png" alt="Paytm">
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-outline-primary w-100 mb-2" id="openPhonePe">
                                                <i class="fas fa-external-link-alt"></i> Open PhonePe
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-outline-success w-100 mb-2" id="openGooglePay">
                                                <i class="fas fa-external-link-alt"></i> Open Google Pay
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="qr-code-container">
                                        <h6>Scan QR Code to Pay</h6>
                                        <div class="qr-code" id="qrCodeContainer">
                                            <!-- QR Code will be generated here -->
                                            <div id="qrCodePlaceholder" style="width: 200px; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                <span class="text-muted">QR Code will be generated</span>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-muted">Scan with any UPI app</p>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label for="upi_id">Or Enter UPI ID</label>
                                        <input type="text" name="upi_id" id="upi_id" 
                                               class="form-control mt-1" 
                                               placeholder="yourname@upi">
                                        <button type="button" class="btn btn-primary mt-2 w-100" id="verifyUpi">
                                            Verify & Pay via UPI
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit/Debit Card -->
                            <div class="payment-method selected" data-method="card">
                                <label class="d-flex align-items-center" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="card" checked 
                                           {{ old('payment_method', 'card') == 'card' ? 'checked' : '' }}>
                                    <i class="fas fa-credit-card payment-icon"></i>
                                    <div>
                                        <strong>Credit/Debit Card</strong>
                                        <div class="card-logos">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/2052px-American_Express_logo_%282018%29.svg.png" alt="Amex">
                                        </div>
                                    </div>
                                </label>
                                <div class="payment-details active" id="card-details">
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <label>Card Number</label>
                                            <input type="text" name="card_number" 
                                                   class="form-control @error('card_number') is-invalid @enderror" 
                                                   placeholder="1234 5678 9012 3456" 
                                                   value="{{ old('card_number') }}">
                                            @error('card_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label>Expiration Date</label>
                                            <input type="text" name="expiry_date" 
                                                   class="form-control @error('expiry_date') is-invalid @enderror" 
                                                   placeholder="MM/YY" 
                                                   value="{{ old('expiry_date') }}">
                                            @error('expiry_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label>Security Code</label>
                                            <input type="text" name="cvv" 
                                                   class="form-control @error('cvv') is-invalid @enderror" 
                                                   placeholder="CVV" 
                                                   value="{{ old('cvv') }}">
                                            @error('cvv')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <label>Name on Card</label>
                                            <input type="text" name="card_holder_name" 
                                                   class="form-control @error('card_holder_name') is-invalid @enderror" 
                                                   placeholder="Card Holder Name" 
                                                   value="{{ old('card_holder_name') }}">
                                            @error('card_holder_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal -->
                            <div class="payment-method" data-method="paypal">
                                <label class="d-flex align-items-center" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="paypal"
                                           {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                    <i class="fab fa-paypal payment-icon" style="color: #003087;"></i>
                                    <div>
                                        <strong>PayPal</strong>
                                        <p class="mb-0 text-muted">Pay securely with your PayPal account</p>
                                    </div>
                                </label>
                                <div class="payment-details" id="paypal-details">
                                    <p class="text-muted">You will be redirected to PayPal to complete your payment securely.</p>
                                </div>
                            </div>

                            <!-- Cash on Delivery -->
                            <div class="payment-method" data-method="cod">
                                <label class="d-flex align-items-center" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="cod"
                                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                                    <i class="fas fa-money-bill-wave payment-icon" style="color: #28a745;"></i>
                                    <div>
                                        <strong>Cash on Delivery</strong>
                                        <p class="mb-0 text-muted">Pay when you receive your order</p>
                                    </div>
                                </label>
                                <div class="payment-details" id="cod-details">
                                    <p class="text-muted">Pay with cash when your order is delivered.</p>
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="fas fa-info-circle"></i>
                                            Additional cash handling fee may apply.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="read-more d-block text-center w-100" id="submitBtn">
                                <span id="submitText">
                                    @if(old('payment_method') == 'cod')
                                        Place Order (Cash on Delivery)
                                    @elseif(old('payment_method') == 'paypal')
                                        Continue to PayPal
                                    @elseif(old('payment_method') == 'upi')
                                        Pay via UPI
                                    @else
                                        Pay Now
                                    @endif
                                </span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-5 mt-5 mt-md-0">
                    <div class="cart-box">
                        <h3 class="cart-heading">Order summary</h3>

                        <div class="row border-bottom pb-2 mb-2 mt-4">
                            <div class="col-6 col-md-4">Subtotal</div>
                            <div class="col-6 col-md-8 text-end">{{ $currency->symbol }}{{ number_format($subtotal, 2) }}</div>
                        </div>
                        <div class="row border-bottom pb-2 mb-2">
                            <div class="col-4 col-md-4">Shipping</div>
                            <div class="col-8 col-md-8 text-end"><small>Enter you address to view shipping</small></div>
                        </div>
                        <div class="row border-bottom pb-2 mb-2">   
                            <div class="col-6 col-md-4">Total</div>
                            <div class="col-6 col-md-8 text-end"><span>{{ $currency->symbol }}{{ number_format($total, 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Modal -->
    <div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="paymentStatusContent">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5>Processing Payment...</h5>
                        <p>Please wait while we process your payment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    $(document).ready(function() {
        let paymentInterval;
        let currentOrderId = null;

        // Payment method selection
        $('.payment-method').click(function() {
            $('.payment-method').removeClass('selected');
            $(this).addClass('selected');
            
            // Update radio button
            $(this).find('input[type="radio"]').prop('checked', true);
            
            // Hide all payment details
            $('.payment-details').removeClass('active');
            
            // Show selected payment details
            const method = $(this).data('method');
            $(`#${method}-details`).addClass('active');
            
            // Update button text
            updateSubmitButtonText(method);

            // Generate QR code for UPI payments
            if (method === 'upi') {
                generateUpiQrCode();
            }
        });

        // Update submit button text based on payment method
        function updateSubmitButtonText(method) {
            const buttonText = $('#submitText');
            switch(method) {
                case 'cod':
                    buttonText.text('Place Order (Cash on Delivery)');
                    break;
                case 'paypal':
                    buttonText.text('Continue to PayPal');
                    break;
                case 'upi':
                    buttonText.text('Pay via UPI');
                    break;
                default:
                    buttonText.text('Pay Now');
            }
        }

        // Generate UPI QR Code
        function generateUpiQrCode() {
            const amount = {{ $total }};
            const upiId = 'your-merchant@upi'; // Replace with your actual UPI ID
            const merchantName = 'Your Store Name';
            const transactionNote = 'Order Payment';
            
            const upiString = `upi://pay?pa=${upiId}&pn=${encodeURIComponent(merchantName)}&am=${amount}&tn=${encodeURIComponent(transactionNote)}&cu=INR`;
            
            // Clear previous QR code
            $('#qrCodeContainer').empty();
            
            // Generate new QR code
            QRCode.toCanvas(upiString, { 
                width: 200, 
                height: 200,
                margin: 1
            }, function(err, canvas) {
                if (err) {
                    console.error('QR Code generation error:', err);
                    $('#qrCodeContainer').html('<div class="text-danger">Failed to generate QR code</div>');
                    return;
                }
                $('#qrCodeContainer').html(canvas);
            });
        }

        // Open PhonePe
        $('#openPhonePe').click(function() {
            const amount = {{ $total }};
            const phonePeUrl = `phonepe://pay?pa=your-merchant@phonepe&pn=YourStore&am=${amount}&tn=OrderPayment`;
            
            // Try to open PhonePe app
            window.location.href = phonePeUrl;
            
            // Fallback to web if app not installed
            setTimeout(function() {
                window.open('https://phonepe.com', '_blank');
            }, 500);
            
            initiatePaymentStatusCheck('phonepe');
        });

        // Open Google Pay
        $('#openGooglePay').click(function() {
            const amount = {{ $total }};
            const googlePayUrl = `tez://upi/pay?pa=your-merchant@okicici&pn=YourStore&am=${amount}&tn=OrderPayment`;
            
            // Try to open Google Pay app
            window.location.href = googlePayUrl;
            
            // Fallback to web if app not installed
            setTimeout(function() {
                window.open('https://gpay.app.goo.gl/', '_blank');
            }, 500);
            
            initiatePaymentStatusCheck('gpay');
        });

        // Verify UPI ID and initiate payment
        $('#verifyUpi').click(function() {
            const upiId = $('#upi_id').val();
            if (!validateUpiId(upiId)) {
                toastr.error('Please enter a valid UPI ID', 'Validation Error');
                return;
            }
            
            initiateUpiPayment(upiId);
        });

        // Validate UPI ID
        function validateUpiId(upiId) {
            const upiRegex = /^[a-zA-Z0-9.\-_]{2,49}@[a-zA-Z]{2,}$/;
            return upiRegex.test(upiId);
        }

        // Initiate UPI Payment
        function initiateUpiPayment(upiId) {
            // Show loading state
            $('#submitBtn').prop('disabled', true);
            $('#loadingSpinner').removeClass('d-none');
            $('#submitText').text('Initiating UPI Payment...');

            // Simulate API call to payment gateway
            $.ajax({
                url: "{{ route('payment.initiate.upi') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    upi_id: upiId,
                    amount: {{ $total }},
                    order_id: generateOrderId()
                },
                success: function(response) {
                    if (response.success) {
                        currentOrderId = response.order_id;
                        
                        // Show payment status modal
                        $('#paymentStatusModal').modal('show');
                        
                        // Start polling for payment status
                        startPaymentStatusCheck();
                        
                        // Redirect to UPI app or show instructions
                        if (response.payment_url) {
                            window.location.href = response.payment_url;
                        }
                    } else {
                        toastr.error(response.message, 'Payment Failed');
                        resetSubmitButton();
                    }
                },
                error: function() {
                    toastr.error('Failed to initiate payment', 'Error');
                    resetSubmitButton();
                }
            });
        }

        // Generate unique order ID
        function generateOrderId() {
            return 'ORD' + Date.now() + Math.floor(Math.random() * 1000);
        }

        // Start polling for payment status
        function startPaymentStatusCheck() {
            paymentInterval = setInterval(function() {
                checkPaymentStatus();
            }, 3000); // Check every 3 seconds
        }

        // Check payment status
        function checkPaymentStatus() {
            if (!currentOrderId) return;

            $.ajax({
                url: "{{ route('payment.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: currentOrderId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        clearInterval(paymentInterval);
                        showPaymentSuccess();
                    } else if (response.status === 'failed') {
                        clearInterval(paymentInterval);
                        showPaymentFailed(response.message);
                    }
                    // If pending, continue polling
                },
                error: function() {
                    console.error('Error checking payment status');
                }
            });
        }

        // Show payment success
        function showPaymentSuccess() {
            $('#paymentStatusContent').html(`
                <div class="payment-success">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h5>Payment Successful!</h5>
                    <p>Your payment has been processed successfully.</p>
                </div>
            `);
            
            setTimeout(function() {
                $('#paymentStatusModal').modal('hide');
                // Redirect to success page
                window.location.href = "{{ route('order.success') }}?order_id=" + currentOrderId;
            }, 2000);
        }

        // Show payment failed
        function showPaymentFailed(message) {
            $('#paymentStatusContent').html(`
                <div class="text-danger">
                    <i class="fas fa-times-circle fa-3x mb-3"></i>
                    <h5>Payment Failed</h5>
                    <p>${message || 'Payment processing failed. Please try again.'}</p>
                    <button class="btn btn-primary mt-2" onclick="retryPayment()">Retry Payment</button>
                </div>
            `);
        }

        // Reset submit button
        function resetSubmitButton() {
            $('#submitBtn').prop('disabled', false);
            $('#loadingSpinner').addClass('d-none');
            updateSubmitButtonText($('input[name="payment_method"]:checked').val());
        }

        // Initialize form validation with conditional rules
        $('#checkoutForm').validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                address: {
                    required: true
                },
                country: {
                    required: true
                },
                city: {
                    required: true
                },
                state: {
                    required: true
                },
                zipcode: {
                    required: true,
                    digits: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true
                },
                payment_method: {
                    required: true
                },
                // Conditional rules for card payment
                card_number: {
                    required: function() {
                        return $('input[name="payment_method"]:checked').val() === 'card';
                    },
                    creditcard: true
                },
                expiry_date: {
                    required: function() {
                        return $('input[name="payment_method"]:checked').val() === 'card';
                    }
                },
                cvv: {
                    required: function() {
                        return $('input[name="payment_method"]:checked').val() === 'card';
                    },
                    digits: true,
                    minlength: 3,
                    maxlength: 4
                },
                card_holder_name: {
                    required: function() {
                        return $('input[name="payment_method"]:checked').val() === 'card';
                    }
                },
                upi_id: {
                    required: function() {
                        return $('input[name="payment_method"]:checked').val() === 'upi' && 
                               $('#verifyUpi').is(':visible');
                    },
                    // Custom UPI validation
                    validateUpi: true
                }
            },
            messages: {
                first_name: {
                    required: "Please enter your first name",
                    minlength: "First name must be at least 2 characters long"
                },
                last_name: {
                    required: "Please enter your last name",
                    minlength: "Last name must be at least 2 characters long"
                },
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                phone: {
                    required: "Please enter your phone number",
                    digits: "Please enter only digits"
                },
                payment_method: {
                    required: "Please select a payment method"
                },
                card_number: {
                    required: "Please enter your card number",
                    creditcard: "Please enter a valid credit card number"
                },
                cvv: {
                    required: "Please enter CVV",
                    digits: "CVV must contain only numbers",
                    minlength: "CVV must be 3-4 digits",
                    maxlength: "CVV must be 3-4 digits"
                },
                upi_id: {
                    required: "Please enter your UPI ID",
                    validateUpi: "Please enter a valid UPI ID (e.g., name@upi)"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            submitHandler: function(form) {
                const paymentMethod = $('input[name="payment_method"]:checked').val();
                
                // For UPI payments, use the custom flow
                if (paymentMethod === 'upi') {
                    const upiId = $('#upi_id').val();
                    if (upiId && validateUpiId(upiId)) {
                        initiateUpiPayment(upiId);
                        return false; // Prevent form submission
                    }
                }

                // For other payment methods, proceed with normal form submission
                $('#submitBtn').prop('disabled', true);
                $('#loadingSpinner').removeClass('d-none');
                
                let processingText = 'Processing...';
                switch(paymentMethod) {
                    case 'paypal':
                        processingText = 'Redirecting to PayPal...';
                        break;
                    case 'cod':
                        processingText = 'Placing Order...';
                        break;
                    case 'upi':
                        processingText = 'Initiating UPI Payment...';
                        break;
                }
                
                $('#submitText').text(processingText);
                form.submit();
            }
        });

        // Custom UPI validation method
        $.validator.addMethod("validateUpi", function(value, element) {
            return this.optional(element) || validateUpiId(value);
        }, "Please enter a valid UPI ID");

        // Format card number
        $('input[name="card_number"]').on('input', function() {
            var value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            var matches = value.match(/\d{4,16}/g);
            var match = matches && matches[0] || '';
            var parts = [];
            
            for (var i = 0, len = match.length; i < len; i += 4) {
                parts.push(match.substring(i, i + 4));
            }
            
            if (parts.length) {
                $(this).val(parts.join(' '));
            }
        });

        // Format expiry date
        $('input[name="expiry_date"]').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            $(this).val(value);
        });

        // Show server-side validation errors with toastr
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', 'Validation Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
            @endforeach
        @endif

        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Success', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
        @endif

        // Initialize based on previously selected payment method
        const selectedMethod = $('input[name="payment_method"]:checked').val();
        if (selectedMethod) {
            $(`.payment-method[data-method="${selectedMethod}"]`).click();
        }

        // Global function for retry payment
        window.retryPayment = function() {
            $('#paymentStatusModal').modal('hide');
            resetSubmitButton();
        };
    });
</script>
@endsection