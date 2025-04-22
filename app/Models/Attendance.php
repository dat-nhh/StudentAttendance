<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'lesson',
        'student',
        'status',
        'datetime',
        'device',
    ];
}
