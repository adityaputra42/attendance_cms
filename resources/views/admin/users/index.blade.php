@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3>Employee List</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Employee
        </a>
    </div>

    <!-- Search/Filter -->
    <div class="mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            <select name="status" class="form-select" style="width: 150px;">
                <option value="all">Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="avatar" style="width: 32px; height: 32px;">
                                <div>
                                    <div class="font-medium">{{ $user->name }}</div>
                                    <div class="text-sm text-gray">{{ $user->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $user->email }}</div>
                            <div class="text-sm text-gray">{{ $user->phone }}</div>
                        </td>
                        <td>
                            <span class="status-badge {{ $user->is_active ? 'status-success' : 'status-danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-neutral"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-neutral"><i class="fa-solid fa-pencil"></i></a>
                                
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-neutral {{ $user->is_active ? 'text-danger' : 'text-success' }}" onclick="return confirm('Are you sure?')">
                                        <i class="fa-solid {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
