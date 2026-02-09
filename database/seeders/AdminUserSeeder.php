<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@attendance.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'admin',
            'is_active' => true,
            'address' => 'Jakarta, Indonesia',
        ]);

        // Create sample employee users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@attendance.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'role' => 'employee',
            'is_active' => true,
            'address' => 'Jakarta, Indonesia',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@attendance.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'role' => 'employee',
            'is_active' => true,
            'address' => 'Jakarta, Indonesia',
        ]);

        $this->command->info('Admin and sample users created successfully!');
        $this->command->info('Admin: admin@attendance.com / password');
        $this->command->info('Employee: john@attendance.com / password');
        $this->command->info('Employee: jane@attendance.com / password');
    }
}
