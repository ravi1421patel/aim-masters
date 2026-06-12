<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {

        $user = auth()->user();

        $wallet = $user->wallet ?? (object)['balance' => 0];

        return view('dashboard', compact('wallet'));
    })->middleware('auth')->name('dashboard');

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
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])
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
});

/*
|--------------------------------------------------------------------------
| Custom 404
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404);
});

require __DIR__ . '/auth.php';
