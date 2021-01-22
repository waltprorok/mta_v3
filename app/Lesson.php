<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'title',
        'start_date',
        'end_date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
