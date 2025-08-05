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
        Schema::create('leads', function (Blueprint $table) {
            $table->id('lead_id');
            $table->uuid('uuid')->unique();
            $table->string('lead_name');
            $table->unsignedBigInteger('lead_owner_id');
            $table->text('collaborators')->nullable();
            $table->string('status')->default('new');
            $table->string('industry')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('price_group')->nullable();
            $table->string('title')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('visit_started_at')->nullable();
            $table->timestamp('visit_ended_at')->nullable();
            $table->string('visit_status')->default('Not Started');
            $table->string('file_url')->nullable();
            $table->string('deal_title')->nullable();
            $table->decimal('deal_amount', 15, 2)->nullable();
            $table->string('deal_status')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('lead_owner_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['status']);
            $table->index(['lead_owner_id']);
            $table->index(['uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
