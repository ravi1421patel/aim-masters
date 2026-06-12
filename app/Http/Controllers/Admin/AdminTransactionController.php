<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function deposits()
    {
        $deposits = Transaction::with('user')
            ->where('type', 'deposit')
            ->latest()
            ->paginate(20);

        return view('admin.deposits.index', compact('deposits'));
    }

    public function withdraws()
    {
        $withdraws = Transaction::with('user')
            ->where('type', 'withdraw')
            ->latest()
            ->paginate(20);

        return view('admin.withdraws.index', compact('withdraws'));
    }

    public function approveDeposit(Transaction $transaction)
    {
        if (
            $transaction->type !== 'deposit' ||
            $transaction->status !== 'pending'
        ) {
            return back()->with('error', 'Invalid transaction.');
        }

        DB::transaction(function () use ($transaction) {

            $wallet = $transaction->user->wallet;

            $wallet->increment('balance', $transaction->amount);

            $transaction->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return back()->with('success', 'Deposit approved successfully.');
    }

    public function rejectDeposit(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit already processed.');
        }

        $deposit->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Deposit rejected.');
    }

    public function approveWithdraw(Transaction $transaction)
    {
        if (
            $transaction->type !== 'withdraw' ||
            $transaction->status !== 'pending'
        ) {
            return back()->with('error', 'Invalid transaction.');
        }

        DB::transaction(function () use ($transaction) {

            $wallet = $transaction->user->wallet;

            if ($wallet->balance < $transaction->amount) {
                throw new \Exception('Insufficient wallet balance.');
            }

            $wallet->decrement('balance', $transaction->amount);

            $transaction->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        });

        return back()->with('success', 'Withdraw approved successfully.');
    }

    public function rejectWithdraw(Transaction $transaction)
    {
        if (
            $transaction->type !== 'withdraw' ||
            $transaction->status !== 'pending'
        ) {
            return back()->with('error', 'Invalid transaction.');
        }

        $transaction->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Withdraw rejected.');
    }
}
