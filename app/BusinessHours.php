<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHours extends Model
{
    protected $fillable = ['teacher_id', 'monday', 'monday_active', 'monday_open_time', 'monday_close_time',
        'tuesday', 'tuesday_active', 'tuesday_open_time', 'tuesday_close_time',
        'wednesday', 'wednesday_active', 'wednesday_open_time', 'wednesday_close_time',
        'thursday', 'thursday_active', 'thursday_open_time', 'thursday_close_time',
        'friday', 'friday_active', 'friday_open_time', 'friday_close_time',
        'saturday', 'saturday_active', 'saturday_open_time', 'saturday_close_time',
        'sunday', 'sunday_active', 'sunday_open_time', 'sunday_close_time',];

    public function businessHours()
    {
        return $this->belongsTo(User::class);
    }
}
