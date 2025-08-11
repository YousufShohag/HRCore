<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function show($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $attendanceToday = Attendance::where('employee_id', $employeeId)
            ->where('date', now()->toDateString())
            ->first();

        return view('attendance', compact('employee', 'attendanceToday'));
    }

    public function store(Request $request, $employeeId)
    {
        $request->validate([
            'attendance_type' => 'required|in:clock_in,clock_out',
            'photo' => 'required|string',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::firstOrNew([
            'employee_id' => $employeeId,
            'date' => $today,
        ]);

        $photoData = base64_decode($request->photo);
        $filename = 'attendance/' . $employeeId . '_' . $today . '_' . $request->attendance_type . '.png';

        Storage::disk('public')->put($filename, $photoData);

        if ($request->attendance_type === 'clock_in') {
            if ($attendance->clock_in) {
                return back()->with('error', 'Already clocked in today.');
            }
            $attendance->clock_in = now();
            $attendance->clock_in_photo = $filename;
        } else {
            if (!$attendance->clock_in) {
                return back()->with('error', 'You need to clock in first.');
            }
            if ($attendance->clock_out) {
                return back()->with('error', 'Already clocked out today.');
            }
            $attendance->clock_out = now();
            $attendance->clock_out_photo = $filename;

            $diff = $attendance->clock_out->diffInMinutes($attendance->clock_in);
            $attendance->total_hours = round($diff / 60, 2);
        }

        $attendance->save();

        return back()->with('success', ucfirst(str_replace('_', ' ', $request->attendance_type)) . ' recorded successfully!');
    }

    public function report(Request $request)
{
    $query = Attendance::with('employee')->orderBy('date', 'desc');

    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    if ($request->filled('from_date')) {
        $query->where('date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->where('date', '<=', $request->to_date);
    }

    $attendances = $query->paginate(20);

    $employees = Employee::orderBy('name')->get();

    return view('attendance.report', compact('attendances', 'employees'));
}

}

