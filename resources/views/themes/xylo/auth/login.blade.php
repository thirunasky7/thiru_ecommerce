@extends('themes.xylo.layouts.master')

@section('title', 'Sign in — ThaiYur')

@section('content')
<section class="ty-section">
    <div class="container">
        <div class="ty-surface-panel mx-auto" style="max-width:440px">
            <div class="text-center mb-4">
                <div class="ty-brand justify-content-center mb-2">
                    <span class="ty-brand__mark">TY</span>
                    <span class="ty-brand__text">ThaiYur</span>
                </div>
                <h1 style="font-family:var(--ty-display);font-size:2rem">Welcome back</h1>
                <p class="text-muted mb-0">Sign in to track orders and manage your account</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email or phone</label>
                    <input type="text" name="login" value="{{ old('login', old('email', old('mobile'))) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="ty-btn ty-btn--solid w-100">Sign in</button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('customer.register') }}" class="ty-link">Create account</a>
            </div>
        </div>
    </div>
</section>
@endsection
