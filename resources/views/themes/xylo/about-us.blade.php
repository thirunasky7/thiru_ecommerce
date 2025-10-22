@extends('themes.xylo.layouts.master')



@section('content')

<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1 class="animate-fade-in">Our Story of Spices & Tradition</h1>
        <p class="animate-fade-in">For generations, we've been bringing the authentic taste of India to kitchens worldwide through our premium spices and traditional recipes</p>
        <div class="floating">
            <i class="fas fa-mortar-pestle" style="font-size: 4rem; opacity: 0.8;"></i>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="story-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="story-content">
                    <h2 class="mb-4">From Humble Beginnings to Spice Masters</h2>
                    <p class="lead mb-4">
                        What started as a small family-run spice shop in the bustling streets of Old Delhi has now grown into a trusted name for authentic Indian spices and food products.
                    </p>
                    <p class="mb-4">
                        Our founder, Mr. Rajesh Agarwal, began this journey with a simple mission: to preserve and share the rich culinary heritage of India through pure, unadulterated spices. He believed that the soul of Indian cooking lies in the quality of its spices.
                    </p>
                    <p class="mb-4">
                        Today, while we've embraced modern technology and expanded our reach globally, we still follow the traditional methods of spice selection and blending that were passed down through generations.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="quality-badge">
                            <i class="fas fa-check-circle"></i> Family Tradition
                        </span>
                        <span class="quality-badge">
                            <i class="fas fa-check-circle"></i> Authentic Recipes
                        </span>
                        <span class="quality-badge">
                            <i class="fas fa-check-circle"></i> Premium Quality
                        </span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="values-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Our Core Values</h2>
            <p class="lead text-muted">The principles that guide everything we do</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="value-card animate-fade-in">
                    <div class="value-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3>Purity & Authenticity</h3>
                    <p>We source only the finest ingredients and maintain traditional preparation methods to ensure every product carries the authentic taste of India.</p>
                    <ul class="service-features mt-3">
                        <li>No artificial preservatives</li>
                        <li>Traditional stone grinding</li>
                        <li>Direct farm sourcing</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="value-card animate-fade-in">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Quality Commitment</h3>
                    <p>Every batch undergoes rigorous quality checks. We believe in delivering nothing but the best to our customers' kitchens.</p>
                    <ul class="service-features mt-3">
                        <li>Stringent quality control</li>
                        <li>Fresh small batches</li>
                        <li>Hygienic processing</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="value-card animate-fade-in">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Customer First</h3>
                    <p>Our customers are part of our extended family. Their satisfaction and trust drive us to continuously improve and innovate.</p>
                    <ul class="service-features mt-3">
                        <li>Personalized service</li>
                        <li>Recipe guidance</li>
                        <li>Quick response</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Process Section -->
<section class="process-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Our Traditional Process</h2>
            <p class="lead text-muted">How we bring authentic flavors to your kitchen</p>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-icon">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <h4>Farm Sourcing</h4>
                    <p>Directly sourced from trusted farms across India's spice regions</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h4>Quality Check</h4>
                    <p>Rigorous testing for purity, aroma, and quality standards</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-icon">
                        <i class="fas fa-mortar-pestle"></i>
                    </div>
                    <h4>Traditional Grinding</h4>
                    <p>Stone-ground using traditional methods for authentic flavor</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h4>Fresh Delivery</h4>
                    <p>Vacuum-sealed and delivered fresh to preserve aroma</p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Final CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="mb-3">Ready to Experience Authentic Flavors?</h2>
        <p class="mb-4 lead opacity-90">Join thousands of customers who trust us for their culinary journey</p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-shopping-cart me-2"></i>Explore Our Products
            </a>
            <a href="#" class="btn btn-outline-light btn-lg">
                <i class="fas fa-comments me-2"></i>Get In Touch
            </a>
        </div>
    </div>
</section>
@endsection
