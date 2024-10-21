<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instrument extends Model
{
    protected $fillable = ['teacher_id', 'name'];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
