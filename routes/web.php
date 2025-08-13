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













Route::get('/', function () {
    return view('welcome');
});

Route::resource('employees', EmployeeController::class);

Route::get('payslips', [PayslipController::class, 'index'])->name('payslips.index');

// Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
// Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
// Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');


// Route::get('payslips/create', [PayslipController::class, 'create'])->name('payslips.create');
// Route::post('payslips', [PayslipController::class, 'store'])->name('payslips.store');
// Route::get('/report/payslip-range', [PayslipReportController::class, 'downloadRangeReport'])->name('payslip.report.range');


// Route::resource('payslips', PayslipController::class);


// Route::resource('payslips', PayslipController::class);
// This will include index, create, store, show, edit, update, destroy methods automatically

Route::get('/payslips/create', [PayslipController::class, 'create'])->name('payslips.create');
Route::post('/payslips', [PayslipController::class, 'store'])->name('payslips.store');
Route::get('/payslips', [PayslipController::class, 'index'])->name('payslips.index');
Route::delete('/payslips/{payslip}', [PayslipController::class, 'destroy'])->name('payslips.destroy');

// ✅ ADD THESE TWO FOR EDIT/UPDATE
Route::get('/payslips/{payslip}/edit', [PayslipController::class, 'edit'])->name('payslips.edit');
Route::put('/payslips/{payslip}', [PayslipController::class, 'update'])->name('payslips.update');


// SHOW HERE EACH PAYSLIPS
Route::get('/payslips/{id}', [PayslipController::class, 'show'])->name('payslips.show');

Route::resource('monthly_work_days', MonthlyWorkDayController::class);




// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


// EXPENCES
// Route::resource('expenses', ExpenseController::class);
Route::resource('expenses', ExpenseController::class)->except(['show']);


Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
Route::post('/expenses/report', [ExpenseController::class, 'reportData'])->name('expenses.report.data');


// Transection system
// Route::resource('transactions', TransactionController::class);

// // Report
// Route::get('transactions/report', [TransactionController::class, 'report'])->name('transactions.report');
Route::get('/transactions', [TransactionController::class, 'index']);
Route::resource('transactions', TransactionController::class)->except(['show', 'edit', 'update']);
//Route::resource('transactions', TransactionController::class)->except(['show', 'edit', 'update']);
Route::get('/transactions/report', [TransactionController::class, 'report'])->name('transactions.report');



Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
Route::get('leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
Route::put('leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

// Approve / Reject
Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

// Attendance
Route::get('/employees/{id}/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
Route::post('/employees/{id}/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');


// IDCARD
Route::get('/employee/{id}/id-card', [EmployeeController::class, 'generateIdCard'])->name('employee.id-card');

// Notice

Route::resource('notices', NoticeController::class);

// Route::resource('notices', NoticeController::class);
Route::get('notices/{id}/print', [NoticeController::class, 'print'])->name('notices.print');





