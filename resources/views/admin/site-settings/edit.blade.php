@extends('admin.layouts.admin')

@section('title', 'Edit Site Settings')

@section('content')
<div class="ad-page-head">
    <div>
        <a href="{{ route('admin.site-settings.index') }}" class="ad-muted small d-inline-block mb-2">&larr; Back</a>
        <h1>Edit site settings</h1>
        <p class="ad-muted">Brand, SEO, and contact details</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.site-settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="site_name">Site name</label>
                    <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings->site_name ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="tagline">Tagline</label>
                    <input type="text" name="tagline" id="tagline" class="form-control" value="{{ old('tagline', $settings->tagline ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="meta_title">Meta title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $settings->meta_title ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="meta_keywords">Meta keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings->meta_keywords ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="meta_description">Meta description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description', $settings->meta_description ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="contact_email">Contact email</label>
                    <input type="email" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="contact_phone">Contact phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $settings->address ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label" for="footer_text">Footer text</label>
                    <textarea name="footer_text" id="footer_text" class="form-control" rows="2">{{ old('footer_text', $settings->footer_text ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark">Save settings</button>
                <a href="{{ route('admin.site-settings.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
