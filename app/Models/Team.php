<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'group_letter'];

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Game::class, 'team1_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Game::class, 'team2_id');
    }
}
