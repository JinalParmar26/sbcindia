<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\OvertimeLogController;
use App\Http\Controllers\Api\MarketingController;
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

    Route::middleware('auth:sanctum')->get('/approval-status', [AuthController::class, 'checkApprovalStatus']);


    // Protected routes
    Route::middleware('auth:sanctum', 'approval.check')->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/qrcode', [AuthController::class, 'getQR']);

        Route::get('/attendances', [AttendanceController::class, 'index']);
        Route::post('/attendances', [AttendanceController::class, 'store']);
        Route::post('/checkin', [AttendanceController::class, 'checkin']);
        Route::put('/checkout', [AttendanceController::class, 'checkout']);
        Route::get('/attendances/{attendance}', [AttendanceController::class, 'show']);
        Route::put('/attendances/{attendance}', [AttendanceController::class, 'update']);
        Route::delete('/attendances/{attendance}', [AttendanceController::class, 'destroy']);

        Route::get('/tickets', [TicketController::class, 'assignedTickets']);
        Route::get('/recentcompletedtickets', [TicketController::class, 'recentCompletedTickets']);
        Route::get('tickets/{uuid}', [TicketController::class, 'show']);
        Route::post('/tickets/{uuid}', [TicketController::class, 'storeTicketEntry']);


        Route::get('/overtime-logs', [OvertimeLogController::class, 'index']);
        Route::post('/overtime-logs', [OvertimeLogController::class, 'store']);
        Route::put('/overtime-logs/{overtimeLog}', [OvertimeLogController::class, 'update']);
        Route::get('/overtime-logs/{overtimeLog}', [OvertimeLogController::class, 'show']);
        Route::delete('/overtime-logs/{overtimeLog}', [OvertimeLogController::class, 'destroy']);

        Route::get('/marketing-visits', [MarketingController::class, 'index']);
        Route::post('/marketing-visits', [MarketingController::class, 'store']);
        Route::get('/marketing-visits/{marketing}', [MarketingController::class, 'show']);
        Route::put('/marketing-visits/{marketing}', [MarketingController::class, 'update']);
        Route::delete('/marketing-visits/{marketing}', [MarketingController::class, 'destroy']);

    });


});
