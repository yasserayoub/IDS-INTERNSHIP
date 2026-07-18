<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTicketController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Http\Controllers\ForgotPasswordController;
use App\Mail\ResetPasswordMail;

// =========================
// Authentication
// =========================

Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//admin routes
Route::get('/admin/dashboard', [AdminUserController::class, 'dashboard'])
    ->middleware('role:Administrator')
    ->name('admin.dashboard');

Route::get('/admin/users', [AdminUserController::class, 'usermanagmentpage'])
    ->middleware('role:Administrator')
    ->name('UserManagementpage');

Route::get('/admin/users/create', [AdminUserController::class, 'create'])
    ->middleware('role:Administrator')
    ->name('adminCreateUser');

Route::post('/admin/users', [AdminUserController::class, 'store'])
    ->middleware('role:Administrator')
    ->name('StoreUser');



Route::view('/layout', 'layouts.app');

Route::view('/profile', 'profile.index')
    ->middleware('role:Administrator,IT Support,Employee');

Route::view('/dashboard', 'dashboard.index')
    ->middleware('role:Administrator,IT Support');

Route::view('/tickets', 'tickets.index')
    ->middleware('role:Administrator,IT Support');

Route::view('/tickets/create', 'tickets.create')
    ->middleware('role:Administrator,IT Support,Employee');

Route::view('/tickets/1001', 'tickets.show')
    ->middleware('role:Administrator,IT Support');

Route::view('/notifications', 'notifications.index')
    ->middleware('role:Administrator,IT Support,Employee');

Route::view('/reports', 'reports.index')
    ->middleware('role:Administrator,IT Support');

//employee routes
Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])
    ->middleware('role:Employee')
    ->name('employee.dashboard');

Route::get('/employee/tickets/{id}', [EmployeeTicketController::class, 'show'])
    ->middleware('role:Employee')
    ->name('employee.tickets.show');

Route::get('/employee/tickets/{id}/edit', [EmployeeTicketController::class, 'edit'])
    ->middleware('role:Employee')
    ->name('employee.tickets.edit');

Route::put('/employee/tickets/{id}', [EmployeeTicketController::class, 'update'])
    ->middleware('role:Employee')
    ->name('employee.tickets.update');

Route::delete('/employee/tickets/{id}', [EmployeeTicketController::class, 'destroy'])
    ->middleware('role:Employee')
    ->name('employee.tickets.destroy');


    Route::get('/test-email', function () {

    Mail::to('ayoubyaser89@gmail.com')->send(new TestMail());

    return 'Email sent successfully!';

});
//forogt password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('passwordForgetpage');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('SendResetLink');

    // Show the reset password form
Route::get('/passwordresetform/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('passwordResetForm');

// Save the new password
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('passwordUpdate');

