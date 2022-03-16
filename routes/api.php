<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
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
    Route::post('/register', 'register')->middleware();
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
Route::get('/test',function(){
    return auth()->user();
})->middleware("auth:sanctum");
