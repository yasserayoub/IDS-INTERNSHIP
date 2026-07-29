<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItManagerController;
use App\Http\Controllers\ItSupportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminItManager;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTicketController;
use App\Http\Controllers\ForgotPasswordController;

use App\Mail\TestMail;

// =====================================================
// Authentication
// =====================================================

Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =====================================================
// Administrator
// =====================================================

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



// IT Manager and adminstartor


Route::get('/manager/tickets', [AdminItManager::class, 'AllTickets'])
    ->middleware('role:Administrator,IT Manager')
    ->name('allticketspage');

    Route::get('/manager/tickets/{id}', [AdminItManager::class, 'ShowTicket'])
    ->middleware('role:Administrator,IT Manager')
    ->name('manager.ticket.show');

    Route::post('/manager/tickets/{id}/assign', [AdminItManager::class, 'AssignTicket'])
    ->middleware('role:Administrator,IT Manager')
    ->name('manager.ticket.assign');

    //load the activity logs
   Route::get('/activity-logs', [AdminItManager::class, 'ActivityLogs'])
    ->name('activity.logs');


 //it agent
Route::get('/support/tickets', [ItSupportController::class, 'MyTickets'])
    ->middleware('role:IT Support')
    ->name('support.tickets');

Route::get('/support/tickets/{id}', [ItSupportController::class, 'ViewTicketDetails'])
    ->middleware('role:IT Support')
    ->name('support.ticket.show');

    Route::post('/support/tickets/{id}/update', [ItSupportController::class, 'UpdateTicket'])
    ->middleware('role:IT Support')
    ->name('support.ticket.status');

    Route::post('/support/tickets/{id}/comments', [ItSupportController::class, 'storeComment'])
    ->name('support.ticket.comment');



// =====================================================
// Shared Routes
// =====================================================

Route::view('/layout', 'layouts.app');

Route::view('/profile', 'profile.index')
    ->middleware('role:Administrator,IT Manager,IT Support,Employee');

Route::view('/dashboard', 'dashboard.index')
    ->middleware('role:Administrator,IT Manager,IT Support');

Route::view('/Assign/tickets', 'tickets.show')
    ->middleware('role:Administrator,IT Manager,IT Support');

Route::view('/notifications', 'notifications.index')
    ->middleware('role:Administrator,IT Manager,IT Support,Employee');

Route::view('/reports', 'reports.index')
    ->middleware('role:Administrator,IT Manager');


// =====================================================
// Employee
// =====================================================

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

Route::delete('/employee/attachments/{id}', [EmployeeTicketController::class, 'deleteAttachment'])
    ->middleware('role:Employee')
    ->name('employee.tickets.deleteAttachment');

    Route::post('/employee/tickets/{id}/comments', //post a comment
    [EmployeeController::class, 'storeComment'])
    ->name('employee.ticket.comment');


// =====================================================
// Test Email
// =====================================================

Route::get('/test-email', function () {

    Mail::to('ayoubyaser89@gmail.com')->send(new TestMail());

    return 'Email sent successfully!';

});


// =====================================================
// Forgot Password
// =====================================================

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('passwordForgetpage');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('SendResetLink');

Route::get('/passwordresetform/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('passwordResetForm');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('passwordUpdate');

    //comments
    Route::post('/manager/tickets/{id}/comments', [ItManagerController::class, 'storeComment'])
    ->name('manager.ticket.comment');
