@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="bg-base-100 p-6 rounded-xl shadow">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">
            Employee: {{ $user->name }}
        </h2>

        <a href="{{ route('admin.users.index') }}" class="btn btn-neutral btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Profile Section -->
    <div class="grid md:grid-cols-2 gap-8">

        <!-- Left Column -->
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">Full Name</p>
                <p class="text-lg font-medium">{{ $user->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email Address</p>
                <p class="text-lg font-medium">{{ $user->email }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Phone Number</p>
                <p class="text-lg font-medium">{{ $user->phone ?? '-' }}</p>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">

            <div>
                <p class="text-sm text-gray-500 mb-1">Role</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $user->role == 'admin'
                        ? 'bg-yellow-100 text-yellow-700'
                        : 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Status</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $user->is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500">Joined Date</p>
                <p class="text-lg font-medium">
                    {{ $user->created_at->format('d M Y') }}
                </p>
            </div>

        </div>

    </div>

    <!-- Attendance History -->
    <div class="mt-10">

        <h3 class="text-lg font-semibold mb-4">
            Recent Attendance
        </h3>

        <div class="overflow-x-auto border rounded-xl">
            <table class="min-w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Check In</th>
                        <th class="px-6 py-4">Check Out</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="px-6 py-4">
                                {{ $attendance->attendance_date->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-green-600 font-medium">
                                {{ $attendance->check_in_time->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-red-600 font-medium">
                                {{ $attendance->check_out_time
                                    ? $attendance->check_out_time->format('H:i')
                                    : '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $attendance->getFormattedWorkDuration() ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $attendance->status == 'checked_in'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-8 text-center text-gray-500">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>

    </div>

</div>
@endsection
