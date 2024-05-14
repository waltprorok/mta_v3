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
//    use SoftDeletes;

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'subject',
        'body',
        'read',
        'deleted'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', false);
    }

    /**
     * @param $query
     * @return mixed
     */
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
