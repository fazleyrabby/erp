<?php

namespace App\Http\Controllers\Admin\PayRoll\Group;

use App\Http\Controllers\Controller;
use App\Models\payroll\OurTeam;
use App\Models\payroll\UserTimeSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserTimeScheduleController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'tbl_payroll_user_time_schedules.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('tbl_payroll_user_time_schedules')
            ->join('tbl_payroll_time_schedule_groups', 'tbl_payroll_user_time_schedules.schedule_group_id', '=', 'tbl_payroll_time_schedule_groups.id')
            ->join('our_teams', 'tbl_payroll_user_time_schedules.employee_id', '=', 'our_teams.id')
            ->leftjoin('groups', 'tbl_payroll_time_schedule_groups.group_id', '=', 'groups.id')
            ->select('tbl_payroll_user_time_schedules.*', 'groups.name as groupName', 'our_teams.member_name')
            ->where('tbl_payroll_user_time_schedules.deleted', '=', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('our_teams.member_name', 'like', "%{$searchTerm}%")
                  ->orWhere('groups.name', 'like', "%{$searchTerm}%")
                  ->orWhere('tbl_payroll_user_time_schedules.note', 'like', "%{$searchTerm}%");
            });
        }

        $items = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $employees = OurTeam::where('deleted', '=', 'No')->get();
        $schedules = DB::table('tbl_payroll_time_schedule_groups')
            ->leftjoin('groups', 'tbl_payroll_time_schedule_groups.group_id', '=', 'groups.id')
            ->select('tbl_payroll_time_schedule_groups.*', 'groups.name')
            ->where('tbl_payroll_time_schedule_groups.deleted', '=', 'No')
            ->where('tbl_payroll_time_schedule_groups.status', '=', 'Active')
            ->get();

        return view('admin.payroll.userScheduleGroup.userScheduleGroupView', compact('items', 'employees', 'schedules'));
    }

    public function getData()
    {

        $schedules = DB::table('tbl_payroll_user_time_schedules')
            ->join('tbl_payroll_time_schedule_groups', 'tbl_payroll_user_time_schedules.schedule_group_id', '=', 'tbl_payroll_time_schedule_groups.id')
            ->join('our_teams', 'tbl_payroll_user_time_schedules.employee_id', '=', 'our_teams.id')
            ->leftjoin('groups', 'tbl_payroll_time_schedule_groups.group_id', '=', 'groups.id')
            ->select('tbl_payroll_user_time_schedules.*', 'groups.name as groupName', 'our_teams.member_name')
            ->where('tbl_payroll_user_time_schedules.deleted', '=', 'No')
            ->get();

        $output = ['data' => []];
        $i = 1;
        foreach ($schedules as $schedule) {
            $status = '';
            if ($schedule->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$schedule->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$schedule->status.'"></i></center>';
            }

            $button = '<div class="btn-grade">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-cog"></i>  <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editTimeSchedule('.$schedule->id.')" class="btn"><i class="fas fa-exchange-alt"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$schedule->id.')"><i class="fas fa-edit"></i> Delete </a></li>
            </li>

            </ul>
        </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$schedule->id.'" />',
                $schedule->member_name,
                $schedule->groupName,
                $schedule->start_date,
                $schedule->end_date,
                $schedule->note,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function store(Request $request)
    {

        $schedules = new UserTimeSchedule;
        $schedules->employee_id = $request->employee_id;
        $schedules->schedule_group_id = $request->schedule_group_id;
        $schedules->start_date = $request->start_date;
        $schedules->end_date = $request->end_date;
        $schedules->note = $request->note;
        $schedules->deleted = 'No';
        $schedules->status = 'Active';
        $schedules->created_by = Auth::user()->id;
        $schedules->save();

        return response()->json(['success' => $request->emoloyee_id.' Saved successfully']);
    }

    public function edit(Request $request)
    {

        $schedules = UserTimeSchedule::where('id', '=', $request->id)->get();

        return $schedules;
    }

    public function update(Request $request)
    {

        $schedules = UserTimeSchedule::find($request->id);
        $schedules->schedule_group_id = $request->schedule_group_id;
        $schedules->start_date = $request->start_date;
        $schedules->end_date = $request->end_date;
        $schedules->note = $request->note;

        $schedules->last_updated_by = Auth::user()->id;
        $schedules->save();

        return response()->json(['success' => $request->schedule_group_id.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $schedules = UserTimeSchedule::find($request->id);

        $schedules->status = 'Inactive';
        $schedules->deleted = 'Yes';
        $schedules->deleted_by = Auth::user()->id;
        $schedules->deleted_date = date('Y-m-d H:i:s');
        $schedules->save();

        return response()->json(['success' => 'Data deleted successfully']);
    }
}
