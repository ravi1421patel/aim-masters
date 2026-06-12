<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function create()
    {
        return view('deposit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => [
                'required',
                'integer',
                'min:10'
            ],
            'screenshot' => 'required|image|max:4096',
            'utr' => 'nullable|string|max:100'
        ]);

        $path = $request
            ->file('screenshot')
            ->store('deposits', 'public');

        Deposit::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'screenshot' => $path,
            'utr' => $request->utr,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('deposit.create')
            ->with(
                'success',
                'Deposit request submitted successfully.'
            );
    }
}
