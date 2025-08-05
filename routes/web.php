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
use App\Http\Controllers\LeadController;

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

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

// Example routes that might be referenced in the views
Route::get('/login-example', LoginExample::class)->name('login-example');
Route::get('/register-example', RegisterExample::class)->name('register-example');
Route::get('/forgot-password-example', ForgotPasswordExample::class)->name('forgot-password-example');
Route::get('/reset-password-example', ResetPasswordExample::class)->name('reset-password-example');
Route::get('/profile-example', ProfileExample::class)->name('profile-example');

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    
    // Routes that require admin panel access
    Route::middleware('permission:access_admin_panel')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/bootstrap-tables', BootstrapTables::class)->name('bootstrap-tables');
        Route::get('/buttons', Buttons::class)->name('buttons');
        Route::get('/forms', Forms::class)->name('forms');
        Route::get('/modals', Modals::class)->name('modals');
        Route::get('/notifications', Notifications::class)->name('notifications');
        Route::get('/typography', Typography::class)->name('typography');
        
        // User Management Routes - Permission Based
        // IMPORTANT: Specific routes MUST come before parameterized routes
        
        Route::middleware('permission:create_users')->group(function () {
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
        });
        
        Route::middleware('permission:view_users')->group(function () {
            Route::get('/users', Users::class)->name('users');
            Route::get('/users/export/csv', [UserController::class, 'exportCsv'])->name('users.export.csv');
            Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');
        });
        
        Route::middleware('permission:edit_users')->group(function () {
            Route::get('/users/{uuid}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{uuid}', [UserController::class, 'update'])->name('users.update');
        });
        
        Route::middleware('permission:view_users')->group(function () {
            // Parameterized routes MUST come after specific routes
            Route::get('/users/{uuid}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{uuid}/pdf', [UserController::class, 'exportSinglePdf'])->name('users.single.pdf');
            Route::get('/users/{uuid}/download-qr', [UserController::class, 'downloadQr'])->name('download-qr');
        });
        
        // Customer Management Routes - Permission Based
        // IMPORTANT: Specific routes MUST come before parameterized routes
        
        Route::middleware('permission:create_customers')->group(function () {
            Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
            Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        });
        
        Route::middleware('permission:view_customers')->group(function () {
            Route::get('/customers', Customers::class)->name('customers');
            Route::get('/customers/export/csv', [CustomerController::class, 'exportCsv'])->name('customers.export.csv');
            Route::get('/customers/export/pdf', [CustomerController::class, 'exportPdf'])->name('customers.export.pdf');
            // Parameterized routes MUST come after specific routes
            Route::get('/customers/{uuid}', [CustomerController::class, 'show'])->name('customers.show');
        });
        
        Route::middleware('permission:edit_customers')->group(function () {
            Route::get('/customers/{id}/edit', Customers::class)->name('customers.edit');
        });
        
        // Product Management Routes - Permission Based
        Route::middleware('permission:view_products')->group(function () {
            Route::get('/products', Products::class)->name('products');
        });
        
        Route::middleware('permission:create_products')->group(function () {
            Route::get('/products/create', Products::class)->name('products.create');
        });
        
        Route::middleware('permission:edit_products')->group(function () {
            Route::get('/products/{id}/edit', Products::class)->name('products.edit');
        });
        
        // Order Management Routes - Permission Based
        // IMPORTANT: Specific routes MUST come before parameterized routes
        
        Route::middleware('permission:create_orders')->group(function () {
            Route::get('/orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
            Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
        });
        
        Route::middleware('permission:view_orders')->group(function () {
            Route::get('/orders', Orders::class)->name('orders');
            Route::get('/orders/export/pdf', [App\Http\Controllers\OrderController::class, 'exportPdf'])->name('orders.export.pdf');
            // Parameterized routes MUST come after specific routes
            Route::get('/orders/{uuid}', Orders::class)->name('orders.show');
            Route::get('/orders/{id}/pdf', [App\Http\Controllers\OrderController::class, 'exportSinglePdf'])->name('orders.single.pdf');
        });
        
        Route::middleware('permission:edit_orders')->group(function () {
            Route::get('/orders/{uuid}/edit', Orders::class)->name('orders.edit');
            Route::put('/orders/{order}', Orders::class)->name('orders.update');
        });
        
        // Ticket Management Routes - Permission Based
        Route::middleware('permission:view_tickets')->group(function () {
            Route::get('/tickets', Tickets::class)->name('tickets');
            Route::get('/tickets/{id}', Tickets::class)->name('tickets.show');
            Route::get('/tickets/export', function() {
                $ticketsComponent = new App\Http\Livewire\Tickets();
                return $ticketsComponent->exportCsv();
            })->name('tickets.export');
            Route::get('/tickets/export/pdf', function() {
                $ticketsComponent = new App\Http\Livewire\Tickets();
                return $ticketsComponent->exportPdf();
            })->name('tickets.export.pdf');
        });
        
        Route::middleware('permission:create_tickets')->group(function () {
            Route::get('/tickets/create', Tickets::class)->name('tickets.create');
        });
        
        Route::middleware('permission:edit_tickets')->group(function () {
            Route::get('/tickets/{id}/edit', Tickets::class)->name('tickets.edit');
        });
        
        // Lead Management Routes - Permission Based
        // IMPORTANT: Specific routes MUST come before parameterized routes
        
        Route::middleware('permission:create_leads|create_marketing')->group(function () {
            Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
            Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        });
        
        Route::middleware('permission:view_leads|view_marketing')->group(function () {
            Route::get('/leads', Leads::class)->name('leads');
            Route::get('/leads/export/csv', function() {
                $leadsComponent = new App\Http\Livewire\Leads();
                return $leadsComponent->exportCsv();
            })->name('leads.export.csv');
            // Parameterized routes MUST come after specific routes
            Route::get('/leads/{id}', Leads::class)->name('leads.show');
        });
        
        Route::middleware('permission:edit_leads|edit_marketing')->group(function () {
            Route::get('/leads/{id}/edit', Leads::class)->name('leads.edit');
        });
        
        // Staff Management Routes - Permission Based
        Route::middleware('permission:view_staff')->group(function () {
            Route::get('/staff', Staff::class)->name('staff');
            Route::get('/staff/visiting-card/{uuid?}', Staff::class)->name('staff.visiting-card');
            Route::get('/staff/profile/{uuid}', Staff::class)->name('staff.profile');
            Route::get('/staff/export/pdf', Staff::class)->name('staff.export.pdf');
            Route::get('/staff-tickets', StaffTickets::class)->name('staff-tickets');
            Route::get('/staff-attendance-actions', StaffAttendanceActions::class)->name('staff-attendance-actions');
            Route::get('/staff-locations', StaffLocations::class)->name('staff-locations');
            Route::get('/staff/locations/data', StaffLocations::class)->name('staff.locations.data');
            Route::get('/staff/locations/live', StaffLocations::class)->name('staff.locations.live');
            Route::get('/staff/tickets', StaffTickets::class)->name('staff.tickets');
            Route::get('/staff/locations', StaffLocations::class)->name('staff.locations');
            Route::get('/staff/actions', StaffAttendanceActions::class)->name('staff.actions');
            Route::get('/staff/attendance/export/pdf', function() {
                $staffComponent = new App\Http\Livewire\StaffAttendanceActions();
                return $staffComponent->exportPdf();
            })->name('staff.attendance.export.pdf');
        });
        
        Route::middleware('permission:manage_attendance')->group(function () {
            Route::post('/staff/checkout/{staff}', Staff::class)->name('staff.checkout');
        });
        
        Route::get('/transactions', Transactions::class)->name('transactions');
        Route::get('/upgrade-to-pro', UpgradeToPro::class)->name('upgrade-to-pro');
        Route::get('/lock', Lock::class)->name('lock');
        Route::get('/404', Err404::class)->name('404');
        Route::get('/500', Err500::class)->name('500');
    });
    
    // Super Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::get('/role-permissions/{roleId}', [App\Http\Controllers\RolePermissionController::class, 'show'])->name('role-permissions');
    });
});

// Test routes for debugging
Route::get('/test-auth', function() {
    if (auth()->check()) {
        return 'User is logged in! User: ' . auth()->user()->email;
    } else {
        return 'User is NOT logged in.';
    }
});

Route::get('/auto-login', function () {
    $user = App\Models\User::where('email', 'admin@sbcerp.com')->first();
    if ($user) {
        auth()->login($user);
        return redirect('/dashboard')->with('success', 'Automatically logged in!');
    } else {
        return 'Admin user not found!';
    }
});
