<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
        ]);
        $admin->assignRole('Admin');

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => Hash::make('manager'),
        ]);
        $manager->assignRole('Manager');

        $viewer = User::create([
            'name' => 'Viewer',
            'email' => 'viewer@test.com',
            'password' => Hash::make('viewer'),
        ]);
        $viewer->assignRole('Viewer');
    }
}
