@extends('layouts.admin')

@section('title', 'Attendance Management')

@section('content')
<div class="bg-white shadow rounded-xl p-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">
            Attendance Records
        </h2>

        <a href="{{ route('admin.attendances.export', request()->query()) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">
            <i class="fa-solid fa-file-excel mr-2"></i>
            Export Data
        </a>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
        <form method="GET"
              action="{{ route('admin.attendances.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Employee -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Employee
                </label>
                <select name="user_id"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                    <option value="all">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <select name="status"
                        class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                    <option value="all">All Status</option>
                    <option value="checked_in"
                        {{ request('status') == 'checked_in' ? 'selected' : '' }}>
                        Checked In
                    </option>
                    <option value="checked_out"
                        {{ request('status') == 'checked_out' ? 'selected' : '' }}>
                        Checked Out
                    </option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Date Range
                </label>
                <div class="flex gap-2">
                    <input type="date"
                           name="start_date"
                           value="{{ request('start_date') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm">

                    <input type="date"
                           name="end_date"
                           value="{{ request('end_date') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                </div>
            </div>

            <!-- Button -->
            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg shadow">
                    <i class="fa-solid fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Employee</th>
                    <th class="px-6 py-3">In</th>
                    <th class="px-6 py-3">Out</th>
                    <th class="px-6 py-3">Work Duration</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ optional($attendance->attendance_date)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random"
                                     class="w-8 h-8 rounded-full">

                                <span class="font-medium text-gray-800">
                                    {{ $attendance->user->name }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-green-600 font-semibold">
                            {{ optional($attendance->check_in_time)->format('H:i') }}
                        </td>

                        <td class="px-6 py-4 text-red-600 font-semibold">
                            {{ $attendance->check_out_time
                                ? $attendance->check_out_time->format('H:i')
                                : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $attendance->getFormattedWorkDuration() ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $attendance->status === 'checked_in'
                                    ? 'bg-green-500 text-white'
                                    : 'bg-gray-500 text-white' }}">
                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.attendances.show', $attendance) }}"
                               class="text-blue-600 hover:underline text-sm font-medium">
                                Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-6 text-center text-gray-500">
                            No attendance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $attendances->withQueryString()->links() }}
    </div>

</div>
@endsection
