<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use Notifiable;

    /**
     * @param $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return '+1' . $this->phone;
    }

    protected $table = 'students';

    protected $fillable = [
        'teacher_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'address_2',
        'city',
        'state',
        'zip',
        'instrument',
        'status',
        'photo',
    ];

    protected $hidden = [
        'teacher_id'
    ];


    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function hasOneLesson()
    {
        return $this->hasOne(Lesson::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    /**
     * @return string|void
     */
    public function getPhoneNumberAttribute()
    {
        if ($this->phone != null) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
            preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
            return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
        }
    }

}
