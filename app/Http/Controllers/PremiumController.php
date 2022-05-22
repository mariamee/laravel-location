<?php

namespace App\Http\Controllers;

use App\Models\Premium;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PremiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $premium = DB::table('annonces')
            ->join('premiums', 'annonces.id', '=', 'premiums.annonce_id')
            ->select('annonces.*', 'premiums.date_debut', 'premiums.date_fin')
            ->where('premiums.date_fin', '>', Carbon::now())
            ->where('disponibilite', '=', 1)
            ->get();

        return response()->json([
            'premium' => $premium,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $premium = Premium::create([
            'annonce_id' => $request->annonce_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Premium  $premium
     * @return \Illuminate\Http\Response
     */
    public function show(Premium $premium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Premium  $premium
     * @return \Illuminate\Http\Response
     */


    /*
    you have to send a put request with the following json format with the annonce id in parameter 

    method: put
    url: http://127.0.0.1:8000/api/premium/update/10

    {
        "date_debut": "2022-05-10",
        "date_fin": "2022-06-23"
    }   
    */
    public function update(Request $request, $id)
    {
        $premium = Premium::where("annonce_id", '=', $id)->first();

        $premium->date_debut = $request->date_debut;
        $premium->date_fin = $request->date_fin;
        $premium->update();

        return response()->json([
            "message" => "updated successfully!",
            'premium' => $premium,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Premium  $premium
     * @return \Illuminate\Http\Response
     */

    /*
    you have to send a put request with the following json format with the annonce id in parameter 
    method: delete
    url: http://127.0.0.1:8000/api/premium/delete/10
    */
    public function destroy($id)
    {
        $premium = Premium::where("annonce_id", '=', $id)->first();
        $premium->delete();

        return response()->json([
            "message" => "deleted successfully!",
        ]);
    }
}
