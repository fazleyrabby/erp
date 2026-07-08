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
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('facilities')
            ->join('groups', 'facilities.group_id', '=', 'groups.id')
            ->select('facilities.*', 'groups.name as groupName')
            ->where('facilities.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('facilities.facility_name', 'like', "%{$searchTerm}%");
            });
        }

        $facilities = $query->orderBy('facilities.' . $sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $groups = Group::where('deleted', 'No')->where('status', 'Active')->get();

        return view('admin.payroll.facility.facilityView', compact('facilities', 'groups'));
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
