<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSpecCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\PdfExportService;

class ProductController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

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
        $product = Product::with([
                        'specs' => function ($query) {
                            $query->orderBy('id', 'asc');
                        },
                        'specs.category'
                    ])->findOrFail($id);

        $data = $product->specs->mapWithKeys(function ($spec) {
            $category = $spec->category;
            $slug = \Str::slug($category->category, '_'); // ← generates slug like "cooling_method"
            return [
                $category->id => [
                    'key' => $category->id,
                    'key_slug' => $slug,
                    'value' => $category->category,
                    'dynamic' => $category->is_dynamic
                ]
            ];
        })->toArray();
        ksort($data);
        return response()->json($data);
    }

    public function getCategoryOptions($categoryId)
    {
        $category = ProductSpecCategory::with('options')->findOrFail($categoryId);
        return response()->json($category->options);
    }

    /**
     * Export products to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Product::with(['createdBy']);

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('model_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            $filters['search'] = $request->search;
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            $query->where('status', $request->status_filter);
            $filters['status_filter'] = $request->status_filter;
        }

        $products = $query->get();

        return $this->pdfExportService->generateProductsPdf($products, $filters);
    }

    /**
     * Export a single product to PDF.
     */
    public function exportSinglePdf($uuid)
    {
        $product = Product::where('uuid', $uuid)->with(['orders', 'tickets'])->firstOrFail();
        
        return $this->pdfExportService->generateSingleProductPdf($product);
    }

}
