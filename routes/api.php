<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SupportDashboardController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use App\Models\User;
use App\Models\Support_ticket;
use Illuminate\Support\Facades\Auth;

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
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware("auth:sanctum");

});

Route::prefix('admin')->name('admin.')->middleware('can:is_admin')->controller(AdminDashboardController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('/home', 'index');

        // User CRUD operations
        Route::get('/user/delete/{id}', 'delete_user');
        Route::get('/user/restore/{id}', 'restore_user');
        Route::get('/user/destroy/{id}', 'destory_user');

        // Travel CRUD operations

        Route::get('/travel', 'travels');
        Route::post('/travel', 'travel_create');
        Route::put('/travel/{id}', 'travel_update');
        Route::get('/travel/delete/{id}', 'travel_delete');
    });
});

Route::prefix('user')->middleware("auth:sanctum")->controller(UserController::class)->group(function(){

    Route::post('/update_infos','update_infos');
    Route::post('/update_password','update_password');

});

Route::prefix('support')->middleware("auth:sanctum")->controller(SupportDashboardController::class)->group(function(){

    Route::group(['middleware' => 'can:acces-supportDashboard'],function(){
        Route::get('/my_supportTickets','supportTickets_get');
        Route::get('/support_ticket/answers/{id}','supportTicketAnswers_get');
        Route::post('/support_ticket/answer/{id}','supportTicketAnswer_add');
    });

    Route::group(['middleware' => 'can:is_support'], function(){
        Route::get('/support_tickets/all', 'index');
        Route::get('/support_ticket/assign/{id}', 'supportTicket_assign');
        Route::get('/support_ticket/disable/{id}', 'supportTicket_disable');
    });

    Route::post('/support_tickets/create','supportTicket_create')->middleware('can:is_passenger');
    
});

Route::get('/test',function(){
    return Support_ticket::find(1)->answers;
});