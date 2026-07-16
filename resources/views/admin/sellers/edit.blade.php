@extends('admin.layouts.admin')

@section('title', 'Edit Vendor')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.sellers.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back to vendors</a>
        <h1>Edit vendor</h1>
        <p class="ad-muted">{{ $seller->name }}</p>
    </div>
</div>

<form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST" class="card">
    <div class="card-body">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Contact name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $seller->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Business name</label>
                <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $seller->business_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $seller->email) }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $seller->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">New password <span class="ad-muted fw-normal">(leave blank to keep)</span></label>
                <input type="password" name="password" class="form-control">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $seller->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ $seller->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $seller->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="rejected" {{ $seller->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="banned" {{ $seller->status == 'banned' ? 'selected' : '' }}>Banned</option>
                </select>
            </div>
        </div>

        @if($seller->status === 'pending')
            <div class="mt-3 d-flex gap-2">
                <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-dark btn-sm">Approve partner</button>
                </form>
                <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" onsubmit="return confirm('Reject this application?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                </form>
            </div>
        @endif

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-dark">Update vendor</button>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-dark">Cancel</a>
        </div>
    </div>
</form>
@endsection
