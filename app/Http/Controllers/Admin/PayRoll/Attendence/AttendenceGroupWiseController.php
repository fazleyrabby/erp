<?php

namespace App\Http\Controllers\Admin\Payroll\Attendence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\payroll\Group;
use App\Models\Payroll\OurTeam;
use App\Models\Payroll\PayrollAttendence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendenceGroupWiseController extends Controller
{
    

    public function index(){
        $groups=Group::where('deleted','=','No')->where('status','=','Active')->get();
        return view('admin.payroll.Attendence.groupAttendence.groupAttendenceView',['groups'=>$groups]);
    }
    

    public function getGroupMonthYear(Request $request){
        $attendence_month_year=PayrollAttendence::where('group_id','=',$request->group_id)
                    ->where('deleted','=','No')
                    ->where('status','=','Active')
                    ->select('month_year')
                    ->distinct('month_year')
                    ->get();

            $data='<option value="" selected>Choose Month Year</option>';

            foreach($attendence_month_year as $month_year){
                $data.='<option value="'.$month_year->month_year.'">'.$month_year->month_year.'</option>';
            }
            //$a=cal_days_in_month(CAL_GREGORIAN, 7, 2009);
           /*  $dt = "2022-07-24";
            $date=date("Y-m-01", strtotime($dt));
            return date("$date D", strtotime($date)); */
            
        return $data;
    }





    public function getMonthYearDatesFromTo(Request $request){

        $monthYear=explode("-", $request->month_year);
        $month=$monthYear[0];
        $year=$monthYear[1];
        $month_value=date("m", strtotime($month));
        $month_days_count=cal_days_in_month(CAL_GREGORIAN, $month_value, $year);

      
        $teams=OurTeam::where('deleted','=','No')->where('status','=','Active')->where('group_id','=',$request->group_id)->get();
        $days=array();
        $table='';
                $table .='<table  id="attendenceTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Employee Name</th>'; 
                                   
                    for($i=0;$i <$month_days_count; $i++){
                        $days[$i]=$i+1;
                          $table .='<th>'.$year.'-'.$month_value.'-'.$days[$i].'</th>';
                    } 
        

                    $table .='</tr>
                            </thead>

                            <tbody>';
                    $k=1;
                    foreach($teams as $team){      
                        $table .='<tr>
                                    <td class="text-center">'.$k++.'</td>
                                    <td>'.$team->member_name.'</td>';

                                $attendences=PayrollAttendence::where('deleted','=','No')
                                    ->where('status','=','Active')
                                    ->where('employee_id','=',$team->id)
                                    ->where('group_id','=',$request->group_id)
                                    ->where('month_year','=',$request->month_year)
                                    ->get(); 

                                $dayss=array();
                                $dates='';
                                for($a=0;$a <$month_days_count; $a++){
                                    $dayss[$a]=$a+1;
                                    $dates=$year.'-'.$month_value.'-'.$dayss[$a];

                                    foreach($attendences as $attendence){
                                        $attendenceDate[]=$attendence->date;
                                        $empId=$attendence->employee_id;
                                    }
                                
                                    $b=array_search($dates,$attendenceDate);

                                    $status='';
                                    $color='';
                                    if($b == true){
                                        $status='Present';
                                        $color='text-success';
                                    }else{
                                        $status='Absent';
                                        $color='text-danger';
                                    }
                                    $table .='<td class="'.$color.' text-center">'.$status.'</td>';
                                }      
            
                        $table .='</tr>';
                    }
                    $table .='</tbody>
                        
                        </table>'; 

        return $table;
    }

































}
