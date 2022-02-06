<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * @return BelongsTo
     */
    public function userFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    /**
     * @return BelongsTo
     */
    public function userTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }
}
