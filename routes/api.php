<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Models\Role;

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

    Route::post('/register', 'register')->middleware();
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware("auth:sanctum");

});

Route::prefix('admin')->middleware('can:is_admin')->controller(AdminDashboardController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function() {
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
});
    
