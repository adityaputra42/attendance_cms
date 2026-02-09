@extends('layouts.admin')

@section('title', 'Add Employee')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3>Create New Employee</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-neutral"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Confirmation</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Profile Picture (Optional)</label>
                <input type="file" name="avatar" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    Active
                </label>
            </div>
        </div>

        <div class="text-right mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Employee</button>
        </div>
    </form>
</div>
@endsection
