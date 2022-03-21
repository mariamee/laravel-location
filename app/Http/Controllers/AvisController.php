<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\AvisClient;
use App\Models\AvisObjet;
use App\Models\AvisPartenaire;
use Illuminate\Http\Request;

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



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAvisAboutAClient(Request $request)
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
            'status'  => $request->status,
            // I don't know if the status should be initialized in the controller or in the request 
            // please make sure to take the best decision for this!!
            // I suggest to initialize status with a value for example: 'status'  => 'active' 
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


    public function addAvisAboutAnAnnouncement(Request $request)
    // this function is responsible about adding an avis about an object, see avis_objet table!
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
            'status'  => $request->status,
            // I don't know if the status should be initialized in the controller or in the request 
            // please make sure to take the best decision for this!!
            // I suggest to initialize status with a value for example: 'status'  => 'active' 
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

    public function addAvisAboutAPartenaire(Request $request)
    // this function is responsible about adding an avis about an Partenaire, see avis_partenaires table!
    {
        $avis = Avis::create([
            'note' => $request->note,
            'commentaire'  => $request->commentaire,
            'status'  => $request->status,
            // I don't know if the status should be initialized in the controller or in the request 
            // please make sure to take the best decision for this!!
            // I suggest to initialize status with a value for example: 'status'  => 'active' 
        ]);

        AvisPartenaire::create([
            'avis_id' => $avis->id,
            'client_id' => auth()->user()->id,
            'particulier_id' => $request->partenaire_id,
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
        $avis->status  = $request->status;

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
