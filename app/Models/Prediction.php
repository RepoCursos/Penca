<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $fillable = ['user_name', 'match_id', 'score1', 'score2', 'points'];

    protected $casts = [
        'score1' => 'integer',
        'score2' => 'integer',
        'points' => 'integer',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id');
    }
}
