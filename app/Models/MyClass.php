<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'name',
        'semester',
        'year',
        'teacher',
    ];
}
