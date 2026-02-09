<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        // Filter by user
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        // Filter by month and year
        if ($request->has('month') && $request->month) {
            $query->whereMonth('attendance_date', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('attendance_date', $request->year);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(20);

        $users = User::where('role', 'employee')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.attendances.index', compact('attendances', 'users'));
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user');
        return view('admin.attendances.show', compact('attendance'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with('user');

        // Apply same filters as index
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        if ($request->has('month') && $request->month) {
            $query->whereMonth('attendance_date', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('attendance_date', $request->year);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->get();

        $filename = 'attendance_report_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'Date',
                'Employee Name',
                'Email',
                'Check In Time',
                'Check In Location',
                'Check Out Time',
                'Check Out Location',
                'Work Duration',
                'Work Description',
                'Status'
            ]);

            // Data
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->attendance_date->format('Y-m-d'),
                    $attendance->user->name,
                    $attendance->user->email,
                    $attendance->check_in_time->format('H:i:s'),
                    $attendance->check_in_address ?? "{$attendance->check_in_latitude}, {$attendance->check_in_longitude}",
                    $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : '-',
                    $attendance->check_out_address ?? ($attendance->check_out_latitude ? "{$attendance->check_out_latitude}, {$attendance->check_out_longitude}" : '-'),
                    $attendance->getFormattedWorkDuration() ?? '-',
                    $attendance->work_description ?? '-',
                    ucfirst(str_replace('_', ' ', $attendance->status))
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function report(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $users = User::where('role', 'employee')
            ->where('is_active', true)
            ->withCount([
                'attendances as total_attendance' => function($query) use ($month, $year) {
                    $query->whereMonth('attendance_date', $month)
                          ->whereYear('attendance_date', $year);
                },
                'attendances as completed_attendance' => function($query) use ($month, $year) {
                    $query->whereMonth('attendance_date', $month)
                          ->whereYear('attendance_date', $year)
                          ->where('status', 'checked_out');
                }
            ])
            ->get();

        // Calculate working days in the month
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $workingDays = 0;
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) {
                $workingDays++;
            }
        }

        return view('admin.attendances.report', compact('users', 'month', 'year', 'workingDays'));
    }
}
