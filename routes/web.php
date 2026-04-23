<?php

use App\Livewire\Auth\Login;
use App\Livewire\Catalog;
use App\Livewire\Events;
use App\Livewire\Feed;
use App\Livewire\Profile;
use Illuminate\Support\Facades\Route;

Route::view('/', Login::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', Feed::class)->name('feed');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/events', Events::class)->name('events');
    Route::get('/catalog', Catalog::class)->name('catalog');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {});

require __DIR__.'/settings.php';
