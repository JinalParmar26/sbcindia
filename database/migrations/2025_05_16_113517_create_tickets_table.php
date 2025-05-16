<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();

            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_product_id')->constrained('order_products')->onDelete('cascade');
            $table->foreignId('customer_contact_person_id')->nullable()->constrained('customer_contact_person')->onDelete('set null');
            $table->foreignId('attended_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['delivery', 'service']);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
