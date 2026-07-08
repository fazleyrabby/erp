<?php

namespace App\Http\Controllers\Admin\PayRoll\gradeAndStep;

use App\Http\Controllers\Controller;
use App\Models\payroll\Grade;
use App\Models\payroll\Steps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StepNewController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('steps')
            ->join('grades', 'steps.grade_id', '=', 'grades.id')
            ->select('steps.*', 'grades.grade_name')
            ->where('steps.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('steps.step_name', 'like', "%{$searchTerm}%");
            });
        }

        $steps = $query->orderBy('steps.' . $sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $grades = Grade::where('deleted', 'No')->where('status', 'Active')->get();

        return view('admin.payroll.steps.stepsNewView', compact('steps', 'grades'));
    }

    public function store(Request $request)
    {

        // return $request;
        $request->validate([
            'step_name' => 'required|max:255|unique:steps,step_name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'grade_id' => 'required',
            'sequence' => 'required',
            'salary_amount' => 'required|numeric',
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',

        ]);

        $steps = new Steps;
        $steps->step_name = $request->step_name;
        $steps->salary_amount = $request->salary_amount;
        $steps->note = $request->note;
        $steps->grade_id = $request->grade_id;
        $steps->priority = $request->sequence;
        $steps->deleted = 'No';
        $steps->created_by = Auth::user()->id;
        $steps->status = 'Active';
        $steps->save();

        return response()->json(['success' => $request->step_name.' Saved successfully']);
    }

    public function edit(Request $request)
    {
        $steps = Steps::find($request->id);

        return $steps;
    }

    public function update(Request $request)
    {
        $request->validate([
            'step_name' => 'required',
            'grade_id' => 'required',
            'sequence' => 'required|integer',
            'salary_amount' => 'numeric',
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $steps = Steps::find($request->id);
        $steps->step_name = $request->step_name;
        $steps->salary_amount = $request->salary_amount;
        $steps->priority = $request->sequence;
        $steps->grade_id = $request->grade_id;
        $steps->note = $request->note;
        $steps->status = $request->status;
        $steps->last_updated_by = Auth::user()->id;
        $result = $steps->save();

        return response()->json(['success' => $request->step_name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $steps = Steps::find($request->id);
        $steps->step_name = $steps->step_name.'deleted'.$request->id;
        $steps->status = 'Inactive';
        $steps->deleted = 'Yes';
        $steps->deleted_by = Auth::user()->id;
        $steps->deleted_date = date('Y-m-d H:i:s');
        $steps->save();

        return response()->json(['success' => 'Step deleted successfully']);

    }
}
