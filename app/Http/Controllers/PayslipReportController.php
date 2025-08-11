<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payslip;
use App\Models\Employee;
use Carbon\Carbon;
use PDF; // Ensure dompdf is installed and configured

class PayslipReportController extends Controller
{
    /**
     * Generate month-to-month report with optional filters
     */
    public function index(Request $request)
{
    $employees = Employee::all(); // Load all employees

    // Your other logic for filtering/reporting...
    // Example:
    $payslips = Payslip::query();

    if ($request->filled('employee_id')) {
        $payslips->where('employee_id', $request->employee_id);
    }

    // other filters...

    $payslips = $payslips->get();

    return view('payslip_report.index', compact('employees', 'payslips'));
}

    public function downloadRangeReport(Request $request)
    {
        // Validate input
        $request->validate([
            'start_month' => 'required|date_format:Y-m',
            'end_month' => 'required|date_format:Y-m|after_or_equal:start_month',
            'employee_id' => 'nullable|exists:employees,id',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
        ]);

        // Parse start and end
        $start = Carbon::parse($request->start_month)->startOfMonth();
        $end = Carbon::parse($request->end_month)->endOfMonth();

        // Build query
        $query = Payslip::with('employee')
            ->whereBetween('month', [$start->format('Y-m'), $end->format('Y-m')]);

        // Optional filters
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('min_salary')) {
            $query->where('final_salary', '>=', $request->min_salary);
        }

        if ($request->filled('max_salary')) {
            $query->where('final_salary', '<=', $request->max_salary);
        }

        $payslips = $query->orderBy('month')->get();

        $rangeLabel = $start->format('F Y') . ' to ' . $end->format('F Y');

        // Generate PDF
        $pdf = PDF::loadView('reports.payslip-range-report', [
            'payslips' => $payslips,
            'rangeLabel' => $rangeLabel,
        ]);

        return $pdf->download("Payslip_Report_{$start->format('Y_m')}_to_{$end->format('Y_m')}.pdf");
    }

    /**
     * Generate single-month report (optional)
     */
    public function downloadMonthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $payslips = Payslip::with('employee')
            ->where('month', $request->month)
            ->get();

        $monthLabel = Carbon::parse($request->month)->format('F Y');

        $pdf = PDF::loadView('reports.payslip-monthly-report', [
            'payslips' => $payslips,
            'monthLabel' => $monthLabel,
        ]);

        return $pdf->download("Payslip_Report_{$request->month}.pdf");
    }
}
