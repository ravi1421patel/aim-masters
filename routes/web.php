<?php

use App\Http\Controllers\DepositController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $wallet = auth()->user()->wallet;
        return view('dashboard', compact('wallet'));
    })->name('dashboard');

    Route::get(
        '/deposit',
        [DepositController::class, 'create']
    )->name('deposit.create');

    Route::post(
        '/deposit',
        [DepositController::class, 'store']
    )->name('deposit.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
