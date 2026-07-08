<?php

namespace App\Http\Controllers\Admin\PayRoll\gradeAndStep;

use App\Http\Controllers\Controller;
use App\Models\payroll\Grade;
use Illuminate\Http\Request;
// use App\Models\payroll\Email;
use Illuminate\Support\Facades\Auth;

class GradeNewController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = Grade::where('deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('grade_name', 'like', "%{$searchTerm}%");
            });
        }

        $grades = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.payroll.grade.gradeViewNew', compact('grades'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'grade_name' => 'required',
        ]);
        $data = new Grade;
        $data->grade_name = $request->grade_name;
        $data->note = $request->note;
        $data->status = 'Active';
        $data->deleted = 'No';
        $data->created_by = Auth::user()->id;
        $data->save();

        return response()->json(['success' => $request->grade_name.' Saved successfully']);

    }

    public function edit(Request $request)
    {
        $grade = Grade::find($request->id);

        return $grade;
        // $count= Email::where('replied_by',NULL)->count();
        // return view('admin.payroll.groups.groupEdit',['group'=>$group,'count'=>$count]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'grade_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            // 'note' => 'max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u'
            'grade_name' => 'required',
        ]);
        $grade = Grade::find($request->id);
        $grade->grade_name = $request->grade_name;
        $grade->note = $request->note;
        $grade->status = $request->status;
        $grade->last_updated_by = Auth::user()->id;
        $result = $grade->save();

        return response()->json(['success' => $request->group_name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $grade = Grade::find($request->id);
        $grade->grade_name = $grade->grade_name.'deleted'.$request->id;
        $grade->status = 'Inactive';
        $grade->deleted = 'Yes';
        $grade->deleted_by = Auth::user()->id;
        $grade->deleted_date = date('Y-m-d H:i:s');
        $grade->save();

        return response()->json(['success' => 'Grade deleted successfully']);

    }
}
