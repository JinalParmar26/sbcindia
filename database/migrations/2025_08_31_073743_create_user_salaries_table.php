<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('salary_date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->decimal('normal_hours', 8, 2)->default(0);
            $table->decimal('service_hours', 8, 2)->default(0);
            $table->decimal('extra_hours', 8, 2)->default(0);
            $table->decimal('normal_salary', 10, 2)->default(0);
            $table->decimal('service_salary', 10, 2)->default(0);
            $table->decimal('extra_salary', 10, 2)->default(0);
            $table->decimal('total_salary', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_salaries');
    }
};
