<?php

namespace App\Models;

use App\Services\PhoneNumberService;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin Builder
 */
class Student extends Model
{
    use Notifiable, SoftDeletes;

    const ACTIVE = 1;
    const WAITLIST = 2;
    const LEAD = 3;
    const INACTIVE = 4;
    const PARENT = 5;

    protected $casts = [
        'student_id' => 'integer',
        'status' => 'integer',
        'auto_schedule' => 'boolean',
    ];

    protected $fillable = [
        'student_id',
        'teacher_id',
        'parent_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'parent_phone',
        'date_of_birth',
        'address',
        'address_2',
        'city',
        'state',
        'zip',
        'instrument',
        'level',
        'auto_schedule',
        'at_home',
        'at_studio',
        'status',
        'photo',
    ];

    protected $hidden = [
        'teacher_id'
    ];

    protected $touches = ['studentUsers'];

    private $phoneNumberService;

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $phoneNumberService = new PhoneNumberService();
        $this->phoneNumberService = $phoneNumberService;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phoneNumberService->getPhoneNumberFormat($this->phone);
    }

    public function getParentPhoneNumberAttribute(): ?string
    {
        return $this->phoneNumberService->getPhoneNumberFormat($this->parent_phone);
    }

    public function getTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function hasOneFutureLesson(): HasOne
    {
        return $this->hasOne(Lesson::class)->where('start_date', '>', Carbon::now()->format('Y-m-d H:i:s'));
    }

    public function hasOneLesson(): HasOne
    {
        return $this->hasOne(Lesson::class)->latest();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'student_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'student_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'student_id');
    }

    /**
     * @param $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification): string
    {
        return '1' . $this->phone;
    }

    public function scopeFirstNameAsc($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    public function studentUsers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
