@extends('admin.layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.orders.kitchen') }}" class="btn btn-warning">
                <i class="fas fa-utensils"></i> Kitchen Display
            </a>
            <a href="{{ route('admin.orders.delivery-schedule') }}" class="btn btn-info">
                <i class="fas fa-truck"></i> Delivery Schedule
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Order Type</label>
                    <select name="type" class="form-select">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="preorder" {{ request('type') == 'preorder' ? 'selected' : '' }}>Pre-orders</option>
                        <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Regular Orders</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Filter</label>
                    <select name="date" class="form-select">
                        <option value="all" {{ request('date') == 'all' ? 'selected' : '' }}>All Dates</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today's Orders</option>
                        <option value="tomorrow" {{ request('date') == 'tomorrow' ? 'selected' : '' }}>Tomorrow's Pre-orders</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                                @if($order->is_guest_order)
                                    <span class="badge bg-secondary">Guest</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $hasPreorder = $order->orderItems->where('meal_type', '!=', 'regular')->count() > 0;
                                    $hasRegular = $order->orderItems->where('meal_type', 'regular')->count() > 0;
                                @endphp
                                @if($hasPreorder && $hasRegular)
                                    <span class="badge bg-info">Mixed</span>
                                @elseif($hasPreorder)
                                    <span class="badge bg-warning">Pre-order</span>
                                @else
                                    <span class="badge bg-success">Regular</span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $order->orderItems->count() }} items
                                    @if($order->orderItems->where('meal_type', '!=', 'regular')->count() > 0)
                                        <br><span class="text-warning">{{ $order->orderItems->where('meal_type', '!=', 'regular')->count() }} pre-orders</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                <small>{{ $order->order_date->format('M j, Y') }}</small>
                                <br>
                                <small class="text-muted">{{ $order->order_date->format('g:i A') }}</small>
                            </td>
                            <td>
                                @php
                                    $earliestDate = $order->orderItems->min('order_for_date');
                                    $latestDate = $order->orderItems->max('order_for_date');
                                @endphp
                                @if($earliestDate == $latestDate)
                                    <small>{{ \Carbon\Carbon::parse($earliestDate)->format('M j, Y') }}</small>
                                @else
                                    <small>Multiple Dates</small>
                                @endif
                            </td>
                            <td>
                                @include('admin.orders.partials.status-badge', ['status' => $order->status])
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                            data-bs-toggle="dropdown">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                               data-bs-target="#statusModal{{ $order->id }}">
                                                <i class="fas fa-sync"></i> Change Status
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="fas fa-times"></i> Cancel Order
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Status Modal -->
                                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Order Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Order #{{ $order->order_number }}</strong></p>
                                                    <p>Customer: {{ $order->customer_name }}</p>
                                                    
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection