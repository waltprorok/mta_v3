<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class BillingRate extends Model
{
    protected $fillable = [
        'teacher_id',
        'type',
        'amount',
        'description',
    ];

    protected $cast = [
        'amount' => 'decimal:2',
    ];

    public function billingRate(): HasOne
    {
        return $this->hasOne(Lesson::class, 'billing_rate_id', 'id')->where('complete', false);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'billing_rate_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
