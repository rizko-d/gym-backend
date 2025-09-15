<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\MemberClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['gymClass', 'trainer']);

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('class_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('class_date', '<=', $request->date_to);
        }

        // Filter by trainer
        if ($request->has('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->orderBy('class_date')
                          ->orderBy('start_time')
                          ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gym_class_id' => 'required|exists:gym_classes,id',
            'trainer_id' => 'required|exists:trainers,id',
            'class_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        // Check trainer availability
        $conflictingSchedule = ClassSchedule::where('trainer_id', $request->trainer_id)
            ->where('class_date', $request->class_date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflictingSchedule) {
            return response()->json([
                'success' => false,
                'message' => 'Trainer is not available at this time'
            ], 422);
        }

        $schedule = ClassSchedule::create($request->all());
        $schedule->load(['gymClass', 'trainer']);

        return response()->json([
            'success' => true,
            'message' => 'Class scheduled successfully',
            'data' => $schedule
        ], 201);
    }

    public function show(ClassSchedule $classSchedule)
    {
        $classSchedule->load(['gymClass', 'trainer', 'members']);
        
        return response()->json([
            'success' => true,
            'data' => $classSchedule
        ]);
    }

    public function bookClass(Request $request, ClassSchedule $classSchedule)
    {
        $memberId = $request->user()->id;

        // Check if class is available for booking
        if ($classSchedule->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Class is not available for booking'
            ], 422);
        }

        // Check if class has available spots
        if (!$classSchedule->hasAvailableSpots()) {
            return response()->json([
                'success' => false,
                'message' => 'Class is fully booked'
            ], 422);
        }

        // Check if member is already booked
        $existingBooking = MemberClass::where('member_id', $memberId)
            ->where('class_schedule_id', $classSchedule->id)
            ->whereIn('status', ['booked', 'attended'])
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'You are already booked for this class'
            ], 422);
        }

        // Create booking
        $memberClass = MemberClass::create([
            'member_id' => $memberId,
            'class_schedule_id' => $classSchedule->id,
            'booked_at' => now(),
            'status' => 'booked'
        ]);

        // Update participant count
        $classSchedule->increment('current_participants');

        return response()->json([
            'success' => true,
            'message' => 'Class booked successfully',
            'data' => $memberClass
        ], 201);
    }

    public function cancelBooking(Request $request, ClassSchedule $classSchedule)
    {
        $memberId = $request->user()->id;

        $memberClass = MemberClass::where('member_id', $memberId)
            ->where('class_schedule_id', $classSchedule->id)
            ->where('status', 'booked')
            ->first();

        if (!$memberClass) {
            return response()->json([
                'success' => false,
                'message' => 'No booking found for this class'
            ], 404);
        }

        // Check if cancellation is allowed (e.g., at least 2 hours before class)
        $classDateTime = Carbon::parse($classSchedule->class_date . ' ' . $classSchedule->start_time);
        if (now()->diffInHours($classDateTime) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Cancellation not allowed. Must cancel at least 2 hours before class.'
            ], 422);
        }

        $memberClass->update(['status' => 'cancelled']);
        $classSchedule->decrement('current_participants');

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully'
        ]);
    }
}
