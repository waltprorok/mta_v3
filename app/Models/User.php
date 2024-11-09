<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use Billable, SoftDeletes;

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

    const ADMIN_ID = 2;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function getBusinessHours(): HasMany
    {
        return $this->hasMany(BusinessHours::class, 'teacher_id');
    }

    public static function getBillingRates()
    {
        return Auth::user()->getTeacherPaymentRate;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id');
    }

    public function getTeacherPaymentRate(): HasMany
    {
        return $this->hasMany(BillingRate::class, 'teacher_id');
    }

    public function isAdmin(): bool
    {
        return $this->admin && $this->admin !== null;
    }

    public function isParent(): bool
    {
        return $this->parent && $this->parent !== null;
    }

    public function isStudent(): bool
    {
        return $this->student && $this->student !== null;
    }

    public function isTeacher(): bool
    {
        return $this->teacher && $this->teacher !== null;
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'teacher_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id_to');
    }

    public function parentOfStudent(): HasOne
    {
        return $this->hasOne(Student::class, 'parent_id');
    }

    public function parentOfStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function scopeFirstNameAsc($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'student_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    public function studentUsers(): HasMany
    {
        return $this->hasMany(Student::class, 'student_id');
    }

    public static function unreadMessagesCount(): int
    {
        return Auth::user()->messages->where('read', '==', false)->count();
    }
}
