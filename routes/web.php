<?php

use App\Http\Livewire\BootstrapTables;
use App\Http\Livewire\Components\Buttons;
use App\Http\Livewire\Components\Forms;
use App\Http\Livewire\Components\Modals;
use App\Http\Livewire\Components\Notifications;
use App\Http\Livewire\Components\Typography;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Err404;
use App\Http\Livewire\Err500;
use App\Http\Livewire\ResetPassword;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Lock;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\ForgotPasswordExample;
use App\Http\Livewire\Index;
use App\Http\Livewire\LoginExample;
use App\Http\Livewire\ProfileExample;
use App\Http\Livewire\RegisterExample;
use App\Http\Livewire\Transactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\Users;
use App\Http\Livewire\Customers;
use App\Http\Livewire\Products;
use App\Http\Livewire\Orders;
use App\Http\Livewire\Tickets;
use App\Http\Livewire\Leads;
use App\Http\Livewire\Staff;
use App\Http\Livewire\StaffTickets;
use App\Http\Livewire\StaffAttendanceActions;
use App\Http\Livewire\StaffLocations;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PublicStaffController;
use App\Http\Controllers\RolePermissionController;
use App\Models\User;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

// Debug route for troubleshooting
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

// Log viewer route for debugging
Route::get('/view-logs', function () {
    $logPath = storage_path('logs/laravel.log');
    
    if (!file_exists($logPath)) {
        return "No log file found at: $logPath";
    }
    
    $lines = file($logPath);
    $recentLines = array_slice($lines, -100); // Get last 100 lines
    
    $output = "<h2>Recent Log Entries (Last 100 lines)</h2>";
    $output .= "<pre style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; max-height: 600px; overflow-y: scroll;'>";
    
    foreach ($recentLines as $line) {
        // Color code different log levels
        if (strpos($line, 'ERROR') !== false) {
            $output .= "<span style='color: red;'>" . htmlspecialchars($line) . "</span>";
        } elseif (strpos($line, 'WARNING') !== false) {
            $output .= "<span style='color: orange;'>" . htmlspecialchars($line) . "</span>";
        } elseif (strpos($line, 'INFO') !== false) {
            $output .= "<span style='color: blue;'>" . htmlspecialchars($line) . "</span>";
        } else {
            $output .= htmlspecialchars($line);
        }
    }
    
    $output .= "</pre>";
    $output .= "<p><strong>Log file location:</strong> $logPath</p>";
    $output .= "<p><strong>Total file size:</strong> " . number_format(filesize($logPath) / 1024, 2) . " KB</p>";
    $output .= "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($logPath)) . "</p>";
    
    return $output;
});

// Debug login form for testing
Route::get('/debug-login-form', function () {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login Form Debug</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .container { max-width: 500px; margin: 0 auto; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="email"], input[type="password"] { 
                width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
            }
            button { 
                background: #007bff; color: white; padding: 10px 20px; 
                border: none; border-radius: 4px; cursor: pointer; 
            }
            button:hover { background: #0056b3; }
            .debug-info { 
                background: #f8f9fa; padding: 15px; border-radius: 4px; 
                margin-top: 20px; font-family: monospace; 
            }
            .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin: 10px 0; }
            .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 4px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Login Form Debug Tool</h1>
            <p>Use this to test login functionality directly:</p>';
            
    if (session('error')) {
        $html .= '<div class="error">' . session('error') . '</div>';
    }
    if (session('success')) {
        $html .= '<div class="success">' . session('success') . '</div>';
    }
    
    $html .= '
            <form id="loginForm" method="POST" action="/debug-login-attempt">
                ' . csrf_field() . '
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="admin@sbcerp.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Try: admin, password, 123456" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember_me" value="1"> Remember Me
                    </label>
                </div>
                
                <button type="submit">Test Login</button>
            </form>
            
            <div class="debug-info">
                <strong>Available Users:</strong><br>
                • admin@sbcerp.com<br>
                • manager@test.com<br>
                • staff@test.com<br>
                • staff1@test.com<br>
                • staff2@test.com<br><br>
                
                <strong>Common Passwords to Try:</strong><br>
                • admin<br>
                • password<br>
                • 123456<br>
                • admin123<br>
                • sbcerp<br><br>
                
                <strong>Debug Links:</strong><br>
                • <a href="/view-logs" target="_blank">View Logs</a><br>
                • <a href="/debug-system" target="_blank">System Check</a>
            </div>
        </div>
        
        <script>
            document.getElementById("loginForm").addEventListener("submit", function(e) {
                console.log("Form submission started");
                console.log("Email:", document.getElementById("email").value);
                console.log("Password length:", document.getElementById("password").value.length);
            });
        </script>
    </body>
    </html>';
    
    return $html;
});

