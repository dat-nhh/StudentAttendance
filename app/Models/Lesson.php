<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'class',
        'date',
    ];
}
