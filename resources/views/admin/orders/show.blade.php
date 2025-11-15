@extends('admin.layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Order #{{ $order->order_number }}</h1>
            <p class="text-muted mb-0">Placed on {{ $order->order_date->format('F j, Y \a\t g:i A') }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <!-- Order Status Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            @include('admin.orders.partials.status-badge', ['status' => $order->status])
                            <p class="mb-0 mt-2 text-muted">Last updated: {{ $order->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="fas fa-sync"></i> Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items by Delivery Date -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($groupedItems as $date => $items)
                    <div class="border-bottom">
                        <div class="p-3 bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-day text-primary"></i>
                                Delivery for: 
                                <strong>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</strong>
                                @if(\Carbon\Carbon::parse($date)->isToday())
                                    <span class="badge bg-success">Today</span>
                                @elseif(\Carbon\Carbon::parse($date)->isTomorrow())
                                    <span class="badge bg-warning">Tomorrow</span>
                                @endif
                            </h6>
                        </div>
                        <div class="p-3">
                            @foreach($items as $item)
                            <div class="row align-items-center mb-3 pb-3 border-bottom">
                                <div class="col-2">
                                    <img src="{{ $item->product_image ? asset('/public/storage/'.$item->product_image) : 'https://via.placeholder.com/80x80' }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="img-fluid rounded" style="max-height: 60px;">
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    <p class="text-muted mb-1 small">Qty: {{ $item->quantity }}</p>
                                    @if($item->meal_type != 'regular')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($item->meal_type) }}</span>
                                    @else
                                    <span class="badge bg-success">Regular</span>
                                    @endif
                                    @if($item->special_requests)
                                    <p class="text-info mb-0 small">
                                        <i class="fas fa-sticky-note"></i> {{ $item->special_requests }}
                                    </p>
                                    @endif
                                </div>
                                <div class="col-2 text-center">
                                    <strong>${{ number_format($item->unit_price, 2) }}</strong>
                                </div>
                                <div class="col-2">
                                    <select class="form-select form-select-sm item-status-select" 
                                            data-item-id="{{ $item->id }}"
                                            data-order-id="{{ $order->id }}">
                                        <option value="pending" {{ $item->preparation_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="preparing" {{ $item->preparation_status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="ready" {{ $item->preparation_status == 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="delivered" {{ $item->preparation_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ $order->customer_name }}</strong>
                        <br>
                        <i class="fas fa-phone text-muted"></i> {{ $order->customer_phone }}
                        @if($order->customer_email)
                        <br>
                        <i class="fas fa-envelope text-muted"></i> {{ $order->customer_email }}
                        @endif
                    </div>
                    <div>
                        <strong>Delivery Address:</strong>
                        <p class="mb-0 text-muted">{{ $order->customer_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total:</strong>
                        <strong>${{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Method:</strong>
                        <span class="badge bg-primary">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->order_notes)
                    <div>
                        <strong>Order Notes:</strong>
                        <p class="mb-0 text-muted small">{{ $order->order_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.item-status-select').change(function() {
        const itemId = $(this).data('item-id');
        const status = $(this).val();
        
        $.ajax({
            url: "{{ url('admin/order-items') }}/" + itemId + "/status",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                preparation_status: status
            },
            success: function(response) {
                toastr.success('Item status updated successfully');
            },
            error: function() {
                toastr.error('Error updating item status');
            }
        });
    });
});
</script>
@endsection
