<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function create()
    {
        return view('withdraw.create');
    }

    public function store(Request $request)
    {
        $wallet = auth()->user()->wallet;

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:100'
            ],
            'upi_id' => [
                'required',
                'string',
                'max:255'
            ]
        ]);

        if (!$wallet) {
            return back()->withErrors([
                'amount' => 'Wallet not found.'
            ]);
        }

        if ($request->amount > $wallet->balance) {
            return back()->withErrors([
                'amount' => 'Insufficient wallet balance.'
            ]);
        }

        Withdraw::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'upi_id' => $request->upi_id,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('withdraw.create')
            ->with(
                'success',
                'Withdraw request submitted successfully.'
            );
    }
}
