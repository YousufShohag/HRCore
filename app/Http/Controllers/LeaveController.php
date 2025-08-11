<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // List with simple filters & pagination
    public function index(Request $request)
    {
        $query = Leave::with('employee');

        if ($request->filled('employee')) {
            $query->whereHas('employee', fn($q) => $q->where('name', 'like', '%' . $request->employee . '%'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        $leaves = $query->orderBy('start_date', 'desc')->paginate(12)->withQueryString();

        // for form
        $employees = Employee::orderBy('name')->get();

        return view('leaves.index', compact('leaves', 'employees'));
    }

    // Store new leave request
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        // calculate approved days taken this year
        $year = $start->year;
        $taken = Leave::where('employee_id', $request->employee_id)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('days');

        $remaining = 12 - $taken;
        if ($days > $remaining) {
            return back()->withInput()->withErrors(['start_date' => "Requested $days day(s) exceeds remaining balance ($remaining) for year $year."]);
        }

        Leave::create([
            'employee_id' => $request->employee_id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted (pending approval).');
    }

    // Show edit form (optional)
    public function edit(Leave $leave)
    {
        $employees = Employee::orderBy('name')->get();
        return view('leaves.edit', compact('leave', 'employees'));
    }

    // Update leave (only pending allowed to edit)
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($leave->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending requests can be edited.']);
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        // check remaining excluding this leave's previous days
        $year = $start->year;
        $taken = Leave::where('employee_id', $leave->employee_id)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('days');

        $remaining = 12 - $taken;
        if ($days > $remaining) {
            return back()->withInput()->withErrors(['start_date' => "Requested $days day(s) exceeds remaining balance ($remaining) for year $year."]);
        }

        $leave->update([
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days' => $days,
            'reason' => $request->reason,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave updated.');
    }

    // Approve a leave
    public function approve(Leave $leave)
{
    if ($leave->status !== 'pending') {
        return back()->withErrors(['error' => 'Leave already processed.']);
    }

    $year = Carbon::parse($leave->start_date)->year;

    $taken = Leave::where('employee_id', $leave->employee_id)
        ->where('status', 'approved')
        ->whereYear('start_date', $year)
        ->sum('days');

    if ($taken + $leave->days > 12) {
        return back()->withErrors(['error' => 'Approving would exceed annual leave balance.']);
    }

    // Update leave status
    $leave->update([
        'status' => 'approved',
        'approved_by' => Auth::id() ?? null,
    ]);

    // Deduct leave days from employee's leave_balance
    $employee = $leave->employee;
    // dd($employee, $employee->leave_balance, $leave->days);
    $employee->leave_balance = max(0, $employee->leave_balance - $leave->days);
    // dd($employee, $employee->leave_balance - $leave->days);
    $employee->save();


    
    return redirect()->route('leaves.index')->with('success', 'Leave approved and balance updated.');
}


    // Reject a leave
    public function reject(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->withErrors(['error' => 'Leave already processed.']);
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id() ?? null,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave rejected.');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }
}
