<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specializations',
        'experience_years',
        'bio',
        'status',
        'hourly_rate',
    ];

    protected $casts = [
        'specializations' => 'array',
        'hourly_rate' => 'decimal:2',
    ];

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
