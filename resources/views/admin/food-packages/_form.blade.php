@php
    $p = $package;
    $selectedMeals = old('meal_types', $p->meal_types ?? ['lunch']);
    $sampleText = old('sample_menu_text', implode("\n", $p->sample_menu ?? []));
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Package name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $p->name ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Badge</label>
        <input type="text" name="badge" class="form-control" placeholder="Best value" value="{{ old('badge', $p->badge ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $p->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">What's included</label>
        <textarea name="includes" class="form-control" rows="3" placeholder="Daily fresh meals, door delivery, customizable spice level...">{{ old('includes', $p->includes ?? '') }}</textarea>
    </div>
    <div class="col-md-3">
        <label class="form-label">Price (₹) *</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $p->price ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Compare price</label>
        <input type="number" step="0.01" name="compare_price" class="form-control" value="{{ old('compare_price', $p->compare_price ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Duration (days) *</label>
        <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', $p->duration_days ?? 30) }}" min="7" max="90" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Meals / day *</label>
        <input type="number" name="meals_per_day" class="form-control" value="{{ old('meals_per_day', $p->meals_per_day ?? 1) }}" min="1" max="5" required>
    </div>
    <div class="col-12">
        <label class="form-label d-block">Meal types</label>
        @foreach(['breakfast','lunch','dinner','snack'] as $meal)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="meal_types[]" value="{{ $meal }}" id="meal_{{ $meal }}"
                    {{ in_array($meal, (array)$selectedMeals) ? 'checked' : '' }}>
                <label class="form-check-label" for="meal_{{ $meal }}">{{ ucfirst($meal) }}</label>
            </div>
        @endforeach
    </div>
    <div class="col-12">
        <label class="form-label">Sample menu (one item per line)</label>
        <textarea name="sample_menu_text" class="form-control" rows="5" placeholder="Mon: Pad Thai&#10;Tue: Green Curry&#10;Wed: Butter Chicken">{{ $sampleText }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Vendor</label>
        <select name="vendor_id" class="form-select">
            <option value="">— Select —</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $p->vendor_id ?? '') == $vendor->id)>
                    {{ $vendor->business_name ?? $vendor->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Shop</label>
        <select name="shop_id" class="form-select">
            <option value="">— Select —</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" @selected(old('shop_id', $p->shop_id ?? '') == $shop->id)>
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Sort order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $p->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Max subscribers</label>
        <input type="number" name="max_subscribers" class="form-control" value="{{ old('max_subscribers', $p->max_subscribers ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Available from</label>
        <input type="date" name="available_from" class="form-control" value="{{ old('available_from', optional($p->available_from ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Available until</label>
        <input type="date" name="available_until" class="form-control" value="{{ old('available_until', optional($p->available_until ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" @checked(old('status', $p->status ?? true))>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featured" @checked(old('is_featured', $p->is_featured ?? false))>
            <label class="form-check-label" for="featured">Featured on storefront</label>
        </div>
    </div>
</div>
