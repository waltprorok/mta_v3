<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class ParentStudent extends Model
{
    protected $fillable = [
        'parent_id',
        'student_id',
    ];
}
