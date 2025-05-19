<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('marketing', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_phone_number');

            $table->string('contact_person_name');
            $table->string('contact_person_phone_number');

            $table->date('visit_date');
            $table->time('visit_start_time')->nullable();
            $table->decimal('visit_start_latitude', 10, 7)->nullable();
            $table->decimal('visit_start_longitude', 10, 7)->nullable();
            $table->string('visit_start_location_name')->nullable();

            $table->time('visit_end_time')->nullable();
            $table->decimal('visit_end_latitude', 10, 7)->nullable();
            $table->decimal('visit_end_longitude', 10, 7)->nullable();
            $table->string('visit_end_location_name')->nullable();

            $table->text('notes')->nullable();
            $table->json('presented_products')->nullable(); // Store product_id(s) in JSON

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing');
    }
};
