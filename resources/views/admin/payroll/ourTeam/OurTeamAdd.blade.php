@extends('admin.master')
@section('title')
Admin Create Team Member
@endsection
@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header"><h3>Create Employee</h3></div>
                        <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        <div class="row g-3">
                            <div class="col-md-12 p-4">
                                <form method="POST"  action="{{ route('storeTeamMember') }}" enctype="multipart/form-data">

                                    @csrf
                                <div class="row g-3">


                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Employee Name</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="member_name" placeholder="Write Employee Name" >
                                    </div>
                                    <span class="text-danger">{{ $errors->has('member_name') ? $errors->first('member_name') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Team Leader</label>

                                    <div >
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect1" name="leader_id">
                                        <option value="0" selected >Choose Leader</option>
                                        @foreach(App\Models\payroll\OurTeam::where('deleted','=','No')->get() as $leader)
                                            <option value="{{$leader->member_id}}">{{$leader->member_name}}</option>
                                        @endforeach                                            
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('leader_id') ? $errors->first('leader_id') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Priority</label>
                                    <span class="text-danger">*</span>
                                    <div >
                                        <input type="text" class="form-control form-control-sm"  name="priority" placeholder="Enter priority">                  
                                    </div>
                                    <span class="text-danger">{{ $errors->has('priority') ? $errors->first('priority') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Desingnation</label>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="member_desingnation" placeholder=" Write Member Desingnation">
                                    </div>
                                    <span class="text-danger">{{ $errors->has('member_desingnation') ? $errors->first('member_desingnation') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Mobile</label>
                                    <span class="text-danger">*</span>
                                    <div >
                                        <input type="text" class="form-control form-control-sm"  name="mobile_number" placeholder="018XXXXXXXX" >
                                    </div>
                                    <span class="text-danger">{{ $errors->has('mobile_number') ? $errors->first('mobile_number') : '' }}</span>
                                </div>




                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Address</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="address" placeholder="Write Address">
                                    </div>
                                    <span class="text-danger">{{ $errors->has('address') ? $errors->first('address') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Job Location</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="job_location" placeholder="Enter workplace location" value="{{Session::get('companySettings')[0]['address']}}">
                                    </div>
                                    <span class="text-danger">{{ $errors->has('job_location') ? $errors->first('job_location') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Education</label>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="member_education" placeholder="Write About Your Educational Background Please" >
                                    </div>
                                    <span class="text-danger">{{ $errors->has('member_education') ? $errors->first('member_education') : '' }}</span>
                                </div>




                                <div class="form-group mb-3 col-md-12">
                                    <label for="carousalCaptionOffer" class="form-label">Employee description</label>
                                    <div>
                                        <textarea type="text" class="form-control form-control-sm"  name="description" placeholder="Write something about you" ></textarea>
                                    </div>
                                    <span class="text-danger">{{ $errors->has('description') ? $errors->first('description') : '' }}</span>
                                </div>


                                <div class="form-group mb-3 col-md-6">
                                <span class="text-danger">*</span>
                                    <label for="carousalCaptionOffer" class="form-label">Joining Date</label>
                                    <div >
                                    <input type="date" class="form-control form-control-sm"  name="joining_date" placeholder="Enter Joining Date">
                                    </div>
                                    <span class="text-danger">{{ $errors->has('joining_date') ? $errors->first('joining_date') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class=" col-form-label">Still Present Now</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect1" name="is_employee">
                                            <option value="0" selected disabled>Choose</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('is_employee') ? $errors->first('is_employee') : '' }}</span>
                                </div>


                                

                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class=" col-form-label">Left Job Date</label>
                                    <div>
                                        <input type="date" class="form-control form-control-sm"  name="job_left_date" placeholder="if he is not in team anymore" >
                                    </div>
                                    <span class="text-danger">{{ $errors->has('job_left_date') ? $errors->first('job_left_date') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Grade</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select form-select-sm" id="current_grade" onchange="loadStep()" name="current_grade">
                                            <option value="" selected >Choose Grade</option>
                                    @foreach(App\Models\payroll\Grade::all()->where('status','=','Active')->where('deleted','=','No') as $grd)
                                            <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                                    @endforeach
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('current_grade') ? $errors->first('current_grade') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Step</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select form-select-sm" id="current_step" onchange="salryGenerate()" name="current_step">
                                        <option value="" selected >Choose Steps</option>
                                    @foreach(App\Models\payroll\Steps::where('status','=','Active')->where('deleted','=','No')->get() as $stps)
                                            <option value="{{$stps->id}}">{{$stps->step_name}}</option>
                                    @endforeach                                         
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('current_step') ? $errors->first('current_step') : '' }}</span>
                                </div>


                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Salary</label>
                                    <div>
                                        <input type="text"  class="form-control form-control-sm"  name="salary" id="salary" placeholder="Enter salary amount" readonly>
                                    </div>
                                    <span class="text-danger">{{ $errors->has('salary') ? $errors->first('salary') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Group</label>
                                    <span class="text-danger">*</span>

                                    <div>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect1" name="group_id">
                                        <option value="" selected >Choose Group</option>
                                    @foreach(App\Models\payroll\Group::all()->where('status','=','Active')->where('deleted','=','No')->sortByDesc('id') as $grps)
                                            <option value="{{$grps->id}}">{{$grps->name}}</option>
                                    @endforeach                                            
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('group_id') ? $errors->first('group_id') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Salary Sheet</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect1" name="sheet_id">
                                        <option value="" selected >Choose sheet</option>
                                        @foreach(App\Models\payroll\SalarySheet::all()->where('status','=','Active')->where('deleted','=','No') as $sSheet)
                                            <option value="{{$sSheet->id}}">{{$sSheet->sheet_name}}</option>
                                        @endforeach                                            
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('sheet_id ') ? $errors->first('sheet_id ') : '' }}</span>
                                </div>





                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Working Hour</label>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="working_hour" placeholder="Write working hour" >
                                    </div>
                                    <span class="text-danger">{{ $errors->has('working_hour') ? $errors->first('working_hour') : '' }}</span>
                                </div>




                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Referred By<span style="color:red;">(Optional)</span></label>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="referred_by" placeholder="Reffered by who" >                  
                                    </div>
                                    <span class="text-danger">{{ $errors->has('referred_by') ? $errors->first('referred_by') : '' }}</span>
                                </div>


                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Salary Type</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect1" name="salary_type" placeholder="Salary">
                                        <option value="0" selected disabled>Choose Salary Type</option>    
                                        <option value="consulate">Consulate</option>    
                                        <option value="scale">Scale</option>                                    
                                        </select>   
                                    </div>
                                    <span class="text-danger">{{ $errors->has('salary_type') ? $errors->first('salary_type') : '' }}</span>
                                </div>



                                <div class="form-group mb-3 col-md-6">
                                    <label for="carousalCaptionOffer" class="form-label">Account Number</label>
                                    <div>
                                        <input type="text" class="form-control form-control-sm"  name="account_no"  >                  
                                    </div>
                                    <span class="text-danger">{{ $errors->has('account_no') ? $errors->first('account_no') : '' }}</span>
                                </div>


                              
                             


                                <div class="col-md-12 row">
                                        <div class="form-group mb-3  col-md-4" >
                                            <label for="carousalCaptionOffer" class="form-label">Laundry:</label>
                                            <div>
                                                <input type="text" class="form-control form-control-sm"  name="laundry"  >                                                
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->has('laundry') ? $errors->first('laundry') : '' }}</span>
                                        <div class="form-group mb-3 col-md-4">
                                            <label for="carousalCaptionOffer" class="form-label">Phone Bill:</label>
                                            <div>
                                            <input type="text" class="form-control form-control-sm" name="phone_bill"  >                                                
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->has('phone_bill') ? $errors->first('phone_bill') : '' }}</span>
                                        <div class="form-group mb-3 col-md-4">
                                            <label for="carousalCaptionOffer" class="form-label">TA/Da:</label>
                                            <div>
                                            <input type="text" class="form-control form-control-sm" name="ta_da" >                                                 
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->has('ta_da') ? $errors->first('ta_da') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-12">
                                    <label for="carousalCaptionOffer" class="form-label">Add Note<span style="color:red;">(Optional)</span></label>
                                    <div>
                                        <textarea type="text" class="form-control form-control-sm"  name="short_note" placeholder="Do you want to add any note?" ></textarea>
                                    </div>
                                    <span class="text-danger">{{ $errors->has('short_note') ? $errors->first('short_note') : '' }}</span>
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <label for="Memberimage" class="form-label">Employee Image</label>
                                    <div >
                                        <input type="file" id="member_image" name="member_image"  class="form-control form-control-sm"  onchange="loadPreview(this);"/>
                                        <span style="color:gray;">Image should must be 500*500 Size</span>
                                    </div>
                                    <span class="text-danger">{{ $errors->has('member_image') ? $errors->first('member_image') : '' }}</span>
                                </div> 
                                <div class=" form-group mb-3 row">
                                    <div class="col-md-3"></div>
                                        <div class=" col-md-9">
                                            <label for="profile_image">Image Preview</label>
                                            <img id="preview_img" src="" alt=" Select image to preview!" class="" width="50" height="50"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary btn-flat float-right" name="addBanner"><i class="fa fa-save"></i> Save </button>
                                </div>
                                </div>
                                
                            </form>

                            
                        </div>
                </div>
            </div>
        @endsection

@section('contentJavaScripts')


<script>
  function loadPreview(input, id) {
    id = id || '#preview_img';
    if (input.files && input.files[0]) {
        var reader = new FileReader();
 
        reader.onload = function (e) {
            $(id)
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
        };
 
        reader.readAsDataURL(input.files[0]);
    }
 }



    function salryGenerate(){
        var current_grade=$('#current_grade').val();
        var current_step=$('#current_step').val(); 
      //  alert(current_step);
       // alert(current_grade); 
        if(current_grade!='0'  &&  current_step !='0')  {
        
        $.ajax({
            url: "{{route('getSalaryData')}}",
               method:"GET",
               data:{"current_grade":current_grade,"current_step":current_step},
               success:function(result){
                   $("#salary").val(result);
               // alert(JSON.stringify(result));
               
               }, error: function(response) {
                    alert(JSON.stringify(response));
                    
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
        })
    }
    }





    function loadStep(){
        var current_grade=$('#current_grade').val();
        
      //  alert(current_step);
        //alert(current_grade); 
        if(current_grade!='0')  {
        
        $.ajax({
            url: "{{route('loadSteps')}}",
               method:"GET",
               data:{"current_grade":current_grade},
               success:function(result){
                   $("#current_step").html(result);
               // alert(JSON.stringify(result));
               
               }, error: function(response) {
                    alert(JSON.stringify(response));
                    
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
        })
    }
    else{
        $("#current_step").html('');
    }
    }

</script>



	

@endsection