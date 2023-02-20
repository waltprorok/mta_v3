<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class, 'billing_rate_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
