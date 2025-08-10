<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\OrderImage;
use App\Models\Ticket;
use App\Models\ProductSpecCategory;
use App\Models\ProductSpecOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\PdfExportService;

class OrderController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }


    public function store(Request $request)
    {
        // Debug logging
        \Log::info('=== ORDER STORE DEBUG ===');
        \Log::info('Form submitted with data:', $request->all());
        \Log::info('Has files:', ['has_files' => $request->hasFile('order_images')]);
        
        if ($request->hasFile('order_images')) {
            \Log::info('Files info:', [
                'count' => count($request->file('order_images')),
                'files' => array_map(function($file) {
                    return [
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'valid' => $file->isValid()
                    ];
                }, $request->file('order_images'))
            ]);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'product_configs' => 'required|array|min:1',
        ]);
        
        // Custom validation for images with AVIF support
        if ($request->hasFile('order_images')) {
            $validationResult = $this->validateImages($request);
            if ($validationResult !== true) {
                return $validationResult;
            }
        }
        
        \Log::info('Validation passed');

        $order = Order::create([
            'uuid' => \Str::uuid(),
            'title' => $request->title,
            'customer_id' => $request->customer_id,
        ]);

        // Handle product creation logic (existing code)
        $productId = $request->product_id;
        $product = Product::findOrFail($productId);
        $productModelBase = $product->model_number;
        $productName = strtoupper(substr($product->name, 0, 1));
        $year = now()->format('Y');

        $yearlyOrderCount = OrderProduct::where('product_id', $productId)
            ->whereYear('created_at', $year)
            ->count() + 1;

        $categories = ProductSpecCategory::orderBy('id')->get();
        $modelParts = [];
        $configurations = [];

        $modelPrefix = 'S-' . $productName;

        foreach ($categories as $category) {
            $key = \Str::slug($category->category, '_');

            $foundValue = null;

            foreach ($request->product_configs as $config) {
                if (isset($config[$key])) {
                    $foundValue = $config[$key];
                    break;
                }
            }

            if ($foundValue === null) {
                continue;
            }

            $cleanValue = strtolower(str_replace('_', '', $foundValue));
            $configurations[$category->category] = $foundValue;

            if ($category->is_dynamic == 0) {
                $optionVal = ProductSpecOption::where('spec_category', $category->id)
                            ->whereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(cat_option, '_', ''), ' ', ''), '\r', ''), '\n', '')) = ?", [$cleanValue])
                            ->value('option_val');
                if ($optionVal) {
                    $modelParts[] = strtoupper($optionVal);
                }
            } else {
                if ($key === 'compressor_configuration') {
                    $modelParts[] = strtoupper($foundValue) . 'S';
                } elseif ($key === 'temperature_range') {
                    $modelParts[] = strtoupper(substr($foundValue, 0, 1));
                } elseif ($key === 'temperature') {
                    $modelParts[] = strtoupper($foundValue);
                } else {
                    $modelParts[] = strtoupper($foundValue);
                }
            }
        }

        $modelNumber = $modelPrefix . implode('', $modelParts);
        
        // Generate unique serial number with better logic
        $serialNumber = $this->generateUniqueSerialNumber($year, $productModelBase);

        OrderProduct::create([
            'uuid' => \Str::uuid(),
            'order_id' => $order->id,
            'product_id' => $productId,
            'serial_number' => $serialNumber,
            'model_number' => $modelNumber,
            'configurations' => json_encode($configurations),
        ]);
        
        \Log::info('Order created:', ['order_id' => $order->id, 'uuid' => $order->uuid]);

        // Handle multiple image uploads
        if ($request->hasFile('order_images')) {
            \Log::info('Processing image uploads...');
            try {
                $this->handleImageUploads($request, $order);
                \Log::info('Image uploads completed successfully');
            } catch (\Exception $e) {
                \Log::error('Image upload failed:', ['error' => $e->getMessage()]);
                return back()->withErrors(['image_upload' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        \Log::info('Redirecting to order show page');
        return redirect()->route('orders.show', $order->uuid)->with('success', 'Order created successfully.');
    }






    public function show($uuid)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($uuid)) {
            $order = Order::with([
                'orderProducts.product',
                'orderProducts.tickets.customer',
                'orderProducts.tickets.assignedTo',
                'orderProducts.tickets.attendedBy',
                'customer'
            ])->findOrFail($uuid);
        } else {
            $order = Order::with([
                'orderProducts.product',
                'orderProducts.tickets.customer',
                'orderProducts.tickets.assignedTo',
                'orderProducts.tickets.attendedBy',
                'customer'
            ])->where('uuid', $uuid)->firstOrFail();
        }
        return view('orders.show', compact('order'));
    }

    public function edit($uuid)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($uuid)) {
            $order = Order::with(['orderProducts.product', 'customer'])->findOrFail($uuid);
        } else {
            $order = Order::with(['orderProducts.product', 'customer'])->where('uuid', $uuid)->firstOrFail();
        }
        
        $customers = Customer::all();
        $products = Product::all();

        // Prepare products data for form - get first (and only) product
        $productsData = [];

        if ($order && $order->orderProducts && $order->orderProducts->count() > 0) {
            // Get the first order product for single-product editing
            $orderProduct = $order->orderProducts->first();
            $config = json_decode($orderProduct->configurations, true);
            $productsData = [
                0 => [
                    'product_id' => $orderProduct->product_id,
                    'configurations' => $config, // Raw config array like store method
                ]
            ];
        } else {
            // If no existing products, provide empty structure
            $productsData = [
                0 => [
                    'product_id' => '',
                    'configurations' => [],
                ]
            ];
        }

        return view('orders.edit', compact('order', 'customers', 'products', 'productsData'));
    }

    public function uploadTest(Order $order)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.upload-test', compact('order', 'customers', 'products'));
    }

   public function update(Request $request, $uuid)
    {
        // Handle both UUID and ID parameters
        if (is_numeric($uuid)) {
            $order = Order::findOrFail($uuid);
        } else {
            $order = Order::where('uuid', $uuid)->firstOrFail();
        }
        
        // Debug: Log all request data
        \Log::info('Update Order Request Data:', [
            'order_id' => $order->id,
            'has_files' => $request->hasFile('order_images'),
            'files_count' => $request->hasFile('order_images') ? count($request->file('order_images')) : 0,
            'all_files' => $request->allFiles(),
            'input_names' => array_keys($request->all())
        ]);

        // Debug: Detailed file information
        if ($request->hasFile('order_images')) {
            foreach ($request->file('order_images') as $index => $file) {
                \Log::info("Image file {$index} details:", [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'client_mime_type' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage()
                ]);
            }
        }

        // First validate everything except images
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'product_configs' => 'required|array|min:1',
        ]);

        // Custom validation for images with better error handling
        if ($request->hasFile('order_images')) {
            $images = $request->file('order_images');
            $allowedMimes = ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp', 'avif'];
            $maxSize = 10240; // 10MB in KB
            
            foreach ($images as $index => $image) {
                if (!$image->isValid()) {
                    return back()->withErrors(["order_images.{$index}" => "Invalid image file uploaded."])->withInput();
                }
                
                $extension = strtolower($image->getClientOriginalExtension());
                if (!in_array($extension, $allowedMimes)) {
                    return back()->withErrors(["order_images.{$index}" => "The image must be a file of type: " . implode(', ', $allowedMimes) . "."])->withInput();
                }
                
                if ($image->getSize() > $maxSize * 1024) {
                    return back()->withErrors(["order_images.{$index}" => "The image may not be greater than 10MB."])->withInput();
                }
                
                // Check if it's actually an image - skip getimagesize for AVIF as it may not be supported
                if ($extension !== 'avif') {
                    $imageInfo = getimagesize($image->getRealPath());
                    if (!$imageInfo) {
                        return back()->withErrors(["order_images.{$index}" => "The uploaded file is not a valid image."])->withInput();
                    }
                } else {
                    // For AVIF files, just check if it's a valid uploaded file and has image mime type
                    $mimeType = $image->getMimeType();
                    if (!in_array($mimeType, ['image/avif'])) {
                        return back()->withErrors(["order_images.{$index}" => "The uploaded file is not a valid AVIF image."])->withInput();
                    }
                }
            }
        }

        $order->update([
            'title' => $request->title,
            'customer_id' => $request->customer_id,
        ]);

        // Delete old product configs
        $order->orderProducts()->delete();

        $productId = $request->product_id;
        $product = Product::findOrFail($productId);
        $productModelBase = $product->model_number;
        $productName = strtoupper(substr($product->name, 0, 1));
        $year = now()->format('Y');

        // Count existing orders in same year and same product_id
        $yearlyOrderCount = OrderProduct::where('product_id', $productId)
            ->whereYear('created_at', $year)
            ->count() + 1;

        // Build model number
        $categories = ProductSpecCategory::orderBy('id')->get();
        $modelParts = [];
        $configurations = [];

        $modelPrefix = 'S-' . $productName;

        foreach ($categories as $category) {
            $key = Str::slug($category->category, '_');

            $foundValue = null;

            // Look for the value in product_configs array
            foreach ($request->product_configs as $config) {
                if (isset($config[$key])) {
                    $foundValue = $config[$key];
                    break;
                }
            }

            if ($foundValue === null) {
                continue;
            }

            $cleanValue = strtolower(str_replace(['_', ' '], '', $foundValue));
            $configurations[$category->category] = $foundValue;

            if ($category->is_dynamic == 0) {
                $optionVal = ProductSpecOption::where('spec_category', $category->id)
                    ->whereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(cat_option, '_', ''), ' ', ''), '\r', ''), '\n', '')) = ?", [$cleanValue])
                    ->value('option_val');

                if ($optionVal) {
                    $modelParts[] = strtoupper($optionVal);
                }
            } else {
                if ($key === 'compressor_configuration') {
                    $modelParts[] = strtoupper($foundValue) . 'S';
                } elseif ($key === 'temperature_range') {
                    $modelParts[] = strtoupper(substr($foundValue, 0, 1));
                } elseif ($key === 'temperature') {
                    $modelParts[] = strtoupper($foundValue);
                } else {
                    $modelParts[] = strtoupper($foundValue);
                }
            }
        }

        $modelNumber = $modelPrefix . implode('', $modelParts);
        
        // Generate unique serial number with better logic
        $serialNumber = $this->generateUniqueSerialNumber($year, $productModelBase);

        OrderProduct::create([
            'uuid' => Str::uuid(),
            'order_id' => $order->id,
            'product_id' => $productId,
            'serial_number' => $serialNumber,
            'model_number' => $modelNumber,
            'configurations' => json_encode($configurations),
        ]);

        // Handle image uploads if provided
        if ($request->hasFile('order_images')) {
            \Log::info('Edit Order - Images found, processing upload for order: ' . $order->id);
            $this->handleImageUploads($request, $order);
        } else {
            \Log::info('Edit Order - No images found in request for order: ' . $order->id);
        }

        return redirect()->route('orders.show', $order->uuid)->with('success', 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }

    /**
     * Export orders to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Order::with(['customer', 'orderProducts.product']);

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            $filters['search'] = $request->search;
        }

        if ($request->filled('customer_filter') && $request->customer_filter != 'all') {
            $query->where('customer_id', $request->customer_filter);
            $filters['customer_filter'] = $request->customer_filter;
        }

        $orders = $query->get();

        return $this->pdfExportService->generateOrdersPdf($orders, $filters);
    }

    /**
     * Export single order to PDF
     */
    public function exportSinglePdf(Order $order)
    {
        $order->load(['customer', 'orderProducts.product']);
        return $this->pdfExportService->generateSingleOrderPdf($order);
    }

    /**
     * Export orders to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Order::with(['customer', 'orderProducts.product']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('customer_filter') && $request->customer_filter != 'all') {
            $query->where('customer_id', $request->customer_filter);
        }

        if ($request->filled('year_filter') && $request->year_filter != 'all') {
            $query->whereYear('created_at', $request->year_filter);
        }

        if ($request->filled('month_filter') && $request->month_filter != 'all') {
            $query->whereMonth('created_at', $request->month_filter);
        }

        $orders = $query->get();

        $filename = 'orders_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Order ID',
                'Customer Name',
                'Title',
                'Total Amount',
                'Status',
                'Payment Method',
                'Delivery Date',
                'Products',
                'Created At',
                'Updated At',
            ]);

            // CSV data
            foreach ($orders as $order) {
                $products = $order->orderProducts->map(function($orderProduct) {
                    return $orderProduct->product ? $orderProduct->product->name : 'N/A';
                })->implode(', ');

                fputcsv($file, [
                    $order->id,
                    $order->customer ? $order->customer->name : 'N/A',
                    $order->title,
                    $order->total,
                    $order->status,
                    $order->payment_method,
                    $order->delivery_date ? $order->delivery_date->format('Y-m-d') : 'TBD',
                    $products,
                    $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '',
                    $order->updated_at ? $order->updated_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Handle multiple image uploads for an order
     */
    private function handleImageUploads(Request $request, Order $order)
    {
        if (!$request->hasFile('order_images')) {
            return;
        }

        $images = $request->file('order_images');

        foreach ($images as $index => $image) {
            if ($image->isValid()) {
                try {
                    // Generate unique filename
                    $filename = time() . '_' . $index . '_' . $image->getClientOriginalName();
                    
                    // Store the image
                    $imagePath = $image->storeAs('order_images', $filename, 'public');
                    
                    // Create database record with correct column names
                    OrderImage::create([
                        'order_id' => $order->id,
                        'image_path' => $imagePath,
                        'image_name' => $image->getClientOriginalName(),
                        'image_size' => $image->getSize(),
                        'image_type' => $image->getMimeType(),
                        'sort_order' => $index,
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error("Error processing image {$index}:", [
                        'error' => $e->getMessage(),
                        'image_name' => $image->getClientOriginalName()
                    ]);
                    throw $e; // Re-throw to handle in calling method
                }
            }
        }
    }

    /**
     * Delete an order image
     */
    public function deleteImage(Request $request, $imageId)
    {
        try {
            $image = OrderImage::findOrFail($imageId);
            
            // Check if user has permission to delete this image
            // You can add authorization logic here
            
            $image->delete(); // This will trigger the model's boot method to delete the file
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    /**
     * Generate unique serial number to prevent duplicates
     */
    private function generateUniqueSerialNumber($year, $productModelBase)
    {
        $attempts = 0;
        $maxAttempts = 100;
        
        do {
            $attempts++;
            
            // Get the highest existing serial number for this year
            $latestSerial = OrderProduct::where('serial_number', 'like', "SBC-{$year}%")
                ->orderBy('serial_number', 'desc')
                ->first();
            
            if ($latestSerial) {
                // Extract the number part from the serial number
                $serialParts = explode('-', $latestSerial->serial_number);
                $lastNumber = isset($serialParts[1]) ? intval($serialParts[1]) : 0;
                $nextNumber = $lastNumber + 1;
            } else {
                // Start with productModelBase if no existing serials
                $nextNumber = $productModelBase + 1;
            }
            
            // Format the serial number
            $serialNumber = 'SBC-' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Check if this serial number already exists
            $exists = OrderProduct::where('serial_number', $serialNumber)->exists();
            
            if (!$exists) {
                return $serialNumber;
            }
            
        } while ($attempts < $maxAttempts);
        
        // Fallback: use timestamp if all attempts failed
        return 'SBC-' . $year . time();
    }

    /**
     * Validate images with AVIF support
     */
    private function validateImages(Request $request)
    {
        $images = $request->file('order_images');
        $allowedMimes = ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp', 'avif'];
        $maxSize = 10240; // 10MB in KB
        
        foreach ($images as $index => $image) {
            if (!$image->isValid()) {
                return back()->withErrors(["order_images.{$index}" => "Invalid image file uploaded."])->withInput();
            }
            
            $extension = strtolower($image->getClientOriginalExtension());
            if (!in_array($extension, $allowedMimes)) {
                return back()->withErrors(["order_images.{$index}" => "The image must be a file of type: " . implode(', ', $allowedMimes) . "."])->withInput();
            }
            
            if ($image->getSize() > $maxSize * 1024) {
                return back()->withErrors(["order_images.{$index}" => "The image may not be greater than 10MB."])->withInput();
            }
            
            // Check if it's actually an image - skip getimagesize for AVIF as it may not be supported
            if ($extension !== 'avif') {
                $imageInfo = getimagesize($image->getRealPath());
                if (!$imageInfo) {
                    return back()->withErrors(["order_images.{$index}" => "The uploaded file is not a valid image."])->withInput();
                }
            } else {
                // For AVIF files, just check if it's a valid uploaded file and has image mime type
                $mimeType = $image->getMimeType();
                if (!in_array($mimeType, ['image/avif'])) {
                    return back()->withErrors(["order_images.{$index}" => "The uploaded file is not a valid AVIF image."])->withInput();
                }
            }
        }
        
        return true;
    }

    public function publicOrderDetails($uuid)
    {
        $order = Order::where('uuid', $uuid)->with([
            'customer',
            'orderProducts.product',
            'images',
            'tickets.assignedTo',
            'tickets.services.serviceItems',
            'tickets.customer',
            'tickets.orderProduct.product'
        ])->firstOrFail();

        return view('public.order-details', compact('order'));
    }
}
