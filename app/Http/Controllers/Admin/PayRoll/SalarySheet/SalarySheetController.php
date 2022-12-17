<?php

namespace App\Http\Controllers\Admin\PayRoll\SalarySheet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\payroll\Email;
use App\Models\payroll\SalarySheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SalarySheetController extends Controller
{
    
    public function index(){

       
        return view('admin.payroll.salarySheet.salarySheetView');

    }





 public function getSalarySheetData(){

        $salarySheet =SalarySheet::where('deleted','=','No')->get();
        
        $output = array('data' => array());
        $i=1;
        foreach ($salarySheet as $sheet) {
            $status = "";
            if($sheet->status == 'Active'){
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$sheet->status.'"></i></center>';
            }else{
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$sheet->status.'"></i></center>';
            }
			/*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editSalarySheet('.$sheet->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$sheet->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';            
			$output['data'][] = array(
				$i++. '<input type="hidden" name="id" id="id" value="'.$sheet->id.'" />',
				$sheet->sheet_name,
				$status,
				$button
			);            
        }
        return $output;
    }



    public function store(Request $request){

        $request->validate([
            'sheet_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
            ]); 

         $salarySheet= new SalarySheet();
         $salarySheet->sheet_name=$request->sheet_name;

         $salarySheet->deleted="No";
         $salarySheet->status="Active";
         $salarySheet->created_by=Auth::user()->id;
         $salarySheet->save();

        return response()->json(['success'=>$request->sheet_name.' Saved successfully']);
    }




    public function edit(Request $request){

        $salarySheet=SalarySheet::find($request->id);
        return $salarySheet;
    }





    public function update(Request $request){

        $request->validate([
            'sheet_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:grades,grade_name,'.$request->id,
        ]);

        $salarySheet=SalarySheet::find($request->id);
        $salarySheet->sheet_name=$request->sheet_name;
        $salarySheet->status=$request->status;
        $salarySheet->last_updated_by=Auth::user()->id;
        $salarySheet->save();
        return response()->json(['success'=>$request->sheet_name.' updated successfully']);
    }



    public function delete(Request $request) {

        $salarySheet = SalarySheet::find($request->id);
        $salarySheet->sheet_name = $salarySheet->sheet_name.'deleted'.$request->id;
        $salarySheet->status = 'Inactive';
        $salarySheet->deleted = 'Yes';
        $salarySheet->deleted_by = Auth::user()->id;
        $salarySheet->deleted_date = date('Y-m-d H:i:s');
        $salarySheet->save();
        return response()->json(['success'=>'Sheet deleted successfully']);

    }






























}
