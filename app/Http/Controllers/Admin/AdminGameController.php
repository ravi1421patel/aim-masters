<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGameController extends Controller
{
    public function index()
    {
        $games = Game::with('participants.user')
            ->latest()
            ->get();

        return view('admin.games.index', compact('games'));
    }

    public function start(Game $game)
    {
        if ($game->status !== 'waiting') {
            return back()->with('error', 'Game already started.');
        }

        $game->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Game started.');
    }

    public function finish(Game $game)
    {
        if ($game->status !== 'running') {
            return back()->with('error', 'Game not running.');
        }

        $game->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        return back()->with('success', 'Game finished. Now select winner.');
    }

    public function declareWinner(Request $request, Game $game)
    {
        $request->validate([
            'winner_id' => 'required|exists:users,id',
        ]);

        if ($game->status !== 'finished') {
            return back()->with('error', 'Finish game first.');
        }

        DB::transaction(function () use ($game, $request) {

            if ($game->payout_done) {
                throw new \Exception('Payout already done');
            }

            $winnerId = $request->winner_id;

            $participantsCount = $game->participants()->count();

            $prizePool = $participantsCount * $game->entry_fee;
            $commission = $prizePool * 0.10;
            $netPrize = $prizePool - $commission;

            // update game
            $game->update([
                'winner_id' => $winnerId,
                'payout_done' => true,
                'locked_at' => now(),
            ]);

            // update participants
            $game->participants()->update([
                'status' => 'lost',
            ]);

            $game->participants()
                ->where('user_id', $winnerId)
                ->update(['status' => 'won']);

            // wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $winnerId],
                ['balance' => 0]
            );

            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $netPrize;

            $wallet->increment('balance', $netPrize);

            // 🧾 WIN TRANSACTION (YOUR STRUCTURE)
            WalletTransaction::create([
                'user_id' => $winnerId,
                'type' => 'win',
                'amount' => $netPrize,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'game',
                'reference_id' => $game->id,
                'description' => 'Game win payout',
            ]);

            // 🧾 COMMISSION TRANSACTION (SYSTEM)
            WalletTransaction::create([
                'user_id' => null,
                'type' => 'commission',
                'amount' => $commission,
                'balance_before' => null,
                'balance_after' => null,
                'reference_type' => 'game',
                'reference_id' => $game->id,
                'description' => 'Platform commission',
            ]);

        });

        return back()->with('success', 'Winner declared & payout completed.');
    }
}
