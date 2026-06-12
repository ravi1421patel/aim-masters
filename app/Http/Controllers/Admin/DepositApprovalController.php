<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;

class DepositApprovalController extends Controller
{
    public function index()
    {
        $deposits = Deposit::latest()->paginate(20);

        return view(
            'admin.deposits.index',
            compact('deposits')
        );
    }

    public function approve(
        Deposit $deposit,
        WalletService $walletService
    ) {

        if ($deposit->status !== 'pending') {
            return back()
                ->with(
                    'error',
                    'Deposit already processed.'
                );
        }

        DB::transaction(function () use (
            $deposit,
            $walletService
        ) {

            $walletService->credit(
                $deposit->user,
                $deposit->amount,
                'deposit',
                'Deposit Approved'
            );

            $deposit->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);
        });

        return back()
            ->with(
                'success',
                'Deposit approved successfully.'
            );
    }

    public function reject(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {

            return back()
                ->with(
                    'error',
                    'Deposit already processed.'
                );
        }

        $deposit->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()
            ->with(
                'success',
                'Deposit rejected.'
            );
    }
}
