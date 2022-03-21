<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blacklist = Blacklist::with('users')->get();

        return response()->json([
            'blacklist' => $blacklist,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUserToBlackList(Request $request)
    {
        $blacklist = Blacklist::create([
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'message' => "user added to blacklist successfully!",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function show(Blacklist $blacklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blacklist $blacklist)
    {
        //
    }
}
