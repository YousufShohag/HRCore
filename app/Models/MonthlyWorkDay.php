<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyWorkDay extends Model
{
    protected $fillable = [
        'month', 'working_days', 'govt_holidays', 'fridays', 'special_holidays', 'total_working_days'
    ];

    protected $dates = ['month'];
}
