<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\OvertimeLogController;
use App\Http\Controllers\Api\MarketingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\LocationController;
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
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/upload-photos', [AuthController::class, 'uploadPhotos']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/update-profile', [AuthController::class, 'updateUserProfile']);
        
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/qrcode', [AuthController::class, 'getQR']);

        Route::get('/attendances', [AttendanceController::class, 'index']);
        Route::post('/attendances', [AttendanceController::class, 'store']);
        Route::post('/checkin', [AttendanceController::class, 'checkin']);
        Route::put('/checkout', [AttendanceController::class, 'checkout']);
        Route::get('/attendances/{attendance}', [AttendanceController::class, 'show']);
        Route::put('/attendances/{attendance}', [AttendanceController::class, 'update']);
        Route::delete('/attendances/{attendance}', [AttendanceController::class, 'destroy']);

	 // Ticket Image APIs
        Route::post('/tickets/upload-images', [TicketController::class, 'uploadTicketImages']);
        Route::get('/tickets/{ticketUuid}/images', [TicketController::class, 'getTicketImages']);

        Route::get('/tickets', [TicketController::class, 'assignedTickets']);
        Route::get('/recentcompletedtickets', [TicketController::class, 'recentCompletedTickets']);
        Route::get('tickets/{uuid}', [TicketController::class, 'show']);
        Route::post('/tickets/{uuid}', [TicketController::class, 'storeTicketEntry']);


        // Notification routes
        Route::post('/notifications/register-fcm-token', [NotificationController::class, 'registerFCMToken']);
        Route::post('/notifications/test', [NotificationController::class, 'sendTestNotification']);
        Route::post('/notifications/send-ticket-notification', [NotificationController::class, 'sendTicketNotification']);
        Route::get('/notifications/settings', [NotificationController::class, 'getNotificationSettings']);
        Route::put('/notifications/settings', [NotificationController::class, 'updateNotificationSettings']);


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

        // Location tracking routes
        Route::post('/locations', [LocationController::class, 'store']);
        Route::post('/locations/bulk', [LocationController::class, 'storeBulk']);
        Route::get('/locations/user', [LocationController::class, 'getUserLocations']);
        Route::get('/locations/user/range', [LocationController::class, 'getUserLocationRange']);
        Route::get('/locations/user/stats', [LocationController::class, 'getLocationStats']);


        Route::get('/services', [ServiceController::class, 'userServices']);


    });


});
