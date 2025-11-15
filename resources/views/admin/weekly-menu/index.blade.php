@extends('admin.layouts.admin')

@section('content')
<h2>Weekly Menu</h2>

<a href="{{ route('admin.weeklymenu.create') }}" class="btn btn-primary mb-2">Add Menu</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Day</th>
            <th>Meal Type</th>
            <th>Products</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($menus as $m)
        <tr>
            <td>{{ ucfirst($m->day) }}</td>
            <td>{{ ucfirst($m->meal_type) }}</td>
            <td>
                @foreach($m->product_ids ?? [] as $pid)
                    <span class="badge bg-info">
                        {{ optional(App\Models\Product::find($pid))->translation->name }}
                    </span>
                @endforeach
            </td>
            <td>
                @if($m->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Disabled</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.weeklymenu.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form method="POST" action="{{ route('admin.weeklymenu.delete', $m->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
