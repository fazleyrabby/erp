<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\payroll\OurTeam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'super.admin@demo.com')->first();
        if (is_null($admin)) {
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'super.admin@demo.com',
                'password' => Hash::make('12345678'),
                'role' => 'Super Admin',
                'status' => 'Active',
                'deleted' => 'No',
            ]);
            $admin->assignRole('Super Admin');
        }

        $manager = User::where('email', 'manager@demo.com')->first();
        if (is_null($manager)) {
            $manager = User::create([
                'name' => 'Jane Manager',
                'email' => 'manager@demo.com',
                'password' => Hash::make('12345678'),
                'role' => 'Manager',
                'status' => 'Active',
                'deleted' => 'No',
            ]);
            $manager->assignRole('Manager');
        }

        $salesman = User::where('email', 'sales@demo.com')->first();
        if (is_null($salesman)) {
            $salesman = User::create([
                'name' => 'Bob Sales',
                'email' => 'sales@demo.com',
                'password' => Hash::make('12345678'),
                'role' => 'Sales Man',
                'status' => 'Active',
                'deleted' => 'No',
            ]);
            $salesman->assignRole('Sales Man');
        }

        $employee = User::where('email', 'employee@demo.com')->first();
        if (is_null($employee)) {
            $employee = User::create([
                'name' => 'John Doe (Employee)',
                'email' => 'employee@demo.com',
                'password' => Hash::make('12345678'),
                'role' => 'Employee',
                'status' => 'Active',
                'deleted' => 'No',
            ]);
            $employee->assignRole('Employee');
        }
    }
}
