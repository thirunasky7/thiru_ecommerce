@extends('admin.layouts.login')

@section('title', 'Admin Login — ThaiYur')

@section('content')
<div class="ty-admin-login">
    <div class="ty-admin-login__card">
        <div class="ty-admin-login__brand">
            <span class="ty-admin-login__mark">TY</span>
            <div>
                <h1>ThaiYur Admin</h1>
                <p>Multivendor marketplace control panel</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', 'admin@thaiyur.com') }}"
                       class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror" required>
            </div>
            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100">Sign in</button>
        </form>

        <div class="ty-admin-login__hint">
            Default: <code>admin@thaiyur.com</code> / <code>password</code>
        </div>
        <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="text-decoration-none">← Back to storefront</a>
        </div>
    </div>
</div>
@endsection
