<?php

namespace App\Http\Controllers;

use App\Models\Favoris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavorisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //voir les annonce que un client a ajouter a ses favoris
        $favoris = DB::table('annonces')
            ->join('favoris', 'annonces.id', '=', 'favoris.annonce_id')
            ->where('favoris.client_id', '=', auth()->user()->id)
            ->select('annonces.*', 'favoris.*')
            ->get();

        return response()->json([
            'favoris' => $favoris,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*
        the request to add an announcement to favoris 
        should be like this 
          {
               "annonce_id" : 2
          }
    */
    public function store(Request $request)
    {
        // ajouter une annonce au favorie
        $favoris = Favoris::create([
            'annonce_id' => $request->annonce_id,
            'client_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Added to Favoris successfully',
            'favoris' => $favoris,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favoris  $favoris
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // supprimer une annonce des favoris
        $favoris = Favoris::findOrFail($id);
        $favoris->delete();

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
}
