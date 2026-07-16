@extends('admin.layouts.admin')

@section('title', 'Edit Food Package')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.food-packages.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back to packages</a>
        <h1>Edit package</h1>
        <p class="ad-muted">{{ $package->name }}</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.food-packages.update', $package) }}" class="card">
    <div class="card-body">
        @csrf
        @method('PUT')
        @include('admin.food-packages._form', ['package' => $package])
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-dark">Update package</button>
            <a href="{{ route('admin.food-packages.index') }}" class="btn btn-outline-dark">Cancel</a>
        </div>
    </div>
</form>
@endsection
