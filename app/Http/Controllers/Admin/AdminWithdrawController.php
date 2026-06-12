<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;

class AdminWithdrawController extends Controller
{
    public function index()
    {
        $withdraws = Withdraw::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.withdraws.index', compact('withdraws'));
    }

    public function approve(Withdraw $withdraw)
    {
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        DB::transaction(function () use ($withdraw) {

            $wallet = $withdraw->user->wallet;

            if (! $wallet || $wallet->balance < $withdraw->amount) {
                throw new \Exception('Insufficient balance.');
            }

            $wallet->decrement('balance', $withdraw->amount);

            $withdraw->update([
                'status' => 'approved',
            ]);
        });

        return back()->with('success', 'Withdraw approved successfully.');
    }

    public function reject(Withdraw $withdraw)
    {
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        $withdraw->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Withdraw rejected.');
    }
}
