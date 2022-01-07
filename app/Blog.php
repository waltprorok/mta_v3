<?php

namespace App;

use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $dates = ['released_on'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'body',
        'image',
        'released_on',
    ];

    public $value;

    /**
     * @var int|mixed|null
     */
    private $author_id;

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        $imageUrl = "";

        if (! is_null($this->image)) {
            $imagePath = public_path() . "/storage/blog/" . $this->image;
            if (file_exists($imagePath)) {
                $imageUrl = asset("storage/blog/" . $this->image);
            }
        }
        return $imageUrl;
    }

    /**
     * @return string
     */
    public function getDateForHumanAttribute(): string
    {
        return is_null($this->released_on) ? '' : $this->released_on->diffForHumans();
    }

    /**
     * @return false|string
     */
    public function getDateTimeAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('M d, Y', strtotime($this->released_on));
    }

    /**
     * @return false|string
     */
    public function getDateBlogRawAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('H:i:s', strtotime($this->released_on));
    }

    /**
     * @return false|string
     */
    public function getDateHourMinAttribute(): string
    {
        return is_null($this->released_on) ? '' : date('h:i A', strtotime($this->released_on));
    }

    /**
     * @return null
     */
    public function getBodyHtmlAttribute(): ?string
    {
        return $this->body ? Markdown::convertToHtml(e($this->body)) : null;
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
