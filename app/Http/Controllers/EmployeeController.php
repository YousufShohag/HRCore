<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

use PDF; // alias from barryvdh/laravel-dompdf


class EmployeeController extends Controller
{
    // public function index()
    // {
    //     // $employees = Employee::all();
    //     $employees = Employee::orderBy('name', 'asc')->get();
    //     return view('employees.index', compact('employees'));
    // }
// public function index(Request $request)
// {
//     $query = Employee::query();

//     if ($request->filled('name')) {
//         $query->where('name', 'like', '%' . $request->name . '%');
//     }

//     if ($request->filled('designation')) {
//         $query->where('designation', 'like', '%' . $request->designation . '%');
//     }

//     if ($request->filled('department')) {
//         $query->where('department', 'like', '%' . $request->department . '%');
//     }

//     if ($request->filled('salary')) {
//         $query->where('basic_salary', $request->salary);
//     }

//     $employees = $query->orderBy('name')->get();

//     return view('employees.index', compact('employees'));
// }

public function index(Request $request)
{
    $query = Employee::query();

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('designation')) {
        $query->where('designation', 'like', '%' . $request->designation . '%');
    }

    if ($request->filled('department')) {
        $query->where('department', 'like', '%' . $request->department . '%');
    }

    if ($request->filled('salary')) {
        $query->where('basic_salary', $request->salary);
    }

    if ($request->filled('status')) {
        $today = now()->toDateString();

        if ($request->status == 'active') {
            $query->where(function($q) use ($today) {
                $q->whereNull('resign_date')
                  ->orWhere('resign_date', '>', $today);
            });
        } elseif ($request->status == 'inactive') {
            $query->where('resign_date', '<=', $today);
        }
    }

    $employees = $query->orderBy('name')->get();

  //$employees = $query->orderBy('name')->get();

    $today = Carbon::today()->toDateString();

    // load today's attendance for each employee
    $employees->load(['attendances' => function ($q) use ($today) {
        $q->where('date', $today);
    }]);

    // add helper attribute to each employee for easy access in blade
    foreach ($employees as $employee) {
        $employee->attendanceToday = $employee->attendances->first();
    }
    


    $employees = Employee::with(['attendanceToday' => function($q) use ($today) {
        $q->where('date', $today);
    }])
    ->paginate(20);

    
    return view('employees.index', compact('employees'));
}


    // public function create()
    // {
    //     return view('employees.create');
    // }

    // public function store(Request $request)
    // {
    //     Employee::create($request->all());
    //     return redirect()->route('employees.index');
    // }

    public function create()
{
    return view('employees.create');
}


// public function store(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'designation' => 'required|string|max:255',
//         'department' => 'nullable|string|max:255',
//         'basic_salary' => 'required|numeric|min:0',
//     ]);

//     Employee::create($request->all());

//     return redirect()->route('employees.create')->with('success', 'Employee added successfully!');
// }
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'basic_salary' => 'required|numeric|min:0',
        'dob' => 'nullable|date',
        'bank_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'document' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
        'join_date' => 'nullable|date',
        'resign_date' => 'nullable|date',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('employee_images', 'public');
    }

    $documentPath = null;
    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('employee_documents', 'public');
    }

    Employee::create([
        'name' => $request->name,
        'designation' => $request->designation,
        'department' => $request->department,
        'basic_salary' => $request->basic_salary,
        'dob' => $request->dob,
        'bank_name' => $request->bank_name,
        'bank_account_number' => $request->bank_account_number,
        'image' => $imagePath,
        'document' => $documentPath,
        'join_date' => $request->join_date,
        'resign_date' => $request->resign_date,
    ]);

    return redirect()->route('employees.create')->with('success', 'Employee added successfully!');
}


public function destroy($id)
{
    $payslip = Employee::findOrFail($id);
    $payslip->delete();

    return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');

}



public function edit($id)
{
    $employee = Employee::findOrFail($id);
    return response()->json($employee);
}

// public function update(Request $request, $id)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'designation' => 'required|string|max:255',
//         'department' => 'nullable|string|max:255',
//         'basic_salary' => 'required|numeric|min:0',
//         'dob' => 'nullable|date',
//         'join_date' => 'nullable|date',
//         'resign_date' => 'nullable|date|after_or_equal:join_date',
//         'bank_name' => 'nullable|string|max:255',
//         'bank_account_number' => 'nullable|string|max:255',
//     ]);

//     $employee = Employee::findOrFail($id);
//     $employee->update($request->only([
//         'name', 'designation', 'department', 'basic_salary',
//         'dob', 'join_date', 'resign_date', 'bank_name', 'bank_account_number'
//     ]));

//     // return response()->json(['success' => 'Employee updated successfully.']);
//      return redirect()->route('employees.index')->with('success', 'Employee Updated successfully!');
// }

public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'basic_salary' => 'required|numeric|min:0',
        'join_date' => 'nullable|date',
        'resign_date' => 'nullable|date',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'document' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
    ]);

    // Handle file uploads if new files provided
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('employee_images', 'public');
        $employee->image = $imagePath;
    }

    if ($request->hasFile('document')) {
        $documentPath = $request->file('document')->store('employee_documents', 'public');
        $employee->document = $documentPath;
    }

    $employee->update([
        'name' => $request->name,
        'designation' => $request->designation,
        'department' => $request->department,
        'basic_salary' => $request->basic_salary,
        'join_date' => $request->join_date,
        'resign_date' => $request->resign_date,
    ]);

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
}



public function generateIdCard($id)
{
    $employee = Employee::findOrFail($id);

    $pdf = PDF::loadView('idcard.employee_id_card', compact('employee'))
              ->setPaper([0, 0, 350, 200]); // optional custom size

    return $pdf->stream("employee_id_card_{$employee->id}.pdf");
}

public function search(Request $request)
    {
        $term = $request->get('q');

        $employees = Employee::where('name', 'like', "%{$term}%")
            ->orWhere('designation', 'like', "%{$term}%")
            ->limit(10)
            ->get();

        $results = [];

        foreach ($employees as $emp) {
            $results[] = [
                'id' => $emp->id,
                'text' => $emp->name . ' (' . $emp->designation . ')',
                'salary' => $emp->basic_salary,
            ];
        }

        return response()->json($results);
    }



}



