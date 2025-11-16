@extends('themes.xylo.partials.app')

@section('title', 'Track Your Order')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Track Your Order</h1>
            <p class="text-gray-600 mt-2">Enter your mobile number to view all your orders</p>
        </div>

        <!-- Tracking Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form id="trackingForm" method="POST" action="{{ route('order.track') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mobile Number -->
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Mobile Number *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">+91</span>
                            </div>
                            <input 
                                type="tel" 
                                id="mobile_number" 
                                name="mobile_number" 
                                value="{{ $mobileNumber ?? '' }}"
                                class="pl-12 block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Enter 10-digit mobile number"
                                maxlength="10"
                                pattern="[0-9]{10}"
                                required
                            >
                        </div>
                    </div>

                    <!-- Order ID (Optional) -->
                    <div>
                        <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Order ID (Optional)
                        </label>
                        <input 
                            type="text" 
                            id="order_id" 
                            name="order_id" 
                            value="{{ $orderId ?? '' }}"
                            class="block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Enter order number"
                        >
                    </div>
                </div>

                <div class="mt-6">
                    <button 
                        type="submit" 
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        Track Orders
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div id="trackingResults">
            @if(isset($orders) && $orders->count() > 0)
                @include('themes.xylo.partials.order-tracking-results', ['orders' => $orders])
            @elseif(isset($mobileNumber))
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fa fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Orders Found</h3>
                    <p class="text-gray-600">We couldn't find any orders for mobile number: <strong>{{ $mobileNumber }}</strong></p>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fa fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Need Help?</h3>
                    <p class="text-blue-700">
                        If you're having trouble finding your orders, please contact our customer support at 
                        <a href="tel:+91-XXXXXXXXXX" class="font-semibold hover:underline">+91-XXXXXXXXXX</a> 
                        or email us at 
                        <a href="mailto:support@example.com" class="font-semibold hover:underline">support@example.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Order Details</h3>
                <button onclick="closeOrderDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>
            <div id="orderDetailsContent" class="overflow-y-auto max-h-[70vh]">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('trackingForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        // Show loading state
        submitButton.textContent = 'Searching...';
        submitButton.disabled = true;
        
        try {
            const response = await fetch('{{ route("order.track") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                document.getElementById('trackingResults').innerHTML = result.html;
            } else {
                showNotification('Error tracking orders', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error tracking orders', 'error');
        } finally {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    });
});

function viewOrderDetails(orderId) {
    fetch(`/order-details/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('orderDetailsContent').innerHTML = data.html;
                document.getElementById('orderDetailsModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading order details', 'error');
        });
}

function closeOrderDetails() {
    document.getElementById('orderDetailsModal').classList.add('hidden');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection