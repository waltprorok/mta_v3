<?php

namespace App\Models;

use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return string|null
     */
    public function getBodyHtmlAttribute(): ?string
    {
        return $this->body ? Markdown::convertToHtml(e($this->body)) : null;
    }

    /**
     * @return string|null
     */
    public function getBodyShortAttribute(): ?string
    {
        return $this->body ? Str::limit($this->body, 260) : null;
    }

    /**
     * @return string
     */
//    public function getDateForHumanAttribute(): string
//    {
//        return is_null($this->released_on) ? '' : $this->released_on->diffForHumans();
//    }

    /**
     * @return string
     */
    public function getDateTimeAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('F j, Y', strtotime($this->released_on));
    }

    /**
     * @return string
     */
    public function getDateBlogRawAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('H:i:s', strtotime($this->released_on));
    }

    /**
     * @return string
     */
    public function getDateHourMinAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('h:i A', strtotime($this->released_on));
    }


    /**
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        $imageUrl = '';

        if (! is_null($this->image)) {
            $imagePath = public_path('/storage/blog/') . $this->image;

            if (file_exists($imagePath)) {
                $imageUrl = asset('storage/blog/' . $this->image);
            }
        } else {
            $imageUrl = asset('webapp/imgs/sheet-music.jpg');
        }

        return $imageUrl;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('released_on', 'desc');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('released_on', '<=', Carbon::now());
    }
}
