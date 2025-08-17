<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayslipController extends Controller
{
    // public function index()
    // {
    //     $payslips = Payslip::with('employee')->get();
    //     return view('payslips.index', compact('payslips'));
    // }
public function index(Request $request)
{
    $query = Payslip::with('employee');

    if ($request->filled('employee_name')) {
        $query->whereHas('employee', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->employee_name . '%');
        });
    }

    if ($request->filled('month')) {
        $query->whereMonth('month', $request->month);
    }

    if ($request->filled('min_salary')) {
        $query->where('net_salary', '>=', $request->min_salary);
    }

    if ($request->filled('max_salary')) {
        $query->where('net_salary', '<=', $request->max_salary);
    }

    $payslips = $query->orderBy('month', 'desc')->get();

    return view('payslips.index', compact('payslips'));
}

    // public function create()
    // {
    //     $employees = Employee::all();
    //     return view('payslips.create', compact('employees'));
    // }

    public function create()
{
    $employees = Employee::all();  // Fetch all employees
    return view('payslips.create', compact('employees'));  // Pass employees to view
}


public function store(Request $request)
{
    $request->validate([
        'employee_id'      => 'required|exists:employees,id',
        'month'            => 'required|date',
        'total_hours'      => 'required|integer|min:1',
        'average_hours'    => 'required|numeric|min:1',
        'absent_days'      => 'nullable|integer|min:0',
        'sick_leave_days'  => 'nullable|integer|min:0',
        'total_worked_days'=> 'nullable|integer|min:0',
        'salary'           => 'required|numeric|min:0',
        'allowance'        => 'nullable|numeric|min:0',
        'deduction'        => 'nullable|numeric|min:0',
        'notes'            => 'nullable|string',
        'document'         => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('document')) {
        $path = $request->file('document')->store('documents', 'public');
    }

    $net_salary = $request->salary + ($request->allowance ?? 0) - ($request->deduction ?? 0);

    Payslip::create([
        'employee_id'       => $request->employee_id,
        'month'             => $request->month,
        'total_hours'       => $request->total_hours,
        'average_hours'     => $request->average_hours,
        'absent_days'       => $request->absent_days ?? 0,
        'sick_leave_days'   => $request->sick_leave_days ?? 0,
        'total_worked_days' => $request->total_worked_days ?? null,
        'salary'            => $request->salary,
        'allowance'         => $request->allowance ?? 0,
        'deduction'         => $request->deduction ?? 0,
        'net_salary'        => $net_salary,
        'notes'             => $request->notes,
        'document'          => $path,
    ]);

    return redirect()->route('payslips.index')->with('success', 'Payslip created successfully!');
}

public function show($id)
{
    $payslip = Payslip::with(['employee', 'monthData'])->findOrFail($id);

    // $payslip = Payslip::with('employee')->findOrFail($id);
    return view('payslips.show', compact('payslip'));
}

public function destroy($id)
{
    $payslip = Payslip::findOrFail($id);
    $payslip->delete();

    return redirect()->route('payslips.index')->with('success', 'Payslip deleted successfully.');
}

public function edit(Payslip $payslip)
{
    $employees = Employee::all();
    return response()->json([
        'payslip' => $payslip,
        'employee_id' => $payslip->employee_id,
        'allowance' => $payslip->allowance,
        'deduction' => $payslip->deduction,
        'salary' => $payslip->salary,
        'month' => $payslip->month,
        'employee_name' => $payslip->employee->name,
    ]);
}

public function update(Request $request, Payslip $payslip)
{
    $request->validate([
        'allowance' => 'nullable|numeric|min:0',
        'deduction' => 'nullable|numeric|min:0',
        'salary'    => 'required|numeric|min:0',
    ]);

    $net_salary = $request->salary + ($request->allowance ?? 0) - ($request->deduction ?? 0);

    $payslip->update([
        'salary'     => $request->salary,
        'allowance'  => $request->allowance ?? 0,
        'deduction'  => $request->deduction ?? 0,
        'net_salary' => $net_salary,
    ]);

    return redirect()->route('payslips.index')->with('success', 'Payslip updated successfully!');
}



}

