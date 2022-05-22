<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\ObjetController;

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


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/locations', function () {
    return 'location test test test test';
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/avis', [AvisController::class, 'index']);
    Route::get('/foo', function () {
        return 'avis test';
    });
    Route::get('/avis/{id}', [AvisController::class, 'show']);
    Route::put('/avis/update/{id}', [AvisController::class, 'update']);
    Route::delete('/avis/delete/{id}', [AvisController::class, 'destroy']);

    Route::post('/avis/annonce/add', [AvisController::class, 'addAvisAboutAnAnnouncement']);

    Route::get('/blacklist', [BlacklistController::class, 'index']);
    Route::post('/blacklist/add', [BlacklistController::class, 'addUserToBlackList']);

    Route::get('/annonces', [AnnonceController::class, 'index']);

    Route::middleware(['partenaire'])->group(function () {

        Route::get('/myobjects', [ObjetController::class, 'myObjects']);
        Route::get('/myobjects/{id}', [ObjetController::class, 'show']);
        Route::post('/myobjects/add', [ObjetController::class, 'store']);
        Route::put('/myobjects/update/{id}', [ObjetController::class, 'update']);
        Route::delete('/myobjects/{id}', [ObjetController::class, 'delete']);


        Route::get('/myannonces', [AnnonceController::class, 'myAnnounces']);
        Route::get('/annonce/{id}', [AnnonceController::class, 'show']);
        Route::post('/annonce/add', [AnnonceController::class, 'store']);
        Route::put('/annonce/update/{id}', [AnnonceController::class, 'update']);
        Route::delete('/annonce/delete/{id}', [AnnonceController::class, 'destroy']);

        Route::post('/avis/partenaire/add', [AvisController::class, 'addAvisAboutAClient']);
    });

    Route::middleware(['client'])->group(function () {
        // Route::get('/annonces', [AnnonceController::class, 'index']);
        Route::get('/annonces/ville/{city}', [AnnonceController::class, 'filterByCity']);

        Route::post('/avis/client/add', [AvisController::class, 'addAvisAboutAPartenaire']);
    });

    Route::get('user', [AuthController::class, 'user']);
    Route::put('user', [AuthController::class, 'update']);
    Route::get('logout', [AuthController::class, 'logout']);
});
