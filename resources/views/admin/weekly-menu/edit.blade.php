@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Edit Weekly Menu</h2>
              <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
@php 
$menu =$weeklymenu;
@endphp
            <form action="{{ route('admin.weeklymenu.update', $menu->id) }}" method="POST">
                @csrf
              
               
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Day</label>
                                <select name="day" class="form-select form-select-lg">
                                    @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $d)
                                        <option value="{{ $d }}" {{ $menu->day == $d ? 'selected' : '' }}>
                                            {{ ucfirst($d) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Meal Type</label>
                                <select name="meal_type" class="form-select form-select-lg">
                                    @foreach(['breakfast','lunch','dinner','snacks'] as $m)
                                        <option value="{{ $m }}" {{ $menu->meal_type == $m ? 'selected' : '' }}>
                                            {{ $m }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Multi Select Dropdown -->
                        <div class="mb-4" x-data="dropdownSelect()">
                            <label class="form-label fw-bold">Select Products</label>

                            <!-- Visible box -->
                            <div @click="open = !open"
                                class="form-control p-3 d-flex justify-content-between align-items-center border-2"
                                :class="open ? 'border-primary' : 'border-secondary'"
                                style="cursor:pointer; min-height: 50px;">
                                <span class="text-muted" x-text="selectedNames.length ? selectedNames.join(', ') : 'Click to select products...'"></span>
                                <i class="fa" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>

                            <!-- Dropdown -->
                            <div x-show="open" x-cloak
                                class="border border-2 border-primary rounded bg-white shadow-lg p-3 mt-2"
                                style="max-height:300px; overflow-y:auto; z-index:1050;">

                                <input type="text" 
                                    x-model="search" 
                                    @input="filterProducts()"
                                    class="form-control form-control-lg mb-3" 
                                    placeholder="Search products...">

                                <div class="products-list">
                                    <template x-for="product in filteredProducts" :key="product.id">
                                        <label class="d-flex align-items-center p-2 rounded hover-bg"
                                            :class="selected.includes(product.id.toString()) ? 'bg-light-primary' : ''"
                                            style="cursor:pointer; transition: background-color 0.2s;">
                                            <input type="checkbox"
                                                :value="product.id"
                                                x-model="selected"
                                                class="form-check-input me-3"
                                                style="transform: scale(1.2)">
                                            <span x-text="product.name" 
                                                :class="selected.includes(product.id.toString()) ? 'fw-bold text-primary' : 'text-dark'"></span>
                                        </label>
                                    </template>
                                </div>

                                <div x-show="filteredProducts.length === 0" class="text-center text-muted py-3">
                                    No products found
                                </div>
                            </div>

                            <!-- Selected products count -->
                            <div class="mt-2">
                                <small class="text-muted" x-text="`${selected.length} product(s) selected`"></small>
                            </div>

                            <!-- Hidden inputs -->
                            <template x-for="id in selected">
                                <input type="hidden" name="product_ids[]" :value="id">
                            </template>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" 
                                    value="active" {{ $menu->status == 1 ? 'checked' : '' }}
                                    style="transform: scale(1.5); margin-right: 10px;">
                                <label class="form-check-label fw-bold fs-6">
                                    Active Menu
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg px-4">
                                <i class="fa fa-save me-2"></i>Update Menu
                            </button>
                            <a href="{{ route('admin.weeklymenu.index') }}" class="btn btn-secondary btn-lg px-4">
                                <i class="fa fa-arrow-left me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-bg:hover {
    background-color: #f8f9fa !important;
}
.bg-light-primary {
    background-color: #e3f2fd !important;
}
[x-cloak] {
    display: none !important;
}
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

<script>
function dropdownSelect() {
    return {
        open: false,
        search: "",
        selected: @json($selectedProducts),
        allProducts: @json($products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->translation->name ?? $p->name
            ];
        })),
        
        get selectedNames() {
            return this.allProducts
                .filter(p => this.selected.includes(p.id.toString()))
                .map(p => p.name);
        },
        
        get filteredProducts() {
            if (!this.search) {
                return this.allProducts;
            }
            return this.allProducts.filter(product => 
                product.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        
        filterProducts() {
            // Reactivity is handled by the getter
        },
        
        init() {
            // Ensure selected values are strings to match checkbox values
            this.selected = this.selected.map(id => id.toString());
            
            console.log('Initialized with selected:', this.selected);
            console.log('All products:', this.allProducts);
        }
    }
}
</script>
@endsection