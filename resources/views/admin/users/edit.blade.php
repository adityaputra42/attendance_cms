@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="bg-base-100 p-6 rounded-xl shadow">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">
            Edit User: {{ $user->name }}
        </h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-neutral">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-6">

            {{-- Full Name --}}
            <div>
                <label class="label">
                    <span class="label-text">Full Name</span>
                </label>
                <input type="text"
                       name="name"
                       class="input input-bordered w-full"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            {{-- Email --}}
            <div>
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email"
                       name="email"
                       class="input input-bordered w-full"
                       value="{{ old('email', $user->email) }}"
                       required>
            </div>

            {{-- Phone --}}
            <div>
                <label class="label">
                    <span class="label-text">Phone</span>
                </label>
                <input type="text"
                       name="phone"
                       class="input input-bordered w-full"
                       value="{{ old('phone', $user->phone) }}">
            </div>

            {{-- Role --}}
            <div>
                <label class="label">
                    <span class="label-text">Role</span>
                </label>
                <select name="role" class="select select-bordered w-full">
                    <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>
                        Employee
                    </option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>

            {{-- Password --}}
            <div>
                <label class="label">
                    <span class="label-text">Password (Leave blank to keep current)</span>
                </label>
                <input type="password"
                       name="password"
                       class="input input-bordered w-full">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="label">
                    <span class="label-text">Confirm Password</span>
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="input input-bordered w-full">
            </div>

            {{-- Address --}}
            <div class="md:col-span-2">
                <label class="label">
                    <span class="label-text">Address</span>
                </label>
                <textarea name="address"
                          rows="3"
                          class="textarea textarea-bordered w-full">{{ old('address', $user->address) }}</textarea>
            </div>

            {{-- Status --}}
            <div>
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="toggle toggle-primary"
                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <span class="ml-2">Active Account</span>
            </div>

            {{-- Avatar --}}
            <div>
                <label class="label">
                    <span class="label-text">Avatar</span>
                </label>

                @if($user->avatar)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$user->avatar) }}"
                             alt="Current Avatar"
                             class="w-24 h-24 object-cover rounded-lg shadow">
                    </div>
                @endif

                <input type="file"
                       name="avatar"
                       class="file-input file-input-bordered w-full">
            </div>

        </div>

        <div class="mt-8 text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update User
            </button>
        </div>

    </form>
</div>
@endsection
