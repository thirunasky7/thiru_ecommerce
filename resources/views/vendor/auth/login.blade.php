@extends('admin.layouts.login')

@section('title', 'Vendor Login — ThaiYur')

@section('content')
<div class="ty-admin-login">
    <div class="ty-admin-login__card">
        <div class="ty-admin-login__brand">
            <span class="ty-admin-login__mark">TY</span>
            <div>
                <h1>Vendor Login</h1>
                <p>Sell on ThaiYur marketplace</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('vendor.login.submit') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', 'food@thaiyur.com') }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror" required>
            </div>
            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Sign in as vendor</button>
        </form>

        <div class="ty-admin-login__hint">
            Demo: <code>food@thaiyur.com</code> / <code>password</code>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('vendor.register') }}" class="text-decoration-none me-3">Become a partner</a>
            <a href="{{ route('admin.login.form') }}" class="text-decoration-none me-3">Admin login</a>
            <a href="{{ url('/') }}" class="text-decoration-none">Storefront</a>
        </div>
    </div>
</div>
@endsection
