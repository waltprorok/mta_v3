<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class Lesson extends Model
{
    use SoftDeletes;

    protected $casts = [
        'billing_rate_id' => 'integer',
        'complete' => 'boolean',
        'invoice_id' => 'integer',
        'student_id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'billing_rate_id',
        'invoice_id',
        'color',
        'complete',
        'end_date',
        'interval',
        'recurrence',
        'notes',
        'title',
        'start_date',
        'student_id',
        'teacher_id',
        'status',
        'status_updated_at',
    ];

    const STATUS = [
        'Scheduled',
        'Re-Scheduled',
        'Cancelled'
    ];

    const RECURRENCE = [
        'Once',
        'Monthly',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function billingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class, 'billing_rate_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function invoices(): HasMany
    {
        return $this->HasMany(Invoice::class);
    }

    public function lessonTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
