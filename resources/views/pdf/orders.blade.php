@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $orders->count() }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">₹{{ number_format($orders->sum('total'), 2) }}</div>
            <div class="stat-label">Total Value</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'completed')->count() }}</div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Delivery</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->created_at ? $order->created_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        @switch($order->status)
                            @case('completed')
                                <span class="badge badge-success">Completed</span>
                                @break
                            @case('pending')
                                <span class="badge badge-warning">Pending</span>
                                @break
                            @case('cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                                @break
                            @default
                                <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                        @endswitch
                    </td>
                    <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
                    <td>{{ $order->payment_method ?? 'N/A' }}</td>
                    <td>{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : 'TBD' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($orders->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No orders found</h3>
            <p>No orders match the current filters.</p>
        </div>
    @endif
@endsection
