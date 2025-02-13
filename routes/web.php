<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function(){
    Route::get('/market', [MarketController::class, 'index'])->name('market');
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/transaction/index', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('/transaction/swap', [TransactionController::class, 'storeSwap'])->name('transaction.storeSwap');
    Route::post('/transaction/send', [TransactionController::class, 'storeSend'])->name('transaction.storeSend');
    Route::post('/transaction/deposit', [TransactionController::class, 'storeDeposit'])->name('transaction.storeDeposit');

    Route::delete('/transaction/delete-swap/{swap}', [TransactionController::class, 'deleteSwap'])->name('transaction.deleteSwap');
    Route::delete('/transaction/delete-send/{send}', [TransactionController::class, 'deleteSend'])->name('transaction.deleteSend');
    Route::delete('/transaction/delete-deposit/{deposit}', [TransactionController::class, 'deleteDeposit'])->name('transaction.deleteDeposit');

    Route::put('/transaction/edit-swap/{swap}', [TransactionController::class, 'editSwap'])->name('transaction.editSwap');
    Route::put('/transaction/edit-send/{send}', [TransactionController::class, 'editSend'])->name('transaction.editSend');
    Route::put('/transaction/edit-deposit/{deposit}', [TransactionController::class, 'editDeposit'])->name('transaction.editDeposit');

});

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

