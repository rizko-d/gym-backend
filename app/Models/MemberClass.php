<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'class_schedule_id',
        'booked_at',
        'status',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
