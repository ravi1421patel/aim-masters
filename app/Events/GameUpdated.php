<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class GameUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $gameId;

    public $players;

    public $maxPlayers;

    public function __construct($gameId, $players, $maxPlayers)
    {
        $this->gameId = $gameId;
        $this->players = $players;
        $this->maxPlayers = $maxPlayers;
    }

    public function broadcastOn()
    {
        return new Channel('game.'.$this->gameId);
    }

    public function broadcastAs()
    {
        return 'game.updated';
    }

    public function broadcastWith()
    {
        return [
            'gameId' => $this->gameId,
            'players' => $this->players,
            'maxPlayers' => $this->maxPlayers,
        ];
    }
}
