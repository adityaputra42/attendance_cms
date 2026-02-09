@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="card">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h3 class="text-xl font-bold">Monthly Report: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h3>
        
        <form action="{{ route('admin.attendances.report') }}" method="GET" class="flex flex-col md:flex-row gap-4 mt-4 md:mt-0">
            <select name="month" class="form-select w-full md:w-40">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month', date('n')) == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>
            
            <select name="year" class="form-select w-full md:w-32">
                @foreach(range(date('Y'), date('Y')-2) as $y)
                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="btn btn-primary whitespace-nowrap">
                <i class="fa-solid fa-sync mr-2"></i> Update View
            </button>
            
            <a href="{{ route('admin.attendances.export', request()->all()) }}" class="btn btn-success whitespace-nowrap">
                <i class="fa-solid fa-file-excel mr-2"></i> Download Report
            </a>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
            <h4 class="text-sm font-medium text-blue-600 uppercase tracking-wider mb-1">Working Days</h4>
            <span class="text-3xl font-bold text-gray-800">{{ $workingDays }}</span>
            <span class="text-sm text-gray-500 ml-1">days</span>
        </div>
        
        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
            <h4 class="text-sm font-medium text-green-600 uppercase tracking-wider mb-1">Total Present</h4>
            <span class="text-3xl font-bold text-gray-800">{{ $users->sum('total_attendance') }}</span>
            <span class="text-sm text-gray-500 ml-1">records</span>
        </div>

        <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
            <h4 class="text-sm font-medium text-purple-600 uppercase tracking-wider mb-1">Total Employees</h4>
            <span class="text-3xl font-bold text-gray-800">{{ $users->count() }}</span>
            <span class="text-sm text-gray-500 ml-1">staff</span>
        </div>
        
        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <h4 class="text-sm font-medium text-yellow-600 uppercase tracking-wider mb-1">Completion Rate</h4>
            @php
                $totalPossible = $users->count() * $workingDays;
                $rate = $totalPossible > 0 ? ($users->sum('total_attendance') / $totalPossible) * 100 : 0;
            @endphp
            <span class="text-3xl font-bold text-gray-800">{{ number_format($rate, 1) }}%</span>
            <span class="text-sm text-gray-500 ml-1">attendance</span>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="table-container overflow-x-auto rounded-lg border border-gray-200">
        <table class="table w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium text-gray-900">Employee Name</th>
                    <th class="px-6 py-3 font-medium text-gray-900 text-center">Working Days</th>
                    <th class="px-6 py-3 font-medium text-gray-900 text-center">Present</th>
                    <th class="px-6 py-3 font-medium text-gray-900 text-center">Absent</th>
                    <th class="px-6 py-3 font-medium text-gray-900 text-center">Attendance %</th>
                    <th class="px-6 py-3 font-medium text-gray-900 text-center">Completed Work</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="w-8 h-8 rounded-full">
                            <div>
                                <div class="font-semibold">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->role }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $workingDays }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 font-semibold text-green-700 bg-green-100 rounded-full">{{ $user->total_attendance }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 font-semibold text-red-700 bg-red-100 rounded-full">{{ max(0, $workingDays - $user->total_attendance) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $percent = $workingDays > 0 ? ($user->total_attendance / $workingDays) * 100 : 0;
                                $color = $percent >= 90 ? 'text-green-600' : ($percent >= 75 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <span class="font-bold {{ $color }}">{{ number_format($percent, 1) }}%</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-gray-700">{{ $user->completed_attendance }}</span>
                            <span class="text-xs text-gray-400 block">Checked Out</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
