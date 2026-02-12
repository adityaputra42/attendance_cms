@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="bg-white shadow rounded-xl p-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Monthly Report:
            {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
        </h2>

        <form action="{{ route('admin.attendances.report') }}"
              method="GET"
              class="flex flex-col sm:flex-row gap-3">

            <select name="month"
                    class="rounded-lg border-gray-300 text-sm shadow-sm">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}"
                        {{ request('month', date('n')) == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select name="year"
                    class="rounded-lg border-gray-300 text-sm shadow-sm">
                @foreach(range(date('Y'), date('Y')-2) as $y)
                    <option value="{{ $y }}"
                        {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                <i class="fa-solid fa-sync mr-2"></i>
                Update
            </button>

            <a href="{{ route('admin.attendances.export', request()->all()) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
                <i class="fa-solid fa-file-excel mr-2"></i>
                Download
            </a>
        </form>
    </div>

    <!-- Summary Cards -->
    @php
        $totalEmployees = $users->count();
        $totalPresent = $users->sum('total_attendance');
        $totalPossible = $totalEmployees * $workingDays;
        $completionRate = $totalPossible > 0
            ? ($totalPresent / $totalPossible) * 100
            : 0;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        <div class="p-5 bg-blue-50 rounded-xl border border-blue-100">
            <p class="text-sm text-blue-600 font-medium uppercase mb-1">
                Working Days
            </p>
            <p class="text-3xl font-bold text-gray-800">
                {{ $workingDays }}
            </p>
        </div>

        <div class="p-5 bg-green-50 rounded-xl border border-green-100">
            <p class="text-sm text-green-600 font-medium uppercase mb-1">
                Total Present
            </p>
            <p class="text-3xl font-bold text-gray-800">
                {{ $totalPresent }}
            </p>
        </div>

        <div class="p-5 bg-purple-50 rounded-xl border border-purple-100">
            <p class="text-sm text-purple-600 font-medium uppercase mb-1">
                Total Employees
            </p>
            <p class="text-3xl font-bold text-gray-800">
                {{ $totalEmployees }}
            </p>
        </div>

        <div class="p-5 bg-yellow-50 rounded-xl border border-yellow-100">
            <p class="text-sm text-yellow-600 font-medium uppercase mb-1">
                Completion Rate
            </p>
            <p class="text-3xl font-bold text-gray-800">
                {{ number_format($completionRate, 1) }}%
            </p>
        </div>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm text-left">

            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4 text-center">Working Days</th>
                    <th class="px-6 py-4 text-center">Present</th>
                    <th class="px-6 py-4 text-center">Absent</th>
                    <th class="px-6 py-4 text-center">Attendance %</th>
                    <th class="px-6 py-4 text-center">Completed</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    @php
                        $present = $user->total_attendance;
                        $absent = max(0, $workingDays - $present);
                        $percent = $workingDays > 0
                            ? ($present / $workingDays) * 100
                            : 0;

                        $color = $percent >= 90
                            ? 'text-green-600'
                            : ($percent >= 75
                                ? 'text-yellow-600'
                                : 'text-red-600');
                    @endphp

                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                 class="w-9 h-9 rounded-full">
                            <div>
                                <div class="font-semibold text-gray-800">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ ucfirst($user->role) }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $workingDays }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                {{ $present }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                {{ $absent }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="font-bold {{ $color }}">
                                {{ number_format($percent, 1) }}%
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="text-gray-800 font-medium">
                                {{ $user->completed_attendance }}
                            </div>
                            <div class="text-xs text-gray-400">
                                Checked Out
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6"
                            class="px-6 py-6 text-center text-gray-500">
                            No employees found for this period.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection
