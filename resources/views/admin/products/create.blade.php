
@extends('admin.layouts.admin')
@section('content')

<div class="card mt-4">
    <div class="card-header card-header-bg text-white">
        <h6 class="d-flex align-items-center mb-0 dt-heading">{{ __('cms.products.title_create') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
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
                        <label class="form-label">{{ __('cms.products.product_name') }} ({{ $language->code }})</label>
                        <input type="text" name="translations[{{ $language->code }}][name]" class="form-control" required>
                        <label class="form-label">{{ __('cms.products.description') }} ({{ $language->code }})</label>
                        <textarea name="translations[{{ $language->code }}][description]" class="form-control ck-editor-multi-languages"></textarea>
                    </div>
                @endforeach
            </div>
            
            <!-- Product Details -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.category') }}</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->translation->name ?? '—' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('cms.products.brand') }}</label>
                    <select name="brand_id" class="form-control">
                        <option value="">{{ __('cms.products.no_brand') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->translation->name ?? '—' }}</option>
                        @endforeach
                    </select>
                </div>
            </div> 

            <!-- Product Variants NEW -->
            <div id="variants-wrapper">
                <!-- Variants will be appended here -->
            </div>

            <button type="button" class="btn btn-sm btn-primary mt-3" id="add-variant-btn">{{ __('cms.products.add_variant') }}</button> 
            <template id="variant-template">
                <div class="card p-3 mt-3 variant-item border rounded" data-index="__INDEX__">
                    <h5>{{ __('cms.products.variants') }}<span class="variant-number">__INDEX__</span></h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label>{{ __('cms.products.variant_name_en') }}</label>
                            <input type="text" name="variants[__INDEX__][name]" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.price') }}</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][price]" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('cms.products.discount_price') }}</label>
                            <input type="number" step="0.01" name="variants[__INDEX__][discount_price]" class="form-control" />
                        </div>
            
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.stock') }}</label>
                            <input type="number" name="variants[__INDEX__][stock]" class="form-control" />
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.sku') }}</label>
                            <input type="text" name="variants[__INDEX__][SKU]" class="form-control" />
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.barcode') }}</label>
                            <input type="text" name="variants[__INDEX__][barcode]" class="form-control" />
                        </div>
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.weight') }}</label>
                            <input type="text" name="variants[__INDEX__][weight]" class="form-control" placeholder="e.g., 1.5 kg" />
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label>{{ __('cms.products.dimension') }}</label>
                            <input type="text" name="variants[__INDEX__][dimension]" class="form-control" placeholder="e.g., 10x20x5 cm" />
                        </div>
                           
                        <div class="col-md-6 mt-2">
                            <label>{{ __('cms.products.size') }}</label>
                            <select name="variants[__INDEX__][size_id]" class="form-control">
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->value }}</option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="col-md-6 mt-2">
                            <label>{{ __('cms.products.color') }}</label>
                            <select name="variants[__INDEX__][color_id]" class="form-control">
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </template>
            {{--
            <!-- Product Images -->
            <div class="mt-3">
                <label class="form-label">{{ __('cms.products.images') }}</label>
                <input type="file" name="images[]" class="form-control" id="productImages" multiple>
            </div>  --}}

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
                <button type="submit" class="btn btn-primary">{{ __('cms.products.save_product') }}</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    let variantIndex = 0;

    $('#add-variant-btn').click(function () {
        const template = $('#variant-template').html().replaceAll('__INDEX__', variantIndex);
        $('#variants-wrapper').append(template);
        variantIndex++;
    });

    $(document).ready(function () {
    $('#add-variant-btn').click(); 
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
                console.error(error);
            });
    });
</script>
@endsection 
