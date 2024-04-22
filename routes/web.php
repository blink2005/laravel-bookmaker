<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AddBalanceController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\EventCreateController;
use App\Http\Controllers\EventSelectController;
use App\Http\Controllers\EventEditController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WithdrawalCreateController;
use App\Http\Controllers\PariListController;
use App\Http\Controllers\WithdrawalCheckController;
use App\Http\Middleware\CheckPayments;
use App\Http\Middleware\Admin;

Route::middleware(['guest'])->group(function () {

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware([CheckPayments::class])->group(function () {
    
    Route::get('/', [IndexController::class, 'index'])->name('welcome');
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/category/{name}', [CategoryController::class, 'openCategory'])->name('category_name');
    Route::get('/event/{id}', [EventSelectController::class, 'show'])->name('event');

    Route::middleware(['auth'])->group(function () {

        Route::get('/logout', [LogoutController::class, 'destroy'])->name('logout');
        Route::get('/pari', [PariListController::class, 'index'])->name('pari');
        Route::get('/withdrawal', [WithdrawalCreateController::class, 'create'])->name('withdrawal');
        Route::get('/addbalance', [AddBalanceController::class, 'create'])->name('addbalance');
        Route::post('/addbalance', [AddBalanceController::class, 'store']);
        Route::post('/event/{id}', [EventSelectController::class, 'store']);
        Route::post('/withdrawal', [WithdrawalCreateController::class, 'store']);

        Route::middleware([Admin::class])->group(function () {
    
            Route::get('/admin', [AdminPanelController::class, 'index'])->name('admin_panel');
            Route::get('/createevent', [EventCreateController::class, 'create'])->name('create_event');
            Route::get('/editevent', [EventEditController::class, 'index'])->name('edit_event');
            Route::post('/editevent/save', [EventEditController::class, 'save']);
            Route::post('/editevent/end', [EventEditController::class, 'end']);
            Route::get('/editevent/{id}', [EventEditController::class, 'create'])->name('edit_event_id');
            Route::get('/payments', [WithdrawalCheckController::class, 'create'])->name('payments');
            Route::post('/createevent', [EventCreateController::class, 'store']);
            Route::post('/payments/accept', [WithdrawalCheckController::class, 'accept']);
            Route::post('/payments/cancel', [WithdrawalCheckController::class, 'cancel']);
        });
    });
});