<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title',
        'entry_fee',
        'max_players',
        'status',
        'winner_id',
        'started_at',
        'finished_at',
    ];

    public function participants()
    {
        return $this->hasMany(GameParticipant::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function getPrizePoolAttribute()
    {
        return $this->participants()->count() * $this->entry_fee;
    }

    public function getCommissionAttribute()
    {
        return $this->prize_pool * 0.10;
    }

    public function getNetPrizeAttribute()
    {
        return $this->prize_pool - $this->commission;
    }
}
