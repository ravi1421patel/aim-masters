<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('wallet.index', compact(
            'wallet',
            'deposits',
            'transactions'
        ));
    }
}
