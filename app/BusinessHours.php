<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHours extends Model
{
    protected $fillable = [
        'teacher_id',
        'day',
        'active',
        'open_time',
        'close_time',
    ];

    public function businessHours(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHourOpenTimeAttribute($value)
    {
        return is_null($this->open_time) ? '' :  date('g:i a', strtotime($this->open_time));
    }

    public function getHourCloseTimeAttribute($value)
    {
        return is_null($this->close_time) ? '' :  date('g:i a', strtotime($this->close_time));
    }
}
