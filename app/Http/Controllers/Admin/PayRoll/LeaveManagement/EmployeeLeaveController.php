<?php

namespace App\Http\Controllers\Admin\PayRoll\LeaveManagement;

use App\Http\Controllers\Controller;
use App\Models\payroll\Leave;
use App\Models\payroll\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myLeaves(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found for your account.');
        }

        $limit = $request->limit ?? 10;
        $leaves = Leave::where('employee_id', $employee->id)
            ->where('deleted', 'No')
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $balances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', date('Y'))
            ->get();

        return view('admin.payroll.LeaveViews.employee-my-leaves', compact('leaves', 'balances', 'employee'));
    }

    public function showApplyForm()
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found for your account.');
        }

        $balances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', date('Y'))
            ->get();

        return view('admin.payroll.LeaveViews.employee-apply-leave', compact('balances', 'employee'));
    }

    public function apply(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $request->validate([
            'leave_type' => 'required|string',
            'leave_start_date' => 'required|date',
            'leave_end_date' => 'required|date|after_or_equal:leave_start_date',
            'leave_reason' => 'required|string|max:1000',
        ]);

        $start = \Carbon\Carbon::parse($request->leave_start_date);
        $end = \Carbon\Carbon::parse($request->leave_end_date);
        $daysRequested = $start->diffInDays($end) + 1;

        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type', $request->leave_type)
            ->where('year', date('Y'))
            ->first();

        if ($balance && $balance->remaining_days < $daysRequested) {
            return back()->with('error', "Insufficient leave balance. You only have {$balance->remaining_days} days remaining for {$request->leave_type}.");
        }

        $overlap = Leave::where('employee_id', $employee->id)
            ->where('deleted', 'No')
            ->where(function ($q) use ($request) {
                $q->whereBetween('leave_start_date', [$request->leave_start_date, $request->leave_end_date])
                  ->orWhereBetween('leave_end_date', [$request->leave_start_date, $request->leave_end_date])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('leave_start_date', '<=', $request->leave_start_date)
                         ->where('leave_end_date', '>=', $request->leave_end_date);
                  });
            })->exists();

        if ($overlap) {
            return back()->with('error', 'You already have a leave application overlapping with these dates.');
        }

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type' => $request->leave_type,
            'leave_start_date' => $request->leave_start_date,
            'leave_end_date' => $request->leave_end_date,
            'leave_reason' => $request->leave_reason,
            'leave_status' => 'Pending',
            'deleted' => 'No',
            'status' => 'Active',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('employee.my-leaves')->with('success', 'Leave application submitted successfully.');
    }
}
