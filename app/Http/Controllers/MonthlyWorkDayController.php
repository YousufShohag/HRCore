<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use App\Models\MonthlyWorkDay;
use Illuminate\Http\Request;

class MonthlyWorkDayController extends Controller
{
    public function index()
    {
          $months = MonthlyWorkDay::orderBy('month', 'desc')->get();
        foreach ($months as $month) {
                 $month->total_hours = $month->total_working_days * 8;
        }

      
        return view('monthly_work_days.index', compact('months'));
        
    }

    public function create()
    {
        return view('monthly_work_days.create');
    }

    // public function create()
    // {
    //     return view('months.create');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|date|unique:monthly_work_days,month',
            'working_days' => 'required|integer|min:0',
            'govt_holidays' => 'required|integer|min:0',
            'fridays' => 'required|integer|min:0',
            'special_holidays' => 'required|integer|min:0',
        ]);

        $total_working_days = $request->working_days - 
            ($request->govt_holidays + $request->fridays + $request->special_holidays);

        MonthlyWorkDay::create([
            'month' => $request->month,
            'working_days' => $request->working_days,
            'govt_holidays' => $request->govt_holidays,
            'fridays' => $request->fridays,
            'special_holidays' => $request->special_holidays,
            'total_working_days' => $total_working_days
        ]);

        return redirect()->route('monthly_work_days.index')->with('success', 'Monthly work days saved.');
    }

    // Add edit, update, destroy as needed...
}


?>