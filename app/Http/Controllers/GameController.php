<?php

namespace App\Http\Controllers;

use App\Events\GameUpdated;
use App\Models\Game;
use App\Models\GameParticipant;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Cache;
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

        // 🔒 SINGLE CLEAN LOCK (IMPORTANT FIX)
        $lock = Cache::lock('game_join_'.$game->id, 10);

        if (! $lock->get()) {
            return back()->with('error', 'System busy, try again.');
        }

        try {

            if ($game->status !== 'waiting') {
                return back()->with('error', 'Game already started.');
            }

            if ($wallet->balance < $game->entry_fee) {
                return back()->with('error', 'Insufficient balance.');
            }

            return DB::transaction(function () use ($game, $user, $wallet) {

                $game = Game::where('id', $game->id)
                    ->lockForUpdate()
                    ->first();

                $alreadyJoined = GameParticipant::where('game_id', $game->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($alreadyJoined) {
                    return back()->with('error', 'Already joined this game.');
                }

                $takenSeats = GameParticipant::where('game_id', $game->id)
                    ->pluck('seat_no')
                    ->toArray();

                $seatNo = null;

                for ($i = 1; $i <= $game->max_players; $i++) {
                    if (! in_array($i, $takenSeats)) {
                        $seatNo = $i;
                        break;
                    }
                }

                if (! $seatNo) {
                    return back()->with('error', 'Game full.');
                }

                $balanceBefore = $wallet->balance;

                $wallet->decrement('balance', $game->entry_fee);

                $balanceAfter = $balanceBefore - $game->entry_fee;

                GameParticipant::create([
                    'game_id' => $game->id,
                    'user_id' => $user->id,
                    'entry_fee' => $game->entry_fee,
                    'seat_no' => $seatNo,
                    'status' => 'joined',
                ]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'game_entry',
                    'amount' => $game->entry_fee,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'reference_type' => 'game',
                    'reference_id' => $game->id,
                    'description' => "Joined game - Seat #{$seatNo}",
                ]);

                $filledSeats = GameParticipant::where('game_id', $game->id)->count();

                if ($filledSeats >= $game->max_players) {
                    $game->update([
                        'status' => 'running',
                    ]);
                }

                // 🚀 LEVEL 5 REAL-TIME TRIGGER (IMPORTANT)
                event(new GameUpdated(
                    $game->id,
                    $filledSeats,
                    $game->max_players
                ));

                return back()->with('success', 'Joined game successfully.');
            });

        } finally {
            optional($lock)->release();
        }
    }
}
