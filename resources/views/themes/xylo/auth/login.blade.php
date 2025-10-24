@extends('themes.xylo.partials.app')


@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center p-4">
    <div class="w-full max-w-6xl flex flex-col lg:flex-row rounded-2xl overflow-hidden shadow-2xl">
        <!-- Left Side - Brand Section -->
       
        <div class="lg:w-1/2 bg-white py-12 p-8 lg:p-12 flex flex-col justify-center">
            <!-- Logo -->
            <!-- <div class="mb-8 lg:mb-12 text-center lg:text-left">
                <img src="{{ asset('assets/images/logo-main.png') }}" width="180" alt="logo main" class="mx-auto lg:mx-0">
            </div> -->
            
            <!-- Welcome Text -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Welcome Back</h2>
                <!-- <p class="text-gray-600 leading-relaxed">
                    To craft an effective marketing message, keep it concise, relevant to your target audience
                </p> -->
            </div>
            
            <!-- Login Form -->
            <form class="formmain" method="POST" action="{{ route('customer.login') }}">
                @csrf
                
                <!-- Mobile Number Input -->
                <div class="mb-6">
                    <label for="mobile" class="block text-gray-700 text-sm font-medium mb-2">
                        Mobile Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">+91</span>
                        </div>
                        <input 
                            type="tel" 
                            id="mobile"
                            name="mobile" 
                            value="{{ old('mobile') }}" 
                            placeholder="Enter your mobile number"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"
                            required
                            autofocus
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    @error('mobile')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Input -->
                <!-- <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            placeholder="Enter your password"
                            class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div> -->
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember"
                            name="remember" 
                            class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <!-- <a href="{{ route('customer.password.request') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Forgot Password?
                    </a> -->
                </div>
                
                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white py-3 px-4 rounded-lg font-semibold hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl"
                >
                    Login Now
                </button>
            </form>
            
            <!-- Signup Link -->
            <!-- <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('customer.register') }}" class="text-amber-600 hover:text-amber-700 font-semibold ml-1">
                        Sign up
                    </a>
                </p>
            </div> -->
            
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .floating {
        animation: float 6s ease-in-out infinite;
    }
</style>

<script>
    // Add some interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effects to form inputs
        const inputs = document.querySelectorAll('input[type="tel"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-amber-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-amber-200');
            });
        });
        
        // Add loading state to login button
        const loginForm = document.querySelector('.formmain');
        loginForm.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Logging in...</span>';
            button.disabled = true;
        });
    });
</script>
@endsection