<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/customers/{customer}/contacts', function (Customer $customer) {
    return $customer->contactPersons()->select('id', 'name', 'email')->get();
});


Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);


        Route::get('/tickets', [TicketController::class, 'assignedTickets']);
        Route::get('tickets/{uuid}', [TicketController::class, 'show']);
        Route::post('/tickets/{ticket}', [TicketController::class, 'storeTicketEntry']);

    });


});
