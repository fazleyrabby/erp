<?php

namespace App\Http\Controllers\Admin\PayRoll\Attendence;

use App\Http\Controllers\Controller;
use App\Models\payroll\OurTeam;
use App\Models\payRoll\PayrollAttendence;
use App\Models\payroll\TimeScheduleGroup;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendenceController extends Controller
{
    public function index()
    {

        $attendences = DB::table('tbl_payroll_attendences')
            ->join('our_teams', 'tbl_payroll_attendences.employee_id', '=', 'our_teams.id')
            ->select('tbl_payroll_attendences.*', 'our_teams.member_name')
            ->orderBy('tbl_payroll_attendences.id', 'DESC')
            ->get();
        $teams = OurTeam::where('deleted', '=', 'No')->get();

        return view('admin.payroll.Attendence.attendenceAdd', ['teams' => $teams, 'attendences' => $attendences]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'employee_id' => 'required',
        ]);

        $ourteams = OurTeam::find($request->employee_id);

        $check = PayrollAttendence::where('employee_id', '=', $request->employee_id)->where('date', '=', $request->date)->first();

        $workHour = '';
        if ($check) {
            $attendences = PayrollAttendence::where('employee_id', '=', $request->employee_id)
                ->where('date', '=', $request->date)
                ->first();
            $attendences->time_out = date('h:ia');
            $origin1 = new DateTime($attendences->time_in);
            $target1 = new DateTime($attendences->time_out);
            $interval1 = $origin1->diff($target1);
            $workHour = $interval1->format('%H:%I');
            $attendences->working_hour = $workHour;
            $attendences->last_updated_by = Auth::user()->id;
            $attendences->month_year = $request->month_year;
            $attendences->group_id = $ourteams->group_id;
            $result = $attendences->save();
            $lastId = $attendences->id;

            $hour = substr($workHour, 0, -3);

            $shiftstatus = '';
            if ($hour >= $ourteams->working_hour) {
                $shiftstatus = 'Completed';
            } else {
                $shiftstatus = 'Incomlete';
            }

            $shiftStatus = PayrollAttendence::find($lastId);
            $shiftStatus->shift_status = $shiftstatus;
            $shiftStatus->save();

            $groupTimeSchedule = TimeScheduleGroup::where('group_id', '=', $ourteams->group_id)->first();
            // return $groupTimeSchedule->time_to;
            $symbolOut = '';
            $originOut = new DateTime($groupTimeSchedule->time_to);
            $targetOut = new DateTime($attendences->time_out);
            if ($originOut > $targetOut) {
                $symbolOut = 'Early';
            } else {
                $symbolOut = 'Late';
            }
            $intervalOut = $originOut->diff($targetOut);
            $time_out_difference = $intervalOut->format('%H:%I');

            $lateEarly = PayrollAttendence::find($lastId);
            $lateEarly->time_out_status = $symbolOut;
            $lateEarly->time_out_difference = $time_out_difference;
            $lateEarly->save();

            return redirect()->route('attendenceIndex')->with('message', 'Departure time taken successfully');
        } else {

            $employees = OurTeam::find($request->employee_id);
            $attendence = new PayrollAttendence;
            $attendence->employee_id = $request->employee_id;
            $attendence->month_year = $request->month_year;
            $attendence->group_id = $employees->group_id;

            $groupTimeSchedule = TimeScheduleGroup::where('group_id', '=', $ourteams->group_id)->first();
            $symbolIn = '';
            $originIn = new DateTime($groupTimeSchedule->time_from);
            $targetIn = new DateTime(date('h:ia'));
            if ($originIn > $targetIn) {
                $symbolIn = 'Early';
            } else {
                $symbolIn = 'Late';
            }
            $intervalIn = $originIn->diff($targetIn);
            $time_in_difference = $intervalIn->format('%H:%I');

            $attendence->time_in_status = $symbolIn;
            $attendence->time_in_difference = $time_in_difference;
            $attendence->time_in = date('h:ia');
            $attendence->date = date('Y-m-d');

            $attendence->created_by = Auth::user()->id;
            $attendence->deleted = 'No';
            $attendence->status = 'Active';
            $result = $attendence->save();

            return redirect()->route('attendenceIndex')->with('message', 'Attendence taken Successfully');
        }
    }

    public function getEntryData(Request $request)
    {

        $attendences = PayrollAttendence::where('employee_id', '=', $request->employee_id)->where('date', '=', $request->date)->first();

        $data = '';

        $data .= $attendences->time_in;

        return $data;
    }

    public function monthlyAttendence()
    {
        $attendences = DB::table('tbl_payroll_attendences')
            ->join('our_teams', 'tbl_payroll_attendences.employee_id', '=', 'our_teams.id')
            ->select('tbl_payroll_attendences.*', 'our_teams.member_name')
            ->orderBy('tbl_payroll_attendences.id', 'DESC')
            ->get();
        $teams = OurTeam::where('deleted', '=', 'No')->get();

        return view('admin.payroll.Attendence.checkMonthlyAttendence', ['attendences' => $attendences, 'teams' => $teams]);
    }

    public function getMonthlyAttendence(Request $request)
    {
        $attendences = DB::table('tbl_payroll_attendences')
            ->join('our_teams', 'tbl_payroll_attendences.employee_id', '=', 'our_teams.id')
            ->select('tbl_payroll_attendences.*', 'our_teams.member_name')
            ->orderBy('tbl_payroll_attendences.id', 'DESC')
            ->where('tbl_payroll_attendences.employee_id', '=', $request->employee_id)
            ->where('tbl_payroll_attendences.date', '>=', $request->date_from)
            ->where('tbl_payroll_attendences.date', '<=', $request->date_to)
            ->get();
        $employee = OurTeam::find($request->employee_id);
        $sl = 1;
        $color = '';
        $incolor = '';
        $outcolor = '';
        $data = '';
        $header = '';

        $header = '<h3 style=" float: left;" >'.$employee->member_name.' '.'Attendence List</h3>';
        $data .= '<thead>
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th width="20%">Employee Name</th>
                                        <th width="10%">Date</th>                                     
                                        <th width="15%">Working Hours</th>
                                        <th width="15%">Entry Time</th>
                                        <th width="15%">Departure Time</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Machine ID</th>
                                    </tr>
                                </thead>
                                <tbody >';

        foreach ($attendences as $attendence) {
            if ($attendence->shift_status == 'Completed') {
                $color = 'text-success';
            } else {
                $color = 'text-danger';
            }

            if ($attendence->time_in_status == 'Late') {
                $incolor = 'text-danger';
            } else {
                $incolor = 'text-success';
            }

            if ($attendence->time_out_status == 'Early') {
                $outcolor = 'text-danger';
            } else {
                $outcolor = 'text-success';
            }

            $data .= '<tr>
                                <td class="text-center">'.$sl++.'</td>
                                <td>'.$attendence->member_name.'</td>
                                <td class="text-center">'.$attendence->date.'</td>
                                <td style="text-align:center;">'.$attendence->working_hour.'</td>
                                <td class="text-center">'.$attendence->time_in.'('.$attendence->time_in_difference.'<span class="'.$incolor.'">'.$attendence->time_in_status.')</span>)</td>
                                <td class="text-center">'.$attendence->time_out.' ('.$attendence->time_out_difference.' <span class="'.$outcolor.'">'.$attendence->time_out_status.'</span>)</td>
                                <td class="'.$color.' text-center" >'.$attendence->shift_status.'</td>
                                <td></td>
                            </tr>';

        }
        $data .= ' </tbody>';

        $array = [
            'header' => $header,
            'data' => $data,
        ];

        return $array;
    }
}
