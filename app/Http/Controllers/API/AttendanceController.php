<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\ClassSchedule;
use App\Models\MemberClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'member_id' => 'required|exists:members,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        $classSchedule = ClassSchedule::find($request->class_schedule_id);
        
        // Check if member is booked for this class
        $memberClass = MemberClass::where('member_id', $request->member_id)
            ->where('class_schedule_id', $request->class_schedule_id)
            ->where('status', 'booked')
            ->first();

        if (!$memberClass) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not booked for this class'
            ], 422);
        }

        // Check if already checked in
        $existingLog = AttendanceLog::where('member_id', $request->member_id)
            ->where('class_schedule_id', $request->class_schedule_id)
            ->whereNull('check_out_time')
            ->first();

        if ($existingLog) {
            return response()->json([
                'success' => false,
                'message' => 'Member is already checked in'
            ], 422);
        }

        // Create attendance log
        $attendanceLog = AttendanceLog::create([
            'member_id' => $request->member_id,
            'class_schedule_id' => $request->class_schedule_id,
            'check_in_time' => now(),
            'notes' => $request->notes,
        ]);

        // Update member class status
        $memberClass->update(['status' => 'attended']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'data' => $attendanceLog
        ], 201);
    }

    public function checkOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'member_id' => 'required|exists:members,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 422);
        }

        $attendanceLog = AttendanceLog::where('member_id', $request->member_id)
            ->where('class_schedule_id', $request->class_schedule_id)
            ->whereNull('check_out_time')
            ->first();

        if (!$attendanceLog) {
            return response()->json([
                'success' => false,
                'message' => 'No active check-in found'
            ], 404);
        }

        $attendanceLog->update([
            'check_out_time' => now(),
            'notes' => $request->notes ? $attendanceLog->notes . '; ' . $request->notes : $attendanceLog->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful',
            'data' => $attendanceLog
        ]);
    }

    public function getAttendanceHistory(Request $request)
    {
        $query = AttendanceLog::with(['member', 'classSchedule.gymClass', 'classSchedule.trainer']);

        // Filter by member
        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('check_in_time', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('check_in_time', '<=', $request->date_to);
        }

        $attendanceLogs = $query->orderBy('check_in_time', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $attendanceLogs
        ]);
    }
}
