<?php

namespace App;

use Carbon\Carbon;
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
     * @var mixed
     */
//    private $user;

    /**
     * @return mixed
     */
    public static function activeStudentCount()
    {
        return Auth::user()->students->where('status', '=', 1)->count();
    }

    /**
     * @return HasMany
     */
    public function blogArticle(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * @return HasOne
     */
    public function getTeacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'teacher_id');
    }

    /**
     * @return HasMany
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    /**
     * @return mixed
     */
    public static function lessonsThisWeek()
    {
        return Auth::user()->lessons->whereBetween('start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
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
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    public function studentUsers(): HasMany
    {
        return $this->hasMany(Student::class, 'student_id');
    }

    public static function unreadMessagesCount()
    {
        return Auth::user()->messages->where('read', '==', false)->count();
    }

}
