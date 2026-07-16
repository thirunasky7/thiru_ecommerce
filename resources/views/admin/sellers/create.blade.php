@extends('admin.layouts.admin')

@section('title', 'Add Vendor')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.sellers.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back to vendors</a>
        <h1>Add vendor</h1>
        <p class="ad-muted">Invite a new seller to the marketplace</p>
    </div>
</div>

<form action="{{ route('admin.sellers.store') }}" method="POST" class="card">
    <div class="card-body">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                    <option value="rejected">Rejected</option>
                    <option value="banned">Banned</option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-dark">Create vendor</button>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-dark">Cancel</a>
        </div>
    </div>
</form>
@endsection
