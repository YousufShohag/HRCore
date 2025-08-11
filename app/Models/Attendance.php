<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'clock_in', 'clock_in_photo',
        'clock_out', 'clock_out_photo', 'total_hours'
    ];

    protected $dates = ['clock_in', 'clock_out', 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

