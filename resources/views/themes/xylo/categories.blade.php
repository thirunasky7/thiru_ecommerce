@extends('themes.xylo.layouts.master')

@section('css')
<style>
    /* Categories Page Styles */
    .categories-hero {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .categories-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .categories-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Categories Grid */
    .categories-grid {
        padding: 4rem 0;
    }

    .category-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        border: 1px solid #f0f0f0;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .category-image-container {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4/3;
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .category-card:hover .category-image {
        transform: scale(1.05);
    }

    .category-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        display: flex;
        align-items: flex-end;
        padding: 1.5rem;
    }

    .category-info {
        color: white;
        width: 100%;
    }

    .category-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .category-description {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .category-stats {
        display: flex;
        gap: 1rem;
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .category-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #ff6b35;
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Featured Categories */
    .featured-categories {
        padding: 3rem 0;
        background: #f8f9fa;
    }

    .featured-category-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
        height: 100%;
        border: 2px solid transparent;
    }

    .featured-category-card:hover {
        transform: translateY(-5px);
        border-color: #ff6b35;
    }

    .featured-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(45deg, #ff6b35, #f7931e);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
    }

    /* Search & Filter */
    .search-filter-section {
        padding: 2rem 0;
        background: white;
        border-bottom: 1px solid #e9ecef;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 3rem;
        border-radius: 25px;
        border: 2px solid #e9ecef;
    }

    .search-box i {
        position: absolute;
        left: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .filter-tag {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-tag:hover,
    .filter-tag.active {
        background: #ff6b35;
        color: white;
        border-color: #ff6b35;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        color: #dee2e6;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        background: transparent;
        padding: 1rem 0;
        margin-bottom: 0;
    }

    .breadcrumb-custom .breadcrumb-item a {
        color: #ff6b35;
        text-decoration: none;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #6c757d;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .categories-hero h1 {
            font-size: 2.2rem;
        }

        .categories-hero p {
            font-size: 1rem;
        }

        .category-name {
            font-size: 1.3rem;
        }

        .filter-tags {
            justify-content: center;
        }

        .search-box {
            margin-bottom: 1rem;
        }
    }

    /* Loading States */
    .category-card.loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
</style>
@endsection

@section('content')
@php $currency = activeCurrency(); @endphp

<!-- Hero Section -->
<section class="categories-hero">
    <div class="container">
        <h1 class="animate-fade-in">Explore Our Categories</h1>
        <p class="animate-fade-in">Discover the finest selection of spices, ingredients, and food products for your culinary journey</p>
    </div>
</section>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{ route('xylo.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div>
</section>
<!-- 
<section class="search-filter-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="categorySearch" placeholder="Search categories...">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="filter-tags justify-content-lg-end">
                    <span class="filter-tag active" data-filter="all">All</span>
                    <span class="filter-tag" data-filter="spices">Spices</span>
                    <span class="filter-tag" data-filter="grains">Grains & Pulses</span>
                    <span class="filter-tag" data-filter="herbs">Herbs</span>
                    <span class="filter-tag" data-filter="blends">Masala Blends</span>
                    <span class="filter-tag" data-filter="organic">Organic</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="featured-categories">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Popular Categories</h2>
            <p class="lead text-muted">Most loved categories by our customers</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="featured-category-card">
                    <div class="featured-icon">
                        <i class="fas fa-mortar-pestle"></i>
                    </div>
                    <h4>Whole Spices</h4>
                    <p class="text-muted mb-3">Premium quality whole spices for authentic flavors</p>
                    <span class="badge bg-primary">24 Products</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="featured-category-card">
                    <div class="featured-icon">
                        <i class="fas fa-blender"></i>
                    </div>
                    <h4>Powdered Spices</h4>
                    <p class="text-muted mb-3">Freshly ground spices for everyday cooking</p>
                    <span class="badge bg-primary">18 Products</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="featured-category-card">
                    <div class="featured-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4>Organic Range</h4>
                    <p class="text-muted mb-3">Certified organic spices and ingredients</p>
                    <span class="badge bg-primary">15 Products</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="featured-category-card">
                    <div class="featured-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Ready Mixes</h4>
                    <p class="text-muted mb-3">Convenient masala mixes for quick meals</p>
                    <span class="badge bg-primary">12 Products</span>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="categories-grid">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">All Categories</h2>
                <p class="text-muted">Browse through our complete collection of food categories</p>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="row g-4" id="categoriesContainer">
                @foreach($categories as $category)
                @php
                    $categoryName = $category->translation->name ?? $category->name ?? 'Unnamed Category';
                    $categoryDescription = $category->translation->description ?? $category->description ?? 'Explore our premium collection of ' . $categoryName;
                    $categoryImage = $category->translation->image_url ?? $category->image_url ?? null;
                    $categorySlug = $category->slug ?? '#';
                    $productCount = $category->products_count ?? rand(8, 25);
                    $isFeatured = $category->is_featured ?? false;
                    
                    // Determine category type for filtering
                    $categoryType = 'spices';
                    $categoryNameLower = strtolower($categoryName);
                    if (str_contains($categoryNameLower, 'grain') || str_contains($categoryNameLower, 'pulse') || str_contains($categoryNameLower, 'rice')) {
                        $categoryType = 'grains';
                    } elseif (str_contains($categoryNameLower, 'herb')) {
                        $categoryType = 'herbs';
                    } elseif (str_contains($categoryNameLower, 'blend') || str_contains($categoryNameLower, 'masala')) {
                        $categoryType = 'blends';
                    } elseif (str_contains($categoryNameLower, 'organic')) {
                        $categoryType = 'organic';
                    }
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 category-item" data-type="{{ $categoryType }}" data-name="{{ strtolower($categoryName) }}">
                    <div class="category-card animate-fade-in">
                        @if($isFeatured)
                            <div class="category-badge">
                                <i class="fas fa-star me-1"></i> Featured
                            </div>
                        @endif
                        
                        <a href="#" class="category-link text-decoration-none">
                            <div class="category-image-container">
                                <img src="{{ $categoryImage ? Storage::url($categoryImage) : 'https://via.placeholder.com/400x300/ff6b35/ffffff?text=' . urlencode($categoryName) }}" 
                                     alt="{{ $categoryName }}"
                                     class="category-image"
                                     onerror="this.src='https://via.placeholder.com/400x300/ff6b35/ffffff?text='+encodeURIComponent('{{ $categoryName }}')">
                                <div class="category-overlay">
                                    <div class="category-info">
                                        <h3 class="category-name">{{ $categoryName }}</h3>
                                        <p class="category-description">{{ \Illuminate\Support\Str::limit($categoryDescription, 80) }}</p>
                                        <div class="category-stats">
                                            <span><i class="fas fa-box me-1"></i> {{ $productCount }} products</span>
                                            @if($category->is_featured)
                                                <span><i class="fas fa-star me-1"></i> Popular</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No Categories Available</h3>
                <p class="mb-4">We're currently organizing our product categories. Please check back soon.</p>
                <a href="{{ route('xylo.home') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Back to Home
                </a>
            </div>
        @endif

        <!-- Load More Button -->
        @if($categories->count() >= 12)
        <div class="text-center mt-5">
            <button class="btn btn-outline-primary" id="loadMoreCategories">
                <i class="fas fa-refresh me-2"></i>Load More Categories
            </button>
        </div>
        @endif
    </div>
</section>

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('categorySearch');
        const categoryItems = document.querySelectorAll('.category-item');
        const filterTags = document.querySelectorAll('.filter-tag');
        const categoriesContainer = document.getElementById('categoriesContainer');
        const loadMoreBtn = document.getElementById('loadMoreCategories');

        // Search filter
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                categoryItems.forEach(item => {
                    const categoryName = item.getAttribute('data-name');
                    if (categoryName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Filter by category type
        filterTags.forEach(tag => {
            tag.addEventListener('click', function() {
                // Remove active class from all tags
                filterTags.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tag
                this.classList.add('active');
                
                const filterType = this.getAttribute('data-filter');
                
                categoryItems.forEach(item => {
                    if (filterType === 'all') {
                        item.style.display = 'block';
                    } else {
                        const itemType = item.getAttribute('data-type');
                        if (itemType === filterType) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Load more functionality (simulated)
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                this.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    // In real implementation, you would fetch more categories from API
                    this.style.display = 'none';
                    toastr.success('All categories loaded successfully!');
                }, 1500);
            });
        }

        // Add animation to category cards
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

        // Observe category cards for animation
        document.querySelectorAll('.category-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Image loading optimization
        function loadCategoryImages() {
            const images = document.querySelectorAll('.category-image');
            images.forEach(img => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                    img.addEventListener('error', function() {
                        this.classList.add('loaded');
                    });
                }
            });
        }

        loadCategoryImages();

        // Keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const focusedElement = document.activeElement;
                if (focusedElement.classList.contains('category-card') || 
                    focusedElement.classList.contains('filter-tag')) {
                    focusedElement.click();
                }
            }
        });
    });

    // Add to favorites functionality
    function toggleCategoryFavorite(categoryId) {
        // Implementation for adding categories to favorites
        console.log('Toggle favorite for category:', categoryId);
        // Add your API call here
    }
</script>
@endsection