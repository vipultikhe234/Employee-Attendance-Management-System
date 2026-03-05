<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create sample locations
        $loc1 = Location::create([
            'location_name' => 'Head Office',
            'latitude' => 51.5074, // example lat for London
            'longitude' => -0.1278, // example lon
            'radius' => 200,
        ]);

        $loc2 = Location::create([
            'location_name' => 'Branch Office',
            'latitude' => 18.5211, // User current IP location lat
            'longitude' => 73.8502, // User current IP location lon
            'radius' => 100000, // Increased radius to encompass IP location variance
        ]);

        // 3. Create sample employees
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'employee1@test.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $emp1 = Employee::create([
            'user_id' => $user1->id,
            'employee_code' => 'EMP-001',
            'designation' => 'Software Engineer',
            'location_id' => $loc1->id,
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'employee2@test.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $emp2 = Employee::create([
            'user_id' => $user2->id,
            'employee_code' => 'EMP-002',
            'designation' => 'Product Manager',
            'location_id' => $loc2->id,
        ]);
        
        // Output info
        $this->command->info('Database seeded successfully with Demo Admin and Employees.');
    }
}
