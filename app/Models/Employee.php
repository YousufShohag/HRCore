<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // protected $fillable = ['name', 'designation', 'department', 'basic_salary'];
    // protected $fillable = ['name', 'designation', 'department', 'basic_salary'];
    // protected $fillable = [
    // 'name', 'designation', 'department', 'basic_salary',
    // 'leave_balance','dob', 'bank_name', 'bank_account_number',
    // 'image', 'document','join_date','resign_date'
    // ];
    protected $fillable = [
    'name', 'designation', 'department', 'basic_salary',
    'leave_balance', // must be here
    'dob', 'bank_name', 'bank_account_number',
    'image', 'document', 'join_date', 'resign_date'
    ];
  // Cast date fields to Carbon instances automatically
    protected $casts = [
        'dob' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
    ];
    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function attendanceToday()
{
    return $this->hasOne(Attendance::class)->where('date', now()->toDateString());
}

}