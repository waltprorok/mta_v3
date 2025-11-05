<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'body',
        'image',
        'released_on',
    ];

    protected $casts = [
        'released_on' => 'datetime',
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
        return is_null($this->released_on) ? '' : $this->released_on->format('F j, Y');
    }

    public function getDateBlogRawAttribute(): string
    {
        return is_null($this->released_on) ? '' : $this->released_on->format('H:i:s');
    }

    public function getDateHourMinAttribute(): string
    {
        return is_null($this->released_on) ? '' : $this->released_on->format('h:i A');
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
