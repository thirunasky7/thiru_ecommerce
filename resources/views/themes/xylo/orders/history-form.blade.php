@extends('themes.xylo.partials.app')

@section('title', 'Thaiyur Shop - Online Shopping')
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;     /* Firefox */
}
.slide {
            display: none;
            opacity: 0;
          
        }
        .active-slide {
            display: block;
            opacity: 1;
        }
        
        /* Food menu availability styles */
        .product-unavailable {
            position: relative;
            
            opacity: 1.5;
            pointer-events: none;
        }
        
        .unavailable-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 10;
            text-align: center;
            font-size: 14px;
        }
        
        .product-card-container {
            position: relative;
        }
</style>
@section('content')
@php $currency = activeCurrency(); @endphp
 
<body class="bg-gray-50">
    <!-- Header -->
    <section class="relative bg-gradient-to-r from-amber-900 to-amber-700 text-white py-16 md:py-24 overflow-hidden">
   
</section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Track Your Orders</h1>
            <p class="text-gray-600 max-w-lg mx-auto">Enter your customer ID to view your complete order history and track current orders</p>
        </div>

        <!-- Customer ID Form -->
        <div class="max-w-md mx-auto bg-white shadow-md rounded-xl p-6 mb-10">
            <h2 class="text-xl font-semibold mb-4 text-center text-green-700">Enter Your Customer ID</h2>

            <!-- Error Message -->
            <div id="errorMessage" class="bg-red-100 text-red-700 p-3 rounded mb-4 hidden">
                Invalid customer ID. Please check and try again.
            </div>

            <form  action="{{ route('orders.fetch') }}" method="post">
                @csrf
                <label class="block mb-2 font-medium text-gray-700">Customer ID</label>
                <input type="text" id="customerId" name="unique_id" placeholder="e.g. THAI673B28A5F4A1"
                       class="w-full border border-gray-300 rounded-lg p-3 mb-4 focus:ring-2 focus:ring-green-300 focus:border-green-500 transition" required>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> View Order History
                </button>
            </form>

            <p class="text-sm text-gray-600 mt-4 text-center">
                Don't know your ID? <a href="{{ url('/contact-us')}}" class="text-green-600 hover:underline">Contact ThaiyurShop support</a> to get your customer code.
            </p>
        </div>

        <!-- Order History Section -->
        <div id="orderHistory" class="hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Your Order History</h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Filter</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Sort</button>
                </div>
            </div>

            <!-- Order Cards -->
            <div class="space-y-6">
                <!-- Order 1 -->
                <div class="order-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">Order #THAI673B28A5F4A1</h3>
                                <span class="status-badge status-delivered">Delivered</span>
                            </div>
                            <p class="text-gray-600">Placed on March 15, 2023</p>
                        </div>
                        <p class="text-lg font-bold text-green-700 mt-2 md:mt-0">₹2,847.00</p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 mb-2">Items</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Organic Rice (5kg)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Coconut Oil (1L)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Spices Pack</span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6">
                                <button class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                    <i class="fas fa-redo-alt mr-1"></i> Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order 2 -->
                <div class="order-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">Order #THAI582C39B6E5D2A3</h3>
                                <span class="status-badge status-shipped">Shipped</span>
                            </div>
                            <p class="text-gray-600">Placed on March 10, 2023</p>
                        </div>
                        <p class="text-lg font-bold text-green-700 mt-2 md:mt-0">₹1,526.00</p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 mb-2">Items</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Assam Tea (250g)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Jaggery (1kg)</span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6">
                                <button class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                    <i class="fas fa-truck mr-1"></i> Track Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order 3 -->
                <div class="order-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">Order #THAI491D4A7F6C3B4</h3>
                                <span class="status-badge status-processing">Processing</span>
                            </div>
                            <p class="text-gray-600">Placed on March 5, 2023</p>
                        </div>
                        <p class="text-lg font-bold text-green-700 mt-2 md:mt-0">₹3,215.00</p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 mb-2">Items</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Basmati Rice (10kg)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Ghee (500g)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Turmeric Powder</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Red Chili</span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6">
                                <button class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order 4 -->
                <div class="order-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">Order #THAI673B28A5F4A1</h3>
                                <span class="status-badge status-cancelled">Cancelled</span>
                            </div>
                            <p class="text-gray-600">Placed on February 28, 2023</p>
                        </div>
                        <p class="text-lg font-bold text-green-700 mt-2 md:mt-0">₹892.00</p>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800 mb-2">Items</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Honey (500g)</span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">Pepper (100g)</span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6">
                                <button class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                    <i class="fas fa-shopping-cart mr-1"></i> Order Again
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Thaiyur Shop</h3>
                    <p class="text-gray-400">Your trusted source for authentic Indian groceries and spices since 2010.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Home</a></li>
                        <li><a href="#" class="hover:text-white transition">Shop</a></li>
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Customer Service</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Track Order</a></li>
                        <li><a href="#" class="hover:text-white transition">Returns & Refunds</a></li>
                        <li><a href="#" class="hover:text-white transition">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> 123 Spice Street, Chennai
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i> +91 98765 43210
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i> support@thaiyurshop.com
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; 2023 Thaiyur Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const customerId = document.getElementById('customerId').value;
            const errorMessage = document.getElementById('errorMessage');
            const orderHistory = document.getElementById('orderHistory');
            
            // Simple validation - in a real app, this would be a server call
            if (customerId && customerId.startsWith('THAI')) {
                errorMessage.classList.add('hidden');
                orderHistory.classList.remove('hidden');
                
                // Scroll to order history
                orderHistory.scrollIntoView({ behavior: 'smooth' });
            } else {
                errorMessage.classList.remove('hidden');
                orderHistory.classList.add('hidden');
            }
        });
    </script>
</body>
</html>