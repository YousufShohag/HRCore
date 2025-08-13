<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title', 'content', 'publish_date', 'expiry_date'];
    protected $casts = [
    'publish_date' => 'date',
    'expiry_date'  => 'date',
];

}


