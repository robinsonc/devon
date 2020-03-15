<?php

namespace App\Http\Controllers;

use App\Player;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;
use Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PlayerResource::collection(Player::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'firstname'                    => 'required', 
            'lastname'                      => 'required', 
            'player_image_uri'                => 'required',
            'team_id'                            => 'required|numeric|not_in:0|exists:teams,id',
        ]);
        
        // Return error if validation fails
        if ($validator->fails()) { 
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);         
        }
        // Insert a new player
        $player = Player::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'player_image_uri' => $request->input('player_image_uri'),
            'team_id'  => $request->input('team_id')
        ]);

        return new PlayerResource($player);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show($player)
    {
         // Get a single player detail
         $player = Player::findOrFail($player);
        
         // Return a player as a resource
         return new PlayerResource($player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $player)
    {
        $validator = Validator::make($request->all(), [ 
            'firstname'                    => 'required', 
            'lastname'                     => 'required', 
            'player_image_uri'             => 'required',
            'team_id'                      => 'required|numeric|not_in:0|exists:teams,id',
        ]);
        
        // Return error if validation fails
        if ($validator->fails()) { 
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);         
        }

        $player = Player::find($player);
        $player->update($request->all());
        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy($player)
    {
        // Get the teams
        $player = Player::findOrFail($player);
        
        //  Delete the player, return as confirmation
        if($player->delete()) {
            return new PlayerResource($player);
        }
    }
}
