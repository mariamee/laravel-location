<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\ObjetController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavorisController;

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

// demande d'inscription sur l'application
Route::post('register', [AuthController::class, 'register']);
// connexion
Route::post('login', [AuthController::class, 'login']);

// voir tous les annonces pour le public non connecter
Route::get('/annoncespublic', [AnnonceController::class, 'public']);
// voir les annonces premium
Route::get('/annonces/premium', [PremiumController::class, 'index']);

// filtrer par categorie, ville et prix tous les annonce non archiver et disponible!
Route::post('/annonces/filter', [AnnonceController::class, 'publicFilter']);

// voir tous les annonce sur l'application
Route::get('/annonces', [AnnonceController::class, 'index']);
// voir les details d'une annonce
Route::get('/annonce/{id}', [AnnonceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // voir tous les avis 
    Route::get('/avis', [AvisController::class, 'index']);
    // voir les details sur un avis ( qui a fait l'avis et sur qui )
    Route::get('/avis/{id}', [AvisController::class, 'show']);
    // update avis
    Route::put('/avis/update/{id}', [AvisController::class, 'update']);
    // supprimer avis
    Route::delete('/avis/delete/{id}', [AvisController::class, 'destroy']);
    // ajouter un avis sur une annonce
    Route::post('/avis/annonce/add', [AvisController::class, 'addAvisAboutAnAnnouncement']);

    // voir tous les avis sur un specifique annonce
    Route::get('/avis/annonce/{id}', [AvisController::class, 'getAvisAboutSpecificAnnouncement']);
    // voir tous les avis sur un specifique partenaire
    Route::get('/avis/annonce/partenaire/{id}', [AvisController::class, 'getAvisAboutSpecificPartenaire']);
    // voir tous les avis sur un specifique client
    Route::get('/avis/annonce/client/{id}', [AvisController::class, 'getAvisAboutSpecificClient']);



    Route::get('/blacklist', [BlacklistController::class, 'index']);
    Route::post('/blacklist/add', [BlacklistController::class, 'addUserToBlackList']);


    Route::middleware(['partenaire'])->group(function () {

        // Voir tous mes annonces pour le partenaire connecter 
        Route::get('/myannonces', [AnnonceController::class, 'myAnnounces']);
        // ajouter une annonce sans l'option premium
        Route::post('/annonce/add', [AnnonceController::class, 'store']);
        // ajouter une annonce avec les information premium
        Route::post('/annonce/addpremium', [AnnonceController::class, 'storeAnnonceWithPremiumOption']);
        // ajouter l'option premium a une annonce qui m'a pas l'option premium
        Route::get('/annonces/add/premium', [PremiumController::class, 'store']);

        /* 
            modifier Premium
            you have to pass the id of the annonce not the id of table premiums!!!
        */
        Route::put('/premium/update/{id}', [PremiumController::class, 'update']);
        // supprimer premium
        Route::delete('/premium/delete/{id}', [PremiumController::class, 'destroy']);

        // modifier annonce
        Route::put('/annonce/update/{id}', [AnnonceController::class, 'update']);
        // supprimer annonce
        Route::delete('/annonce/delete/{id}', [AnnonceController::class, 'destroy']);

        // voir tous les annonce archiver pour le partenaire connecter
        Route::get('/archived', [AnnonceController::class, 'getMyArchivedAnnonces']);
        // voir tous les annonce non archiver pour le partenaire connecter
        Route::get('/unarchive', [AnnonceController::class, 'getMyNotArchivedAnnonces']);
        // archiver une annonce
        Route::put('/archived/{id}', [AnnonceController::class, 'archived']);
        // desarchiver une annonce archiver
        Route::put('/unarchive/{id}', [AnnonceController::class, 'unarchive']);

        // ajouter un avis sur un client
        Route::post('/avis/partenaire/add', [AvisController::class, 'addAvisAboutAClient']);

        // tous les reservation recu par un particulier en attente
        Route::get('/reservation/partenaire', [ReservationController::class, 'getPendingReservationForSpecificPartenaire']);
        // tous les reservation accepter par un particulier
        Route::get('/reservation/partenaire/accepter', [ReservationController::class, 'getAcceptedReservationForSpecificPartenaire']);
        // accepter une demande de reservation
        Route::put('/reservation/accepter/{id}', [ReservationController::class, 'accepter']);
        // refuser une demande de reservation
        Route::put('/reservation/refuser/{id}', [ReservationController::class, 'refuser']);
        // finaliser de reservation
        Route::put('/reservation/finaliser/{id}', [ReservationController::class, 'finaliser']);
    });

    Route::middleware(['client'])->group(function () {

        // le client peu voir tous les reservation qui a effectuer 
        Route::get('/reservation', [ReservationController::class, 'index']);
        // voir les details d'une reservation
        Route::get('/reservation/{id}', [ReservationController::class, 'show']);
        // ajouer une demande de reservation
        Route::post('/reservation/add', [ReservationController::class, 'store']);
        // annuler une demande de reservation
        Route::put('/reservation/annuler/{id}', [ReservationController::class, 'annuler']);

        // voir tout les annonce dans mes favoris
        Route::get('/favoris', [FavorisController::class, 'index']);
        // ajouter une annonce a mes favoris
        Route::post('/favoris/add', [FavorisController::class, 'store']);
        // supprimer une annonce de mes favoris
        Route::delete('/favoris/delete/{id}', [FavorisController::class, 'destroy']);


        // filtrer par ville
        Route::get('/annonces/ville/{city}', [AnnonceController::class, 'filterByCity']);
        // ajouter un avis sur le partenaire
        Route::post('/avis/client/add', [AvisController::class, 'addAvisAboutAPartenaire']);
    });

    // voir mes information si je suis connecter
    Route::get('user', [AuthController::class, 'user']);
    // voir les information d'un utilisateur 
    Route::get('user/{id}', [AuthController::class, 'annonceUser']);
    // modifier les information d'un utilisateur
    Route::put('user', [AuthController::class, 'update']);
    // ajouter la photo du profile a un utilisateur
    Route::post('user/add/photo', [AuthController::class, 'addProfilePicture']);
    // se deconnecter
    Route::get('logout', [AuthController::class, 'logout']);
});
