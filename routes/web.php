<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/market', [MarketController::class, 'index'])->name('market');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');

Route::get('/transaction/index', [TransactionController::class, 'index'])->name('transaction.index');

Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
Route::post('/transaction/swap', [TransactionController::class, 'storeSwap'])->name('transaction.storeSwap');
Route::post('/transaction/send', [TransactionController::class, 'storeSend'])->name('transaction.storeSend');
Route::post('/transaction/deposit', [TransactionController::class, 'storeDeposit'])->name('transaction.storeDeposit');

Route::get('/set-currency/{currency}', function ($currency) {
    session(['currency' => $currency]);
    return redirect()->back();
})->name('set.currency');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

