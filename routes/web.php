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
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\Users;
use App\Http\Livewire\Customers;
use App\Http\Livewire\Products;
use App\Http\Livewire\Orders;
use App\Http\Livewire\Tickets;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;


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





Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile-example', ProfileExample::class)->name('profile-example');
    Route::get('/users', Users::class)->name('users');
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
    });


    Route::get('/customers', Customers::class)->name('customers');

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::get('/{uuid}', [CustomerController::class, 'show'])->name('show');
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
    });


    Route::get('/orders', Orders::class)->name('orders');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{uuid}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
    });

    Route::get('/tickets', Tickets::class)->name('tickets');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{uuid}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
    });

});
