<?php declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use Notifiable;

    const ACTIVE = 1;
    const WAITLIST = 2;
    const LEAD = 3;
    const INACTIVE = 4;

    protected $table = 'students';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'parent_id',
        'first_name',
        'last_name',
        'email',
        'parent_email',
        'phone',
        'date_of_birth',
//        'address',
//        'address_2',
//        'city',
//        'state',
//        'zip',
        'instrument',
        'status',
        'photo',
    ];

    protected $hidden = [
        'teacher_id'
    ];

    /**
     * @return string|null
     */
    public function getPhoneNumberAttribute(): ?string
    {
        if ($this->phone != null) {
            $cleaned = preg_replace('/[^[:digit:]]/', '', $this->phone);
            if (strlen($cleaned) == 10) {
                preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
            } else if (strlen($cleaned) == 11) {
                preg_match('/(\d)(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
                return "{$matches[1]}-{$matches[2]}-{$matches[3]}-{$matches[4]}";
            }
        }

        return $this->phone;
    }

    /**
     * @return HasOne
     */
    public function hasOneLesson(): HasOne
    {
        return $this->hasOne(Lesson::class);
    }

    /**
     * @return BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return HasMany
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    /**
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * @param $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification): string
    {
        return '+1' . $this->phone;
    }

    public function studentUsers(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
