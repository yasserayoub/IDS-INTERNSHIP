<?php

use App\Http\Controllers\AdminItsupport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTicketController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Http\Controllers\ForgotPasswordController;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\TicketController;


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

    //admin and it support routes
Route::get('/All/Tickets', [AdminItsupport::class, 'AllTickets'])
    ->middleware('role:Administrator,IT Support')
    ->name('allticketspage');



Route::view('/layout', 'layouts.app');

Route::view('/profile', 'profile.index')->middleware('role:Administrator,IT Support,Employee');


Route::view('/dashboard', 'dashboard.index') ->middleware('role:Administrator,IT Support');






Route::view('/tickets/1001', 'tickets.show')
    ->middleware('role:Administrator,IT Support');

Route::view('/notifications', 'notifications.index')
    ->middleware('role:Administrator,IT Support,Employee');

Route::view('/reports', 'reports.index')
    ->middleware('role:Administrator,IT Support');

//ticker creation employee,it support,admin







//employee routes
Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])
    ->middleware('role:Employee')
    ->name('employee.dashboard');


Route::get('/employee/tickets/{id}', [EmployeeTicketController::class, 'show'])
    ->middleware('role:Employee')
    ->name('employee.tickets.show');

Route::get('/tickets/create', [EmployeeTicketController::class, 'create'])
    ->middleware('role:Administrator,Employee')
    ->name('CreateTicket');

    Route::post('/tickets', [EmployeeTicketController::class, 'store'])
    ->middleware('role:Administrator,Employee')
    ->name('tickets.store');

Route::get('/employee/tickets/{id}/edit', [EmployeeTicketController::class, 'edit'])
    ->middleware('role:Administrator,Employee')
    ->name('employee.tickets.edit');

Route::put('/employee/tickets/{id}', [EmployeeTicketController::class, 'update'])
    ->middleware('role:Administrator,Employee')
    ->name('employee.tickets.update');

Route::delete('/employee/tickets/{id}', [EmployeeTicketController::class, 'destroy'])
   ->middleware('role:Administrator,Employee')
    ->name('employee.tickets.destroy');
    Route::get('/employee/attachments/{id}', [EmployeeTicketController::class, 'download'])
    ->middleware('role:Administrator,Employee')
    ->name('employee.tickets.downloadAttachment');

    Route::delete('/employee/attachments/{id}',
    [EmployeeTicketController::class, 'deleteAttachment'])
    ->middleware('role:Employee')
    ->name('employee.tickets.deleteAttachment');



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

