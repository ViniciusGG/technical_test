<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::apiResource('/accounts', BankAccountController::class)->names('accounts');

Route::post('/transactions', [TransactionController::class, 'transactions'])->name('transactions');
