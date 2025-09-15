<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_class_id',
        'trainer_id',
        'class_date',
        'start_time',
        'end_time',
        'room',
        'current_participants',
        'status',
        'notes',
    ];

    protected $casts = [
        'class_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function memberClasses()
    {
        return $this->hasMany(MemberClass::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_classes')
                    ->withPivot('status', 'booked_at')
                    ->withTimestamps();
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function hasAvailableSpots()
    {
        return $this->current_participants < $this->gymClass->max_participants;
    }
}
