<?php

use App\Livewire\Feed;
use Illuminate\Support\Facades\Route;

Route::view('/', 'livewire.auth.login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('feed', Feed::class)->name('feed');
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {});

require __DIR__.'/settings.php';
