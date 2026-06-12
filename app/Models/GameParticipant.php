<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Game;
class GameParticipant extends Model
{
    protected $fillable = [
        'game_id',
        'user_id',
        'entry_fee',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
