<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Payslip;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $year = $request->input('year');
    $month = $request->input('month');
    $department = $request->input('department');

    // Base payslip query
    $payslipQuery = Payslip::with('employee');

    // Apply filters
    if ($year) {
        $payslipQuery->whereYear('month', $year);
    }
    if ($month) {
        $payslipQuery->whereMonth('month', $month);
    }
    if ($department) {
        $payslipQuery->whereHas('employee', function ($query) use ($department) {
            $query->where('department', $department);
        });
    }

    // Latest 20 payslips
    $payslips = $payslipQuery->orderBy('created_at', 'desc')->limit(20)->get();

    // Filter dropdown data
    $years = Payslip::selectRaw('YEAR(month) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    $departments = Employee::select('department')
        ->distinct()
        ->whereNotNull('department')
        ->pluck('department');

    // Chart data
    $chartYear = $year ?? date('Y');
    $monthlyData = Payslip::selectRaw('MONTH(month) as month, COUNT(*) as count')
        ->whereYear('month', $chartYear)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');

    $months = [];
    $counts = [];
    for ($m = 1; $m <= 12; $m++) {
        $months[] = Carbon::create()->month($m)->format('F');
        $counts[] = $monthlyData->get($m, 0);
    }

    $totalEmployees = Employee::count();
    $issuedCount = $payslipQuery->count();

    // Expenses for dashboard
    $expenses = Expense::latest()->take(3)->get();

    // Transaction for dashboard
    $transactions = Transaction::latest()->take(3)->get();


        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // return view('dashboard', compact('totalIncome', 'totalExpense', 'netBalance'));


    // ✅ Return all data in one view
    return view('dashboard.index', compact(
        'payslips',
        'years',
        'departments',
        'months',
        'counts',
        'issuedCount',
        'totalEmployees',
        'expenses',
        'transactions',
        'totalIncome',
        'totalExpense',
        'netBalance'

    ));
}


   

// public function index(Request $request)
// {
//     $years = Payslip::selectRaw('YEAR(month) as year')->distinct()->pluck('year')->sortDesc();
//     $departments = Employee::select('department')->distinct()->pluck('department');

//     $payslipsQuery = Payslip::with('employee')->latest();

//     // Optional: Apply filters
//     if ($request->filled('year')) {
//         $payslipsQuery->whereYear('month', $request->year);
//     }

//     if ($request->filled('month')) {
//         $payslipsQuery->whereMonth('month', $request->month);
//     }

//     if ($request->filled('department')) {
//         $payslipsQuery->whereHas('employee', function ($q) use ($request) {
//             $q->where('department', $request->department);
//         });
//     }

//     $payslips = $payslipsQuery->limit(10)->get(); // 👈 latest 10 payslips

//     $totalEmployees = Employee::count();
//     $issuedCount = Payslip::count();

//     // For chart (this stays as-is)
//     $monthlyData = Payslip::selectRaw('MONTH(month) as month, COUNT(*) as count')
//         ->groupBy('month')
//         ->orderBy('month')
//         ->get();

//     $months = $monthlyData->pluck('month')->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F'));
//     $counts = $monthlyData->pluck('count');

//     return view('dashboard.index', compact('payslips', 'years', 'departments', 'months', 'counts', 'totalEmployees', 'issuedCount'));
// }

}
