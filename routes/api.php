<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SupportDashBoardController;
use App\Http\Controllers\ValidatorDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Repositories\TicketRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;

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

        // Reviews
        Route::get('reviews/{id}', 'reviews_get');

});

Route::prefix('user')->middleware("auth:sanctum")->controller(UserController::class)
                                                 ->group(function(){

    Route::post('/update_infos','update_infos');
    Route::post('/update_password','update_password');
    Route::post('/reset_password', 'reset_password')->middleware('resetAcess');
    Route::post('/password_pin', [UserRepository::class, 'passwordPIN']);

    Route::group(['middleware' => 'can:is_passenger'], function(){
        Route::post('/reviews/create/{id}', 'review_add');
        Route::get('/my_travels', 'get_personnalTravels');
    });
});



Route::prefix('support')->middleware(["auth:sdownloadTicketAsPDFanctum", "can:is_supportORpassenger"])
                        ->controller(SupportDashBoardController::class)->group(function(){

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

Route::prefix('validator')->middleware(["auth:sanctum", "can:is_validator"])
    ->controller(ValidatorDashboardController::class)->group(function(){

        Route::get('/tickets/{id}', 'get_travelTickets');
        Route::get('/todayTravels', 'get_todayTravels');
        Route::post('/tickets/validate/{id}', 'validate_ticket');

});
Route::get('/authUser', [App\Http\Controllers\UserController::class, 'get_authUser']);


Route::post('/validator/login', [AuthController::class, 'validatorLogin']);
Route::prefix('validator')->middleware(["auth:sanctum", "can:is_validator"])
                          ->group(function(){
    Route::post('/validate_ticket', [TicketRepository::class, 'validateTicket']);

});


Route::get('/PDF', [PDFController::class, 'downloadTicketAsPDF']);

Route::post('/route', [ReservationController::class, 'PassThroughTravels']);
Route::post('/checkAvailability', [ReservationController::class, 'AvAndP']);

Route::get('/travels', [ReservationController::class, 'AllTravels']);

Route::get('/test',function(){

    return;
});