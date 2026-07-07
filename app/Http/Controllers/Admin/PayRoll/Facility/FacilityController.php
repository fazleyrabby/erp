<?php

namespace App\Http\Controllers\Admin\PayRoll\Facility;

use App\Http\Controllers\Controller;
use App\Models\payroll\Facility;
use App\Models\payroll\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    public function index()
    {

        $groups = Group::where('deleted', '=', 'No')->where('status', '=', 'Active')->get();

        return view('admin.payroll.facility.facilityView', ['groups' => $groups]);
    }

    public function getFacilityData()
    {

        $facilities = DB::table('facilities')
            ->join('groups', 'facilities.group_id', '=', 'groups.id')
            ->select('facilities.*', 'groups.name as groupName')
            ->where('facilities.deleted', '=', 'No')->orderBy('facilities.id', 'ASC')->get();

        $output = ['data' => []];
        $i = 1;
        foreach ($facilities as $facility) {
            $status = '';
            if ($facility->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$facility->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$facility->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editFacility('.$facility->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$facility->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$facility->id.'" />',
                $facility->facility_name,
                $facility->groupName,
                $facility->amount,
                $facility->lower_limit,
                $facility->upper_limit,
                $facility->location,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'amount' => 'required',
            'location' => 'required',
        ]);
        $facility = new Facility;
        $facility->facility_name = $request->facility_name;
        $facility->amount = $request->amount;
        $facility->group_id = $request->group_id;
        $facility->lower_limit = $request->lower_limit;
        $facility->upper_limit = $request->upper_limit;
        $facility->location = $request->location;
        $facility->deleted = 'No';
        $facility->status = 'Active';
        $facility->created_by = Auth::user()->id;
        $result = $facility->save();

        return response()->json(['success' => $request->facility_name.' Saved successfully']);
    }

    public function edit(Request $request)
    {
        $facilities = Facility::find($request->id);

        return $facilities;
    }

    public function update(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'amount' => 'required',
            'location' => 'required',
        ]);
        $facility = Facility::find($request->id);
        $facility->facility_name = $request->facility_name;
        $facility->amount = $request->amount;
        $facility->group_id = $request->group_id;
        $facility->lower_limit = $request->lower_limit;
        $facility->upper_limit = $request->upper_limit;
        $facility->location = $request->location;
        $facility->status = $request->status;
        $facility->last_updated_by = Auth::user()->id;
        $result = $facility->save();

        return response()->json(['success' => $request->facility_name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $facilities = Facility::find($request->id);

        $facilities->facility_name = $facilities->facility_name.'deleted'.$request->id;
        $facilities->status = 'Inactive';
        $facilities->deleted = 'Yes';
        $facilities->deleted_by = Auth::user()->id;
        $facilities->deleted_date = date('Y-m-d H:i:s');
        $facilities->save();

        return response()->json(['success' => 'Deleted successfully']);

    }
}
