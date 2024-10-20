<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\GuideController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\ProgrammeController;
use App\Http\Controllers\Api\TouristController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//AUTH ROUTES-------------------------------------------------------------------------------------------------

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);

Route::post('adminRegister', [AuthController::class, 'adminRegister']);

Route::get('get-open-tours', [TourController::class, 'getOpenTours']);

Route::get('get-tours', [TourController::class, 'getTours']);

Route::get('get-tour-detalis/{id}', [TourController::class, 'getTourDetails']);

Route::get('get-guide-detalis/{id}', [GuideController::class, 'getGuideDetails']);

Route::get('get-driver-detalis/{id}', [DriverController::class, 'getDriverDetails']);

Route::get('get-programme-detalis/{id}', [ProgrammeController::class, 'getProgrammeDetails']);

Route::post('search-tours', [TourController::class, 'searchTours']);



//ADMIN ROUTES------------------------------------------------------------------------------------------------

Route::middleware(['admin'])->group(function(){


    Route::get('get-guides', [GuideController::class, 'getGuides']);
    Route::get('get-drivers', [DriverController::class, 'getDrivers']);
    Route::get('get-programmes', [ProgrammeController::class, 'getProgrammes']);
    Route::get('get-tourists', [TouristController::class, 'getTourists']);

    //TOUR CRUD ROUTES--------------------------------------------------------------------
    Route::post('add-tour', [TourController::class, 'addTour']);
    Route::put('update-tour/{id}', [TourController::class, 'updateTour']);
    Route::delete('delete-tour/{id}', [TourController::class, 'destroyTour']);

    //DRIVER CRUD ROUTES--------------------------------------------------------------------
    Route::post('add-driver', [DriverController::class, 'addDriver']);
    Route::put('update-driver/{id}', [DriverController::class, 'updateDriver']);
    Route::delete('delete-driver/{id}', [DriverController::class, 'destroyDriver']);

    //GUIDE CRUD ROUTES--------------------------------------------------------------------
    Route::post('add-guide', [GuideController::class, 'addGuide']);
    Route::put('update-guide/{id}', [GuideController::class, 'updateGuide']);
    Route::delete('delete-guide/{id}', [GuideController::class, 'destroyGuide']);

    //PROGRAMME CRUD ROUTES--------------------------------------------------------------------
    Route::post('add-programme', [ProgrammeController::class, 'addProgramme']);
    Route::put('update-programme/{id}', [ProgrammeController::class, 'updateProgramme']);
    Route::delete('delete-programme/{id}', [ProgrammeController::class, 'destroyProgramme']);

    Route::post('count-tour-drivers', [DriverController::class, 'getToursforDriver']);
});

Route::middleware(['apiAuth'])->post('apply-for-tour/{tour_id}', [TouristController::class, 'applyForTour']);

Route::middleware(['apiAuth'])->post('available-programme', [ProgrammeController::class, 'getAvailableProgramme']);

Route::middleware(['apiAuth'])->get('my-tours/{id}', [TouristController::class, 'getMyTour']);

Route::middleware(['apiAuth'])->post('logout', [AuthController::class, 'logout']);
