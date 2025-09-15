<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'max_participants',
        'price',
        'difficulty_level',
        'equipment_needed',
        'status',
    ];

    protected $casts = [
        'equipment_needed' => 'array',
        'price' => 'decimal:2',
    ];

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
