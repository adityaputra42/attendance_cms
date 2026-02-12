@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
<div class="bg-white shadow rounded-xl p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Employee List
        </h2>

        <a href="{{ route('admin.users.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
            <i class="fa-solid fa-plus mr-2"></i>
            Add New Employee
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6">
        <form action="{{ route('admin.users.index') }}"
              method="GET"
              class="flex flex-col md:flex-row gap-3">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by name or email..."
                   class="w-full md:w-1/3 rounded-lg border-gray-300 shadow-sm text-sm">

            <select name="status"
                    class="rounded-lg border-gray-300 text-sm shadow-sm md:w-40">
                <option value="all">All Status</option>
                <option value="active"
                    {{ request('status') == 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive"
                    {{ request('status') == 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm text-left">

            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Contact</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr class="border-t hover:bg-gray-50 transition">

                        <!-- Employee -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
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
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div>{{ $user->email }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $user->phone ?? '-' }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $user->is_active
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>

                                <form action="{{ route('admin.users.toggle-status', $user) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf

                                    <button type="submit"
                                            class="px-3 py-1 rounded-md text-sm
                                            {{ $user->is_active
                                                ? 'bg-red-100 text-red-600 hover:bg-red-200'
                                                : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                        <i class="fa-solid
                                            {{ $user->is_active
                                                ? 'fa-ban'
                                                : 'fa-check' }}">
                                        </i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-8 text-center text-gray-500">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>

</div>
@endsection
