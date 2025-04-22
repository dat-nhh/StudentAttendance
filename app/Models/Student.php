<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
        'surname',
        'forename',
        'class',
        'email',
    ];
}
