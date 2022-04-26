<?php

namespace App\Models;

use App\Services\PhoneNumberService;
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
