@extends('layouts.admin')

@section('title', 'Attendance Management')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold">Attendance Records</h3>
        <a href="{{ route('admin.attendances.export', request()->query()) }}" class="btn btn-success">
            <i class="fa-solid fa-file-excel mr-2"></i> Export Data
        </a>
    </div>

    <!-- Search/Filter -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <form action="{{ route('admin.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                <select name="user_id" class="form-select w-full rounded-md border-gray-300 shadow-sm">
                    <option value="all">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-select w-full rounded-md border-gray-300 shadow-sm">
                    <option value="all">All Status</option>
                    <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <div class="flex space-x-2">
                    <input type="date" name="start_date" class="form-control w-full rounded-md border-gray-300 shadow-sm" value="{{ request('start_date') }}">
                    <span class="self-center">-</span>
                    <input type="date" name="end_date" class="form-control w-full rounded-md border-gray-300 shadow-sm" value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa-solid fa-filter mr-2"></i> Filter Records
                </button>
            </div>
        </form>
    </div>

    <div class="table-container overflow-x-auto">
        <table class="table w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Employee</th>
                    <th scope="col" class="px-6 py-3">In</th>
                    <th scope="col" class="px-6 py-3">Out</th>
                    <th scope="col" class="px-6 py-3">Work Duration</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $attendance->attendance_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random" class="w-8 h-8 rounded-full">
                                <div class="font-medium text-gray-900">{{ $attendance->user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-green-600 font-semibold">
                            {{ $attendance->check_in_time->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-semibold">
                            {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $attendance->getFormattedWorkDuration() ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 font-semibold leading-tight text-white rounded-full {{ $attendance->status == 'checked_in' ? 'bg-green-500' : 'bg-gray-500' }}">
                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.attendances.show', $attendance) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No attendance records found with current filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
