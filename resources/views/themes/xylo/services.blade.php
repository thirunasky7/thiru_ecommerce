@extends('themes.xylo.layouts.master')

@section('css')
<style>
    /* Services Page Styles */
    .services-hero {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .services-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .services-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .service-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        border: 1px solid #f0f0f0;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .service-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(45deg, #ff6b35, #f7931e);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .service-card h3 {
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .service-card p {
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .service-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .service-features li {
        padding: 0.5rem 0;
        color: #495057;
        position: relative;
        padding-left: 1.5rem;
    }

    .service-features li:before {
        content: "✓";
        color: #ff6b35;
        font-weight: bold;
        position: absolute;
        left: 0;
    }

    /* Quality Standards Section */
    .quality-standards {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
    }

    .quality-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: 100%;
    }

    .quality-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    /* Delivery Process */
    .process-step {
        text-align: center;
        padding: 2rem 1rem;
        position: relative;
    }

    .process-step:not(:last-child):after {
        content: "";
        position: absolute;
        top: 50%;
        right: -10%;
        width: 20%;
        height: 2px;
        background: #ff6b35;
        transform: translateY(-50%);
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, #ff6b35, #f7931e);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 auto 1rem;
    }

    /* Bulk Orders */
    .bulk-order-section {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 3rem;
        margin: 3rem 0;
    }

    .bulk-feature {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .bulk-feature i {
        color: #ff6b35;
        font-size: 1.5rem;
        margin-right: 1rem;
        min-width: 30px;
    }

    /* Testimonials */
    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin: 1rem;
        border-left: 4px solid #ff6b35;
    }

    .testimonial-text {
        font-style: italic;
        color: #6c757d;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .client-info {
        display: flex;
        align-items: center;
    }

    .client-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #ff6b35;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        border-radius: 20px;
        margin: 4rem 0;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .services-hero h1 {
            font-size: 2rem;
        }

        .services-hero p {
            font-size: 1rem;
        }

        .service-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .process-step:not(:last-child):after {
            display: none;
        }

        .bulk-order-section {
            padding: 2rem 1rem;
            margin: 2rem 0;
        }

        .quality-card {
            margin-bottom: 1.5rem;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection

@section('content')
@php $currency = activeCurrency(); @endphp

<!-- Hero Section -->
<section class="services-hero">
    <div class="container">
        <h1 class="animate-fade-in">Our Premium Services</h1>
        <p class="animate-fade-in">Experience the finest quality food products and spices with our comprehensive range of services designed for modern living</p>
    </div>
</section>

<!-- Main Services Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>What We Offer</h2>
            <p class="lead text-muted">Comprehensive food and masala solutions for homes and businesses</p>
        </div>

        <div class="row g-4">
            <!-- Fresh Grocery Delivery -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card animate-fade-in">
                    <div class="service-icon">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <h3>Fresh Grocery Delivery</h3>
                    <p>Get fresh vegetables, fruits, and daily essentials delivered to your doorstep with guaranteed quality and freshness.</p>
                    <ul class="service-features">
                        <li>Daily fresh produce delivery</li>
                        <li>Organic and conventional options</li>
                        <li>Same-day delivery available</li>
                        <li>Quality guaranteed or money back</li>
                        <li>Flexible delivery schedules</li>
                    </ul>
                </div>
            </div>

            <!-- Premium Masala Blends -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card animate-fade-in">
                    <div class="service-icon">
                        <i class="fas fa-mortar-pestle"></i>
                    </div>
                    <h3>Premium Masala Blends</h3>
                    <p>Authentic, freshly ground spices and custom masala blends prepared using traditional methods for authentic flavors.</p>
                    <ul class="service-features">
                        <li>100% pure and authentic spices</li>
                        <li>Custom masala blends</li>
                        <li>Traditional stone grinding</li>
                        <li>No preservatives or additives</li>
                        <li>Recipe-specific blends available</li>
                    </ul>
                </div>
            </div>

            <!-- Bulk Supply for Businesses -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card animate-fade-in">
                    <div class="service-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h3>Bulk Supply for Businesses</h3>
                    <p>Reliable bulk supply solutions for restaurants, caterers, and food businesses with competitive pricing.</p>
                    <ul class="service-features">
                        <li>Competitive wholesale prices</li>
                        <li>Regular supply contracts</li>
                        <li>Custom packaging options</li>
                        <li>Quality consistency guaranteed</li>
                        <li>Dedicated account manager</li>
                    </ul>
                </div>
            </div>

            <!-- Custom Spice Blends -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card animate-fade-in">
                    <div class="service-icon">
                        <i class="fas fa-blender"></i>
                    </div>
                    <h3>Custom Spice Blends</h3>
                    <p>Create your own signature spice blends with our expert guidance and premium quality ingredients.</p>
                    <ul class="service-features">
                        <li>Personalized blend creation</li>
                        <li>Expert consultation included</li>
                        <li>Small batch production</li>
                        <li>Private labeling available</li>
                        <li>Recipe development support</li>
                    </ul>
                </div>
            </div>

          

            <!-- Recipe & Cooking Support -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card animate-fade-in">
                    <div class="service-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Recipe & Cooking Support</h3>
                    <p>Get expert cooking tips, recipes, and guidance to make the most of our premium spices and ingredients.</p>
                    <ul class="service-features">
                        <li>Expert cooking guidance</li>
                        <li>Traditional recipe collection</li>
                        <li>Video tutorials</li>
                        <li>Spice usage recommendations</li>
                        <li>Monthly recipe newsletters</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quality Standards Section -->
<section class="quality-standards">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Our Quality Promise</h2>
            <p class="lead opacity-90">Committed to delivering the highest standards in every product</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="quality-card">
                    <div class="quality-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4>100% Natural</h4>
                    <p>No artificial colors, flavors, or preservatives in any of our products</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quality-card">
                    <div class="quality-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4>Premium Quality</h4>
                    <p>Hand-picked ingredients from trusted sources and regions</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quality-card">
                    <div class="quality-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Freshly Prepared</h4>
                    <p>Small batch preparation ensuring maximum freshness and potency</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="quality-card">
                    <div class="quality-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Hygienic Processing</h4>
                    <p>Stringent hygiene standards maintained throughout processing</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delivery Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>How Our Service Works</h2>
            <p class="lead text-muted">Simple and reliable process from order to delivery</p>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h4>Place Order</h4>
                    <p>Browse our catalog and place your order online or via phone</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h4>Quality Check</h4>
                    <p>Our experts verify and prepare your order with care</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h4>Careful Packaging</h4>
                    <p>Products are carefully packaged to maintain freshness</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h4>Fast Delivery</h4>
                    <p>Quick and reliable delivery to your preferred location</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bulk Orders Section -->
<section class="py-5">
    <div class="container">
        <div class="bulk-order-section">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4">Bulk Orders & Business Solutions</h2>
                    <p class="lead mb-4">Specialized services for restaurants, caterers, and food businesses looking for reliable bulk supply partners.</p>
                    
                    <div class="bulk-feature">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h5>Customized Pricing</h5>
                            <p>Competitive wholesale rates based on your volume requirements</p>
                        </div>
                    </div>
                    
                    <div class="bulk-feature">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h5>Regular Supply Contracts</h5>
                            <p>Ensure consistent quality and timely deliveries for your business</p>
                        </div>
                    </div>
                    
                    <div class="bulk-feature">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h5>Private Labeling</h5>
                            <p>Custom packaging with your brand for retail or distribution</p>
                        </div>
                    </div>
                    
                    <div class="bulk-feature">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h5>Quality Consistency</h5>
                            <p>Maintain the same high standards in every batch delivered</p>
                        </div>
                    </div>

                    <a href="#" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-handshake me-2"></i>Request Bulk Quote
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/images/bulk-orders.png') }}" 
                         alt="Bulk Orders" 
                         class="img-fluid rounded"
                         onerror="this.src='https://via.placeholder.com/500x400/ff6b35/ffffff?text=Bulk+Order+Solutions'"
                         style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>What Our Customers Say</h2>
            <p class="lead text-muted">Trusted by thousands of home cooks and professional chefs</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "The quality of spices is exceptional! As a restaurant owner, consistency is key, and they never disappoint. Their bulk ordering system has streamlined our operations significantly."
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">RK</div>
                        <div>
                            <h6 class="mb-0">Rajesh Kumar</h6>
                            <small class="text-muted">Restaurant Owner, Delhi</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Living abroad, it's hard to find authentic Indian spices. Their international shipping service brings the taste of home right to my doorstep. The packaging is always perfect!"
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">PS</div>
                        <div>
                            <h6 class="mb-0">Priya Sharma</h6>
                            <small class="text-muted">Home Cook, USA</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "The custom masala blends have transformed my cooking. Their team helped me create a signature blend for my catering business that my clients absolutely love!"
                    </div>
                    <div class="client-info">
                        <div class="client-avatar">AM</div>
                        <div>
                            <h6 class="mb-0">Anita Mehta</h6>
                            <small class="text-muted">Catering Business, Mumbai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="mb-3">Ready to Experience the Difference?</h2>
        <p class="mb-4 lead opacity-90">Join thousands of satisfied customers who trust us for their food and spice needs</p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
            <a href="#" class="btn btn-light btn-lg">
                <i class="fas fa-shopping-cart me-2"></i>Shop Now
            </a>
            <a href="#" class="btn btn-outline-light btn-lg">
                <i class="fas fa-phone me-2"></i>Contact Us
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Frequently Asked Questions</h2>
            <p class="lead text-muted">Get answers to common questions about our services</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="servicesFAQ">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                What is your delivery area and time?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#servicesFAQ">
                            <div class="accordion-body">
                                We deliver across major cities with same-day delivery available in most areas. For international orders, delivery typically takes 5-10 business days depending on the destination.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Do you offer custom spice blends?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#servicesFAQ">
                            <div class="accordion-body">
                                Yes! We specialize in creating custom spice blends tailored to your specific needs. Our experts will work with you to develop the perfect blend for your recipes.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                What are your bulk order requirements?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#servicesFAQ">
                            <div class="accordion-body">
                                For bulk orders, we typically require a minimum order value of ₹5,000. However, we're flexible and can work with businesses of all sizes. Contact us for customized solutions.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                How do you ensure spice freshness?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#servicesFAQ">
                            <div class="accordion-body">
                                We use vacuum-sealed packaging and small-batch processing to ensure maximum freshness. Our spices are ground weekly and stored in optimal conditions to maintain potency.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to elements when they come into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all service cards and other elements
        document.querySelectorAll('.service-card, .quality-card, .process-step, .testimonial-card').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });

        // Smooth scroll for FAQ accordion
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });
    });
</script>
@endsection