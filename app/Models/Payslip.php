<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'total_hours',
        'average_hours',
        'absent_days',
        'sick_leave_days',
        'total_worked_days',   // ✅ New
        'salary',
        'allowance',
        'deduction',
        'net_salary',
        'document',            // ✅ New
        'notes',               // ✅ New
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function monthData() 
    {
        return $this->hasOne(\App\Models\MonthlyWorkDay::class, 'month', 'month');
    }
    public function getNetSalaryAttribute()
    {
    return ($this->salary ?? 0) + ($this->allowance ?? 0) - ($this->deduction ?? 0);
    }
}
