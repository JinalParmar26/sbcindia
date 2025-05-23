<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.serial_number' => 'nullable|string',
            'products.*.config_keys' => 'nullable|array',
            'products.*.config_values' => 'nullable|array',
        ]);

        $order = Order::create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'customer_id' => $request->customer_id,
        ]);

        foreach ($request->products as $productData) {
            $configurations = [];

            if (!empty($productData['config_keys']) && is_array($productData['config_keys'])) {
                foreach ($productData['config_keys'] as $idx => $key) {
                    $val = $productData['config_values'][$idx] ?? null;
                    if ($key !== null && $key !== '') {
                        $configurations[$key] = $val;
                    }
                }
            }
            $serial_number="SBC".date("YmdHis").rand(1,99);
            OrderProduct::create([
                'uuid' => Str::uuid(),
                'order_id' => $order->id,
                'product_id' => $productData['product_id'],
                'serial_number' => $serial_number,
                'configurations' => json_encode($configurations),
            ]);
        }

        return redirect()->route('orders.show', $order->uuid)->with('success', 'Order created successfully.');
    }

    public function show($uuid)
    {
        $order = Order::with('orderProducts.product', 'customer')->where('uuid', $uuid)->firstOrFail();
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(Request $request,Order $order)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.serial_number' => 'nullable|string',
            'products.*.config_keys' => 'nullable|array',
            'products.*.config_values' => 'nullable|array',
        ]);

        $order->update([
            'title' => $request->title,
            'customer_id' => $request->customer_id,
        ]);

        $order->orderProducts()->delete();

        // Add updated order products
        foreach ($request->products as $productData) {
            $configurations = [];

            if (!empty($productData['config_keys']) && is_array($productData['config_keys'])) {
                foreach ($productData['config_keys'] as $idx => $key) {
                    $val = $productData['config_values'][$idx] ?? null;
                    if ($key !== null && $key !== '') {
                        $configurations[$key] = $val;
                    }
                }
            }

            OrderProduct::create([
                'uuid' => Str::uuid(),
                'order_id' => $order->id,
                'product_id' => $productData['product_id'],
                'serial_number' => $productData['serial_number'],
                'configurations' => json_encode($configurations),
            ]);
        }

        return redirect()->route('orders.show', $order->uuid)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }
}
