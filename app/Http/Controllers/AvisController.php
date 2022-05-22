<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\AvisClient;
use App\Models\AvisObjet;
use App\Models\AvisPartenaire;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $avis = Avis::all();
        return response()->json([
            'avis' => $avis,
        ]);
    }

    public function getAvisAboutSpecificPartenaire($id)
    {

        $avis = DB::table('avis')
            ->join('avis_partenaires', 'avis.id', '=', 'avis_partenaires.avis_id')
            ->join('users', 'avis_partenaires.partenaire_id', '=', 'users.id')
            ->where('users.id', '=', $id)
            ->select('avis.*', 'avis_partenaires.*', 'users.*')
            ->get();

        foreach ($avis as $av) {
            $client = DB::table('users')->where('id', '=', $av->client_id)->get();
            $av->client_id = $client;
        }

        return response()->json([
            'avis' => $avis,
        ]);
    }

    public function getAvisAboutSpecificAnnouncement($id)
    {
        $avis = DB::table('avis')
            ->join('avis_objets', 'avis.id', '=', 'avis_objets.avis_id')
            ->join('annonces', 'avis_objets.annonce_id', '=', 'annonces.id')
            ->where('annonces.id', '=', $id)
            ->select('avis.*', 'avis_objets.*', 'annonces.*')
            ->get();

        foreach ($avis as $av) {
            $client = DB::table('users')->where('id', '=', $av->client_id)->get();
            $partenaire = DB::table('users')->where('id', '=', $av->particulier_id)->get();

            $av->client_id = $client;
            $av->particulier_id =  $partenaire;
        }

        return response()->json([
            'avis' => $avis,
        ]);
    }

    public function getAvisAboutSpecificClient($id)
    {

        $avis = DB::table('avis')
            ->join('avis_clients', 'avis.id', '=', 'avis_clients.avis_id')
            ->join('users', 'avis_clients.client_id', '=', 'users.id')
            ->where('users.id', '=', $id)
            ->select('avis.*', 'avis_clients.*', 'users.*')
            ->get();

        foreach ($avis as $av) {
            $particulier = DB::table('users')->where('id', '=', $av->particulier_id)->get();
            $av->particulier_id = $particulier;
        }

        return response()->json([
            'avis' => $avis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*
        * the request to add a new avis about a client should be as following
        {
            "note": 3,
            "commentaire" : "tres bon service",
            "client_id" : 1
        }
    */
    public function addAvisAboutAClient(Request $request)
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
        ]);

        AvisClient::create([
            'avis_id' => $avis->id,
            'client_id' => $request->client_id,
            'particulier_id' => auth()->user()->id,
        ]);

        return response()->json([
            'avis' => $avis,
        ]);
    }


    /*
        * the request to add a new avis about a client should be as following
        {
            "note": 3,
            "commentaire" : "tres bon service",
            "client_id" : 1,
            "annonce_id": 1
        }
    */
    public function addAvisAboutAnAnnouncement(Request $request)
    // this function is responsible about adding an avis about an object, see avis_objet table!
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
        ]);

        AvisObjet::create([
            'avis_id' => $avis->id,
            'client_id' => $request->client_id,
            'annonce_id' => $request->annonce_id,
        ]);

        return response()->json([
            'avis' => $avis,
        ]);
    }
    /*
        * the request to add a new avis about a client should be as following
        {
            "note": 3,
            "commentaire" : "tres bon service",
            "partenaire_id": 1
        }
    */
    public function addAvisAboutAPartenaire(Request $request)
    // this function is responsible about adding an avis about an Partenaire, see avis_partenaires table!
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
        ]);

        AvisPartenaire::create([
            'avis_id' => $avis->id,
            'client_id' => auth()->user()->id,
            'partenaire_id' => $request->partenaire_id,
        ]);

        return response()->json([
            'avis' => $avis,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $avis = Avis::findOrFail($id);
        return response()->json([
            'avis' => $avis,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $avis = Avis::findOrFail($id);
        $avis->note = $request->note;
        $avis->commentaire  = $request->commentaire;
        $avis->update();
        return response()->json([
            "message" => "avis updated successfully!",
            'avis' => $avis,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Avis  $avis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Avis $avis)
    {
        // $avis = Avis::findOrFail($id);

        // $avis->delete(); 

        return response()->json([
            "message" => "avis delete successfully!",
        ]);
    }
}