Route::post('/debug-login-attempt', function () {
    Log::info('Debug login attempt received', [
        'email' => request('email'),
        'password_provided' => !empty(request('password')),
        'password_length' => strlen(request('password') ?? ''),
        'remember_me' => request('remember_me', false),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    try {
        $email = request('email');
        $password = request('password');
        
        // Check if user exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::warning('Debug login: User not found', ['email' => $email]);
            return back()->with('error', 'User not found with email: ' . $email);
        }
        
        Log::info('Debug login: User found', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_hash' => substr($user->password, 0, 20) . '...'
        ]);
        
        // Try authentication
        if (auth()->attempt(['email' => $email, 'password' => $password], request('remember_me'))) {
            Log::info('Debug login: SUCCESS', ['user_id' => $user->id]);
            return redirect('/dashboard')->with('success', 'Login successful!');
        } else {
            Log::warning('Debug login: Authentication failed', [
                'email' => $email,
                'password_check' => Hash::check($password, $user->password) ? 'MATCH' : 'NO_MATCH'
            ]);
            
            return back()->with('error', 'Invalid credentials. Password does not match.');
        }
        
    } catch (\Exception $e) {
        Log::error('Debug login: Exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
});

// Password reset utility for debugging
Route::get('/reset-admin-password', function () {
    try {
        $user = User::where('email', 'admin@sbcerp.com')->first();
        
        if (!$user) {
            return 'Admin user not found!';
        }
        
        // Set a known password
        $newPassword = 'admin123';
        $user->password = Hash::make($newPassword);
        $user->save();
        
        Log::info('Admin password reset', [
            'user_id' => $user->id,
            'email' => $user->email,
            'new_password' => $newPassword
        ]);
        
        return "
        <h2>Password Reset Successful!</h2>
        <p><strong>Email:</strong> admin@sbcerp.com</p>
        <p><strong>New Password:</strong> admin123</p>
        <p><a href='/login'>Go to Login Page</a></p>
        <p><a href='/debug-login-form'>Use Debug Login Form</a></p>
        ";
        
    } catch (\Exception $e) {
        Log::error('Password reset failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/register', Register::class)->name('register');

Route::get('/login', Login::class)->name('login');

Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');

Route::get('/reset-password/{id}', ResetPassword::class)->name('reset-password')->middleware('signed');

Route::get('/404', Err404::class)->name('404');
Route::get('/500', Err500::class)->name('500');
Route::get('/upgrade-to-pro', UpgradeToPro::class)->name('upgrade-to-pro');

//user public profile
Route::get('/user-profile/{uuid}', [UserController::class, 'showPublicProfile'])->name('showPublicProfile');
Route::get('/download-qr/{uuid}', [UserController::class, 'downloadQr'])->name('download-qr');

Route::get('/qr/{uuid}', [UserController::class, 'showQr'])->name('showQr');

// Public Staff Visiting Card Routes (No Authentication Required)
Route::get('/staff/card/{uuid}', [PublicStaffController::class, 'visitingCard'])->name('staff.visiting-card');
Route::get('/staff/profile/{uuid}', [PublicStaffController::class, 'profile'])->name('staff.profile');


// Debug route to check users with UUIDs
Route::get('/debug-users', function () {
    $users = User::whereNotNull('uuid')->take(10)->get(['id', 'name', 'email', 'uuid']);
    
    if ($users->isEmpty()) {
        return 'No users with UUIDs found. Let me update some users...';
    }
    
    $output = "Users with UUIDs:\n\n";
    foreach ($users as $user) {
        $output .= "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, UUID: {$user->uuid}\n";
        $output .= "Visiting Card URL: " . route('staff.visiting-card', $user->uuid) . "\n";
        $output .= "Public Profile URL: " . route('showPublicProfile', $user->uuid) . "\n\n";
    }
    
    return nl2br($output);
});

// Debug route to update user UUIDs
Route::get('/update-user-uuids', function () {
    $users = User::whereNull('uuid')->orWhere('uuid', '')->get();
    
    if ($users->isEmpty()) {
        return 'All users already have UUIDs.';
    }
    
    $output = "Found {$users->count()} users without UUIDs. Updating...\n\n";
    
    foreach ($users as $user) {
        $user->uuid = Str::uuid();
        $user->save();
        $output .= "Updated user {$user->id} ({$user->name}) with UUID: {$user->uuid}\n";
    }
    
    $output .= "\nUUID update completed!";
    return nl2br($output);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile-example', ProfileExample::class)->name('profile-example');
    Route::get('/users', Users::class)->name('users');
    Route::get('/roles', \App\Http\Livewire\RoleManagement::class)->name('roles');
    Route::get('/role-permissions', function () {
        return redirect()->route('roles');
    })->name('role-permissions.index');
    Route::get('/role-permissions/{roleId}', [RolePermissionController::class, 'show'])->name('role-permissions');
    Route::get('/login-example', LoginExample::class)->name('login-example');
    Route::get('/register-example', RegisterExample::class)->name('register-example');
    Route::get('/forgot-password-example', ForgotPasswordExample::class)->name('forgot-password-example');
    Route::get('/reset-password-example', ResetPasswordExample::class)->name('reset-password-example');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/transactions', Transactions::class)->name('transactions');
    Route::get('/bootstrap-tables', BootstrapTables::class)->name('bootstrap-tables');
    Route::get('/lock', Lock::class)->name('lock');
    Route::get('/buttons', Buttons::class)->name('buttons');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/forms', Forms::class)->name('forms');
    Route::get('/modals', Modals::class)->name('modals');
    Route::get('/typography', Typography::class)->name('typography');


     Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [UserController::class, 'editProfile'])->name('edit');
        Route::put('/', [UserController::class, 'updateProfile'])->name('update');
    });
    //developer added routes

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('create'); // Form page
        Route::post('/', [UserController::class, 'store'])->name('store'); // Handle create
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit'); // Edit form
        Route::put('/{user}', [UserController::class, 'update'])->name('update'); // Handle edit
        Route::get('/{uuid}', [UserController::class, 'show'])->name('show');
        Route::get('/export/csv', [UserController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [UserController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{uuid}/pdf', [UserController::class, 'exportSinglePdf'])->name('single.pdf');
        
        // Visiting Cards Management
        Route::get('/visiting-cards', [UserController::class, 'manageVisitingCards'])->name('visiting-cards');
        Route::get('/visiting-card-links', [UserController::class, 'generateVisitingCardLinks'])->name('visiting-card-links');
        Route::put('/{user}/staff-profile', [UserController::class, 'updateStaffProfile'])->name('update-staff-profile');
    });


    Route::get('/customers', Customers::class)->name('customers');

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::get('/{uuid}', [CustomerController::class, 'show'])->name('show');
        Route::get('/export/csv', [CustomerController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [CustomerController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{uuid}/pdf', [CustomerController::class, 'exportSinglePdf'])->name('single.pdf');
    });

    Route::get('/products', Products::class)->name('products');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::get('/{uuid}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/specs', [ProductController::class, 'getSpecs'])->name('specs');
        Route::get('/{category}/options', [ProductController::class, 'getCategoryOptions']);
        Route::get('/export/csv', [ProductController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [ProductController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{uuid}/pdf', [ProductController::class, 'exportSinglePdf'])->name('single.pdf');
    });


    Route::get('/orders', Orders::class)->name('orders');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{uuid}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::get('/{order}/upload-test', [OrderController::class, 'uploadTest'])->name('upload-test');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::get('/export/pdf', [OrderController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/{uuid}/pdf', [OrderController::class, 'exportSinglePdf'])->name('single.pdf');
    });

    Route::delete('/orders/images/{image}', [OrderController::class, 'deleteImage'])->name('orders.images.delete');

    Route::get('/leads', Leads::class)->name('leads');

    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/create', [LeadController::class, 'create'])->name('create');
        Route::post('/', [LeadController::class, 'store'])->name('store');
        Route::get('/{uuid}', [LeadController::class, 'show'])->name('show');
        Route::get('/{uuid}/edit', [LeadController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [LeadController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [LeadController::class, 'destroy'])->name('destroy');
        Route::post('/{uuid}/start-visit', [LeadController::class, 'startVisit'])->name('start-visit');
        Route::post('/{uuid}/end-visit', [LeadController::class, 'endVisit'])->name('end-visit');
        Route::get('/export/csv', [LeadController::class, 'exportCsv'])->name('export.csv');
    });

    Route::get('/tickets', Tickets::class)->name('tickets');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/export', [TicketController::class, 'exportCsv'])->name('export');
        Route::get('/export/pdf', [TicketController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{uuid}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::get('/{uuid}/pdf', [TicketController::class, 'exportSinglePdf'])->name('single.pdf');
        
        // Ticket Image routes for web interface
        Route::post('/upload-images', [TicketController::class, 'uploadTicketImages'])->name('upload-images');
        Route::get('/{uuid}/images', [TicketController::class, 'getTicketImages'])->name('images');
    });

    Route::delete('/tickets/images/{image}', [TicketController::class, 'deleteTicketImage'])->name('tickets.images.delete');

    // Staff attendance routes
    Route::get('/staff', Staff::class)->name('staff');
    Route::get('/staff/tickets', StaffTickets::class)->name('staff.tickets');
    Route::get('/staff/actions', StaffAttendanceActions::class)->name('staff.actions');
    Route::get('/staff/locations', StaffLocations::class)->name('staff.locations');
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::post('/{user}/checkout', [StaffController::class, 'checkOut'])->name('checkout');
        Route::get('/{user}/attendance', [StaffController::class, 'getAttendanceDetails'])->name('attendance');
        Route::get('/export/pdf', [StaffController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/attendance/export/pdf', [StaffController::class, 'exportAttendancePdf'])->name('attendance.export.pdf');
        
        // Staff Location Tracking routes
        Route::get('/locations/live-tracking', [\App\Http\Controllers\LocationManagementController::class, 'liveLocationTracking'])->name('locations.live');
        Route::get('/locations/live-data', [\App\Http\Controllers\LocationManagementController::class, 'getLiveLocationData'])->name('locations.data');
        Route::get('/locations/{userId}', [\App\Http\Controllers\LocationManagementController::class, 'showUserLocations'])->name('locations.show');
        Route::get('/locations/{userId}/trail', [\App\Http\Controllers\LocationManagementController::class, 'getUserLocationTrail'])->name('locations.trail');
    });

    // Location Management routes
    Route::prefix('location-management')->name('location.')->group(function () {
        Route::get('/', [\App\Http\Controllers\LocationManagementController::class, 'index'])->name('index');
        Route::get('/live-tracking', [\App\Http\Controllers\LocationManagementController::class, 'liveLocationTracking'])->name('live.tracking');
        Route::get('/live-data', [\App\Http\Controllers\LocationManagementController::class, 'getLiveLocationData'])->name('live.data');
        Route::get('/user/{userId}/trail', [\App\Http\Controllers\LocationManagementController::class, 'getUserLocationTrail'])->name('user.trail');
        Route::get('/user/{userId}', [\App\Http\Controllers\LocationManagementController::class, 'showUserLocations'])->name('user.show');
        Route::get('/user/{userId}/data', [\App\Http\Controllers\LocationManagementController::class, 'getLocationData'])->name('user.data');
        Route::get('/user/{userId}/summary', [\App\Http\Controllers\LocationManagementController::class, 'getUserLocationSummary'])->name('user.summary');
        Route::get('/user/{userId}/export', [\App\Http\Controllers\LocationManagementController::class, 'exportLocationData'])->name('user.export');
        Route::get('/users-with-data', [\App\Http\Controllers\LocationManagementController::class, 'getUsersWithLocationData'])->name('users.with.data');
        Route::delete('/cleanup', [\App\Http\Controllers\LocationManagementController::class, 'cleanupOldLocations'])->name('cleanup');
    });

});

