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
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('monthly_amounts')
            ->join('our_teams', 'monthly_amounts.user_id', '=', 'our_teams.id')
            ->select('monthly_amounts.*', 'our_teams.member_name')
            ->where('monthly_amounts.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('our_teams.member_name', 'like', "%{$searchTerm}%")
                  ->orWhere('monthly_amounts.facility_name', 'like', "%{$searchTerm}%");
            });
        }

        $monthlyAmounts = $query->orderBy('monthly_amounts.' . $sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $employees = OurTeam::where('deleted', 'No')->where('status', 'Active')->get();
        $facilities = Facility::get();

        return view('admin.payroll.monthlyAmount.monthlyAmountView', compact('monthlyAmounts', 'employees', 'facilities'));
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
