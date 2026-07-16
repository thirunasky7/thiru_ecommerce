@extends('admin.layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="ad-page-head">
    <div>
        <h1>Site settings</h1>
        <p class="ad-muted">Brand name, SEO, and storefront metadata</p>
    </div>
    <a href="{{ route('admin.site-settings.edit') }}" class="btn btn-dark btn-sm">
        <i class="fas fa-pen me-1"></i> Edit settings
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Setting</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Site Name</td>
                    <td>{{ $settings->site_name ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Tagline</td>
                    <td>{{ $settings->tagline ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Meta Title</td>
                    <td>{{ $settings->meta_title ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Meta Description</td>
                    <td>{{ $settings->meta_description ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Meta Keywords</td>
                    <td>{{ $settings->meta_keywords ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Contact Email</td>
                    <td>{{ $settings->contact_email ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Contact Phone</td>
                    <td>{{ $settings->contact_phone ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $settings->address ?? 'Not set' }}</td>
                </tr>
                <tr>
                    <td>Footer Text</td>
                    <td>{{ $settings->footer_text ?? 'Not set' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
