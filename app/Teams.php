<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $fillable = [
        'name', 'logo_uri'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the players of this team.
     */
    public function players()
    {
        return $this->hasMany('App\Player', 'team_id');
    }
}
