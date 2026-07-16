@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Dashboard</h1>
        <p class="ad-muted">Marketplace overview · updated {{ now()->format('g:i A') }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.orders.kitchen') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-utensils me-1"></i> Kitchen</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-dark btn-sm"><i class="fas fa-bag-shopping me-1"></i> Orders</a>
        <button onclick="updateStats()" class="btn btn-outline-dark btn-sm" type="button"><i class="fas fa-sync-alt me-1"></i> Refresh</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="ad-stat">
            <div class="ad-stat__label">Monthly revenue</div>
            <div class="ad-stat__value">₹{{ number_format($monthlyRevenue ?? 0, 0) }}</div>
            <div class="ad-stat__meta">
                <span class="text-{{ ($revenueGrowth ?? 0) >= 0 ? 'success' : 'danger' }}">
                    <i class="fas fa-{{ ($revenueGrowth ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($revenueGrowth ?? 0) }}%
                </span>
                vs last month
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="ad-stat ad-stat--green">
            <div class="ad-stat__label">Today's revenue</div>
            <div class="ad-stat__value" id="today-revenue">₹{{ number_format($todayRevenue ?? 0, 0) }}</div>
            <div class="ad-stat__meta">{{ now()->format('M j, Y') }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="ad-stat ad-stat--blue">
            <div class="ad-stat__label">Total orders</div>
            <div class="ad-stat__value" id="total-orders">{{ $totalOrders ?? 0 }}</div>
            <div class="ad-stat__meta">
                <span class="text-{{ ($orderGrowth ?? 0) >= 0 ? 'success' : 'danger' }}">
                    {{ abs($orderGrowth ?? 0) }}% growth
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="ad-stat ad-stat--orange">
            <div class="ad-stat__label">Pending orders</div>
            <div class="ad-stat__value" id="pending-orders">{{ $pendingOrders ?? 0 }}</div>
            <div class="ad-stat__meta">Needs attention</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Order mix</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-calendar-check text-warning me-1"></i> Packages / pre-orders</span>
                    <strong>{{ $preorderCount ?? 0 }}</strong>
                </div>
                <div class="progress mb-3" style="height:8px;border-radius:999px">
                    <div class="progress-bar bg-warning" style="width: {{ ($totalOrders ?? 0) > 0 ? (($preorderCount ?? 0)/$totalOrders)*100 : 0 }}%"></div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-bolt text-success me-1"></i> Regular orders</span>
                    <strong>{{ $regularOrderCount ?? 0 }}</strong>
                </div>
                <div class="progress" style="height:8px;border-radius:999px">
                    <div class="progress-bar bg-success" style="width: {{ ($totalOrders ?? 0) > 0 ? (($regularOrderCount ?? 0)/$totalOrders)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Today's kitchen</span>
                <a href="{{ route('admin.orders.kitchen') }}" class="ad-chip">Open</a>
            </div>
            <div class="card-body">
                @forelse(($todaysPreorders ?? []) as $mealType => $items)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-capitalize">{{ $mealType }}</span>
                        <span class="badge bg-dark">{{ count($items) }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No meal/package items scheduled for today.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Quick stats</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="ad-stat__label">Today orders</div>
                        <div class="fs-4 fw-semibold" id="today-orders">{{ $todayOrders ?? 0 }}</div>
                    </div>
                    <div class="col-6">
                        <div class="ad-stat__label">Preparing</div>
                        <div class="fs-4 fw-semibold">{{ $preparingOrders ?? 0 }}</div>
                    </div>
                    <div class="col-6">
                        <div class="ad-stat__label">Customers</div>
                        <div class="fs-4 fw-semibold">{{ $totalCustomers ?? 0 }}</div>
                    </div>
                    <div class="col-6">
                        <div class="ad-stat__label">Products</div>
                        <div class="fs-4 fw-semibold">{{ $totalProducts ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Catalog health</span>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark btn-sm">Manage products</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background:rgba(15,118,110,.06)">
                            <div class="ad-stat__label">Categories</div>
                            <div class="fs-3 fw-semibold">{{ $totalCategories ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background:rgba(196,92,38,.08)">
                            <div class="ad-stat__label">Low stock variants</div>
                            <div class="fs-3 fw-semibold">{{ $lowStockProducts ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background:rgba(27,79,114,.08)">
                            <div class="ad-stat__label">Avg order value</div>
                            <div class="fs-3 fw-semibold">₹{{ number_format($averageOrderValue ?? 0, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Shortcuts</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.food-packages.index') }}" class="btn btn-outline-dark">Monthly packages</a>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-dark">Vendors</a>
                <a href="{{ route('admin.orders.delivery-schedule') }}" class="btn btn-outline-dark">Delivery schedule</a>
                <a href="{{ route('admin.weeklymenu.index') }}" class="btn btn-outline-dark">Kitchen menu</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function updateStats() {
    fetch("{{ route('admin.dashboard.stats') }}")
        .then(r => r.json())
        .then(data => {
            if (data.today_revenue !== undefined) document.getElementById('today-revenue').textContent = '₹' + Number(data.today_revenue).toLocaleString();
            if (data.total_orders !== undefined) document.getElementById('total-orders').textContent = data.total_orders;
            if (data.pending_orders !== undefined) document.getElementById('pending-orders').textContent = data.pending_orders;
            if (data.today_orders !== undefined) document.getElementById('today-orders').textContent = data.today_orders;
        })
        .catch(() => {});
}
</script>
@endsection
