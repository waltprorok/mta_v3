<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Message extends Model
{
    protected $casts = [
        'read' => 'boolean',
        'deleted' => 'boolean',
        'created_at' => 'datetime:g:i a | M d D',
    ];

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'body',
        'read',
        'deleted'
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }

    public function scopeIsDeleted($query)
    {
        return $query->where('deleted', true);
    }

    public function userFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function userTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }
}
