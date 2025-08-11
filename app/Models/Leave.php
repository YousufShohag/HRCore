<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'status',
        'approved_by',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    protected static function booted()
{
    static::deleting(function ($leave) {
        \Log::info('Deleting leave: ', ['leave_id' => $leave->id, 'days' => $leave->days]);

        $employee = $leave->employee;

        if ($employee) {
            \Log::info('Before update', ['leave_balance' => $employee->leave_balance]);

            $employee->leave_balance += $leave->days;
            if ($employee->leave_balance < 0) {
                $employee->leave_balance = 0;
            }
            $employee->save();

            \Log::info('After update', ['leave_balance' => $employee->leave_balance]);
        }
    });
}

}