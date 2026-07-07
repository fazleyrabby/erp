<?php

namespace App\Http\Controllers\Admin\PayRoll\SalarySheet;

use App\Http\Controllers\Controller;
use App\Models\payroll\BonusList;
use App\Models\payroll\Group;
use App\Models\payroll\SavedSalarySheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function index()
    {

        $salrysheets = SavedSalarySheet::where('deleted', '=', 'No')->select('month_year')->distinct()->get();
        $groups = Group::where('deleted', '=', 'No')->where('status', '=', 'Active')->get();

        return view('admin.payroll.salarySheet.bonusListView', ['salrysheets' => $salrysheets, 'groups' => $groups]);
    }

    public function getBonusData()
    {

        $bonuses = DB::table('bonus_lists')
            ->leftjoin('groups', 'bonus_lists.group_id', '=', 'groups.id')
            ->select('bonus_lists.*', 'groups.name as groupName')
            ->Where('bonus_lists.deleted', '=', 'No')
            ->get();

        $output = ['data' => []];
        $i = 1;
        foreach ($bonuses as $bonus) {
            $status = '';
            if ($bonus->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$bonus->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$bonus->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editBonusList('.$bonus->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$bonus->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$bonus->id.'" />',
                $bonus->bonus_name,
                $bonus->groupName,
                $bonus->month_year,
                $bonus->amount,
                $bonus->note,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function store(Request $request)
    {

        $request->validate([
            'bonus_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:bonus_lists,bonus_name,'.$request->id,
            'month_year' => 'required',
            'group_id' => 'required',
            'amount' => 'required',
        ]);

        $bonus = new BonusList;
        $bonus->bonus_name = $request->bonus_name;
        $bonus->applicable_from = $request->applicable_from;
        $bonus->month_year = $request->month_year;
        $bonus->group_id = $request->group_id;
        $bonus->amount = $request->amount;
        $bonus->note = $request->note;

        $bonus->deleted = 'No';
        $bonus->status = 'Active';
        $bonus->created_by = Auth::user()->id;
        $bonus->save();

        // return back();
        response()->json(['success' => $request->bonus_name.' saved successfully']);
    }

    public function edit(Request $request)
    {
        $bonus = BonusList::find($request->id);

        return $bonus;
    }

    public function update(Request $request)
    {
        // return $request;
        $request->validate([
            'bonus_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:bonus_lists,bonus_name,'.$request->id,
            'month_year' => 'required',
            'group_id' => 'required',
            'amount' => 'required',
        ]);

        $bonus = BonusList::find($request->id);
        $bonus->bonus_name = $request->bonus_name;
        $bonus->month_year = $request->month_year;
        $bonus->group_id = $request->group_id;
        $bonus->amount = $request->amount;

        $bonus->applicable_from = $request->applicable_from;
        $bonus->note = $request->note;
        $bonus->status = $request->status;
        $bonus->last_updated_by = Auth::user()->id;
        $bonus->save();

        return response()->json(['success' => $request->bonus_name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $bonus = BonusList::find($request->id);
        $bonus->bonus_name = $bonus->bonus_name.'deleted'.$request->id;
        $bonus->status = 'Inactive';
        $bonus->deleted = 'Yes';
        $bonus->deleted_by = Auth::user()->id;
        $bonus->deleted_date = date('Y-m-d H:i:s');
        $bonus->save();

        return response()->json(['success' => 'Bonus deleted successfully']);

    }
}
