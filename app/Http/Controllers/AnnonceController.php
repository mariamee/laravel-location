<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Premium;

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
        $annonces = Annonce::where('status', '=', '1')->get();
        return response()->json([
            'annonces' => $annonces,
        ]);
    }



    public function public()
    {
        $annonces = Annonce::where('disponibilite', '=', 1)
            ->where('status', 'like', "Not archived")
            ->get();

        return response()->json([
            'annonces' => $annonces,
        ]);
    }




    /* this filter will be used to search for all the announcements by;
            category, price, city, availability 
    */
    public function publicFilter(Request $request)
    {

        /*
        {
            "categorie": "categorie",
            "prix": 200,  <-this is the max price
            "ville": "Tetouan",
        }
        */

        $annonces = Annonce::where('ville', 'like', $request->ville)
            ->where("categorie", "like", $request->categorie)
            ->where("prix", "<=", $request->prix)
            ->where('status', 'like', "Not archived")
            ->where('disponibilite', '=', 1)
            ->get();


        if ($annonces->isEmpty()) {
            return response()->json([
                "message" => "pas d'annonces trouver pour cette recherche",
                'annonces' => $annonces,
            ]);
        }

        return response()->json([
            'annonces' => $annonces,
        ]);
    }


    public function filterByCity($city)
    {
        $annonces = Annonce::where('ville', 'like', $city)->get();

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

        //data should be sended in the next format
        // {
        //     "particulier_id"  : 1, 
        //     "categorie" : "category name",
        //     "marque" : "nike",
        //     "prix" : 789,
        //     "ville" : "ville",
        //     "image" : image upload
        //     "title" : "title",
        //     "description" : " description ",
        //     "date_debut" : "2022-05-11",
        //     "date_fin" : "2022-06-21",
        //     "disponibilite" : 1,
        // }

        // if(!$request->hasFile('image')) {
        //     return response()->json(['upload_file_not_found'], 400);
        // }

        $path = "";
        if ($request->hasFile('image')) {

            $destination_path = '/public/images/annonces';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($destination_path, $image_name);
        }

        $annonce = Annonce::create([
            'particulier_id' => $request->particulier_id,
            'categorie' => $request->categorie,
            'marque' => $request->marque,
            'prix' => $request->prix,
            'ville' => $request->ville,
            'title' => $request->title,
            'image' => $path,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            // 'date_pub' => $request->date_pub,
            'disponibilite' => $request->disponibilite,
            'status' => "1"
        ]);

        return response()->json([
            'annonce' => $annonce
        ]);
    }


    public function storeAnnonceWithPremiumOption(Request $request)
    {

        //data should be sended in the next format
        // {
        //     "particulier_id"  : 1, 
        //     "categorie" : "category name",
        //     "marque" : "nike",
        //     "prix" : 789,
        //     "image" : image upload
        //     "ville" : "ville",
        //     "title" : "title",
        //     "description" : " description ",
        //     "date_debut" : "2022-05-11",
        //     "date_fin" : "2022-06-21",
        //     "disponibilite" : 1,
        //     "date_debut_premium": "",
        //     "date_fin_premium": ""
        // }

        $path = "";
        if ($request->hasFile('image')) {

            $destination_path = '/public/images/annonces';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($destination_path, $image_name);
        }

        $annonce = Annonce::create([
            'particulier_id' => $request->particulier_id,
            'categorie' => $request->categorie,
            'marque' => $request->marque,
            'prix' => $request->prix,
            'image' => $path,
            'ville' => $request->ville,
            'title' => $request->title,
            'description' => $request->description,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            //'date_pub' => $request->date_pub,
            'disponibilite' => $request->disponibilite,
            'status' => "Not archived"
        ]);

        $premium = $premium = Premium::create([
            'annonce_id' => $annonce->id,
            'date_debut' => $request->date_debut_premium,
            'date_fin' => $request->date_fin_premium,
        ]);


        return response()->json([
            "message" => "annonce created with premium options successfully!",
            'annonce' => $annonce,
            "premium" => $premium
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


    public function archived($id)
    {
        $annonce = Annonce::findOrFail($id);

        $annonce->status = "archived";

        $annonce->update();

        return response()->json([
            "message" => "annonce archiver!",
            'annonce' => $annonce
        ]);
    }

    public function unarchive($id)
    {
        $annonce = Annonce::findOrFail($id);

        $annonce->status = "Not archived";

        $annonce->update();

        return response()->json([
            "message" => "annonce desarchiver!",
            'annonce' => $annonce
        ]);
    }

    public function getMyArchivedAnnonces()
    {
        $annonces = Annonce::where('particulier_id', '=', auth()->user()->id)
            ->where('status', 'like', "archived")
            ->get();

        return response()->json([
            'annonces' => $annonces,
        ]);
    }

    public function getMyNotArchivedAnnonces()
    {
        $annonces = Annonce::where('particulier_id', '=', auth()->user()->id)
            ->where('status', 'like', "Not archived")
            ->get();

        return response()->json([
            'annonces' => $annonces,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*
        {
            
            "particulier_id": 2,
            "categorie": "category update",
            "marque": "nike update",
            "prix": 500,
            "ville": "tetouan",
            "title": "bdskbvasd;b update",
            "description": "dsjkkjasdvkndskajbvdsjk update",
            "date_debut": "2022-05-11",
            "date_fin": "2022-05-15",
            "date_pub": "2022-05-21 19:34:42",
            "disponibilite": 1,
            "image" : "url"

        }
        */
        $annonce = Annonce::findOrFail($id);

        $path = "";
        if ($request->hasFile('image')) {

            $destination_path = '/public/images/annonces';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $annonce->image = $request->file('image')->storeAs($destination_path, $image_name);
        }

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
