<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
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
    public function scopeDeleted($query)
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
