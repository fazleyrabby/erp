<?php

namespace App\Http\Controllers\Admin\PayRoll\MonthlyAmount;

use App\Http\Controllers\Controller;
use App\Models\payroll\Facility;
use App\Models\payroll\MonthlyAmount;
use App\Models\payroll\OurTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyAmountController extends Controller
{
    public function index()
    {

        $employees = OurTeam::where('deleted', '=', 'No')->where('status', 'Active')->get();

        $facilities = Facility::get();

        return view('admin.payroll.monthlyAmount.monthlyAmountView', ['employees' => $employees, 'facilities' => $facilities]);
    }

    public function getMonthlyAmountData()
    {

        $monthlyAmounts = DB::table('monthly_amounts')
            ->join('our_teams', 'monthly_amounts.user_id', '=', 'our_teams.id')
            ->select('monthly_amounts.*', 'our_teams.member_name')
            ->where('monthly_amounts.deleted', '=', 'No')->get();

        $output = ['data' => []];
        $i = 1;
        foreach ($monthlyAmounts as $monthlyAmount) {
            $status = '';
            if ($monthlyAmount->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$monthlyAmount->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$monthlyAmount->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editMonthlyAmount('.$monthlyAmount->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$monthlyAmount->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$monthlyAmount->id.'" />',
                $monthlyAmount->member_name,
                $monthlyAmount->facility_name,
                $monthlyAmount->amount,
                $monthlyAmount->type,
                // $status,
                $button,
            ];
        }

        return $output;
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'user_id' => 'required',
            // 'note' => 'max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u'
        ]);
        $monthlyAmounts = new MonthlyAmount;
        $monthlyAmounts->facility_name = $request->facility_name;
        $monthlyAmounts->amount = $request->amount;
        $monthlyAmounts->user_id = $request->user_id;
        $monthlyAmounts->type = $request->type;
        $monthlyAmounts->cause = $request->cause;
        $monthlyAmounts->month_year = $request->month_year;
        $monthlyAmounts->deleted = 'No';
        $monthlyAmounts->status = 'Active';
        $monthlyAmounts->created_by = Auth::user()->id;
        $result = $monthlyAmounts->save();

        return response()->json(['success' => $request->facility_name.' Saved successfully']);
    }

    public function edit(Request $request)
    {

        $monthlyAmounts = MonthlyAmount::find($request->id);

        // $monthlyAmounts['member_name'] = OurTeam::where('id', $monthlyAmounts->user_id)->value('member_name')->get();

        return $monthlyAmounts;
    }

    public function update(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        $monthlyAmounts = MonthlyAmount::find($request->id);
        $monthlyAmounts->user_id = $request->user_id;
        $monthlyAmounts->amount = $request->amount;
        $monthlyAmounts->facility_name = $request->facility_name;
        $monthlyAmounts->type = $request->type;
        $monthlyAmounts->cause = $request->cause;
        $monthlyAmounts->last_updated_by = Auth::user()->id;
        $result = $monthlyAmounts->save();

        return response()->json(['success' => $request->facility_name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $monthlyAmounts = MonthlyAmount::find($request->id);

        $monthlyAmounts->facility_name = $monthlyAmounts->facility_name.'deleted'.$request->id;
        $monthlyAmounts->status = 'Inactive';
        $monthlyAmounts->deleted = 'Yes';
        $monthlyAmounts->deleted_by = Auth::user()->id;
        $monthlyAmounts->deleted_date = date('Y-m-d H:i:s');
        $monthlyAmounts->save();

        return response()->json(['success' => 'Deleted successfully']);

    }
}
