<?php

namespace App\Http\Controllers\Admin\PayRoll\gradeAndStep;

use App\Http\Controllers\Controller;
use App\Models\payroll\Grade;
use Illuminate\Http\Request;
// use App\Models\payroll\Email;
use Illuminate\Support\Facades\Auth;

class GradeNewController extends Controller
{
    public function index()
    {

        // $count= Email::where('replied_by',NULL)->count();

        return view('admin.payroll.grade.gradeViewNew');

    }

    public function getGradeData()
    {

        $grades = Grade::where('deleted', '=', 'No')->orderBy('id', 'ASC')->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($grades as $grade) {
            $status = '';
            if ($grade->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$grade->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$grade->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editGrade('.$grade->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$grade->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$grade->id.'" />',
                $grade->grade_name,
                $grade->note,
                $status,
                $button,
            ];
        }

        return $output;
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
