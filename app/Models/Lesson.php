<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'color',
        'complete',
        'teacher_id',
        'title',
        'start_date',
        'end_date'
    ];

    protected $casts = ['complete' => 'boolean'];

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lessonTeacherId(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
