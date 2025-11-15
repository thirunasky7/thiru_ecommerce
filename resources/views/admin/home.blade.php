@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Last updated: </span>
            <span id="last-updated">{{ now()->format('g:i A') }}</span>
            <button onclick="updateStats()" class="btn btn-sm btn-outline-primary ms-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Revenue Row -->
    <div class="row">
        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Monthly Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($monthlyRevenue, 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($revenueGrowth) }}%
                                </span>
                                vs last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="today-revenue">
                                ${{ number_format($todayRevenue, 2) }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                {{ now()->format('M j, Y') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-orders">
                                {{ $totalOrders }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $orderGrowth >= 0 ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $orderGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($orderGrowth) }}%
                                </span>
                                growth
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-orders">
                                {{ $pendingOrders }}
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                Needs attention
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Type Breakdown -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Types</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">
                                <i class="fas fa-calendar-check"></i> Pre-orders
                            </span>
                            <strong>{{ $preorderCount }}</strong>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $totalOrders > 0 ? ($preorderCount/$totalOrders)*100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">
                                <i class="fas fa-bolt"></i> Regular Orders
                            </span>
                            <strong>{{ $regularOrderCount }}</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $totalOrders > 0 ? ($regularOrderCount/$totalOrders)*100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Pre-orders Overview -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Today's Pre-orders</h6>
                </div>
                <div class="card-body">
                    @forelse($todaysPreorders as $mealType => $items)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-capitalize {{ $mealType == 'breakfast' ? 'text-primary' : ($mealType == 'lunch' ? 'text-success' : 'text-info') }}">
                                <i class="fas fa-{{ $mealType == 'breakfast' ? 'sun' : ($mealType == 'lunch' ? 'sun' : 'moon') }}"></i>
                                {{ $mealType }}
                            </span>
                            <span class="badge bg-{{ $mealType == 'breakfast' ? 'primary' : ($mealType == 'lunch' ? 'success' : 'info') }}">
                                {{ count($items) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No pre-orders for today</p>
                    @endforelse
                    <div class="mt-3">
                        <a href="{{ route('admin.orders.kitchen') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-utensils"></i> Kitchen View
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Today's Orders</small>
                        <div class="h6" id="today-orders">{{ $todayOrders }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Preparing Orders</small>
                        <div class="h6">{{ $preparingOrders }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Weekly Revenue</small>
                        <div class="h6">${{ number_format($weeklyRevenue, 2) }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Avg Order Value</small>
                        <div class="h6">${{ number_format($averageOrderValue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Growth -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Customers</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="h1 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                        <p class="mb-2">Total Customers</p>
                        <div class="mb-3">
                            <span class="badge bg-success">
                                +{{ $newCustomers }} today
                            </span>
                        </div>
                        <div class="small text-{{ $customerGrowth >= 0 ? 'success' : 'danger' }}">
                            <i class="fas fa-{{ $customerGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($customerGrowth) }}% growth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Trend</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">#{{ $order->order_number }}</h6>
                                    <p class="mb-1 small text-muted">
                                        {{ $order->customer->name ?? 'Guest' }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $order->order_date->format('M j, g:i A') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted">No recent orders</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Update stats via AJAX
function updateStats() {
    $.get('{{ route("admin.dashboard.stats") }}', function(data) {
        $('#today-orders').text(data.today_orders);
        $('#pending-orders').text(data.pending_orders);
        $('#today-revenue').text('$' + parseFloat(data.today_revenue).toFixed(2));
        $('#last-updated').text(data.updated_at);
    });
}

// Auto-update every 2 minutes
setInterval(updateStats, 120000);

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($revenueChart['labels']),
        datasets: [
            {
                label: 'Total Revenue',
                data: @json($revenueChart['data']),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Pre-order Revenue',
                data: @json($revenueChart['preorder_data']),
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.1)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Regular Order Revenue',
                data: @json($revenueChart['regular_data']),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            }
        }
    }
});
</script>
@endpush