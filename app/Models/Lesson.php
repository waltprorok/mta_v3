<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class Lesson extends Model
{
    protected $casts = [
        'billing_rate_id' => 'integer',
        'complete' => 'boolean',
        'invoice_id' => 'integer',
        'student_id' => 'integer',
    ];

    protected $fillable = [
        'billing_rate_id',
        'invoice_id',
        'color',
        'complete',
        'end_date',
        'interval',
        'title',
        'start_date',
        'student_id',
        'teacher_id',
    ];

    public function billingRate(): BelongsTo
    {
        return $this->belongsTo(BillingRate::class, 'billing_rate_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function lessonTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
