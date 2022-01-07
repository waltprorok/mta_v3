<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    protected $table = 'parent_students';

    protected $fillable = [
        'parent_id',
        'student_id',
    ];
}
