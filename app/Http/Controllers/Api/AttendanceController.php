<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Check if user can check in based on location
     */
    private function isWithinAllowedRadius($userLat, $userLon): bool
    {
        $officeLat = (float) AttendanceSetting::getValue('office_latitude', -6.200000);
        $officeLon = (float) AttendanceSetting::getValue('office_longitude', 106.816666);
        $allowedRadius = (int) AttendanceSetting::getValue('allowed_radius_meters', 500);

        $distance = $this->calculateDistance($userLat, $userLon, $officeLat, $officeLon);

        return $distance <= $allowedRadius;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check in
     */
    public function checkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if user already checked in today
        $existingAttendance = Attendance::todayForUser($user->id)->first();
        
        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already checked in today'
            ], 400);
        }

        // Validate location
        if (!$this->isWithinAllowedRadius($request->latitude, $request->longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'You are too far from the office location'
            ], 400);
        }

        // Store photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance/check-in', 'public');
        }

        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in_time' => now(),
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photoPath,
            'check_in_address' => $request->address,
            'attendance_date' => today(),
            'status' => 'checked_in',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check in successful',
            'data' => $attendance
        ], 201);
    }

    /**
     * Check out
     */
    public function checkOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'address' => 'nullable|string',
            'work_description' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Get today's attendance
        $attendance = Attendance::todayForUser($user->id)
            ->where('status', 'checked_in')
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have not checked in today or already checked out'
            ], 400);
        }

        // Validate location
        if (!$this->isWithinAllowedRadius($request->latitude, $request->longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'You are too far from the office location'
            ], 400);
        }

        // Store photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance/check-out', 'public');
        }

        // Update attendance record
        $attendance->update([
            'check_out_time' => now(),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photoPath,
            'check_out_address' => $request->address,
            'work_description' => $request->work_description,
            'work_duration_minutes' => $attendance->calculateWorkDuration(),
            'status' => 'checked_out',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check out successful',
            'data' => $attendance
        ]);
    }

    /**
     * Get today's attendance status
     */
    public function todayStatus(Request $request)
    {
        $user = $request->user();
        $attendance = Attendance::todayForUser($user->id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'attendance' => $attendance,
                'has_checked_in' => $attendance !== null,
                'has_checked_out' => $attendance && $attendance->status === 'checked_out',
            ]
        ]);
    }

    /**
     * Get attendance history
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        $perPage = $request->input('per_page', 15);
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc');

        if ($month && $year) {
            $query->whereMonth('attendance_date', $month)
                  ->whereYear('attendance_date', $year);
        }

        $attendances = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    /**
     * Get attendance detail
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $attendance = Attendance::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Get attendance settings
     */
    public function settings()
    {
        $settings = AttendanceSetting::getAllSettings();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get attendance statistics
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get();

        $totalDays = $attendances->count();
        $totalCheckedOut = $attendances->where('status', 'checked_out')->count();
        $totalWorkMinutes = $attendances->sum('work_duration_minutes');
        $averageWorkMinutes = $totalCheckedOut > 0 ? $totalWorkMinutes / $totalCheckedOut : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'month' => $month,
                'year' => $year,
                'total_attendance_days' => $totalDays,
                'total_completed_days' => $totalCheckedOut,
                'total_work_hours' => round($totalWorkMinutes / 60, 2),
                'average_work_hours' => round($averageWorkMinutes / 60, 2),
            ]
        ]);
    }
}
