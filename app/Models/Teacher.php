<?php

namespace App\Models;

use App\Services\PhoneNumberService;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class Teacher extends Model
{
    use SoftDeletes;

    protected $casts = [
        'teacher_id' => 'integer'
    ];

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
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phoneNumberService->getPhoneNumberFormat($this->phone);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'teacher_id', 'teacher_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'teacher_id', 'teacher_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'teacher_id', 'teacher_id');
    }

    public function scopeFirstNameAsc($query)
    {
        return $query->orderBy('first_name', 'asc');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'teacher_id');
    }
}
