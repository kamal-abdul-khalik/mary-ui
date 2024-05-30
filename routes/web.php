<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Auth::loginUsingId(1);

Route::view('/', 'welcome')->name('welcome');

Volt::route('/login', 'auth.login')->name('login');

// Protected routes here
Route::middleware('auth')->group(function () {
    // Define the logout
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    });

    Volt::route('/dashboard', 'dashboard')->name('home');
    Volt::route('/users', 'users.index')->name('users.index');
    Volt::route('/users/create', 'users.create')->name('users.create');
    Volt::route('/users/{user}/edit', 'users.edit')->name('users.edit');
    // ... more
});
