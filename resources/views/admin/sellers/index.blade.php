@extends('admin.layouts.admin')

@section('title', 'Sellers')

@section('content')
<div class="container">
    <h2 class="mb-4">Sellers List</h2>
    
    <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary mb-3">Add New Seller</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sellers as $seller)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $seller->name }}</td>
                    <td>{{ $seller->email }}</td>
                    <td>{{ $seller->phone ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $seller->status == 'active' ? 'success' : ($seller->status == 'inactive' ? 'warning' : 'danger') }}">
                            {{ ucfirst($seller->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sellers->links() }}
</div>
@endsection
