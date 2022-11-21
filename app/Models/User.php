<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $casts = [
        'admin' => 'boolean',
        'student' => 'boolean',
        'teacher' => 'boolean',
        'parent' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'teacher',
        'student',
        'parent',
        'terms',
        'trial_ends_at',
    ];

    protected $guarded = [
        'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return mixed
     */
    public static function activeStudentCount(): int
    {
        return Auth::user()->students->where('status', 1)->count();
    }

    /**
     * @return HasMany
     */
    public function blogArticle(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * @return HasMany
     */
    public function businessHours(): HasMany
    {
        return $this->hasMany(BusinessHours::class, 'teacher_id');
    }

    /**
     * @return HasOne
     */
    public function getTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id');
    }

    public function getTeacherPaymentRate(): HasMany
    {
        return $this->hasMany(BillingRate::class, 'teacher_id');
    }

    /**
     * @return HasMany
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public static function lessonsThisWeek(): int
    {
        return Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    public static function lessonsThisMonth(): int
    {
        return Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
    }

    public static function getBillingRates()
    {
        return Auth::user()->getTeacherPaymentRate;
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id_to');
    }

    public function parentOfStudent(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            ParentStudent::class,
            'parent_id',
            'id',
            'id',
            'student_id'
        );
    }

    public function parentStudentPivot(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_students', 'parent_id');
    }

    public function scopeFirstNameAsc($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    public function studentUsers(): HasMany
    {
        return $this->hasMany(Student::class, 'student_id');
    }

    public static function monthlyIncome(): int
    {
        $lessonsInMonth = self::lessonsThisMonth();
        $billingRate = self::getBillingRates();

        // TODO improve this logic there can be more than one rate
        // Is Rate (lesson, hourly, weekly, monthly, yearly)?
        // Get lessons for the month
        // check which rate is assigned to the lesson
        // ** add column and relationship for lesson and rate
        // ** add option during lesson schedule
        // Break down the rate vs lesson
        foreach ($billingRate as $rate) {
            $monthlyAmount = ($rate->amount * $lessonsInMonth);
        }

        return $monthlyAmount ?? 0;
    }

    public static function openTimeBlocks(): int
    {
        $minutesInDay = 0;
        $lessonsInWeek = self::lessonsThisWeek() * 30;
        $businessHours = Auth::user()->businessHours->where('active', true);

        foreach ($businessHours as $businessHour) {
            $closeTime = Carbon::createFromFormat('H:i', $businessHour->close_time);
            $openTime = Carbon::createFromFormat('H:i', $businessHour->open_time);
            $minutesInDay += $closeTime->diffInMinutes($openTime);
        }

        return ($minutesInDay - $lessonsInWeek) / 30 ?? 0;
    }

    public static function unreadMessagesCount(): int
    {
        return Auth::user()->messages->where('read', '==', false)->count();
    }
}
