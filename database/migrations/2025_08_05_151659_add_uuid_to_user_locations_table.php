<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if uuid column doesn't exist before adding it
        if (!Schema::hasColumn('user_locations', 'uuid')) {
            Schema::table('user_locations', function (Blueprint $table) {
                $table->string('uuid')->nullable()->after('id');
            });
        }

        // Generate UUIDs for existing records that don't have them
        $locations = DB::table('user_locations')->whereNull('uuid')->orWhere('uuid', '')->get();
        foreach ($locations as $location) {
            DB::table('user_locations')
                ->where('id', $location->id)
                ->update(['uuid' => Str::uuid()]);
        }

        // Add unique constraint if it doesn't exist
        if (!Schema::hasColumn('user_locations', 'uuid')) {
            Schema::table('user_locations', function (Blueprint $table) {
                $table->string('uuid')->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
