<?php

namespace App\Http\Controllers;

use App\Models\Objet;
use Illuminate\Http\Request;

class ObjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myObjects()
    {
        $objet = Objet::where('partenaire_id', '=', auth()->user()->id)->get();

        return response()->json([
            'objet' => $objet,
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
        $objet = Objet::create([
            'categorie' => $request->categorie,
            'marque' => $request->marque,
            'prix' => $request->prix,
            'partenaire_id' => auth()->user()->id,
        ]);

        return response()->json([
            "message" => "object created successfully!",
            'objet' => $objet,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Objet  $objet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $objet = Objet::findOrFail($id);

        return response()->json([
            'objet' => $objet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Objet  $objet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Objet $objet)
    {
        $objet = Objet::findOrFail($id);
        $objet->categorie = $request->categorie;
        $objet->marque = $request->marque;
        $objet->prix = $request->prix;

        $objet->update();

        return response()->json([
            "message" => "object updated successfully!",
            'objet' => $objet,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Objet  $objet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Objet $objet)
    {
        $objet = Objet::findOrFail($id);

        $objet->delete();

        return response()->json([
            "message" => "object deleted successfully!",
        ]);
    }
}
