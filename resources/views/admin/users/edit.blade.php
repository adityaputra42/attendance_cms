@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3>Edit User: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-neutral"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active Account</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Avatar</label>
                @if($user->avatar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Current Avatar" class="img-thumbnail" style="height: 100px;">
                    </div>
                @endif
                <input type="file" name="avatar" class="form-control">
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update User</button>
        </div>
    </form>
</div>
@endsection
