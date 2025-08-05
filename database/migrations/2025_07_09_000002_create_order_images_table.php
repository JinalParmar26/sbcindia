<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('image_path')->comment('Path to the uploaded image file');
            $table->string('image_name')->comment('Original filename of the uploaded image');
            $table->integer('image_size')->comment('File size in bytes');
            $table->string('image_type', 50)->comment('MIME type of the image');
            $table->integer('sort_order')->default(0)->comment('Order for displaying images');
            $table->text('description')->nullable()->comment('Optional description for the image');
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['order_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_images');
    }
};
