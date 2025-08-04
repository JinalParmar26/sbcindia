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
            $table->string('role')->default('staff')->after('phone_number');
            $table->string('department')->nullable()->after('role');
            $table->text('bio')->nullable()->after('department');
            $table->string('linkedin_url')->nullable()->after('bio');
            $table->string('twitter_url')->nullable()->after('linkedin_url');
            $table->boolean('public_profile')->default(true)->after('twitter_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'bio', 'linkedin_url', 'twitter_url', 'public_profile']);
        });
    }
};
