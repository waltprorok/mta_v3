<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Builder
 */
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

    protected $casts = [
        'all_day' => 'boolean',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function scopeGetTeacherHolidaysForTwoYears($query)
    {
        return $query->where('teacher_id', Auth::id())
            ->whereBetween('start_date', [now()->subWeeks(52), now()->addWeeks(52)]);
    }
}
