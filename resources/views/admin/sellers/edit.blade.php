@extends('admin.layouts.admin')

@section('title', 'Edit Seller')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Seller</h2>

    <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $seller->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $seller->email) }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $seller->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $seller->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $seller->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="banned" {{ $seller->status == 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Seller</button>
        <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
