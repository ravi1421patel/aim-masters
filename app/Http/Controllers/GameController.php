<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameParticipant;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withCount('participants')
            ->where('status', 'waiting')
            ->latest()
            ->get();

        return view('games.index', compact('games'));
    }

    public function join(Game $game)
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        if ($game->status !== 'waiting') {
            return back()->with('error', 'Game already started.');
        }

        if ($wallet->balance < $game->entry_fee) {
            return back()->with('error', 'Insufficient balance.');
        }

        if ($game->participants()->count() >= $game->max_players) {
            return back()->with('error', 'Game full.');
        }

        DB::transaction(function () use ($game, $user, $wallet) {

            $wallet->decrement('balance', $game->entry_fee);

            GameParticipant::create([
                'game_id' => $game->id,
                'user_id' => $user->id,
                'entry_fee' => $game->entry_fee,
                'status' => 'joined',
            ]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'game_entry',
                'amount' => $game->entry_fee,
                'reference_id' => $game->id,
            ]);
        });

        return back()->with('success', 'Joined game successfully.');
    }
}
