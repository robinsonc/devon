<?php

namespace App\Http\Controllers;

use App\Teams;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Validator;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TeamResource::collection(Teams::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  Allow for team update *or* create a new team
        // $team        = $request->isMethod('put') ? Teams::findOrFail($request->id) : new Teams;
        // $team->id    = $request->input('id');
        // $team->name = $request->input('name');
        // $team->logo_uri  = $request->input('logo_uri');
        
        // if ($team->save()) {
        //     return new TeamResource($team);
        // }
        
        $validator = Validator::make($request->all(), [ 
            'name'                 => 'required', 
            'logo_uri'             => 'required',
        ]);
        
        // Return error if validation fails
        if ($validator->fails()) { 
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);         
        }
        // Insert a new team
        $team = Teams::create([
            'name' => $request->input('name'),
            'logo_uri'  => $request->input('logo_uri')
        ]);

        return new TeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teams = Teams::find($id);
        $teams->update($request->all());
        return new TeamResource($teams);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function show($teams)
    {
        // Get a single team information
        $teams = Teams::findOrFail($teams);
        
        // Return a single team as a resource
        return new TeamResource($teams);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function destroy($teams)
    {
        // Get the teams
        $teams = Teams::findOrFail($teams);
        
        //  Delete the teams, return as confirmation
        if($teams->delete()) {
            return new TeamResource($teams);
        }
    }
}
