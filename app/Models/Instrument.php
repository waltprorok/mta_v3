<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Builder
 */
class Instrument extends Model
{
    protected $fillable = ['teacher_id', 'name'];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->timezone(Auth::user()->getTimeZone())->format('Y-m-d H:i:s');
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
