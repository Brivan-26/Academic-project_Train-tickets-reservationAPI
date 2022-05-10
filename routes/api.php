<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SupportDashBoardController;
use App\Http\Controllers\UserController;


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

Route::controller(AuthController::class)->group(function(){

     //authentification routes
    Route::post('/register', 'register')->middleware("guest");
    Route::post('/login', 'login')->middleware("guest");
    Route::post('/logout', 'logout')->middleware("auth:sanctum");

});

Route::prefix('admin')->middleware(['auth:sanctum', 'can:is_admin'])->controller(AdminDashboardController::class)->group(function () {
        Route::get('/home', 'index');

        // User CRUD operations
        Route::get('/user/delete/{id}', 'delete_user');
        Route::get('/user/restore/{id}', 'restore_user');
        Route::get('/user/destroy/{id}', 'destory_user');
        Route::get('/user/upgradeRole/{id}', 'upgradeRole_user');

        // Travel CRUD operations

        Route::get('/travel', 'travels');
        Route::post('/travel', 'travel_create');
        Route::put('/travel/{id}', 'travel_update');
        Route::get('/travel/delete/{id}', 'travel_delete');

        // Station CRUD operations

        Route::get('/station', 'stations');
        Route::post('/station', 'station_create');
        Route::put('/station/{id}', 'station_update');
        Route::get('/station/delete/{id}', 'station_delete');
        Route::get('/station/restore/{id}', 'station_restore');
        Route::get('/station/destroy/{id}', 'station_destroy');

        // Tickets
        Route::get('/tickets', 'tickets');
        Route::get('/tickets/nonExpired', 'tickets_nonExpired');
        Route::get('/ticket/{id}', 'ticket_get');
});

Route::prefix('user')->middleware("auth:sanctum")->controller(UserController::class)->group(function(){

    Route::post('/update_infos','update_infos');
    Route::post('/update_password','update_password');

});

Route::prefix('support')->middleware(["auth:sanctum", "can:is_supportORpassenger"])->controller(SupportDashBoardController::class)->group(function(){

        Route::get('/my_supportTickets','supportTickets_get');
        Route::get('/support_ticket/answers/{id}','supportTicketAnswers_get');
        Route::post('/support_ticket/answer/{id}','supportTicketAnswer_add');

    Route::group(['middleware' => 'can:is_support'], function(){
        Route::get('/support_tickets/all', 'index');
        Route::get('/support_ticket/assign/{id}', 'supportTicket_assign');
        Route::get('/support_ticket/disable/{id}', 'supportTicket_disable');
    });

    Route::post('/support_tickets/create','supportTicket_create')->middleware('can:is_passenger');
    
});