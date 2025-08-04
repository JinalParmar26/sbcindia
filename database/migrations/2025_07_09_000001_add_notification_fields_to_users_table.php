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
        Schema::table('users', function (Blueprint $table) {
            $table->text('fcm_token')->nullable()->after('remember_token');
            $table->boolean('notifications_enabled')->default(true)->after('fcm_token');
            $table->boolean('ticket_notifications')->default(true)->after('notifications_enabled');
            $table->boolean('attendance_notifications')->default(true)->after('ticket_notifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fcm_token', 'notifications_enabled', 'ticket_notifications', 'attendance_notifications']);
        });
    }
};
