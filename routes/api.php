<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// All routes expects a JSON response
Route::group(['middleware' => ['json.response']], function() {
    Route::post('login', 'PassportController@login');
    Route::post('register', 'PassportController@register');

    # Route related to manage teams
    // Route::apiResource('team', 'TeamsController');

    // List all teams
    Route::get('team', 'TeamsController@index');

    // Retreive a team info
    Route::get('team/{id}', 'TeamsController@show');

    // Create a team
    Route::post('team', 'TeamsController@store')->middleware('auth:api', 'is_admin');
    
    // Update a team
    Route::put('team/{id}', 'TeamsController@update')->middleware('auth:api', 'is_admin');

    // Delete a team
    Route::delete('team/{id}', 'TeamsController@destroy')->middleware('auth:api', 'is_admin');


    # Routes related to Players

    // List all players
    Route::get('player', 'PlayerController@index');

    // Retreive a player info
    Route::get('player/{id}', 'PlayerController@show');

    // Create player
    Route::post('player', 'PlayerController@store')->middleware('auth:api', 'is_admin');

    // Update player
    Route::put('player/{id}', 'PlayerController@update')->middleware('auth:api', 'is_admin');

    // Delete player
    Route::delete('player/{id}', 'PlayerController@destroy')->middleware('auth:api', 'is_admin');

});