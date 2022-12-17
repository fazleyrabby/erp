<?php

namespace App\Http\Controllers\Admin\PayRoll\Group;

use App\Http\Controllers\Controller;
use App\Models\payRoll\TimeScheduleGroup;
use App\Models\payRoll\Group;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TimeGroupController extends Controller
{
    
    public function index(){
        $groups=Group::where('deleted','=','No')->where('status','=','Active')->get();
        return view('admin.payroll.TimeScheduleGroup.timeScheduleGroupView',['groups'=>$groups]);
    }

    


    public function  getScheduleGroupData(){

        //$scheduleGroups1=TimeScheduleGroup::where('deleted','=','No')->orderBy('id', 'DESC')->get();
        $scheduleGroups=DB::table('tbl_payroll_time_schedule_groups')
        ->leftjoin('groups', 'tbl_payroll_time_schedule_groups.group_id', '=', 'groups.id')
        ->select('tbl_payroll_time_schedule_groups.*', 'groups.name as groupName')
        ->where('tbl_payroll_time_schedule_groups.deleted','=','No')
        ->where('tbl_payroll_time_schedule_groups.status','=','Active')
        ->orderBy('tbl_payroll_time_schedule_groups.id', 'DESC')
        ->get();
        
        $output = array('data' => array());
        $i=1;
        foreach ($scheduleGroups as $group) {
            $status = "";
            if($group->status == 'Active'){
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$group->status.'"></i></center>';
            }else{
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$group->status.'"></i></center>';
            }
			/*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#" onclick="editScheduleGroup('.$group->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$group->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';            
			$output['data'][] = array(
				$i++. '<input type="hidden" name="id" id="id" value="'.$group->id.'" />',
				$group->groupName,
                $group->time_from,
                $group->time_to,
				$group->working_hour,
				$status,
				$button
			);            
        }
        return $output;
    }



    public function store(Request $request){

         $validated = $request->validate([
            'group_id' => 'required|unique:tbl_payroll_time_schedule_groups',
            'time_from' => 'required',
            'time_to' => 'required',
            'working_hour' => 'required'
        ]); 
       
            $scheduleGroups= new TimeScheduleGroup();
            $scheduleGroups->group_id=$request->group_id;
            $scheduleGroups->time_from=$request->time_from;
            $scheduleGroups->time_to=$request->time_to;
            $scheduleGroups->working_hour=$request->working_hour;
    
            $scheduleGroups->deleted="No";
            $scheduleGroups->status="Active";
            $scheduleGroups->created_by=Auth::user()->id;
            $result=$scheduleGroups->save();
            
                return response()->json(['success'=>$request->group_name.' Saved successfully']);
           
    }




    public function edit(Request $request){
        $scheduleGroups=TimeScheduleGroup::find($request->id);
        return $scheduleGroups;
    
    }




    public function update(Request $request){
        
        $scheduleGroups=TimeScheduleGroup::find($request->id);
        $scheduleGroups->group_id=$request->group_id;
        $scheduleGroups->time_from=$request->time_from;
        $scheduleGroups->time_to=$request->time_to;
        $scheduleGroups->working_hour=$request->working_hour;
        $scheduleGroups->status=$request->status;
        $scheduleGroups->last_updated_by=Auth::user()->id;
        $scheduleGroups->save();

        return response()->json(['success'=>$request->group_name.' updated successfully']);

    }




    public function delete(Request $request){
        $scheduleGroups = TimeScheduleGroup::find($request->id);
        $scheduleGroups->group_id = $scheduleGroups->group_id.'deleted'.$request->id;
        $scheduleGroups->status = 'Inactive';
        $scheduleGroups->deleted = 'Yes';
        $scheduleGroups->deleted_by = Auth::user()->id;
        $scheduleGroups->deleted_date = date('Y-m-d H:i:s');
        $scheduleGroups->save();
        return response()->json(['success'=>'Group deleted successfully']);
    }





















}
