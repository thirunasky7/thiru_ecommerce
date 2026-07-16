@extends('admin.layouts.admin')

@section('title', 'Create Menu')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.menus.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back</a>
        <h1>Create menu</h1>
        <p class="ad-muted">Add a navigation group for the storefront</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(session('error'))
            <div id="errorBar" class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">{{ __('cms.menus.menu_title') }}</label>
                <input type="text"
                       name="title"
                       id="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">{{ __('cms.menus.button_create') ?? 'Create' }}</button>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
