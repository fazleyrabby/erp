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
    public function index()
    {

        $grades = Grade::where('deleted', '=', 'No')->where('status', '=', 'Active')->get();

        return view('admin.payroll.steps.stepsNewView', ['grades' => $grades]);
    }

    public function getSteps()
    {

        $steps = DB::table('steps')
            ->join('grades', 'steps.grade_id', '=', 'grades.id')
            ->select('steps.*', 'grades.grade_name')
            ->where('steps.deleted', '=', 'No')->orderBy('steps.id', 'ASC')->get();

        $output = ['data' => []];
        $i = 1;

        foreach ($steps as $step) {
            $status = '';
            if ($step->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$step->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$step->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-step">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-cog"></i>  <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

        <li class="action"><a href="#/" onclick="editStep('.$step->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
        <li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$step->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                

            </ul>
        </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$step->id.'" />',
                $step->step_name,
                $step->salary_amount,
                $step->grade_name,
                $step->note,
                $step->priority,
                $status,
                $button,
            ];
        }

        return $output;

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
