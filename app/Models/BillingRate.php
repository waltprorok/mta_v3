<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class BillingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'type',
        'amount',
        'description',
        'default',
        'active',
        'flat_rate',
        'cancelled_twenty_four_hours',
        'cancelled_forty_eight_hours',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'default' => 'boolean',
        'active' => 'boolean',
        'flat_rate' => 'boolean',
        'cancelled_twenty_four_hours' => 'boolean',
        'cancelled_forty_eight_hours' => 'boolean',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class, 'billing_rate_id', 'id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'billing_rate_id', 'id');
    }

    public function scopeGetTeacherActiveRates($query)
    {
        return $query->where('teacher_id', Auth::id())->where('active', true)->orderBy('default', 'desc');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
