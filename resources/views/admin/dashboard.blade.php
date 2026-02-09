@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <!-- Total Employees -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>TOTAL EMPLOYEES</h3>
            <div class="value">{{ number_format($totalEmployees ?? 0) }}</div>
            <span class="status-badge status-neutral mt-2">{{ number_format($activeEmployees ?? 0) }} Active</span>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="stat-card">
        <div class="stat-info">
            <h3>TODAY'S ATTENDANCE</h3>
            <div class="value">{{ number_format($todayAttendances ?? 0) }}</div>
            <div class="flex gap-2 mt-2">
                <span class="status-badge status-success">{{ number_format($todayCheckedIn ?? 0) }} In</span>
                <span class="status-badge status-warning">{{ number_format($todayCheckedOut ?? 0) }} Out</span>
            </div>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3>Recent Activity</h3>
        <a href="{{ route('admin.attendances.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAttendances as $attendance)
                    <tr>
                        <td>{{ $attendance->created_at->format('H:i') }} <small class="text-gray">{{ $attendance->created_at->format('d M') }}</small></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random" class="avatar" style="width: 24px; height: 24px;">
                                <span>{{ $attendance->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($attendance->status == 'checked_in')
                                <i class="fa-solid fa-arrow-right-to-bracket text-success"></i> Check In
                            @else
                                <i class="fa-solid fa-arrow-right-from-bracket text-warning"></i> Check Out
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $attendance->status == 'checked_in' ? 'status-success' : 'status-neutral' }}">
                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray py-4">No recent activity found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
