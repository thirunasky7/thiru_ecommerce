@extends('themes.xylo.layouts.master')

@section('css')
<style>
    /* About Us Page Styles */
    .about-hero {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 5rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .about-hero:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .about-hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .about-hero p {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    /* Story Section */
    .story-section {
        padding: 5rem 0;
        background: #f8f9fa;
    }

    .story-content {
        position: relative;
    }

    .year-marker {
        position: absolute;
        left: -100px;
        top: 0;
        background: #ff6b35;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Values Section */
    .values-section {
        padding: 5rem 0;
    }

    .value-card {
        text-align: center;
        padding: 2rem 1.5rem;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        background: white;
        border: 1px solid #f0f0f0;
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .value-icon {
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

    /* Team Section */
    .team-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .team-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .team-card:hover {
        transform: translateY(-5px);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        background: linear-gradient(45deg, #ff6b35, #f7931e);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .team-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Process Section */
    .process-section {
        padding: 5rem 0;
        background: #f8f9fa;
    }

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
        height: 3px;
        background: #ff6b35;
        transform: translateY(-50%);
    }

    .step-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff6b35;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 3px solid #ff6b35;
    }

    /* Stats Section */
    .stats-section {
        padding: 4rem 0;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: block;
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 600;
    }

    /* Quality Promise */
    .quality-promise {
        padding: 5rem 0;
    }

    .quality-badge {
        display: inline-flex;
        align-items: center;
        background: #e7f7ef;
        color: #0d6832;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        margin: 0.5rem 0.5rem 0.5rem 0;
    }

    .quality-badge i {
        margin-right: 0.5rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2.5rem;
        }

        .about-hero p {
            font-size: 1.1rem;
        }

        .year-marker {
            position: relative;
            left: 0;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .process-step:not(:last-child):after {
            display: none;
        }

        .stat-number {
            font-size: 2.5rem;
        }

        .team-card {
            margin-bottom: 2rem;
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
        animation: fadeInUp 0.8s ease-out;
    }

    /* Floating Elements */
    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
</style>
@endsection

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
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="{{ asset('assets/images/about-traditional.png') }}" 
                         alt="Traditional Spice Making" 
                         class="img-fluid rounded shadow-lg"
                         onerror="this.src='https://via.placeholder.com/600x400/ff6b35/ffffff?text=Traditional+Spice+Heritage'"
                         style="max-width: 100%; height: auto;">
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



<!-- Quality Promise Section -->
<section class="quality-promise">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="{{ asset('assets/images/quality-promise.png') }}" 
                         alt="Quality Promise" 
                         class="img-fluid rounded shadow"
                         onerror="this.src='https://via.placeholder.com/500x400/28a745/ffffff?text=Quality+Assurance'"
                         style="max-width: 100%; height: auto;">
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Our Quality Promise</h2>
                <p class="lead mb-4">
                    We take pride in delivering spices that not only enhance your cooking but also maintain the nutritional values and authentic flavors.
                </p>
                
                <div class="mb-4">
                    <h5><i class="fas fa-check text-success me-2"></i>100% Natural Ingredients</h5>
                    <p class="text-muted">No artificial colors, flavors, or preservatives. Just pure, natural spices as nature intended.</p>
                </div>

                <div class="mb-4">
                    <h5><i class="fas fa-check text-success me-2"></i>Traditional Preparation</h5>
                    <p class="text-muted">Using time-tested methods that preserve the essential oils and aromas of the spices.</p>
                </div>

                <div class="mb-4">
                    <h5><i class="fas fa-check text-success me-2"></i>Hygienic Processing</h5>
                    <p class="text-muted">State-of-the-art facilities maintaining the highest standards of hygiene and safety.</p>
                </div>

                <div class="mb-4">
                    <h5><i class="fas fa-check text-success me-2"></i>Freshness Guaranteed</h5>
                    <p class="text-muted">Small batch processing and vacuum sealing to ensure maximum freshness upon delivery.</p>
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
                    
                    // Animate stats counting
                    if (entry.target.classList.contains('stat-number')) {
                        animateValue(entry.target, 0, parseInt(entry.target.textContent), 2000);
                    }
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.value-card, .team-card, .process-step, .stat-item').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });

        // Number counting animation for stats
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value + (element.textContent.includes('+') ? '+' : '');
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Add floating animation to multiple elements
        document.querySelectorAll('.floating').forEach((element, index) => {
            element.style.animationDelay = (index * 0.5) + 's';
        });
    });
</script>
@endsection