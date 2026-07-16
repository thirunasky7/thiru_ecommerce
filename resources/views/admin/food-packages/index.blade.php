@extends('admin.layouts.admin')

@section('title', 'Food Packages')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Monthly food packages</h1>
        <p class="ad-muted">Subscription-style meal plans for customers</p>
    </div>
    <a href="{{ route('admin.food-packages.create') }}" class="btn btn-dark btn-sm">
        <i class="fas fa-plus me-1"></i> Add package
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Vendor / Shop</th>
                    <th>Duration</th>
                    <th>Meals/day</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td>
                            <strong>{{ $package->name }}</strong>
                            @if($package->badge)
                                <span class="badge bg-warning text-dark ms-1">{{ $package->badge }}</span>
                            @endif
                            @if($package->is_featured)
                                <span class="badge bg-dark ms-1">Featured</span>
                            @endif
                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit($package->description, 60) }}</div>
                        </td>
                        <td>
                            <div>{{ optional($package->vendor)->business_name ?? optional($package->vendor)->name ?? '—' }}</div>
                            <div class="small text-muted">{{ optional($package->shop)->name }}</div>
                        </td>
                        <td>{{ $package->duration_days }} days</td>
                        <td>{{ $package->meals_per_day }} <small class="text-muted">({{ $package->meal_types_label }})</small></td>
                        <td>
                            <strong>₹{{ number_format($package->price, 0) }}</strong>
                            @if($package->compare_price)
                                <div class="small text-muted"><s>₹{{ number_format($package->compare_price, 0) }}</s></div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $package->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $package->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.food-packages.edit', $package) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                            <form action="{{ route('admin.food-packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No packages yet. Create your first monthly plan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($packages->hasPages())
        <div class="card-footer">{{ $packages->links() }}</div>
    @endif
</div>
@endsection
