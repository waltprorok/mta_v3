<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHours extends Model
{
    protected $fillable = ['teacher_id', 'day', 'active', 'open_time', 'close_time'];

    public function businessHours()
    {
        return $this->belongsTo(User::class);
    }
}
