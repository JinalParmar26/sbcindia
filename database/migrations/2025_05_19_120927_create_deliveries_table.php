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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Foreign key to tickets
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            $table->string('challan_no')->nullable();
            $table->string('vehical_no')->nullable();
            $table->string('delivered_by')->nullable()->constrained('users')->onDelete('set null'); // Can be changed to foreignId if referencing users

            $table->longText('log')->nullable();

            $table->dateTime('start_date_time')->nullable();
            $table->decimal('start_location_lat', 10, 7)->nullable();
            $table->decimal('start_location_long', 10, 7)->nullable();
            $table->string('start_location_name')->nullable();

            $table->dateTime('end_date_time')->nullable();
            $table->decimal('end_location_lat', 10, 7)->nullable();
            $table->decimal('end_location_long', 10, 7)->nullable();
            $table->string('end_location_name')->nullable();

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
        Schema::dropIfExists('deliveries');
    }
};
