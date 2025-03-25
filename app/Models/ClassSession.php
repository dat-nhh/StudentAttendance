<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'class',
        'date',
    ];
}
