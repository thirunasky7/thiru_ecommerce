@extends('admin.layouts.admin')

@section('title', 'Review Details')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back to reviews</a>
        <h1>Review details</h1>
        <p class="ad-muted">Customer feedback for moderation</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Customer:</strong> {{ $review->customer->first_name }} {{ $review->customer->last_name }}</p>
        <p><strong>Product:</strong> {{ $review->product->title }}</p>
        <p><strong>Rating:</strong> {{ $review->rating }} / 5</p>
        <p><strong>Review:</strong> {{ $review->review }}</p>
        <p><strong>Status:</strong>
            @if($review->is_approved)
                <span class="badge bg-success">Approved</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </p>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-dark">Back to list</a>
    </div>
</div>
@endsection
