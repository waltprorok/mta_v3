<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'student_id' => 'integer',
    ];

    protected $fillable = [
        'billing_rate_id',
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

    /**
     * @return HasOne
     */
    public function lessonTeacherId(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
