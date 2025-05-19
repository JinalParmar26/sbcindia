<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->enum('service_type', ['equipment_testing', 'commissioning', 'service_report']);

            $table->dateTime('start_date_time')->nullable();
            $table->decimal('start_location_lat', 10, 7)->nullable();
            $table->decimal('start_location_long', 10, 7)->nullable();
            $table->string('start_location_name')->nullable();

            $table->dateTime('end_date_time')->nullable();
            $table->decimal('end_location_lat', 10, 7)->nullable();
            $table->decimal('end_location_long', 10, 7)->nullable();
            $table->string('end_location_name')->nullable();

            $table->string('contact_person_name')->constrained('customer_contact_person')->onDelete('set null');;
            $table->enum('payment_type', ['warranty', 'amc', 'camc', 'paid', 'other'])->nullable();

            $table->longText('log')->nullable(); // General service log or notes
            $table->string('unit_model_number')->nullable();
            $table->string('unit_sr_no')->nullable();
            $table->enum('payment_status', ['received', 'pending'])->nullable();

            $table->longText('service_description')->nullable();

            $table->string('refrigerant')->nullable();
            $table->string('voltage')->nullable();
            $table->string('amp_r')->nullable();
            $table->string('amp_y')->nullable();
            $table->string('amp_b')->nullable();

            $table->string('standing_pressure')->nullable();
            $table->string('suction_pressure')->nullable();
            $table->string('discharge_pressure')->nullable();

            $table->string('suction_temp')->nullable();
            $table->string('discharge_temp')->nullable();
            $table->string('exv_opening')->nullable();

            $table->string('chilled_water_in')->nullable();
            $table->string('chilled_water_out')->nullable();
            $table->string('con_water_in')->nullable();
            $table->string('con_water_out')->nullable();

            $table->string('water_tank_temp')->nullable();
            $table->string('cabinet_temp')->nullable();
            $table->string('room_temp')->nullable();
            $table->string('room_supply_air_temp')->nullable();
            $table->string('room_return_air_temp')->nullable();

            $table->string('lp_setting')->nullable();
            $table->string('hp_setting')->nullable();
            $table->string('aft')->nullable();
            $table->string('thermostat_setting')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}
