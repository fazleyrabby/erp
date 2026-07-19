<?php

namespace Database\Seeders;

use App\Models\payroll\Facility;
use App\Models\payroll\FinalSalarySheet;
use App\Models\payroll\Grade;
use App\Models\payroll\Group;
use App\Models\payroll\LeaveBalance;
use App\Models\Payroll\MonthlyAmount;
use App\Models\payroll\OurTeam;
use App\Models\payroll\PayrollSetting;
use App\Models\payroll\SalaryInstruction;
use App\Models\payroll\SalaryLoan;
use App\Models\payroll\SalaryLoanDetails;
use App\Models\payroll\SalarySheet;
use App\Models\payroll\SavedSalarySheet;
use App\Models\payroll\Steps;
use App\Models\payroll\TimeScheduleGroup;
use App\Models\payroll\UserTimeSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        $grades = [
            ['grade_name' => 'Grade 1', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['grade_name' => 'Grade 2', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['grade_name' => 'Grade 3', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];
        foreach ($grades as $g) {
            Grade::firstOrCreate(['grade_name' => $g['grade_name']], $g);
        }

        $gradeIds = Grade::pluck('id', 'grade_name');

        $stepsData = [
            ['step_name' => 'Step 1', 'grade_id' => $gradeIds['Grade 1'], 'salary_amount' => 25000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['step_name' => 'Step 2', 'grade_id' => $gradeIds['Grade 1'], 'salary_amount' => 30000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['step_name' => 'Step 1', 'grade_id' => $gradeIds['Grade 2'], 'salary_amount' => 35000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['step_name' => 'Step 2', 'grade_id' => $gradeIds['Grade 2'], 'salary_amount' => 42000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['step_name' => 'Step 1', 'grade_id' => $gradeIds['Grade 3'], 'salary_amount' => 50000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];
        foreach ($stepsData as $s) {
            Steps::firstOrCreate(
                ['step_name' => $s['step_name'], 'grade_id' => $s['grade_id']],
                $s
            );
        }

        $groups = [
            ['name' => 'Management', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Staff', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['name' => 'Workers', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];
        foreach ($groups as $g) {
            Group::firstOrCreate(['name' => $g['name']], $g);
        }

        $groupIds = Group::pluck('id', 'name');

        $facilities = [
            ['facility_name' => 'House Rent', 'group_id' => $groupIds['Staff'], 'amount' => 8000, 'status' => 'Active', 'deleted' => 'No'],
            ['facility_name' => 'Medical Allowance', 'group_id' => $groupIds['Staff'], 'amount' => 1500, 'status' => 'Active', 'deleted' => 'No'],
            ['facility_name' => 'Transport Allowance', 'group_id' => $groupIds['Staff'], 'amount' => 2000, 'status' => 'Active', 'deleted' => 'No'],
            ['facility_name' => 'House Rent', 'group_id' => $groupIds['Management'], 'amount' => 15000, 'status' => 'Active', 'deleted' => 'No'],
            ['facility_name' => 'Medical Allowance', 'group_id' => $groupIds['Management'], 'amount' => 3000, 'status' => 'Active', 'deleted' => 'No'],
            ['facility_name' => 'House Rent', 'group_id' => $groupIds['Workers'], 'amount' => 4000, 'status' => 'Active', 'deleted' => 'No'],
        ];
        foreach ($facilities as $f) {
            Facility::firstOrCreate(
                ['facility_name' => $f['facility_name'], 'group_id' => $f['group_id']],
                $f
            );
        }

        $gradeIdMap = Grade::pluck('id', 'grade_name');
        $step1g1 = Steps::where('step_name', 'Step 1')->where('grade_id', $gradeIdMap['Grade 1'])->first();
        $step2g1 = Steps::where('step_name', 'Step 2')->where('grade_id', $gradeIdMap['Grade 1'])->first();
        $step1g2 = Steps::where('step_name', 'Step 1')->where('grade_id', $gradeIdMap['Grade 2'])->first();
        $step2g2 = Steps::where('step_name', 'Step 2')->where('grade_id', $gradeIdMap['Grade 2'])->first();
        $step1g3 = Steps::where('step_name', 'Step 1')->where('grade_id', $gradeIdMap['Grade 3'])->first();

        $employees = [
            ['member_name' => 'John Doe (Employee)', 'member_desingnation' => 'Junior Staff', 'group_id' => $groupIds['Staff'], 'current_grade' => $gradeIdMap['Grade 1'], 'current_step' => $step1g1 ? $step1g1->id : null, 'salary' => 25000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['member_name' => 'Jane Smith', 'member_desingnation' => 'Senior Staff', 'group_id' => $groupIds['Staff'], 'current_grade' => $gradeIdMap['Grade 2'], 'current_step' => $step1g2 ? $step1g2->id : null, 'salary' => 35000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['member_name' => 'Alice Johnson', 'member_desingnation' => 'Manager', 'group_id' => $groupIds['Management'], 'current_grade' => $gradeIdMap['Grade 3'], 'current_step' => $step1g3 ? $step1g3->id : null, 'salary' => 50000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['member_name' => 'Bob Williams', 'member_desingnation' => 'Worker', 'group_id' => $groupIds['Workers'], 'current_grade' => $gradeIdMap['Grade 1'], 'current_step' => $step2g1 ? $step2g1->id : null, 'salary' => 30000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['member_name' => 'Charlie Brown', 'member_desingnation' => 'Worker', 'group_id' => $groupIds['Workers'], 'current_grade' => $gradeIdMap['Grade 1'], 'current_step' => $step1g1 ? $step1g1->id : null, 'salary' => 25000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
            ['member_name' => 'Diana Prince', 'member_desingnation' => 'Accountant', 'group_id' => $groupIds['Staff'], 'current_grade' => $gradeIdMap['Grade 2'], 'current_step' => $step2g2 ? $step2g2->id : null, 'salary' => 42000, 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1],
        ];

        $user = User::where('email', 'employee@demo.com')->first();

        foreach ($employees as $emp) {
            $team = OurTeam::firstOrCreate(
                ['member_name' => $emp['member_name']],
                $emp
            );
            if ($team->member_name === 'John Doe (Employee)' && $user && !$team->user_id) {
                $team->update(['user_id' => $user->id]);
            }
        }

        $leaveBalances = [
            ['leave_type' => 'Casual Leave', 'total_days' => 10],
            ['leave_type' => 'Medical Leave', 'total_days' => 14],
            ['leave_type' => 'Earn Leave', 'total_days' => 15],
            ['leave_type' => 'Duty Leave', 'total_days' => 5],
        ];

        $employees_all = OurTeam::where('deleted', 'No')->get();
        $year = date('Y');

        foreach ($employees_all as $employee) {
            foreach ($leaveBalances as $bal) {
                LeaveBalance::firstOrCreate(
                    ['employee_id' => $employee->id, 'leave_type' => $bal['leave_type'], 'year' => $year],
                    ['total_days' => $bal['total_days'], 'used_days' => 0]
                );
            }
        }

        PayrollSetting::firstOrCreate(
            ['id' => 1],
            ['status' => 'Active', 'deleted' => 'No']
        );

        SalarySheet::firstOrCreate(
            ['sheet_name' => 'Monthly Salary - ' . date('F Y')],
            ['status' => 'Active', 'deleted' => 'No', 'created_by' => 1]
        );

        TimeScheduleGroup::firstOrCreate(
            ['group_id' => $groupIds['Staff'], 'time_from' => '09:00', 'time_to' => '17:00'],
            ['working_hour' => '8', 'status' => 'Active', 'deleted' => 'No', 'created_by' => 1]
        );

        $scheduleGroup = TimeScheduleGroup::where('group_id', $groupIds['Staff'])->first();
        if ($scheduleGroup) {
            foreach ($employees_all as $employee) {
                UserTimeSchedule::firstOrCreate(
                    ['employee_id' => $employee->id, 'schedule_group_id' => $scheduleGroup->id],
                    ['status' => 'Active', 'deleted' => 'No', 'created_by' => 1]
                );
            }
        }
    }
}
