@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $products->count() }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $products->where('stock', '>', 0)->count() }}</div>
            <div class="stat-label">In Stock</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?? 'N/A' }}</td>
                    <td class="text-right">₹{{ number_format($product->price, 2) }}</td>
                    <td class="text-center">{{ $product->stock ?? 0 }}</td>
                    <td>
                        @switch($product->status)
                            @case('active')
                                <span class="badge badge-success">Active</span>
                                @break
                            @case('inactive')
                                <span class="badge badge-danger">Inactive</span>
                                @break
                            @case('draft')
                                <span class="badge badge-warning">Draft</span>
                                @break
                            @default
                                <span class="badge badge-info">{{ ucfirst($product->status) }}</span>
                        @endswitch
                    </td>
                    <td>{{ $product->created_at ? $product->created_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $product->updated_at ? $product->updated_at->format('Y-m-d') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($products->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No products found</h3>
            <p>No products match the current filters.</p>
        </div>
    @endif
@endsection
