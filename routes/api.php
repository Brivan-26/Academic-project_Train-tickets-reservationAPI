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

Route::prefix('admin')->name('admin.')->middleware('can:is_admin')->controller(AdminDashboardController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('/home', 'index');
        Route::get('/delete/user/{id}', 'delete_user');
        Route::get('/restore/user/{id}', 'restore_user');
        Route::get('/destory/user/{id}', 'destory_user');
    });
});
    
