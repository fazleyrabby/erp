<?php

namespace App\Http\Controllers\Admin\PayRoll\LeaveManagement;

use App\Http\Controllers\Controller;
use App\Models\PayRoll\Leave;
use App\Models\PayRoll\OurTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function index()
    {
        $employees = OurTeam::where('deleted', '=', 'No')->get();

        return view('admin.payroll.LeaveViews.leaveView', ['employees' => $employees]);
    }

    public function store(Request $request)
    {

        $leaves = new Leave;
        $leaves->employee_id = $request->employee_id;
        $leaves->leave_type = $request->leave_type;
        $leaves->leave_start_date = $request->leave_start_date;
        $leaves->leave_end_date = $request->leave_end_date;
        $leaves->leave_reason = $request->leave_reason;
        $leaves->admin_remarks = $request->admin_remarks;
        $leaves->deleted = 'No';
        $leaves->leave_status = 'Pending';
        $leaves->status = 'Active';
        $leaves->created_by = Auth::user()->id;
        $leaves->save();

        return response()->json(['success' => $request->emoloyee_id.' Saved successfully']);
    }

    public function getData()
    {
        $leaves = DB::table('tbl_payroll_leaves')
            ->join('our_teams', 'tbl_payroll_leaves.employee_id', '=', 'our_teams.id')
            ->select('tbl_payroll_leaves.*', 'our_teams.member_name')
            ->where('tbl_payroll_leaves.deleted', '=', 'No')
            ->orderBy('tbl_payroll_leaves.id', 'Desc')
            ->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($leaves as $leave) {
            $leaveStatus = '';
            if ($leave->leave_status == 'Pending') {
                $leaveStatus = '<span class="badge badge-warning">'.$leave->leave_status.'</span style="color:">';
            } elseif ($leave->leave_status == 'Approved') {
                $leaveStatus = '<span class="badge badge-success">'.$leave->leave_status.'</span>';
            } else {
                $leaveStatus = '<span class="badge badge-danger">'.$leave->leave_status.'</span>';
            }

            $button = '<div class="btn-grade">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
        
        <li class="action"><a href="#" onclick="editLeave('.$leave->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
        <li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$leave->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                    </li>
        
                    </ul>
                </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$leave->id.'" />',
                $leave->member_name,
                $leave->leave_type,
                $leave->leave_start_date,
                $leave->leave_end_date,
                $leave->leave_reason,
                $leave->admin_remarks,
                $leaveStatus,
                $button,
            ];
        }

        return $output;
    }

    public function edit(Request $request)
    {
        $leaves = Leave::find($request->id);

        return response()->json($leaves);
    }

    public function update(Request $request)
    {

        $leaves = Leave::find($request->id);
        $leaves->leave_start_date = $request->leave_start_date;
        $leaves->leave_type = $request->leave_type;
        $leaves->leave_end_date = $request->leave_end_date;
        $leaves->leave_reason = $request->leave_reason;
        $leaves->admin_remarks = $request->admin_remarks;
        $leaves->leave_status = $request->leave_status;

        $leaves->last_updated_by = Auth::user()->id;
        $leaves->save();

        return response()->json(['success' => $request->leave_start_date.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $leaves = Leave::find($request->id);

        $leaves->status = 'Inactive';
        $leaves->deleted = 'Yes';
        $leaves->deleted_by = Auth::user()->id;
        $leaves->deleted_date = date('Y-m-d H:i:s');
        $leaves->save();

        return response()->json(['success' => 'Leave deleted successfully']);
    }
}
