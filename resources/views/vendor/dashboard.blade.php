@extends('vendor.layouts.master')

@section('title', 'Vendor Dashboard — ThaiYur')

@section('content')
<div class="container-fluid">
    <h2 class="mb-1">Welcome back</h2>
    <p class="text-muted mb-4">Manage your products and store presence on ThaiYur.</p>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5>Products</h5>
                    <p class="text-muted">Add and update your catalog items.</p>
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-dark btn-sm">Manage products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5>Add product</h5>
                    <p class="text-muted">Create a new listing for your shop.</p>
                    <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-dark btn-sm">Create</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5>Storefront</h5>
                    <p class="text-muted">See how customers browse ThaiYur.</p>
                    <a href="{{ url('/') }}" class="btn btn-outline-dark btn-sm" target="_blank">Open site</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
