@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Order: {{ $order->title }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->company_name }})</p>

        <hr>
        <h5>Products</h5>
        @foreach ($order->orderProducts as $item)
        <div class="border p-3 mb-3">
            <p><strong>Product:</strong> {{ $item->product->name }}</p>
            <p><strong>Serial Number:</strong> {{ $item->serial_number ?? '-' }}</p>
            <p><strong>Configurations:</strong></p>
            @php $configs = json_decode($item->configurations ?? '{}', true); @endphp
            @if($configs)
            <ul>
                @foreach ($configs as $key => $val)
                <li><strong>{{ $key }}:</strong> {{ $val }}</li>
                @endforeach
            </ul>
            @else
            <p>—</p>
            @endif
        </div>
        @endforeach

        <div class="mt-3">
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit Order</a>
            <a href="{{ route('orders') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
