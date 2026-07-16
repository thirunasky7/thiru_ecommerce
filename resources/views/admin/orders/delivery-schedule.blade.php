@extends('admin.layouts.admin')

@section('title', 'Delivery Schedule')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Delivery schedule</h1>
        <p class="ad-muted">Upcoming deliveries for packages and meal orders</p>
    </div>
</div>

@forelse($schedule as $date => $groups)
    <div class="card mb-4">
        <div class="card-header">
            <strong>{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</strong>
        </div>
        <div class="card-body">
            @forelse($groups as $mealType => $items)
                <h6 class="text-uppercase ad-muted mt-2 mb-2" style="letter-spacing:.06em;font-size:.75rem;">{{ $mealType }}</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Item</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ optional($item->order)->order_number }}</td>
                                    <td>
                                        {{ $item->product_name }}
                                        @if($item->item_type === 'package')
                                            <span class="badge bg-warning text-dark">Package</span>
                                            <div class="small text-muted">
                                                {{ optional($item->package_start_date)->format('M j') }}
                                                – {{ optional($item->package_end_date)->format('M j') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ optional($item->order)->customer_name }}
                                        <div class="small text-muted">{{ optional($item->order)->customer_phone }}</div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><span class="badge bg-secondary">{{ optional($item->order)->status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="text-muted mb-0">No deliveries for this day.</p>
            @endforelse
        </div>
    </div>
@empty
    <div class="alert alert-info">No upcoming deliveries.</div>
@endforelse
@endsection
