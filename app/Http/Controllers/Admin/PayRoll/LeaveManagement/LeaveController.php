<?php

namespace App\Http\Controllers\Admin\PayRoll\LeaveManagement;

use App\Http\Controllers\Controller;
use App\Models\payroll\Leave;
use App\Models\payroll\LeaveBalance;
use App\Models\payroll\OurTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'tbl_payroll_leaves.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('tbl_payroll_leaves')
            ->join('our_teams', 'tbl_payroll_leaves.employee_id', '=', 'our_teams.id')
            ->select('tbl_payroll_leaves.*', 'our_teams.member_name')
            ->where('tbl_payroll_leaves.deleted', '=', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('our_teams.member_name', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_payroll_leaves.leave_type', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_payroll_leaves.leave_reason', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_payroll_leaves.admin_remarks', 'like', "%{$searchTerm}%");
            });
        }

        $items = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $employees = OurTeam::where('deleted', '=', 'No')->get();

        return view('admin.payroll.LeaveViews.leaveView', compact('items', 'employees'));
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

        return response()->json(['success' => $request->emoloyee_id . ' Saved successfully']);
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
                $leaveStatus = '<span class="badge badge-warning">' . $leave->leave_status . '</span style="color:">';
            } elseif ($leave->leave_status == 'Approved') {
                $leaveStatus = '<span class="badge badge-success">' . $leave->leave_status . '</span>';
            } else {
                $leaveStatus = '<span class="badge badge-danger">' . $leave->leave_status . '</span>';
            }

            $button = '<div class="btn-grade">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
        
        <li class="action"><a href="#" onclick="editLeave(' . $leave->id . ')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
        <li class="action"><a href="#/" class="btn" onclick="confirmDelete(' . $leave->id . ')"><i class="fas fa-trash"></i> Delete </a></li>
                    </li>
        
                    </ul>
                </div>';
            $output['data'][] = [
                $i++ . '<input type="hidden" name="id" id="id" value="' . $leave->id . '" />',
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
        DB::beginTransaction();
        try {
            $leaves = Leave::find($request->id);
            $oldStatus = $leaves->leave_status;
            $leaves->leave_start_date = $request->leave_start_date;
            $leaves->leave_type = $request->leave_type;
            $leaves->leave_end_date = $request->leave_end_date;
            $leaves->leave_reason = $request->leave_reason;
            $leaves->admin_remarks = $request->admin_remarks;
            $leaves->leave_status = $request->leave_status;

            $leaves->last_updated_by = Auth::user()->id;
            $leaves->save();

            // Track leave balance on approval
            if ($request->leave_status === 'Approved' && $oldStatus !== 'Approved') {
                $this->deductLeaveBalance($leaves);
            }

            // Restore balance if previously approved but now changed
            if ($oldStatus === 'Approved' && $request->leave_status !== 'Approved') {
                $this->restoreLeaveBalance($leaves);
            }

            DB::commit();
            return response()->json(['success' => $request->leave_start_date . ' updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $leaves = Leave::find($request->id);

            if ($leaves->leave_status === 'Approved') {
                $this->restoreLeaveBalance($leaves);
            }

            $leaves->status = 'Inactive';
            $leaves->deleted = 'Yes';
            $leaves->deleted_by = Auth::user()->id;
            $leaves->deleted_date = date('Y-m-d H:i:s');
            $leaves->save();

            DB::commit();
            return response()->json(['success' => 'Leave deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function deductLeaveBalance($leave)
    {
        $days = $leave->days_count;
        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type', $leave->leave_type)
            ->where('year', date('Y'))
            ->first();

        if ($balance) {
            $balance->increment('used_days', $days);
        }
    }

    private function restoreLeaveBalance($leave)
    {
        $days = $leave->days_count;
        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type', $leave->leave_type)
            ->where('year', date('Y'))
            ->first();

        if ($balance) {
            $balance->decrement('used_days', $days);
        }
    }
}
