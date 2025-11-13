@extends('admin.layouts.admin')

@section('css')
<style>
    body {
        background-color: #f8f9fa;
        color: #333333;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #66b3ff;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card.sales {
        border-left-color: #28a745;
    }
    
    .stat-card.orders {
        border-left-color: #007bff;
    }
    
    .stat-card.customers {
        border-left-color: #6f42c1;
    }
    
    .stat-card.products {
        border-left-color: #fd7e14;
    }
    
    .stat-card.revenue {
        border-left-color: #20c997;
    }
    
    .stat-card.pending {
        border-left-color: #ffc107;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .stat-change {
        font-size: 12px;
        font-weight: 500;
    }
    
    .stat-change.positive {
        color: #28a745;
    }
    
    .stat-change.negative {
        color: #dc3545;
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }
    
    .recent-orders {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    
    .order-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background: #cce7ff;
        color: #004085;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        font-size: 13px;
        text-transform: uppercase;
    }
    
    .top-products {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    
    .product-rank {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #66b3ff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    
    .product-rank.rank-1 {
        background: #ffd700;
    }
    
    .product-rank.rank-2 {
        background: #c0c0c0;
    }
    
    .product-rank.rank-3 {
        background: #cd7f32;
    }
    
    .quick-stats {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    
    .quick-stat-item {
        text-align: center;
        padding: 15px;
    }
    
    .quick-stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #66b3ff;
    }
    
    .quick-stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 500;
    }
    
    .dashboard-section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2d2d2d;
        display: flex;
        align-items: center;
    }
    
    .dashboard-section-title i {
        margin-right: 10px;
        color: #66b3ff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    
    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $todayOrders }}</div>
                    <div class="quick-stat-label">Today's Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $pendingOrders }}</div>
                    <div class="quick-stat-label">Pending Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $lowStockProducts }}</div>
                    <div class="quick-stat-label">Low Stock Items</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $newCustomers }}</div>
                    <div class="quick-stat-label">New Customers</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $totalProducts }}</div>
                    <div class="quick-stat-label">Total Products</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-number">{{ $totalCategories }}</div>
                    <div class="quick-stat-label">Categories</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card sales">
                <div class="stat-number">₹{{ number_format($totalSales, 2) }}</div>
                <div class="stat-label">Total Sales</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $salesGrowth }}% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orders">
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $orderGrowth }}% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card customers">
                <div class="stat-number">{{ $totalCustomers }}</div>
                <div class="stat-label">Total Customers</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $customerGrowth }}% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card revenue">
                <div class="stat-number">₹{{ number_format($averageOrderValue, 2) }}</div>
                <div class="stat-label">Average Order Value</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 5.2% from last month
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Data Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-md-8">
            <div class="chart-container">
                <div class="dashboard-section-title">
                    <i class="fas fa-chart-line"></i> Sales Overview (Last 30 Days)
                </div>
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col-md-4">
            <div class="chart-container">
                <div class="dashboard-section-title">
                    <i class="fas fa-chart-pie"></i> Order Status
                </div>
                <canvas id="orderStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Top Products Row -->
    <div class="row mt-4">
        <!-- Recent Orders -->
        <div class="col-md-8">
            <div class="recent-orders">
                <div class="dashboard-section-title">
                    <i class="fas fa-shopping-cart"></i> Recent Orders
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td> {{ $order->customer->unique_id }}</td>
                                <td>
                                    @if($order->customer_id)
                                        {{ $order->customer->name ?? 'N/A' }}
                                    @else
                                        {{ $order->guest_email }}
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="order-status status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-md-4">
            <div class="top-products">
                <div class="dashboard-section-title">
                    <i class="fas fa-star"></i> Top Selling Products
                </div>
                <div class="list-group">
                    @foreach($topProducts as $index => $product)
                    <div class="list-group-item d-flex align-items-center">
                        <div class="product-rank rank-{{ $index + 1 }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="mb-1">{{ $product->name }}</h6>
                            <small class="text-muted">{{ $product->total_sold }} sold • ₹{{ number_format($product->total_revenue, 2) }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
<!-- Inventory Alert -->
<div class="top-products mt-4">
    <div class="dashboard-section-title">
        <i class="fas fa-exclamation-triangle"></i> Low Stock Alert
    </div>
    <div class="list-group">
        @foreach($lowStockItems as $item)
        <div class="list-group-item d-flex align-items-center">
            <div class="me-3 text-warning">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">{{ $item->name }}</h6>
                <small class="text-muted">
                    Stock: {{ $item->stock }} • Min: {{ $item->min_stock }}
                    @if(isset($item->SKU))
                        • SKU: {{ $item->SKU }}
                    @endif
                </small>
            </div>
        </div>
        @endforeach
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChart['labels']) !!},
                datasets: [{
                    label: 'Sales ($)',
                    data: {!! json_encode($salesChart['data']) !!},
                    borderColor: '#66b3ff',
                    backgroundColor: 'rgba(102, 179, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Order Status Chart
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($orderStatusData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($orderStatusData['data']) !!},
                    backgroundColor: [
                        '#28a745',
                        '#007bff',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection