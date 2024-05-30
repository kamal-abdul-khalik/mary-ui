<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Auth::loginUsingId(3);

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
});

Route::middleware('role:superadmin')->group(function () {
    Volt::route('/roles', 'permissions.role.index')->name('roles.index');
    Volt::route('/roles/create', 'permissions.role.create')->name('roles.create');
    Volt::route('/roles/{role}/edit', 'permissions.role.edit')->name('roles.edit');

    Volt::route('/permissions', 'permissions.permission.index')->name('permissions.index');
    Volt::route('/permissions/create', 'permissions.permission.create')->name('permissions.create');
    Volt::route('/permissions/{role}/edit', 'permissions.permission.edit')->name('permissions.edit');
});
