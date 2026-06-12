<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    public function credit(
        User $user,
        float $amount,
        string $type,
        ?string $remarks = null
    ): Transaction {

        return DB::transaction(function () use (
            $user,
            $amount,
            $type,
            $remarks
        ) {

            $wallet = $user->wallet()->lockForUpdate()->first();

            $balanceBefore = $wallet->balance;

            $wallet->balance += $amount;

            $wallet->save();

            return Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'remarks' => $remarks,
                'status' => 'completed'
            ]);
        });
    }

    public function debit(
        User $user,
        float $amount,
        string $type,
        ?string $remarks = null
    ): Transaction {

        return DB::transaction(function () use (
            $user,
            $amount,
            $type,
            $remarks
        ) {

            $wallet = $user->wallet()->lockForUpdate()->first();

            if ($wallet->balance < $amount) {
                throw new Exception('Insufficient balance');
            }

            $balanceBefore = $wallet->balance;
            $wallet->balance -= $amount;

            $wallet->save();

            return Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $wallet->balance,
                'remarks' => $remarks,
                'status' => 'completed'
            ]);
        });
    }
}
