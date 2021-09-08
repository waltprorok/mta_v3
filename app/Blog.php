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
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute($value)
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

    public function getDateForHumanAttribute($value)
    {
        return is_null($this->released_on) ? '' : $this->released_on->diffForHumans();
    }

    public function getDateTimeAttribute($value)
    {
        return is_null($this->released_on) ? '' : date('M d, Y', strtotime($this->released_on));
    }

    public function getDateBlogRawAttribute($value)
    {
        return is_null($this->released_on) ? '' : date('H:i:s', strtotime($this->released_on));
    }

    public function getDateHourMinAttribute($value)
    {
        return is_null($this->released_on) ? '' : date('h:i A', strtotime($this->released_on));
    }

    public function getBodyHtmlAttribute($value)
    {
        return $this->body ? Markdown::convertToHtml(e($this->body)) : null;
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
