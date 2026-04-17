<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'livewire.auth.login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
