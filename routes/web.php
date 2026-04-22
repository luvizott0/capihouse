<?php

use App\Livewire\Acervo;
use App\Livewire\Catalog;
use App\Livewire\Events;
use App\Livewire\Feed;
use App\Livewire\Profile;
use Illuminate\Support\Facades\Route;

Route::view('/', 'livewire.auth.login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('feed', Feed::class)->name('feed');
    Route::get('perfil', Profile::class)->name('perfil');
    Route::get('eventos', Events::class)->name('eventos');
    Route::get('acervo', Catalog::class)->name('acervo');
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {});

require __DIR__.'/settings.php';
