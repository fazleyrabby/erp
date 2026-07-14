<?php

namespace App\Http\Controllers\Admin\PayRoll\Attendence;

use App\Http\Controllers\Controller;
use App\Models\payroll\OurTeam;
use App\Models\payroll\PayrollAttendence;
use App\Models\payroll\TimeScheduleGroup;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myAttendance(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found for your account.');
        }

        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $attendences = PayrollAttendence::forEmployee($employee->id)
            ->forMonth($year, $month)
            ->orderBy('date', 'DESC')
            ->get();

        $presentDays = $attendences->where('time_in', '!=', null)->count();
        $totalWorkingDays = now()->startOfMonth()->diffInDays(now()->endOfMonth()) + 1;
        $todayAttendence = PayrollAttendence::forEmployee($employee->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        return view('admin.payroll.Attendence.employee-my-attendance', compact(
            'attendences', 'employee', 'year', 'month',
            'presentDays', 'totalWorkingDays', 'todayAttendence'
        ));
    }

    public function clockIn(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $existing = PayrollAttendence::where('employee_id', $employee->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        $groupTimeSchedule = TimeScheduleGroup::where('group_id', $employee->group_id)->first();
        $symbolIn = 'Early';
        $timeInDiff = '00:00';

        if ($groupTimeSchedule) {
            $originIn = new DateTime($groupTimeSchedule->time_from);
            $targetIn = new DateTime(now()->format('h:ia'));
            if ($originIn <= $targetIn) {
                $symbolIn = 'Late';
            }
            $intervalIn = $originIn->diff($targetIn);
            $timeInDiff = $intervalIn->format('%H:%I');
        }

        PayrollAttendence::create([
            'employee_id' => $employee->id,
            'date' => now()->format('Y-m-d'),
            'month_year' => now()->format('Y-m'),
            'group_id' => $employee->group_id,
            'time_in' => now()->format('h:ia'),
            'time_in_status' => $symbolIn,
            'time_in_difference' => $timeInDiff,
            'created_by' => Auth::id(),
            'deleted' => 'No',
            'status' => 'Active',
        ]);

        return redirect()->route('employee.my-attendance')->with('success', 'Clocked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $attendence = PayrollAttendence::where('employee_id', $employee->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if (!$attendence) {
            return back()->with('error', 'You have not clocked in today.');
        }

        if ($attendence->time_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $attendence->time_out = now()->format('h:ia');

        $origin1 = new DateTime($attendence->time_in);
        $target1 = new DateTime($attendence->time_out);
        $interval1 = $origin1->diff($target1);
        $workHour = $interval1->format('%H:%I');
        $attendence->working_hour = $workHour;

        $hour = (int) substr($workHour, 0, 2);
        $attendence->shift_status = ($hour >= ($employee->working_hour ?? 8)) ? 'Completed' : 'Incomlete';

        $groupTimeSchedule = TimeScheduleGroup::where('group_id', $employee->group_id)->first();
        if ($groupTimeSchedule) {
            $originOut = new DateTime($groupTimeSchedule->time_to);
            $targetOut = new DateTime($attendence->time_out);
            $symbolOut = ($originOut > $targetOut) ? 'Early' : 'Late';
            $intervalOut = $originOut->diff($targetOut);
            $attendence->time_out_status = $symbolOut;
            $attendence->time_out_difference = $intervalOut->format('%H:%I');
        }

        $attendence->last_updated_by = Auth::id();
        $attendence->save();

        return redirect()->route('employee.my-attendance')->with('success', 'Clocked out successfully.');
    }
}
