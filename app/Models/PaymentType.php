<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'invoice_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }
}
