<?php

use App\Livewire\App\Catalog;
use App\Livewire\App\Events;
use App\Livewire\App\Feed;
use App\Livewire\App\Profile;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/feed', Feed\Index::class)->name('feed');
    Route::get('/profile', Profile\Index::class)->name('profile');
    Route::get('/profile/{user:username}', Profile\Index::class)->name('profile.show');
    Route::get('/events', Events\Index::class)->name('events');
    Route::get('/catalog', Catalog\Index::class)->name('catalog');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {});

require __DIR__.'/settings.php';
