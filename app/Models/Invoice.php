<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class Invoice extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'lesson_id',
        'subtotal',
        'discount',
        'total',
        'balance_due',
        'payment',
        'adjustments',
        'payment_type_id',
        'check_number',
        'payment_information',
        'due_date',
        'is_paid',
    ];

    protected $casts = [
        'balance_due' => 'integer',
        'is_paid' => 'boolean',
        'payment_type_id' => 'integer'
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'invoice_id');
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class, 'invoice_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

}
