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
        $admin = User::where('email', 'super.admin@gmail.com')->first();
        if (is_null($admin)) {
            $admin = new User;
            $admin->name = 'Super Admin';
            $admin->email = 'super.admin@gmail.com';
            $admin->password = Hash::make('12345678');
            $admin->role = 'Super Admin';
            $admin->save();
            $admin->assignRole('Super Admin');
        }

        $empUser = User::where('email', 'employee@demo.com')->first();
        if (is_null($empUser)) {
            $empUser = new User;
            $empUser->name = 'John Doe (Employee)';
            $empUser->email = 'employee@demo.com';
            $empUser->password = Hash::make('12345678');
            $empUser->role = 'Employee';
            $empUser->save();
            $empUser->assignRole('Employee');

            $team = new \App\Models\payroll\OurTeam;
            $team->member_name = $empUser->name;
            $team->user_id = $empUser->id;
            $team->status = 'Active';
            $team->deleted = 'No';
            $team->save();
        }
    }
}
