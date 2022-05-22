<?php

namespace App\Http\Controllers;

use App\Notifications\AvisClientNotification;
use App\Notifications\AvisPartenaireNotification;
use App\Notifications\ReservationAccepterNotification;
use App\Notifications\ReservationRefuserNotification;


use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Annonce;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::where("client_id", "=", auth()->user()->id)
            ->get();

        return response()->json([
            'reservations' => $reservations
        ]);
    }

    public function getPendingReservationForSpecificPartenaire()
    {
        $reservations = DB::table('reservations')
            ->join('annonces', 'annonces.id', '=', 'reservations.annonce_id')
            ->select('reservations.*', 'annonces.*')
            ->where('reservations.status', 'like', 'en attente')
            ->where('annonces.particulier_id', '=', auth()->user()->id)
            ->get();


        return response()->json([
            'reservations' => $reservations
        ]);
    }

    public function getAcceptedReservationForSpecificPartenaire()
    {
        $reservations = DB::table('reservations')
            ->join('annonces', 'annonces.id', '=', 'reservations.annonce_id')
            ->select('reservations.*', 'annonces.*')
            ->where('reservations.status', 'like', 'accepter')
            ->where('annonces.particulier_id', '=', auth()->user()->id)
            ->get();


        return response()->json([
            'reservations' => $reservations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * you have to send your request as following
        { 
            "annonce_id" : 1,
            "date_debut" : "2022-06-02",
            "date_fin" : "2022-06-02",
        }
           
     */
    public function store(Request $request)
    {
        $reservation = Reservation::create([
            'annonce_id' => $request->annonce_id,
            'client_id' => auth()->user()->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'date_acceptation' => $request->date_acceptation,
            'status' => "en attente",
        ]);

        return response()->json([
            'reservation' => $reservation
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        return response()->json([
            "reservation" => $reservation
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function annuler($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = "annuler";
        $reservation->update();

        return response()->json([
            "message" => "Reservation annuler",
        ]);
    }

    public function accepter($id)
    {
        $reservation = Reservation::findOrFail($id);
        $annonce = Annonce::findOrFail($id);

        if ($annonce->particulier_id == auth()->user()->id) {

            $reservation->date_acceptation = Carbon::now();
            $reservation->status = "accepter";
            $reservation->update();

            $annonce->disponibilite = 0;
            $annonce->update();

            $client = User::findOrFail($reservation->client_id);

            $client->notify(new ReservationAccepterNotification($client));

            return response()->json([
                "message" => "Reservation accepter",
            ]);
        }

        return response()->json([
            "error" => "you are not authorized to access to this route!",
        ]);
    }

    public function finaliser($id)
    {
        $reservation = Reservation::findOrFail($id);
        $annonce = Annonce::findOrFail($id);

        if ($annonce->particulier_id == auth()->user()->id) {

            $reservation->date_acceptation = Carbon::now();
            $reservation->status = "finaliser";
            $reservation->update();

            $client = User::findOrFail($reservation->client_id);
            $partenaire = User::findOrFail(auth()->user()->id);

            // Notify client and partenaire by email
            $client->notify(new AvisPartenaireNotification($client));
            $partenaire->notify(new AvisClientNotification($partenaire));

            return response()->json([
                "message" => "Reservation finaliser!",
            ]);
        }

        return response()->json([
            "error" => "you are not authorized to access to this route!",
        ]);
    }


    public function refuser($id)
    {
        $reservation = Reservation::findOrFail($id);
        $annonce = Annonce::findOrFail($id);

        if ($annonce->particulier_id == auth()->user()->id) {

            $reservation->status = "refuser";
            $reservation->update();


            $client = User::findOrFail($reservation->client_id);

            $client->notify(new ReservationRefuserNotification($client));

            return response()->json([
                "message" => "Reservation refuser",
            ]);
        }

        return response()->json([
            "error" => "you are not authorized to access to this route!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
