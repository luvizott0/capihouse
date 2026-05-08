<?php

use App\Livewire\Admin\Users\Index as AdminUsers;
use App\Livewire\App\Catalog;
use App\Livewire\App\Events;
use App\Livewire\App\Feed;
use App\Livewire\App\Profile;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('home');
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/feed', Feed\Index::class)->name('feed');
    Route::get('/profile', Profile\Index::class)->name('profile');
    Route::get('/profile/{user:username}', Profile\Index::class)->name('profile.show');
    Route::get('/events', Events\Index::class)->name('events');
    Route::get('/catalog', Catalog\Index::class)->name('catalog');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::domain('admin.'.parse_url(config('app.url'), PHP_URL_HOST))
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/users', AdminUsers::class)->name('users');
    });

Route::middleware('auth')->group(function () {});

require __DIR__.'/settings.php';
