<?php

namespace App\Http\Controllers\Admin\PayRoll\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\payroll\OurTeam;
use Image;
use App\Models\payroll\Steps;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OurTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $members = DB::table('our_teams')
            ->join('grades', 'our_teams.current_grade', '=', 'grades.id')
            ->join('groups', 'our_teams.group_id', '=', 'groups.id')
            ->join('steps', 'our_teams.current_step', '=', 'steps.id')
            ->select('our_teams.*', 'grades.grade_name','steps.step_name','groups.name as groupName')
            ->orderBy('priority','ASC')
            ->get();
        
        return view('admin.payroll.ourTeam.OurTeamView',['members'=>$members]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$count= Email::where('replied_by',NULL)->count();
        return view('admin.payroll.ourTeam.OurTeamAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'address' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
           
            'member_desingnation'=>'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'member_education'=>'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'job_location'=>'required|max:255',
            'joining_date'=>'required|max:255',
            'is_employee'=>'required|max:255',
            'current_grade'=>'required|max:255',
            'current_step'=>'required|max:255',
            'group_id'=>'required|max:255',
            'sheet_id'=>'required|max:255',
            'priority'=>'nullable|numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'salary'=>'required',
            'ta_da'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'phone_bill'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'laundry'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'salary_type'=>'required|max:255',
            'mobile_number' => 'required|min:13|numeric|min:10',
            'working_hour' => 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'account_no' => 'required|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'short_note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'description' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);







         //---Image resize And upload in public        
		 $imageName = '';
		 if($request->hasFile('member_image')){
			 
		 $userImage = $request->file('member_image'); 
		 $name = $userImage->getClientOriginalName();
		 $uploadPath = 'upload/employee_image/';
		 $uploadPathOriginal = 'upload/original_employee_image/';
		 $imageName = time().$name;
		 $imageUrl = $uploadPath.$imageName;
		 $imageOriginalUrl = $uploadPathOriginal.time().$name;
		 //--resize image upload in public--//
		 Image::make($userImage)->resize(200,200)->save($imageUrl);
 
		 //--original image upload in public--//
		 $request->member_image->move(public_path($uploadPathOriginal), $imageName);
 
		 } else{
			 $imageName = "no_image.png";
		 }
		 //---End-Image resize And upload in public
        
       $member= new OurTeam();
       $member->member_name = $request->member_name;
       $member->member_image = $imageName;
       $member->member_desingnation = $request->member_desingnation;
       $member->is_employee = $request->is_employee;
       $member->salary_type = $request->salary_type;
       $member->job_location = $request->job_location;
       $member->mobile_number = $request->mobile_number;
       $member->address = $request->address;
       $member->laundry = $request->laundry;
       $member->phone_bill = $request->phone_bill;
       $member->ta_da = $request->ta_da;
       $member->member_education = $request->member_education;
       $member->description = $request->description;
       $member->social_links = $request->social_links;
       $member->created_by = Auth::user()->id;
       $member->joining_date = $request->joining_date;
       $member->job_left_date = $request->job_left_date;
       $member->short_note = $request->short_note;
       $member->current_grade = $request->current_grade;
       $member->current_step = $request->current_step;
       $member->group_id = $request->group_id;
       $member->sheet_id = $request->sheet_id;
       $member->account_no = $request->account_no;
       $member->priority = $request->priority;
       $member->working_hour = $request->working_hour;

       
       $member->deleted = "NO";
       $member->referred_by = $request->referred_by;
       $member->status = 'Active';
       $member->created_by = Auth::user()->id;

       $member->salary = $request->salary;

       $member->save();
       return redirect('/payroll/ourTeam')->with('message','Member'
           . ' saved seccessfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($member_id)
    {
        
      
        
        $member=OurTeam::where('id','=',$member_id)->first();
        return view('admin.payroll.ourTeam.ourTeamEdit',['member'=>$member]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



     
    public function update(Request $request)
    {
         
        $validated = $request->validate([
            'member_name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'address' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
           
            'member_desingnation'=>'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'member_education'=>'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'job_location'=>'required|max:255',
            'joining_date'=>'required|max:255',
            'is_employee'=>'required|max:255',
            'current_grade'=>'required|max:255',
            'current_step'=>'required|max:255',
            'group_id'=>'required|max:255',
            'sheet_id'=>'required|max:255',
            'priority'=>'nullable|numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'salary'=>'required',
            'ta_da'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'phone_bill'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'laundry'=> 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'salary_type'=>'required|max:255',
            'mobile_number' => 'required|min:13|numeric|min:10',
            'working_hour' => 'numeric|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'account_no' => 'required|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'short_note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'description' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
    
        $imageName = '';
        if ($request->hasFile('member_image')) {
            $request->validate([
                'member_image'   =>  'image|max:2048'
            ]);
            //--- Image resize And upload in public 
            $userImage = $request->file('member_image');
            $name = $userImage->getClientOriginalName();
            $uploadPath = 'upload/employee_image/';
            $uploadPathOriginal = 'upload/original_employee_image/';
            $imageName = time() . $name;
            $imageUrl = $uploadPath . $imageName;
            $imageOriginalUrl = $uploadPathOriginal . time() . $name;
            //--resize image upload in public--//
            Image::make($userImage)->resize(200, 200)->save($imageUrl);
            //--original image upload in public--//
            //$request->image->move(public_path($uploadPathOriginal), $imageName);
        } else {
            $imageName =  $request->oldImage;
        }

        $member = OurTeam::where('id','=',$request->member_id)->first();
        //dd($member);
        $member->member_name = $request->member_name;
        $member->member_image = $imageName;
        $member->member_desingnation = $request->member_desingnation;
        $member->mobile_number = $request->mobile_number;
        $member->address = $request->address;
        $member->laundry = $request->laundry;
        $member->phone_bill = $request->phone_bill;
        $member->ta_da = $request->ta_da;
        $member->job_location = $request->job_location;
        $member->is_employee = $request->is_employee;
        $member->salary_type = $request->salary_type;
        $member->member_education = $request->member_education;
        $member->description = $request->description;
        $member->social_links = $request->social_links;
        $member->created_by = Auth::user()->id;
        $member->joining_date = $request->joining_date;
        $member->job_left_date = $request->job_left_date;
        $member->short_note = $request->short_note;
        $member->priority = $request->priority;
        $member->working_hour = $request->working_hour;

        $member->current_grade = $request->current_grade;
       $member->current_step = $request->current_step;
       $member->group_id = $request->group_id;
       $member->sheet_id = $request->sheet_id;
       $member->account_no = $request->account_no;
        
        
        $member->last_updated_by=Auth::user()->id;
        $member->referred_by = $request->referred_by;
        $member->status = $request->status;
        $member->salary = $request->salary;
        $result=$member->save();


        if($result){

            return redirect()->route('ourTeam')->with('message',' Member info updated successfully!');

        } else {

            return back()->with('message',' Something went wrong!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




public function memberChangeStatus($member_id)
{

    // return "Please enable this feature.";

    $member = OurTeam::find($member_id);

    if($member->status == 'Active'){
         $member->status = 'In-active';
         $member->is_employee = 'No';
    } else {
        $member->status = 'Active';
    }

    $member->last_updated_by = Auth::user()->id;
    $result = $member->save();


    if($result){

        return back()->with('message','Status updated successfully!');

    } else {

        return back()->with('message',' Something went wrong!');
    }


}


    public function getSalary(Request $request){
        $getsalary=Steps::find($request->current_step);
        return $getsalary->salary_amount;
    }


    public function getSteps(Request $request){
        $getsteps=Steps::where('grade_id','=',$request->current_grade)->where('deleted','=','No')->get();
        $steps="<option value='0' selected>Select Step</option>";
        foreach($getsteps as $getstep){
            $steps.="<option value='".$getstep->id."'>".$getstep->step_name."</option>";
        }
        return $steps;
    }


























}