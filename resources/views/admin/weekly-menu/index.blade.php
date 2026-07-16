@extends('admin.layouts.admin')

@section('title', 'Weekly Menu')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Weekly menu</h1>
        <p class="ad-muted">Day-by-day meal offerings for the kitchen</p>
    </div>
    <a href="{{ route('admin.weeklymenu.create') }}" class="btn btn-dark btn-sm">
        <i class="fas fa-plus me-1"></i> Add menu
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Meal type</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th width="160">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($menus as $m)
                <tr>
                    <td><strong>{{ ucfirst($m->day) }}</strong></td>
                    <td>{{ ucfirst($m->meal_type) }}</td>
                    <td>
                        @foreach($m->product_ids ?? [] as $pid)
                            <span class="badge bg-secondary">
                                {{ optional(App\Models\Product::find($pid))->translation->name }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        @if($m->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Disabled</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.weeklymenu.edit', $m->id) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                        <form method="POST" action="{{ route('admin.weeklymenu.delete', $m->id) }}" class="d-inline" onsubmit="return confirm('Delete this menu entry?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No weekly menu entries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
