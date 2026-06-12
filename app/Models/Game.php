<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
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
}
