


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md flex flex-col">

        <div class="p-6 font-bold text-xl border-b">
            Admin Panel
        </div>

        <nav class="p-4 space-y-2 flex-1">

            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded-lg transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-medium' : 'hover:bg-gray-100' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="block px-4 py-2 rounded-lg transition
               {{ request()->routeIs('admin.users.*') ? 'bg-gray-200 font-medium' : 'hover:bg-gray-100' }}">
                Employees
            </a>

            <a href="{{ route('admin.attendances.index') }}"
               class="block px-4 py-2 rounded-lg transition
               {{ request()->routeIs('admin.attendances.*') ? 'bg-gray-200 font-medium' : 'hover:bg-gray-100' }}">
                Attendance
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="block px-4 py-2 rounded-lg transition
               {{ request()->routeIs('admin.settings.*') ? 'bg-gray-200 font-medium' : 'hover:bg-gray-100' }}">
                Settings
            </a>

        </nav>

        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-50 text-red-600">
                    Logout
                </button>
            </form>
        </div>

    </aside>


    {{-- Main Content --}}
    <main class="flex-1 flex flex-col">

        {{-- Header --}}
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h2 class="font-semibold text-lg">
                @yield('title', 'Dashboard')
            </h2>

            <div class="text-sm text-gray-600">
                {{ auth()->user()?->name }}
            </div>
        </header>

        {{-- Page Content --}}
        <div class="p-6 flex-1">
            @yield('content')
        </div>

    </main>

</div>

@livewireScripts
</body>
</html>
