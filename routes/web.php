<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/', 'auth.login');
Route::view('layout', 'layouts.app');
Route::view('/dashboard', 'dashboard.index');
Route::view('/tickets', 'tickets.index');
Route::view('/tickets/create', 'tickets.create');
Route::view('/tickets/1001', 'tickets.show');
Route::view('/notifications', 'notifications.index');
Route::view('/reports', 'reports.index');
Route::view('/admin/dashboard', 'admin.dashboard');
Route::view('/admin/users', 'admin.users.index');
Route::view('/profile', 'profile.index');
