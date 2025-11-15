{{-- resources/views/admin/orders/partials/status-badge.blade.php --}}
@php
    $statusColors = [
        'pending' => 'bg-secondary',
        'confirmed' => 'bg-primary', 
        'preparing' => 'bg-warning text-dark',
        'out_for_delivery' => 'bg-info',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger'
    ];
    
    $statusIcons = [
        'pending' => 'fas fa-clock',
        'confirmed' => 'fas fa-check-circle',
        'preparing' => 'fas fa-utensils',
        'out_for_delivery' => 'fas fa-truck',
        'delivered' => 'fas fa-box-check',
        'cancelled' => 'fas fa-times-circle'
    ];
@endphp

<span class="badge {{ $statusColors[$status] ?? 'bg-secondary' }}">
    <i class="{{ $statusIcons[$status] ?? 'fas fa-circle' }} me-1"></i>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>