@extends('admin.layouts.admin')
@section('content')

<div class="card mt-4">
    <div class="card-header card-header-bg text-white">
        <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.products.title_edit') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
            @csrf
            @method('PUT')
            
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Product Type Selection -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.product_type') }} *</label>
                    <select name="product_type" class="form-control" id="productType" required>
                        <option value="simple" {{ old('product_type', $product->product_type) === 'simple' ? 'selected' : '' }}>
                            {{ __('cms.products.simple_product') }}
                        </option>
                        <option value="variable" {{ old('product_type', $product->product_type) === 'variable' ? 'selected' : '' }}>
                            {{ __('cms.products.variable_product') }}
                        </option>
                    </select>
                </div>
            </div>
            
            <!-- Multilingual Product Name & Description -->
            <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                @foreach($activeLanguages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $language->name }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $language->name }}" type="button" role="tab">{{ ucwords($language->name) }}</button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content mt-3" id="languageTabContent">
                @foreach($activeLanguages as $language)
                    @php
                        $translation = $product->translations->where('language_code', $language->code)->first();
                    @endphp
                    <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="{{ $language->name }}" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">{{ __('cms.products.product_name') }} ({{ $language->code }}) *</label>
                            <input type="text" name="translations[{{ $language->code }}][name]" class="form-control" 
                                   value="{{ old('translations.'.$language->code.'.name', $translation->name ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('cms.products.description') }} ({{ $language->code }})</label>
                            <textarea name="translations[{{ $language->code }}][description]" class="form-control ck-editor-multi-languages">
                                {{ old('translations.'.$language->code.'.description', $translation->description ?? '') }}
                            </textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Product Details -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.category') }} *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">{{ __('cms.products.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->translation->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.brand') }}</label>
                    <select name="brand_id" class="form-control">
                        <option value="">{{ __('cms.products.no_brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->translation->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div> 

            <!-- Simple Product Fields -->
            <div id="simple-product-fields" class="product-type-fields" style="display: none;">
                <div class="card p-3 mt-3 border rounded">
                    <h5>{{ __('cms.products.product_details') }}</h5>
                    <div class="row">
                        @php
                            $primaryVariant = $product->variants->first();
                        @endphp
                        <div class="col-md-4">
                            <label>{{ __('cms.products.price') }} *</label>
                            <input type="number" step="0.01" name="price" class="form-control" 
                                   value="{{ old('price', $primaryVariant->price ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.discount_price') }}</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" 
                                   value="{{ old('discount_price', $primaryVariant->discount_price ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.stock') }} *</label>
                            <input type="number" name="stock" class="form-control" 
                                   value="{{ old('stock', $primaryVariant->stock ?? '') }}" required>
                        </div>
            
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.sku') }} *</label>
                            <input type="text" name="SKU" class="form-control" 
                                   value="{{ old('SKU', $primaryVariant->SKU ?? '') }}" required>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.barcode') }}</label>
                            <input type="text" name="barcode" class="form-control" 
                                   value="{{ old('barcode', $primaryVariant->barcode ?? '') }}">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.weight') }}</label>
                            <input type="text" name="weight" class="form-control" 
                                   value="{{ old('weight', $primaryVariant->weight ?? '') }}" placeholder="e.g., 1.5 kg">
                        </div>
                        
                        <div class="col-md-6 mt-2">
                            <label>{{ __('cms.products.dimensions') }}</label>
                            <input type="text" name="dimensions" class="form-control" 
                                   value="{{ old('dimensions', $primaryVariant->dimensions ?? '') }}" placeholder="e.g., 10x20x5 cm">
                        </div>
                           
                        <div class="col-md-3 mt-2">
                            <label>{{ __('cms.products.size') }}</label>
                            <select name="size_id" class="form-control">
                                <option value="">{{ __('cms.products.select_size') }}</option>
                                @foreach($sizes as $size)
                                    @php
                                        $isSelected = false;
                                        if ($primaryVariant) {
                                            $isSelected = $primaryVariant->attributeValues->contains('id', $size->id);
                                        }
                                    @endphp
                                    <option value="{{ $size->id }}" {{ old('size_id') == $size->id || $isSelected ? 'selected' : '' }}>
                                        {{ $size->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="col-md-3 mt-2">
                            <label>{{ __('cms.products.color') }}</label>
                            <select name="color_id" class="form-control">
                                <option value="">{{ __('cms.products.select_color') }}</option>
                                @foreach($colors as $color)
                                    @php
                                        $isSelected = false;
                                        if ($primaryVariant) {
                                            $isSelected = $primaryVariant->attributeValues->contains('id', $color->id);
                                        }
                                    @endphp
                                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id || $isSelected ? 'selected' : '' }}>
                                        {{ $color->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variable Product Fields -->
            <div id="variable-product-fields" class="product-type-fields" style="display: none;">
                <div id="variants-wrapper">
                    <!-- Variants will be appended here -->
                </div>

                <button type="button" class="btn btn-sm btn-primary mt-3" id="add-variant-btn">{{ __('cms.products.add_variant') }}</button> 
            </div>

            <!-- Template for Variants -->
            <template id="variant-template">
                <div class="card p-3 mt-3 variant-item border rounded" data-index="__INDEX__">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('cms.products.variant') }} <span class="variant-number">__INDEX_PLUS_ONE__</span></h5>
                        <button type="button" class="btn btn-sm btn-danger remove-variant-btn">__REMOVE_BUTTON__</button>
                    </div>
                    <input type="hidden" name="variants[__INDEX__][id]" value="__VARIANT_ID__">
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>{{ __('cms.products.variant_name_en') }} *</label>
                            <input type="text" name="variants[__INDEX__][name]" class="form-control variant-name" value="__VARIANT_NAME__">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.price') }} *</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][price]" class="form-control variant-price" value="__VARIANT_PRICE__">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.discount_price') }}</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][discount_price]" class="form-control variant-discount-price" value="__VARIANT_DISCOUNT_PRICE__">
                        </div>
            
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.stock') }} *</label>
                            <input type="number" name="variants[__INDEX__][stock]" class="form-control variant-stock" value="__VARIANT_STOCK__">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.sku') }} *</label>
                            <input type="text" name="variants[__INDEX__][SKU]" class="form-control variant-sku" value="__VARIANT_SKU__">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.barcode') }}</label>
                            <input type="text" name="variants[__INDEX__][barcode]" class="form-control variant-barcode" value="__VARIANT_BARCODE__">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.weight') }}</label>
                            <input type="text" name="variants[__INDEX__][weight]" class="form-control variant-weight" value="__VARIANT_WEIGHT__" placeholder="e.g., 1.5 kg">
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.dimension') }}</label>
                            <input type="text" name="variants[__INDEX__][dimension]" class="form-control variant-dimension" value="__VARIANT_DIMENSION__" placeholder="e.g., 10x20x5 cm">
                        </div>
                           
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.size') }}</label>
                            <select name="variants[__INDEX__][size_id]" class="form-control variant-size">
                                <option value="">{{ __('cms.products.select_size') }}</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->value }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.color') }}</label>
                            <select name="variants[__INDEX__][color_id]" class="form-control variant-color">
                                <option value="">{{ __('cms.products.select_color') }}</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.language_code') }} *</label>
                            <select name="variants[__INDEX__][language_code]" class="form-control variant-language">
                                @foreach($activeLanguages as $language)
                                    <option value="{{ $language->code }}">{{ strtoupper($language->code) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Product Images -->
            <div class="mt-3">
                <label class="form-label">{{ __('cms.products.images') }}</label>
                
                <!-- Existing Images -->
                @if($product->images->count() > 0)
                <div class="mb-3">
                    <label class="form-label">{{ __('cms.products.existing_images') }}</label>
                    <div class="d-flex flex-wrap">
                        @foreach($product->images as $image)
                            <div class="position-relative me-2 mb-2">
                                <img src="{{ Storage::url($image->image_url) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                        onclick="deleteImage({{ $image->id }})" title="Delete Image">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- New Images -->
                <div class="custom-file">
                    <label class="btn btn-primary" for="productImages">{{ __('cms.products.choose_new_files') }}</label>
                    <input type="file" name="images[]" class="form-control d-none" id="productImages" multiple onchange="previewMultipleImages(this)">
                </div>

                <!-- Preview Area -->
                <div id="productImagesPreview" class="mt-2 d-flex flex-wrap"></div>
            </div>
          
            <!-- Submit Button -->
            <div class="mt-4 text-start">
                <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('cms.products.update_product') }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('cms.common.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    let variantIndex = 0;

    // Product type toggle
    $('#productType').change(function() {
        const productType = $(this).val();
        $('.product-type-fields').hide();
        
        if (productType === 'simple') {
            $('#simple-product-fields').show();
            removeVariantRequiredAttributes();
        } else {
            $('#variable-product-fields').show();
            addVariantRequiredAttributes();
        }
    });

    // Remove required attributes from all variant fields
    function removeVariantRequiredAttributes() {
        $('.variant-name').removeAttr('required');
        $('.variant-price').removeAttr('required');
        $('.variant-stock').removeAttr('required');
        $('.variant-sku').removeAttr('required');
        $('.variant-language').removeAttr('required');
    }

    // Add required attributes to all variant fields
    function addVariantRequiredAttributes() {
        $('.variant-name').attr('required', 'required');
        $('.variant-price').attr('required', 'required');
        $('.variant-stock').attr('required', 'required');
        $('.variant-sku').attr('required', 'required');
        $('.variant-language').attr('required', 'required');
    }

    // Add variant function
    function addVariant(variantData = {}) {
        const isFirstVariant = variantIndex === 0;
        const removeButtonHtml = isFirstVariant 
            ? '<button type="button" class="btn btn-sm btn-danger remove-variant-btn" style="display:none;">{{ __("cms.products.remove_variant") }}</button>'
            : '<button type="button" class="btn btn-sm btn-danger remove-variant-btn">{{ __("cms.products.remove_variant") }}</button>';
        
        let template = $('#variant-template').html()
            .replaceAll('__INDEX__', variantIndex)
            .replaceAll('__INDEX_PLUS_ONE__', variantIndex + 1)
            .replaceAll('__REMOVE_BUTTON__', removeButtonHtml)
            .replaceAll('__VARIANT_ID__', variantData.id || '')
            .replaceAll('__VARIANT_NAME__', variantData.name || '')
            .replaceAll('__VARIANT_PRICE__', variantData.price || '')
            .replaceAll('__VARIANT_DISCOUNT_PRICE__', variantData.discount_price || '')
            .replaceAll('__VARIANT_STOCK__', variantData.stock || '')
            .replaceAll('__VARIANT_SKU__', variantData.SKU || '')
            .replaceAll('__VARIANT_BARCODE__', variantData.barcode || '')
            .replaceAll('__VARIANT_WEIGHT__', variantData.weight || '')
            .replaceAll('__VARIANT_DIMENSION__', variantData.dimension || '');
        
        $('#variants-wrapper').append(template);
        
        // Set selected values for dropdowns
        const newVariant = $('#variants-wrapper').find('.variant-item').last();
        if (variantData.size_id) {
            newVariant.find('.variant-size').val(variantData.size_id);
        }
        if (variantData.color_id) {
            newVariant.find('.variant-color').val(variantData.color_id);
        }
        if (variantData.language_code) {
            newVariant.find('.variant-language').val(variantData.language_code);
        }
        
        // Add required attributes if we're in variable product mode
        if ($('#productType').val() === 'variable') {
            newVariant.find('.variant-name').attr('required', 'required');
            newVariant.find('.variant-price').attr('required', 'required');
            newVariant.find('.variant-stock').attr('required', 'required');
            newVariant.find('.variant-sku').attr('required', 'required');
            newVariant.find('.variant-language').attr('required', 'required');
        }
        
        variantIndex++;
    }

    $('#add-variant-btn').click(() => addVariant());

    // Remove variant
    $(document).on('click', '.remove-variant-btn', function() {
        if ($('.variant-item').length > 1) {
            $(this).closest('.variant-item').remove();
            updateVariantNumbers();
        }
    });

    // Update variant numbers after removal
    function updateVariantNumbers() {
        $('.variant-item').each(function(index) {
            $(this).find('.variant-number').text(index + 1);
            if (index === 0) {
                $(this).find('.remove-variant-btn').hide();
            } else {
                $(this).find('.remove-variant-btn').show();
            }
        });
    }

    // Custom form validation (same as create form)
    $('#productForm').on('submit', function(e) {
        const productType = $('#productType').val();
        let isValid = true;
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        if (productType === 'simple') {
            const requiredFields = [
                { selector: 'input[name="price"]', message: 'Price is required' },
                { selector: 'input[name="stock"]', message: 'Stock is required' },
                { selector: 'input[name="SKU"]', message: 'SKU is required' }
            ];
            
            requiredFields.forEach(field => {
                const element = $(field.selector);
                if (!element.val().trim()) {
                    element.addClass('is-invalid');
                    element.after('<div class="invalid-feedback">' + field.message + '</div>');
                    isValid = false;
                }
            });
            
        } else {
            const variantCount = $('.variant-item').length;
            if (variantCount === 0) {
                alert('Please add at least one variant for variable products.');
                isValid = false;
            } else {
                $('.variant-item').each(function(index) {
                    const variantFields = [
                        { selector: '.variant-name', message: 'Variant name is required' },
                        { selector: '.variant-price', message: 'Variant price is required' },
                        { selector: '.variant-stock', message: 'Variant stock is required' },
                        { selector: '.variant-sku', message: 'Variant SKU is required' },
                        { selector: '.variant-language', message: 'Language code is required' }
                    ];
                    
                    variantFields.forEach(field => {
                        const element = $(this).find(field.selector);
                        if (!element.val().trim()) {
                            element.addClass('is-invalid');
                            element.after('<div class="invalid-feedback">' + field.message + '</div>');
                            isValid = false;
                        }
                    });
                });
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            $('.is-invalid').first().focus();
        } else {
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        }
    });

    // Initialize on page load
    $(document).ready(function () {
        const productType = '{{ $product->product_type }}';
        $('#productType').val(productType).trigger('change');
        
        // Load existing variants for variable products
        @if($product->product_type === 'variable' && $product->variants->count() > 0)
            @foreach($product->variants as $variant)
                @php
                    $sizeId = $variant->attributeValues->where('attribute.name', 'Size')->first()->id ?? null;
                    $colorId = $variant->attributeValues->where('attribute.name', 'Color')->first()->id ?? null;
                @endphp
                addVariant({
                    id: '{{ $variant->id }}',
                    name: '{{ $variant->translations->first()->name ?? $variant->name }}',
                    price: '{{ $variant->price }}',
                    discount_price: '{{ $variant->discount_price }}',
                    stock: '{{ $variant->stock }}',
                    SKU: '{{ $variant->SKU }}',
                    barcode: '{{ $variant->barcode }}',
                    weight: '{{ $variant->weight }}',
                    dimension: '{{ $variant->dimensions }}',
                    size_id: '{{ $sizeId }}',
                    color_id: '{{ $colorId }}',
                    language_code: '{{ $variant->translations->first()->language_code ?? 'en' }}'
                });
            @endforeach
        @endif
    });
</script>

<script>
    function previewMultipleImages(input) {
        const previewContainer = document.getElementById('productImagesPreview');
        previewContainer.innerHTML = '';
    
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail m-1';
                    img.style.maxWidth = '150px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function deleteImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch('{{ route('admin.products.delete-image', '') }}/' + imageId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }
    }
</script>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('.ck-editor-multi-languages').forEach((element) => {
        ClassicEditor
            .create(element)
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    });
</script>
@endsection