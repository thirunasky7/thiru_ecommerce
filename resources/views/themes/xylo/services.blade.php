@extends('themes.xylo.partials.app')

@section('title', 'Services - MyStore')

@section('content')
<div class="w-full bg-orange-500 text-white py-16 px-4 md:px-10 text-center">
  <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Premium Services</h1>
  <p class="text-lg md:text-xl opacity-90 max-w-3xl mx-auto">
    Experience exceptional quality and reliability with every product and service we offer.
  </p>
</div>

<!-- Services Grid -->
<section class="py-16 px-4 md:px-10 bg-gray-50">
  <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">What We Offer</h2>
  <div class="grid gap-8 md:grid-cols-3">
    @foreach (['Fast Delivery' => 'Quick doorstep delivery for all orders.',
                '24/7 Support' => 'We’re here to help you anytime, anywhere.',
                'Easy Returns' => 'Hassle-free return and refund process.',
                'Secure Payments' => 'Your transactions are 100% safe and encrypted.',
                'Exclusive Offers' => 'Enjoy seasonal discounts and special deals.',
                'Customer Rewards' => 'Earn loyalty points on every purchase.'] as $title => $desc)
    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition-all duration-300 text-center">
      <div class="text-orange-500 text-4xl mb-4">
        <i class="fas fa-star"></i>
      </div>
      <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ $title }}</h3>
      <p class="text-gray-600 text-sm">{{ $desc }}</p>
    </div>
    @endforeach
  </div>
</section>

<!-- Process Section -->
<section class="py-16 px-4 md:px-10 bg-white">
  <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">How It Works</h2>
  <div class="grid md:grid-cols-3 gap-8 text-center">
    <div class="flex flex-col items-center">
      <div class="text-orange-500 text-4xl mb-3">
        <i class="fas fa-search"></i>
      </div>
      <h4 class="text-lg font-semibold mb-1">Browse Products</h4>
      <p class="text-gray-600 text-sm">Explore our wide range of premium products tailored to your needs.</p>
    </div>
    <div class="flex flex-col items-center">
      <div class="text-orange-500 text-4xl mb-3">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <h4 class="text-lg font-semibold mb-1">Add to Cart</h4>
      <p class="text-gray-600 text-sm">Easily add products to your cart and proceed to secure checkout.</p>
    </div>
    <div class="flex flex-col items-center">
      <div class="text-orange-500 text-4xl mb-3">
        <i class="fas fa-truck"></i>
      </div>
      <h4 class="text-lg font-semibold mb-1">Fast Delivery</h4>
      <p class="text-gray-600 text-sm">Sit back and relax while we deliver your order swiftly and safely.</p>
    </div>
  </div>
</section>

<!-- Testimonials -->
<!-- <section class="py-16 px-4 md:px-10 bg-gray-50">
  <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">What Our Customers Say</h2>
  <div class="grid md:grid-cols-3 gap-8">
    @foreach ([['name'=>'Priya Sharma','text'=>'Excellent service! The delivery was super fast and the quality was great.'],
               ['name'=>'Arjun Kumar','text'=>'I love the offers and customer support. Highly recommend!'],
               ['name'=>'Divya Patel','text'=>'The checkout process was smooth, and I got my items on time.']] as $review)
    <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition duration-300">
      <div class="flex items-center mb-4">
        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 text-xl font-bold">
          {{ strtoupper(substr($review['name'],0,1)) }}
        </div>
        <div class="ml-4">
          <h4 class="text-gray-800 font-semibold">{{ $review['name'] }}</h4>
          <div class="text-yellow-400 text-sm">
            <i class="fas fa-star"></i> <i class="fas fa-star"></i> 
            <i class="fas fa-star"></i> <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
          </div>
        </div>
      </div>
      <p class="text-gray-600 text-sm">{{ $review['text'] }}</p>
    </div>
    @endforeach
  </div>
</section> -->

<!-- FAQ -->
<section class="py-16 px-4 md:px-10 bg-white">
  <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Frequently Asked Questions</h2>
  <div class="max-w-3xl mx-auto">
    @foreach ([
        ['q'=>'How do I track my order?','a'=>'You can track your order from your account dashboard under the "My Orders" section.'],
        ['q'=>'What is the return policy?','a'=>'We offer an easy 7-day return policy for most items.'],
        ['q'=>'Do you offer international shipping?','a'=>'Yes, we ship globally with trusted courier partners.']
      ] as $faq)
    <div class="border-b border-gray-200 py-4">
      <button class="w-full text-left flex justify-between items-center text-gray-800 font-semibold faq-toggle">
        {{ $faq['q'] }}
        <i class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
      </button>
      <div class="faq-content hidden mt-2 text-gray-600 text-sm">{{ $faq['a'] }}</div>
    </div>
    @endforeach
  </div>
</section>

<!-- Call to Action -->
<section class="py-16 px-4 md:px-10 text-center bg-orange-500 text-white">
  <h2 class="text-3xl font-bold mb-4">Ready to Experience the Best?</h2>
  <p class="text-lg opacity-90 mb-6">Join thousands of happy customers who trust MyStore.</p>
  <a href="/shop" class="bg-white text-orange-500 font-semibold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition">
    Shop Now
  </a>
</section>

@endsection

@section('js')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // FAQ toggle
    document.querySelectorAll(".faq-toggle").forEach(btn => {
      btn.addEventListener("click", () => {
        const content = btn.nextElementSibling;
        const icon = btn.querySelector("i");
        content.classList.toggle("hidden");
        icon.classList.toggle("rotate-180");
      });
    });

    // Scroll animation
    const elements = document.querySelectorAll("section");
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("opacity-100", "translate-y-0");
        }
      });
    });
    elements.forEach(el => {
      el.classList.add("opacity-0", "translate-y-4", "transition", "duration-700");
      observer.observe(el);
    });
  });
</script>
@endsection
