<!DOCTYPE html>
<html>
<head>
    <title>Image Upload Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .upload-test {
            border: 2px dashed #ccc;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .preview {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }
        .preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h2>Image Upload Test for Order Edit</h2>
    
    <form action="{{ route('orders.update', $order) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="title" value="{{ $order->title }}">
        <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
        <input type="hidden" name="product_id" value="{{ $order->orderProducts->first()->product_id ?? '' }}">
        <input type="hidden" name="product_configs" value='{{ json_encode([["test" => "value"]]) }}'>
        
        <div class="upload-test">
            <label>Select Images:</label>
            <input type="file" name="order_images[]" multiple accept="image/*" onchange="previewImages(this)">
            <div class="preview" id="preview"></div>
        </div>
        
        @if($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <button type="submit">Update Order (Test)</button>
    </form>
    
    <script>
        function previewImages(input) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                        console.log('File added:', file.name, 'Size:', file.size);
                    }
                });
            }
            
            console.log('Total files:', input.files.length);
        }
    </script>
</body>
</html>
