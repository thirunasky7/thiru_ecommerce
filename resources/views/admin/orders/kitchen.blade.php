@extends('admin.layouts.admin')

@section('title', 'Kitchen Display')

@section('content')
<style>
    .kitchen-display {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    .meal-section {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .order-item {
        border-left: 4px solid #007bff;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .order-item.urgent {
        border-left-color: #dc3545;
        background: #fff5f5;
    }
    .order-item.ready {
        border-left-color: #28a745;
        background: #f8fff9;
    }
</style>

<div class="kitchen-display">
    <div class="ad-page-head">
        <div>
            <h1><i class="fas fa-utensils me-2"></i>Kitchen display</h1>
            <p class="ad-muted">Live prep board · auto-refreshes every 30s</p>
        </div>
        <div class="text-end">
            <div class="ad-muted">{{ now()->format('l, F j, Y') }}</div>
            <div class="ad-stat__value" id="current-time" style="font-size:1.75rem;">{{ now()->format('g:i:s A') }}</div>
        </div>
    </div>

        <div class="row">
            <!-- Today's Pre-orders -->
            <div class="col-lg-8">
                <div class="meal-section">
                    <h2 class="text-primary mb-4">
                        <i class="fas fa-calendar-day"></i> Today's meal & package orders
                        <span class="badge bg-primary fs-6">{{ $today->format('M j') }}</span>
                    </h2>

                    @if(isset($packageOrders) && $packageOrders->count())
                        <div class="alert alert-warning">
                            <strong>{{ $packageOrders->count() }}</strong> active monthly package subscription(s) covering today.
                        </div>
                    @endif
                    @forelse($todayPreorders as $mealType => $items)
                    <div class="mb-4">
                        <h4 class="text-warning border-bottom pb-2">
                            <i class="fas fa-{{ $mealType == 'breakfast' ? 'sun' : ($mealType == 'lunch' ? 'sun' : 'moon') }}"></i>
                            {{ ucfirst($mealType) }} Orders
                            <span class="badge bg-warning text-dark">{{ count($items) }}</span>
                        </h4>
                        
                        <div class="row">
                            @foreach($items as $item)
                            <div class="col-md-6 mb-3">
                                <div class="order-item {{ $item->preparation_status == 'ready' ? 'ready' : '' }} {{ $item->created_at->diffInMinutes(now()) > 30 ? 'urgent' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">{{ $item->product_name }}</h5>
                                        <span class="badge bg-{{ $item->preparation_status == 'ready' ? 'success' : 'primary' }}">
                                            {{ ucfirst($item->preparation_status) }}
                                        </span>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Qty:</strong> {{ $item->quantity }}
                                        | <strong>Order:</strong> #{{ $item->order->order_number }}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        <small>
                                            <i class="fas fa-user"></i> {{ $item->order->customer_name }}
                                            | <i class="fas fa-clock"></i> {{ $item->created_at->format('g:i A') }}
                                        </small>
                                    </p>
                                    @if($item->special_requests)
                                    <p class="mb-0 text-info">
                                        <i class="fas fa-sticky-note"></i> {{ $item->special_requests }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No pre-orders for today</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Regular Orders -->
            <div class="col-lg-4">
                <div class="meal-section">
                    <h3 class="text-success mb-4">
                        <i class="fas fa-bolt"></i> Regular Orders
                        <span class="badge bg-success">{{ count($regularOrders) }}</span>
                    </h3>
                    
                    @forelse($regularOrders as $item)
                    <div class="order-item {{ $item->preparation_status == 'ready' ? 'ready' : '' }} {{ $item->created_at->diffInMinutes(now()) > 20 ? 'urgent' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                            <span class="badge bg-{{ $item->preparation_status == 'ready' ? 'success' : 'primary' }}">
                                {{ ucfirst($item->preparation_status) }}
                            </span>
                        </div>
                        <p class="mb-1 small">
                            <strong>Qty:</strong> {{ $item->quantity }}
                            | <strong>Order:</strong> #{{ $item->order->order_number }}
                        </p>
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-user"></i> {{ $item->order->customer_name }}
                            | <i class="fas fa-clock"></i> {{ $item->created_at->format('g:i A') }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted">No regular orders</p>
                    </div>
                    @endforelse
                </div>

                <!-- Statistics -->
                <div class="meal-section">
                    <h4 class="text-info mb-3">
                        <i class="fas fa-chart-bar"></i> Today's Stats
                    </h4>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-primary text-white rounded">
                                <h4 class="mb-0">{{ $todayPreorders->flatten()->count() }}</h4>
                                <small>Pre-orders</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-success text-white rounded">
                                <h4 class="mb-0">{{ count($regularOrders) }}</h4>
                                <small>Regular Orders</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
// Update time every second
function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = 
        now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
}
setInterval(updateTime, 1000);

// Auto-refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
@endsection
