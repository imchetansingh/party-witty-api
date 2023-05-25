<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\Artist\StoreRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $artist = new Artist();
        $artist->username = $request->username;
        $artist->email = $request->email;
        $artist->password = Hash::make($request->password);
        $artist->referral_code = 'PW'. rand(100000, 999999);
        if($artist->save()){
            return response()->json([
                'success' => true,
                'message' => 'Artist created successfully',
                'data' => $artist
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Artist not created',
        ], 400);

    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        //
    }
}
