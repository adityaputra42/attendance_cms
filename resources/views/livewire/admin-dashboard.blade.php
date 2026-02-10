<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

new class extends Component
{
    public $totalEmployees;
    public $activeEmployees;
    public $todayAttendances;
    public $todayCheckedIn;
    public $todayCheckedOut;
    public $recentAttendances;
    public $monthlyStats;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalEmployees = User::where('role', 'employee')->count();
        $this->activeEmployees = User::where('role', 'employee')->where('is_active', true)->count();

        $this->todayAttendances = Attendance::whereDate('attendance_date', today())->count();
        $this->todayCheckedIn = Attendance::whereDate('attendance_date', today())
            ->where('status', 'checked_in')
            ->count();
        $this->todayCheckedOut = Attendance::whereDate('attendance_date', today())
            ->where('status', 'checked_out')
            ->count();

        // Get recent attendances
        $this->recentAttendances = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly statistics
        $this->monthlyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Attendance::whereYear('attendance_date', $date->year)
                ->whereMonth('attendance_date', $date->month)
                ->count();

            $this->monthlyStats[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
    }
}; ?>

<div wire:poll.30s="loadStats" class="font-sans p-6">
    <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="text-gray-500 mt-1 text-sm">Overview of your employee attendance.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-2 py-1 rounded-xl shadow-sm border border-gray-100">
            <div class="px-4 py-2 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Date</p>
                <p class="text-sm font-bold text-gray-900">{{ now()->format('d M Y') }}</p>
            </div>
            <div class="px-4 py-2 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Time</p>
                <p class="text-sm font-bold text-gray-900" x-data x-init="setInterval(() => $el.innerText = new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'}), 1000)">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Employees -->
        <div class="bg-white rounded-2xl shadow border border-gray-100 group">
       <div class="m-6 space-y-4">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div class="flex items-center gap-1 text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full text-xs font-semibold">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>Active</span>
            </div>
        </div>

        <div>
            <h3 class="text-4xl font-bold text-gray-900 mb-1">
                {{ number_format($totalEmployees) }}
            </h3>
            <p class="text-gray-500 font-medium">Total Employees</p>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-sm">
            <span class="text-gray-400">Can Check-in</span>
            <span class="font-semibold text-gray-700">
                {{ number_format($activeEmployees) }} Users
            </span>
        </div>
    </div>
</div>


        <!-- Today's Attendance -->
        <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-clipboard-check text-xl"></i>
                </div>
                <div class="flex items-center gap-1 text-purple-600 bg-purple-50 px-2 py-1 rounded-full text-xs font-semibold">
                    <span>Today</span>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-bold text-gray-900 mb-1">{{ number_format($todayAttendances) }}</h3>
                <p class="text-gray-500 font-medium">Checked In Today</p>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 grid grid-cols-2 gap-2 text-sm">
                <div class="text-center p-1 bg-gray-50 rounded">
                    <span class="block text-xs text-gray-400">In</span>
                    <span class="font-bold text-emerald-600">{{ number_format($todayCheckedIn) }}</span>
                </div>
                <div class="text-center p-1 bg-gray-50 rounded">
                    <span class="block text-xs text-gray-400">Out</span>
                    <span class="font-bold text-amber-600">{{ number_format($todayCheckedOut) }}</span>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-chart-pie text-xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-bold text-gray-900 mb-1">
                    {{ $activeEmployees > 0 ? round(($todayAttendances / $activeEmployees) * 100) : 0 }}%
                </h3>
                <p class="text-gray-500 font-medium">Present Rate</p>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50">
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full" style="width: {{ $activeEmployees > 0 ? min(100, ($todayAttendances / $activeEmployees) * 100) : 0 }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2 text-right">Daily Goal: 100%</p>
            </div>
        </div>

         <!-- Monthly Report -->
         <div class="bg-linear-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-white/10 backdrop-blur-sm rounded-xl">
                        <i class="fa-solid fa-file-invoice text-xl text-white"></i>
                    </div>
                </div>
                <div>
                     <p class="text-indigo-200 text-sm font-medium mb-1">Generate Report</p>
                    <h3 class="text-2xl font-bold text-white leading-tight">Monthly Attendance Analysis</h3>
                </div>
                 <button onclick="window.location='{{ route('admin.attendances.report') }}'" class="mt-4 w-full bg-white text-indigo-700 py-2.5 px-4 rounded-xl font-semibold text-sm hover:bg-indigo-50 transition-colors shadow-sm">
                    View Reports
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Live Activity</h3>
                    <p class="text-sm text-gray-500">Real-time attendance monitoring</p>
                </div>
                <a href="{{ route('admin.attendances.index') }}" class="group flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                    View All
                    <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentAttendances as $attendance)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=random" alt="">
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $attendance->created_at->format('H:i') }}</span>
                                        <span class="text-xs text-gray-500">{{ $attendance->created_at->format('d M') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance->status == 'checked_in')
                                        <div class="flex items-center gap-2 text-emerald-600">
                                            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center">
                                                <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium">Check In</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-amber-600">
                                            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center">
                                                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium">Check Out</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $attendance->status == 'checked_in' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $attendance->status == 'checked_in' ? 'On Time' : 'Completed' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="p-4 bg-gray-50 rounded-full mb-3">
                                            <i class="fa-regular fa-clipboard text-3xl"></i>
                                        </div>
                                        <p class="font-medium">No activity recorded today</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Overview Chart -->
        <div class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-gray-100 p-6 flex flex-col h-full">
            <h3 class="font-bold text-gray-900 text-lg mb-6">Attendance Trends</h3>
            <div class="flex-1 space-y-5">
                @foreach($monthlyStats as $stat)
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 font-medium">{{ $stat['month'] }}</span>
                            <span class="text-gray-900 font-bold">{{ $stat['count'] }} <span class="text-xs text-gray-400 font-normal">Check-ins</span></span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000 ease-out hover:bg-indigo-600"
                                style="width: {{ $activeEmployees > 0 ? min(100, ($stat['count'] / ($activeEmployees * 30)) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.settings.index') }}" class="block p-4 bg-linear-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:text-indigo-600 transition-colors">
                            <i class="fa-solid fa-gear text-lg text-gray-400 group-hover:text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">System Settings</h4>
                            <p class="text-xs text-gray-500">Configure shifts & locations</p>
                        </div>
                        <i class="fa-solid fa-chevron-right ml-auto text-gray-300 group-hover:text-indigo-600 transition-colors"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
