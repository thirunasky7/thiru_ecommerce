@extends('admin.layouts.login')

@section('title', 'Partner Registration — ThaiYur')

@section('content')
<div class="ty-admin-login">
    <div class="ty-admin-login__card" style="max-width:640px;">
        <div class="ty-admin-login__brand">
            <span class="ty-admin-login__mark">TY</span>
            <div>
                <h1>Become a partner</h1>
                <p>Apply to sell food, grocery, or products on ThaiYur</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.register.submit') }}" autocomplete="off">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Contact name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="business_name">Business name</label>
                    <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="service_type_id">Service type</label>
                    <select id="service_type_id" name="service_type_id" class="form-select" required>
                        <option value="">Select type</option>
                        @foreach($serviceTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('service_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="shop_name">Shop / kitchen name</label>
                    <input id="shop_name" type="text" name="shop_name" value="{{ old('shop_name') }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="city">City</label>
                    <input id="city" type="text" name="city" value="{{ old('city') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pincode">Pincode</label>
                    <input id="pincode" type="text" name="pincode" value="{{ old('pincode') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label" for="address">Address</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">About your business</label>
                    <textarea id="description" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>

            <p class="small text-muted mt-3 mb-3">
                Applications are reviewed by ThaiYur admin. You can sign in only after approval.
            </p>

            <button type="submit" class="btn btn-dark btn-lg w-100">Submit application</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('vendor.login') }}" class="text-decoration-none me-3">Already a partner? Sign in</a>
            <a href="{{ url('/') }}" class="text-decoration-none">Storefront</a>
        </div>
    </div>
</div>
@endsection
