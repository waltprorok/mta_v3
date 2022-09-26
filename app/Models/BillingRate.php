<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
