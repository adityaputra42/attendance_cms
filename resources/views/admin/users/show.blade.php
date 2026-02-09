@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3>Employee: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-neutral"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Full Name</label>
                <p class="text-lg">{{ $user->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Email Address</label>
                <p class="text-lg">{{ $user->email }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Phone Number</label>
                <p class="text-lg">{{ $user->phone ?? '-' }}</p>
            </div>
        </div>
        <div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Role</label>
                <span class="status-badge {{ $user->role == 'admin' ? 'status-warning' : 'status-neutral' }}">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Status</label>
                <span class="status-badge {{ $user->is_active ? 'status-success' : 'status-danger' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-500 text-sm font-bold mb-2">Joined Date</label>
                <p class="text-lg">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="mt-8">
        <h4 class="mb-4 text-xl font-bold">Recent Attendance</h4>
        <div class="table-container">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="border-b px-4 py-2 text-left">Date</th>
                        <th class="border-b px-4 py-2 text-left">Check In</th>
                        <th class="border-b px-4 py-2 text-left">Check Out</th>
                        <th class="border-b px-4 py-2 text-left">Duration</th>
                        <th class="border-b px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-100">
                            <td class="border-b px-4 py-2">{{ $attendance->attendance_date->format('d M Y') }}</td>
                            <td class="border-b px-4 py-2 text-green-600 font-medium">{{ $attendance->check_in_time->format('H:i') }}</td>
                            <td class="border-b px-4 py-2 text-red-600 font-medium">{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                            <td class="border-b px-4 py-2">{{ $attendance->getFormattedWorkDuration() ?? '-' }}</td>
                            <td class="border-b px-4 py-2">
                                <span class="status-badge {{ $attendance->status == 'checked_in' ? 'status-success' : 'status-neutral' }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border-b px-4 py-2 text-center text-gray-500">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $attendances->links() }} 
        </div>
    </div>
</div>
@endsection
