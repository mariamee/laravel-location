<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $annonces = Annonce::all();
        return response()->json([
            'annonces' => $annonces,
        ]);
    }

    public function filterByCity($city)
    {
        $annonces = Annonce::where('ville', '=', $city);
        return response()->json([
            'annonces' => $annonces,
        ]);
    }

    public function myAnnounces()
    {
        $annonces = Annonce::where('particulier_id', '=', auth()->user()->id)->get();
        return response()->json([
            'annonces' => $annonces,
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
        $annonce = Annonce::create([
            'particulier_id' => $request->particulier_id,
            'categorie' => $request->categorie,
            'marque' => $request->marque,
            'prix' => $request->prix,
            'ville' => $request->ville,
            'title' => $request->title,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            // 'date_pub' => $request->date_pub,
            'disponibilite' => $request->disponibilite,
            'status' => $request->status
        ]);

        return response()->json([
            'annonce' => $annonce
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $annonce = Annonce::findOrFail($id);
        return response()->json([
            'annonce' => $annonce
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Annonce $annonce)
    {
        $annonce = Annonce::findOrFail($request->id);

        $annonce->ville = $request->ville;
        $annonce->title = $request->title;
        $annonce->description = $request->description;
        $annonce->date_debut = $request->date_debut;
        $annonce->date_fin = $request->date_fin;
        $annonce->disponibilite = $request->disponibilite;
        $annonce->status = $request->status;

        $annonce->categorie = $request->categorie;
        $annonce->marque = $request->marque;
        $annonce->prix = $request->prix;

        $annonce->update();

        return response()->json([
            'message' => 'annonce updated successfully!',
            'annonce' => $annonce
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();

        return response()->json([
            'message' => 'annonce deleted successfully!',
        ]);
    }
}
