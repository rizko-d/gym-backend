<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'class_schedule_id',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function getDurationAttribute()
    {
        if ($this->check_out_time) {
            return $this->check_in_time->diffInMinutes($this->check_out_time);
        }
        return null;
    }
}
