<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSpecCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
            'model_number' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['uuid'] = Str::uuid();
        $validated['created_by'] = Auth::id();


        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

         if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
            ]);
        }

        return redirect()->route('products.show', $product->uuid)->with('success', 'Product created successfully.');
    }

    public function show($uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'model_number' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['updated_by'] = Auth::id();

        if ($request->hasFile('image')) {
            // Optionally delete old image
            if ($product->image && \Storage::exists($product->image)) {
                \Storage::delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Keep old image if no new one is uploaded
            $validated['image'] = $product->image;
        }
        $product->update($validated);

        return redirect()->route('products.show', $product->uuid)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products')->with('success', 'Product deleted successfully.');
    }

    // app/Http/Controllers/ProductController.php
    public function getSpecs($id)
    {
        $product = Product::with(['specs.category'])->findOrFail($id);

        $data = $product->specs->map(function ($spec) {
            return [
                'key' => $spec->category->id,
                'value' => $spec->category->category
            ];
        });

        return response()->json($data);
    }

    public function getCategoryOptions($categoryId)
    {
        $category = ProductSpecCategory::with('options')->findOrFail($categoryId);
        return response()->json($category->options);
    }


}
