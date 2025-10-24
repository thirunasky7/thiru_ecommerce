@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@php $currency = activeCurrency(); @endphp

<div class="flex items-center justify-center min-h-[70vh] bg-gray-50 px-4">
  <div class="bg-white shadow-lg rounded-2xl p-8 text-center max-w-md w-full">
      
      <!-- ✅ Success Icon -->
      <div class="text-green-500 mb-4">
          <i class="fas fa-check-circle text-6xl"></i>
      </div>

      <!-- ✅ Main Message -->
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Order Confirmed!</h2>
      <p class="text-gray-500 mb-6">
          Thank you for your purchase.<br>
          Your order <span class="font-semibold text-gray-800">#{{ $order->order_number }}</span> has been received.
      </p>

      <!-- ✅ Button -->
      <a href="{{ url('/home') }}" 
         class="inline-block bg-orange-600 text-white px-6 py-2 rounded-full font-medium hover:bg-orange-700 transition">
          Continue Shopping
      </a>
  </div>
</div>
@endsection
