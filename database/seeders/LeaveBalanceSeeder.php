<?php

namespace Database\Seeders;

use App\Models\payroll\LeaveBalance;
use App\Models\payroll\OurTeam;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Link existing employees to users by matching name
        $users = User::all();
        foreach ($users as $user) {
            $employee = OurTeam::where('member_name', $user->name)->first();
            if ($employee && !$employee->user_id) {
                $employee->update(['user_id' => $user->id]);
            }
        }

        $year = date('Y');
        $employees = OurTeam::where('deleted', 'No')->get();

        $defaultBalances = [
            ['leave_type' => 'Casual Leave', 'total_days' => 10],
            ['leave_type' => 'Medical Leave', 'total_days' => 14],
            ['leave_type' => 'Earn Leave', 'total_days' => 15],
            ['leave_type' => 'Duty Leave', 'total_days' => 5],
        ];

        foreach ($employees as $employee) {
            foreach ($defaultBalances as $bal) {
                LeaveBalance::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'leave_type' => $bal['leave_type'],
                        'year' => $year,
                    ],
                    [
                        'total_days' => $bal['total_days'],
                        'used_days' => 0,
                    ]
                );
            }
        }

        $this->command->info('Leave balances seeded successfully.');
    }
}
