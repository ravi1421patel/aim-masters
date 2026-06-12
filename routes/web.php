<?php

use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/withdraw', [WithdrawController::class, 'create'])
    ->name('withdraw.create');
Route::post('/withdraw', [WithdrawController::class, 'store'])
    ->name('withdraw.store');
/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/games', [GameController::class, 'index'])
        ->name('games.index');

    Route::post('/games/{game}/join', [GameController::class, 'join'])
        ->name('games.join');

    Route::get('/dashboard', function () {

        $user = auth()->user();

        $wallet = $user->wallet ?? (object) ['balance' => 0];

        $pendingDeposits = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->count();

        $totalDeposited = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        $totalWithdrawn = Transaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->where('status', 'approved')
            ->sum('amount');

        $totalWinnings = Transaction::where('user_id', $user->id)
            ->where('type', 'game_win')
            ->where('status', 'approved')
            ->sum('amount');

        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'wallet',
            'pendingDeposits',
            'totalDeposited',
            'totalWithdrawn',
            'totalWinnings',
            'transactions'
        ));
    })->name('dashboard');

    Route::get('/deposit', [DepositController::class, 'create'])
        ->name('deposit.create');

    Route::post('/deposit', [DepositController::class, 'store'])
        ->name('deposit.store');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::get('/wallet', [WalletController::class, 'index'])
        ->name('wallet.index');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/deposits', [AdminTransactionController::class, 'deposits'])
        ->name('deposits');

    Route::post('/deposits/{deposit}/approve', [AdminTransactionController::class, 'approveDeposit'])
        ->name('deposits.approve');

    Route::post('/deposits/{deposit}/reject', [AdminTransactionController::class, 'rejectDeposit'])
        ->name('deposits.reject');

    Route::get('/withdraws', [AdminTransactionController::class, 'withdraws'])
        ->name('withdraws');

    Route::post('/withdraws/{deposit}/approve', [AdminTransactionController::class, 'approveWithdraw'])
        ->name('withdraws.approve');

    Route::post('/withdraws/{deposit}/reject', [AdminTransactionController::class, 'rejectWithdraw'])
        ->name('withdraws.reject');

    Route::get('/withdraws', [AdminWithdrawController::class, 'index'])
        ->name('admin.withdraws');

    Route::post('/withdraws/{withdraw}/approve', [AdminWithdrawController::class, 'approve'])
        ->name('admin.withdraws.approve');

    Route::post('/withdraws/{withdraw}/reject', [AdminWithdrawController::class, 'reject'])
        ->name('admin.withdraws.reject');

});

/*
|--------------------------------------------------------------------------
| Custom 404
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404);
});

require __DIR__.'/auth.php';
