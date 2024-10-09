<?php

namespace App\Models;

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
        'created_at' => 'datetime:h:i a | M d',
    ];

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'body',
        'read',
        'deleted'
    ];

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
