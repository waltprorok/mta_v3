<?php declare(strict_types = 1);

namespace App\Models;

use App\Services\PhoneNumberService;
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

    private $phoneNumberService;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $phoneNumberService = new PhoneNumberService();
        $this->phoneNumberService = $phoneNumberService;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phoneNumberService->getPhoneNumberFormat($this->phone);
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
    public function scopeFirstNameAsc($query)
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

    public function studentTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function studentUsers(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
