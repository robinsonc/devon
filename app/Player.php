<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'firstname', 'lastname', 'player_image_uri', 'team_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    /**
     * Get the team of this player.
     */
    public function team()
    {
        return $this->belongsTo('App\Teams', 'team_id');
    }

}
