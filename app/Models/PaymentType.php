<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class PaymentType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'invoice_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }
}
