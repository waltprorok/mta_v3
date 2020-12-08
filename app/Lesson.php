<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
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
