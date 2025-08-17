<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\MonthlyWorkDayController;
use App\Http\Controllers\PayslipReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Auth routes (register, login, forgot password, etc.)
require __DIR__.'/auth.php';

// Dashboard route (authenticated)
Route::middleware(['auth', 'verified'])->group(function () {

    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employees
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::get('/employees/{id}/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/employees/{id}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/employee/{id}/id-card', [EmployeeController::class, 'generateIdCard'])->name('employee.id-card');
    

    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');



    // Payslips
    Route::get('/payslips', [PayslipController::class, 'index'])->name('payslips.index');
    Route::get('/payslips/create', [PayslipController::class, 'create'])->name('payslips.create');
    Route::post('/payslips', [PayslipController::class, 'store'])->name('payslips.store');
    Route::get('/payslips/{payslip}/edit', [PayslipController::class, 'edit'])->name('payslips.edit');
    Route::put('/payslips/{payslip}', [PayslipController::class, 'update'])->name('payslips.update');
    Route::delete('/payslips/{payslip}', [PayslipController::class, 'destroy'])->name('payslips.destroy');
    Route::get('/payslips/{id}', [PayslipController::class, 'show'])->name('payslips.show');



// Route::get('/payslips', [PayslipController::class, 'index'])->name('payslips.index');
// Route::get('/payslips/export-pdf', [PayslipController::class, 'exportPdf'])->name('payslips.exportPdf');
// existing create/show/update/destroy routes stay as you have them


    // monthly_work_days
    Route::resource('monthly_work_days', MonthlyWorkDayController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
    Route::post('/expenses/report', [ExpenseController::class, 'reportData'])->name('expenses.report.data');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::resource('transactions', TransactionController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/transactions/report', [TransactionController::class, 'report'])->name('transactions.report');

    // Leaves
    Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

    // Notices
    Route::resource('notices', NoticeController::class);
    Route::get('notices/{id}/print', [NoticeController::class, 'print'])->name('notices.print');

});
