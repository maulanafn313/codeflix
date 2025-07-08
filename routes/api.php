<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::post('/payment/callback', [TransactionController::class, 'callback'])->name('payment.callback');
