<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $table = 'matches';

    protected $fillable = ['team1_id', 'team2_id', 'match_date', 'match_time', 'score1', 'score2', 'is_completed'];

    protected $casts = [
        'match_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function getMatchDateTime(): Carbon
    {
        $date = $this->match_date instanceof Carbon ? $this->match_date->format('Y-m-d') : $this->match_date;
        $time = $this->match_time instanceof \DateTimeInterface ? $this->match_time->format('H:i:s') : $this->match_time;

        return Carbon::parse($date.' '.$time);
    }

    public function isPredictionLocked(): bool
    {
        $matchDateTime = $this->getMatchDateTime();
        $now = now();

        return $now->greaterThanOrEqualTo($matchDateTime->subMinutes(30));
    }

    public function canLoadResult(): bool
    {
        $matchDateTime = $this->getMatchDateTime();
        $now = now();

        return $now->greaterThanOrEqualTo($matchDateTime->addHours(2));
    }

    public function getResultForUser(string $userName): ?Prediction
    {
        return $this->predictions()->where('user_name', $userName)->first();
    }

    public function calculatePointsForUser(string $userName): int
    {
        $prediction = $this->getResultForUser($userName);

        if (! $prediction || ! $this->is_completed) {
            return 0;
        }

        if ($prediction->score1 === $this->score1 && $prediction->score2 === $this->score2) {
            return 5;
        }

        $resultPrediction = $this->getResult($prediction->score1, $prediction->score2);
        $resultReal = $this->getResult($this->score1, $this->score2);

        if ($resultPrediction === $resultReal) {
            return 2;
        }

        return 0;
    }

    private function getResult(int $score1, int $score2): string
    {
        if ($score1 > $score2) {
            return 'team1';
        } elseif ($score2 > $score1) {
            return 'team2';
        }

        return 'draw';
    }
}
