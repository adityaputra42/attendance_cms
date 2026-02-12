@extends('layouts.admin')

@section('title', 'Add Employee')

@section('content')
<div class="bg-base-100 p-6 rounded-xl shadow">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">
            Create New Employee
        </h2>

        <a href="{{ route('admin.users.index') }}" class="btn btn-neutral btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">

            {{-- Full Name --}}
            <div>
                <label class="label">
                    <span class="label-text">Full Name</span>
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="input input-bordered w-full @error('name') input-error @enderror"
                       required>

                @error('name')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="input input-bordered w-full @error('email') input-error @enderror"
                       required>

                @error('email')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="label">
                    <span class="label-text">Password</span>
                </label>
                <input type="password"
                       name="password"
                       class="input input-bordered w-full @error('password') input-error @enderror"
                       required>

                @error('password')
                    <span class="text-error text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="label">
                    <span class="label-text">Password Confirmation</span>
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="input input-bordered w-full"
                       required>
            </div>

            {{-- Phone --}}
            <div>
                <label class="label">
                    <span class="label-text">Phone Number</span>
                </label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       class="input input-bordered w-full">
            </div>

            {{-- Role --}}
            <div>
                <label class="label">
                    <span class="label-text">Role</span>
                </label>
                <select name="role"
                        class="select select-bordered w-full">
                    <option value="employee"
                        {{ old('role') == 'employee' ? 'selected' : '' }}>
                        Employee
                    </option>
                    <option value="admin"
                        {{ old('role') == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>

            {{-- Avatar --}}
            <div>
                <label class="label">
                    <span class="label-text">Profile Picture (Optional)</span>
                </label>
                <input type="file"
                       name="avatar"
                       class="file-input file-input-bordered w-full">
            </div>

            {{-- Address --}}
            <div>
                <label class="label">
                    <span class="label-text">Address</span>
                </label>
                <textarea name="address"
                          rows="3"
                          class="textarea textarea-bordered w-full">{{ old('address') }}</textarea>
            </div>

            {{-- Status --}}
            <div class="md:col-span-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="toggle toggle-primary"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="label-text">Active Account</span>
                </label>
            </div>

        </div>

        <div class="mt-8 text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Save Employee
            </button>
        </div>

    </form>
</div>
@endsection
