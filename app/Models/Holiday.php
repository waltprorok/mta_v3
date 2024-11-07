<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Holiday extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'color',
        'start_date',
        'end_date',
        'all_day',
    ];

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
