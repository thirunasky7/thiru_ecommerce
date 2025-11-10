@extends('admin.layouts.admin')
@section('content')

<div class="card mt-4">
    <div class="card-header card-header-bg text-white">
        <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.products.title_create') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
            @csrf
            
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
                    <select name="product_type" class="form-select" id="productType" required>
                        <option value="simple">{{ __('cms.products.simple_product') }}</option>
                        <option value="variable">{{ __('cms.products.variable_product') }}</option>
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
                    <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="{{ $language->name }}" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">{{ __('cms.products.product_name') }} ({{ $language->code }}) *</label>
                            <input type="text" name="translations[{{ $language->code }}][name]" class="form-control" value="{{ old('translations.'.$language->code.'.name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('cms.products.description') }} ({{ $language->code }})</label>
                            <textarea name="translations[{{ $language->code }}][description]" class="form-control ck-editor-multi-languages">{{ old('translations.'.$language->code.'.description') }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Product Details -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.category') }} *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">{{ __('cms.products.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->translation->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.brand') }}</label>
                    <select name="brand_id" class="form-select">
                        <option value="">{{ __('cms.products.no_brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->translation->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div> 

            <!-- Simple Product Fields -->
            <div id="simple-product-fields" class="product-type-fields">
                <div class="card p-3 mt-3 border rounded">
                    <h5>{{ __('cms.products.product_details') }}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label>{{ __('cms.products.price') }} *</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.discount_price') }}</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price') }}">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.stock') }} *</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                        </div>
            
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.sku') }} *</label>
                            <input type="text" name="SKU" class="form-control" value="{{ old('SKU') }}" required>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.barcode') }}</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.weight') }}</label>
                            <input type="text" name="weight" class="form-control" value="{{ old('weight') }}" placeholder="e.g., 1.5 kg">
                        </div>
                        
                        <div class="col-md-6 mt-2">
                            <label>{{ __('cms.products.dimensions') }}</label>
                            <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}" placeholder="e.g., 10x20x5 cm">
                        </div>
                           
                        <div class="col-md-3 mt-2">
                            <label>{{ __('cms.products.size') }}</label>
                            <select name="size_id" class="form-control">
                                <option value="">{{ __('cms.products.select_size') }}</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
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
                                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
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
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>{{ __('cms.products.variant_name_en') }} *</label>
                            <input type="text" name="variants[__INDEX__][name]" class="form-control variant-name">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.price') }} *</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][price]" class="form-control variant-price">
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.discount_price') }}</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][discount_price]" class="form-control variant-discount-price">
                        </div>
            
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.stock') }} *</label>
                            <input type="number" name="variants[__INDEX__][stock]" class="form-control variant-stock">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.sku') }} *</label>
                            <input type="text" name="variants[__INDEX__][SKU]" class="form-control variant-sku">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.barcode') }}</label>
                            <input type="text" name="variants[__INDEX__][barcode]" class="form-control variant-barcode">
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.weight') }}</label>
                            <input type="text" name="variants[__INDEX__][weight]" class="form-control variant-weight" placeholder="e.g., 1.5 kg">
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.dimension') }}</label>
                            <input type="text" name="variants[__INDEX__][dimension]" class="form-control variant-dimension" placeholder="e.g., 10x20x5 cm">
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
                <div class="custom-file">
                    <label class="btn btn-primary" for="productImages">{{ __('cms.products.choose_file') }}</label>
                    <input type="file" name="images[]" class="form-control d-none" id="productImages" multiple onchange="previewMultipleImages(this)">
                </div>

                <!-- Preview Area -->
                <div id="productImagesPreview" class="mt-2 d-flex flex-wrap"></div>
            </div>
          
            <!-- Submit Button -->
            <div class="mt-4 text-start">
                <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('cms.products.save_product') }}</button>
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
            // Remove required attributes from variant fields when they're hidden
            removeVariantRequiredAttributes();
        } else {
            $('#variable-product-fields').show();
            // Add required attributes to variant fields when they're visible
            addVariantRequiredAttributes();
            // Ensure at least one variant exists
            if ($('#variants-wrapper').children().length === 0) {
                addVariant();
            }
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
    function addVariant() {
        const isFirstVariant = variantIndex === 0;
        const removeButtonHtml = isFirstVariant 
            ? '<button type="button" class="btn btn-sm btn-danger remove-variant-btn" style="display:none;">{{ __("cms.products.remove_variant") }}</button>'
            : '<button type="button" class="btn btn-sm btn-danger remove-variant-btn">{{ __("cms.products.remove_variant") }}</button>';
        
        const template = $('#variant-template').html()
            .replaceAll('__INDEX__', variantIndex)
            .replaceAll('__INDEX_PLUS_ONE__', variantIndex + 1)
            .replaceAll('__REMOVE_BUTTON__', removeButtonHtml);
        
        $('#variants-wrapper').append(template);
        
        // Add required attributes if we're in variable product mode
        if ($('#productType').val() === 'variable') {
            const newVariant = $('#variants-wrapper').find('.variant-item').last();
            newVariant.find('.variant-name').attr('required', 'required');
            newVariant.find('.variant-price').attr('required', 'required');
            newVariant.find('.variant-stock').attr('required', 'required');
            newVariant.find('.variant-sku').attr('required', 'required');
            newVariant.find('.variant-language').attr('required', 'required');
        }
        
        variantIndex++;
    }

    $('#add-variant-btn').click(addVariant);

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
            // Update the first variant's remove button visibility
            if (index === 0) {
                $(this).find('.remove-variant-btn').hide();
            } else {
                $(this).find('.remove-variant-btn').show();
            }
        });
    }

    // Custom form validation
    $('#productForm').on('submit', function(e) {
        const productType = $('#productType').val();
        let isValid = true;
        
        // Remove any existing custom validation messages
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        if (productType === 'simple') {
            // Validate simple product fields
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
            // Validate variable product fields
            const variantCount = $('.variant-item').length;
            if (variantCount === 0) {
                alert('Please add at least one variant for variable products.');
                isValid = false;
            } else {
                // Validate each variant
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
            // Scroll to first error
            $('.is-invalid').first().focus();
        } else {
            // Show loading state
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        }
    });

    // Initialize on page load
    $(document).ready(function () {
        // Show simple product fields by default
        $('#simple-product-fields').show();
        
        // Check if there's old input for product type
        @if(old('product_type'))
            $('#productType').val('{{ old('product_type') }}').trigger('change');
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