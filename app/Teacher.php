<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $table = 'teachers';

    protected $fillable = [
        'teacher_id',
        'studio_name',
        'first_name',
        'last_name',
        'address',
        'address_2',
        'city',
        'state',
        'zip',
        'email',
        'phone',
        'logo'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function getPhoneNumberAttribute()
    {
        if ($this->phone != null) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
            preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
            return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
        }
    }

}
