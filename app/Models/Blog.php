<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @mixin Builder
 */
class Blog extends Model
{
    use SoftDeletes;

    protected $dates = ['released_on'];

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'body',
        'image',
        'released_on',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getBodyShortAttribute(): ?string
    {
        return $this->body ? Str::limit($this->body, 220) : null;
    }

    /**
     * @return string
     */
//    public function getDateForHumanAttribute(): string
//    {
//        return is_null($this->released_on) ? '' : $this->released_on->diffForHumans();
//    }

    public function getDateTimeAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('F j, Y', strtotime($this->released_on));
    }

    public function getDateBlogRawAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('H:i:s', strtotime($this->released_on));
    }

    public function getDateHourMinAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('h:i A', strtotime($this->released_on));
    }

    public function getImageUrlAttribute(): string
    {
        $imageUrl = '';

        if (! is_null($this->image)) {
            $imagePath = public_path('/storage/blog/') . $this->image;

            if (file_exists($imagePath)) {
                $imageUrl = asset('storage/blog/' . $this->image);
            }
        } else {
            $imageUrl = asset('webapp/img/sheet-music.jpg');
        }

        return $imageUrl;
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('released_on', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('released_on', '<=', Carbon::now());
    }
}
