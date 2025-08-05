<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

Route::get('/debug-system', function () {
    try {
        Log::info('Debug system check started');
        
        $checks = [];
        
        // 1. Check database connection
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'Connected';
            Log::info('Database connection: OK');
        } catch (Exception $e) {
            $checks['database'] = 'Failed: ' . $e->getMessage();
            Log::error('Database connection failed', ['error' => $e->getMessage()]);
        }
        
        // 2. Check users table
        try {
            $userCount = User::count();
            $checks['users_table'] = "Table exists, $userCount users found";
            Log::info("Users table check: $userCount users found");
        } catch (Exception $e) {
            $checks['users_table'] = 'Failed: ' . $e->getMessage();
            Log::error('Users table check failed', ['error' => $e->getMessage()]);
        }
        
        // 3. Check storage permissions
        $storagePath = storage_path('logs');
        $checks['storage_writable'] = is_writable($storagePath) ? 'Writable' : 'Not writable';
        
        // 4. Check app configuration
        $checks['app_env'] = config('app.env');
        $checks['app_debug'] = config('app.debug') ? 'Enabled' : 'Disabled';
        $checks['app_url'] = config('app.url');
        
        // 5. Check session configuration
        $checks['session_driver'] = config('session.driver');
        $checks['session_lifetime'] = config('session.lifetime');
        
        // 6. List first few users (for debugging)
        try {
            $users = User::select('id', 'email', 'created_at')->take(5)->get();
            $checks['sample_users'] = $users->toArray();
        } catch (Exception $e) {
            $checks['sample_users'] = 'Failed to fetch: ' . $e->getMessage();
        }
        
        Log::info('Debug system check completed', $checks);
        
        return response()->json([
            'status' => 'Debug completed',
            'checks' => $checks,
            'timestamp' => now(),
            'note' => 'Check storage/logs/laravel.log for detailed logs'
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        Log::error('Debug system check exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'Debug failed',
            'error' => $e->getMessage(),
            'timestamp' => now()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});
