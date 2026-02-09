<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $activeEmployees = User::where('role', 'employee')->where('is_active', true)->count();
        
        $todayAttendances = Attendance::whereDate('attendance_date', today())->count();
        $todayCheckedIn = Attendance::whereDate('attendance_date', today())
            ->where('status', 'checked_in')
            ->count();
        $todayCheckedOut = Attendance::whereDate('attendance_date', today())
            ->where('status', 'checked_out')
            ->count();

        // Get recent attendances
        $recentAttendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly statistics
        $monthlyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Attendance::whereYear('attendance_date', $date->year)
                ->whereMonth('attendance_date', $date->month)
                ->count();
            
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'todayAttendances',
            'todayCheckedIn',
            'todayCheckedOut',
            'recentAttendances',
            'monthlyStats'
        ));
    }
}
