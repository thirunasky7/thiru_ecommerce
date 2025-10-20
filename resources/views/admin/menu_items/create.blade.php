
{{--
@extends('admin.layouts.admin')

@section('content')
<div class="container">

    <!-- Card-like Heading for "Create Menu Item" -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h6>{{ __('cms.menu_items.create') }}</h6>
        </div>
    </div>

    <!-- Menu Item Form -->
    <div class="card mt-4">
        <div class="card-body">
            @if(session('error'))
                <div id="errorBar" class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            <!-- Form for creating Menu Item -->
            <form action="{{ route('admin.menus.items.store', $menu->id) }}" method="POST">
                @csrf

                               <!-- Select Menu -->
            <div class="mb-3">
                <label for="menu_id" class="form-label">Menus</label>
                <select class="form-select" id="menu_id" name="menu_id" required>
                    <option value="" disabled selected>{{ __('cms.menu_items.select_menu') }}</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                    @endforeach
                </select>
            </div>

                <!-- Order Number -->
                <div class="mb-3">
                    <label for="order_number" class="form-label">{{ __('cms.menu_items.order_number') }}</label>
                    <input type="number" name="order_number" id="order_number" class="form-control" required>
                </div>

                <!-- Parent Item -->
                <div class="mb-3">
                    <label for="parent_id" class="form-label">{{ __('cms.menu_items.parent_item') }}</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">{{ __('cms.menu_items.parent_none') }}</option>
                        @foreach($menu->menuItems as $item)
                            <option value="{{ $item->id }}">
                                @php
                                    $translation = $item->translations ? $item->translations->first() : null;
                                @endphp
                                {{ $translation->title ?? 'No Title' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dynamic Title Fields for Each Language -->
                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach ($languages as $language)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $language->code }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $language->code }}" type="button" role="tab">{{ ucwords($language->name) }}</button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabContent">
                    @foreach ($languages as $language)
                        <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="{{ $language->code }}" role="tabpanel">
                            <label class="form-label">{{ __('cms.menu_items.title') }} ({{ $language->code }})</label>
                            <input type="text" name="title_{{ $language->code }}" class="form-control" required>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-3 btn btn-success">{{ __('cms.menu_items.button') }}</button>
            </form>
        </div>
    </div>

</div>
@endsection

--}}



@extends('admin.layouts.admin')

@section('content')
    <div class="card mt-4">
        <div class="card-header card-header-bg text-white">
            <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.menu_items.create') }}
            </h6>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div id="errorBar" class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.menus.items.store', $menu->id) }}" method="POST">
                @csrf

                <!-- Title Fields for Each Language -->
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ($languages as $language)
                        <li class="nav-item">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ $language->code }}" type="button">
                                {{ ucwords($language->name) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3">
                    @foreach ($languages as $language)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $language->code }}">
                            <label class="form-label">{{ __('cms.menu_items.title') }} ({{ $language->code }})</label>
                            <input type="text" name="title[{{ $language->code }}]" class="form-control" required>
                        </div>
                    @endforeach
                </div>
                <br />

                <!-- Select Menu -->
                <div class="mb-3">
                    <label for="menu_id" class="form-label">{{ __('cms.menu_items.select_menu') }}</label>
                    <select class="form-select" id="menu_id" name="menu_id" required>
                        <option value="" disabled>{{ __('cms.menu_items.select_menu') }}</option>
                        @foreach($menus as $menuItem)
                            <option value="{{ $menuItem->id }}" {{ $menuItem->id == $menu->id ? 'selected' : '' }}>
                                {{ $menuItem->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Parent Item -->
                <div class="mb-3">
                    <label for="parent_id" class="form-label">{{ __('cms.menu_items.parent_item') }}</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">{{ __('cms.menu_items.parent_none') }}</option>
                        @foreach($menu->menuItems as $item)
                            <option value="{{ $item->id }}">
                                {{ optional($item->translations->first())->title ?? 'No Title' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Number -->
                <div class="mb-3">
                    <label for="order_number" class="form-label">{{ __('cms.menu_items.order_number') }}</label>
                    <input type="number" name="order_number" id="order_number" class="form-control" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-3 btn btn-success">{{ __('cms.menu_items.button') }}</button>
            </form>
        </div>
    </div>
@endsection


