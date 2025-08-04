@extends('pdf.layout')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <h3>Order Information</h3>
            <table>
                <tr>
                    <td><strong>Order ID:</strong></td>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
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
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td>{{ $order->payment_method ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Created:</strong></td>
                    <td>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div>
            <h3>Customer Information</h3>
            <table>
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $order->customer->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td>{{ $order->customer->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>{{ $order->customer->address ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($order->products && count($order->products) > 0)
        <div class="page-break">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                            <td class="text-right">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    @if($order->notes)
        <div style="margin-top: 20px;">
            <h3>Notes</h3>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                {{ $order->notes }}
            </div>
        </div>
    @endif
@endsection
