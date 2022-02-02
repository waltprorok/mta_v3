<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * @return string|null
     */
    public function getPhoneNumberAttribute(): ?string
    {
        if ($this->phone !== null) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);

            switch (strlen($cleaned)) {
                case 10:
                    preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                    return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
                case 11:
                    preg_match('/(\d{1})(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                    return "{$matches[1]}-{$matches[2]}-{$matches[3]}-{$matches[4]}";
                default:
                    return $this->phone;
            }
        }

        return null;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFirstNameAsc($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    /**
     * @return HasMany
     */
    public function student(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function teacherStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'teacher_id');
    }
}
