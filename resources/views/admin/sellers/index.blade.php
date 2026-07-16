@extends('admin.layouts.admin')

@section('title', 'Vendors')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Vendors</h1>
        <p class="ad-muted">Multivendor partners selling on ThaiYur</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($pendingCount > 0)
            <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}" class="btn btn-outline-dark btn-sm">
                {{ $pendingCount }} pending approval{{ $pendingCount > 1 ? 's' : '' }}
            </a>
        @endif
        <a href="{{ route('admin.sellers.create') }}" class="btn btn-dark btn-sm">
            <i class="fas fa-plus me-1"></i> Add vendor
        </a>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap mb-3">
    <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm {{ empty($status) ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}" class="btn btn-sm {{ ($status ?? '') === 'pending' ? 'btn-dark' : 'btn-outline-dark' }}">Pending</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'active']) }}" class="btn btn-sm {{ ($status ?? '') === 'active' ? 'btn-dark' : 'btn-outline-dark' }}">Active</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ ($status ?? '') === 'rejected' ? 'btn-dark' : 'btn-outline-dark' }}">Rejected</a>
    <a href="{{ route('admin.sellers.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ ($status ?? '') === 'inactive' ? 'btn-dark' : 'btn-outline-dark' }}">Inactive</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Partner</th>
                    <th>Contact</th>
                    <th>Shop / Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sellers as $seller)
                    @php
                        $shop = $seller->shops->first();
                        $badge = match($seller->status) {
                            'active' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            'banned' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ $sellers->firstItem() + $loop->index }}</td>
                        <td>
                            <strong>{{ $seller->business_name ?: $seller->name }}</strong>
                            @if($seller->business_name)
                                <div class="small text-muted">{{ $seller->name }}</div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $seller->email }}</div>
                            <div class="small text-muted">{{ $seller->phone ?? '—' }}</div>
                        </td>
                        <td>
                            @if($shop)
                                <div>{{ $shop->name }}</div>
                                <div class="small text-muted">{{ optional($shop->serviceType)->name ?? '—' }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($seller->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($seller->status === 'pending')
                                    <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-dark">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this partner application?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this vendor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No vendors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($sellers, 'links'))
        <div class="card-footer">{{ $sellers->links() }}</div>
    @endif
</div>
@endsection
