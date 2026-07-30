@extends('vendor.layouts.master')

@section('title', 'Brand images')

@section('content')
<div class="p-4">
    <h2 class="mb-1">Brand & shop images</h2>
    <p class="text-muted mb-4">Upload logo and cover photos for your storefront</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vendor.profile.update') }}" enctype="multipart/form-data" class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Contact name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $vendor->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business name</label>
                    <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $vendor->business_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $vendor->city) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $vendor->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vendor logo</label>
                    @if($vendor->logo)
                        <div class="mb-2"><img src="{{ media_url($vendor->logo) }}" alt="" style="height:64px;border-radius:12px;object-fit:cover;"></div>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vendor cover</label>
                    @if($vendor->cover_image)
                        <div class="mb-2"><img src="{{ media_url($vendor->cover_image) }}" alt="" style="height:64px;width:120px;border-radius:12px;object-fit:cover;"></div>
                    @endif
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                </div>

                @if($shop)
                    <div class="col-12"><hr><h5 class="mb-0">Primary shop</h5></div>
                    <div class="col-md-6">
                        <label class="form-label">Shop name</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $shop->name) }}">
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <label class="form-label">Shop logo</label>
                        @if($shop->logo)
                            <div class="mb-2"><img src="{{ media_url($shop->logo) }}" alt="" style="height:64px;border-radius:12px;object-fit:cover;"></div>
                        @endif
                        <input type="file" name="shop_logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shop cover</label>
                        @if($shop->cover_image)
                            <div class="mb-2"><img src="{{ media_url($shop->cover_image) }}" alt="" style="height:64px;width:120px;border-radius:12px;object-fit:cover;"></div>
                        @endif
                        <input type="file" name="shop_cover" class="form-control" accept="image/*">
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-dark">Save images</button>
            </div>
        </div>
    </form>
</div>
@endsection
