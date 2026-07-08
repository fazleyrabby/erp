@extends('admin.master')
@section('title')
Admin Create Team Member
@endsection
@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header ">
                    <h3 class="text">Edit Team Member</h3>
                </div>
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                    <div class="row g-3">
                        <div class="col-md-12 p-4" >

                            <form method="POST"  action="{{route('UpdateOurTeam')}}" enctype="multipart/form-data">

                                @csrf
                                <input type="hidden"  name="member_id" value="{{$member->id}}"  >
                            
                            <div class="row g-3">
                           


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Member Name</label>

                                <div>
                                    <input type="text" value="{{$member->member_name}}" class="form-control"  name="member_name" placeholder=" Write Member Name" required>
                                    
                                </div>
                            </div>




                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Leader</label>

                                <div>
                                    <select class="form-control" id="exampleFormControlSelect1" name="leader_id">
                                    <option value="{{$member->leader_id}}" selected >Choose Leader</option>
                        @foreach(App\Models\payroll\OurTeam::where('deleted','=','No')->get() as $leader)
                                        <option value="{{$leader->id}}">{{$leader->member_name}}</option>
                        @endforeach                                            
                                    </select>   
                                </div>
                            </div>


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Priority</label>
                                <div>
                                    <input type="text" class="form-control"  name="priority" value="{{$member->priority}}" >                  
                                </div>
                            </div>



                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Desingnation</label>

                                <div>
                                    <input type="text" value="{{$member->member_desingnation}}" class="form-control"  name="member_desingnation" placeholder=" Write Member Desingnation" required>
                                    
                                </div>
                            </div>



                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Mobile Number</label>

                                <div>
                                    <input type="text" value="{{$member->mobile_number}}" class="form-control"  name="mobile_number" placeholder="018XXXXXXXX" required>
                                    
                                </div>
                            </div>




                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Address</label>

                                <div>
                                    <input type="text" class="form-control"  name="address" placeholder="Write Address" value="{{$member->address}}">
                                    
                                </div>
                            </div>





                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Job Location</label>

                                <div>
                                    <input type="text" class="form-control"  name="job_location" value="{{$member->job_location}}" >
                                    
                                </div>
                            </div>


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Education</label>

                                <div>
                                    <input type="text" value="{{$member->member_education}}" class="form-control"  name="member_education" placeholder="Write About Your Educational Background Please" >
                                    
                                </div>
                            </div>




                            <div class="form-group mb-3 col-md-12">
                                <label for="carousalCaptionOffer" class="form-label">Description</label>

                                <div>
                                    <textarea type="text"  class="form-control"  name="description" placeholder="Write something about you" >{{$member->description}}</textarea>
                                    
                                </div>
                            </div>


                            
                            


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Joining Date</label>

                                <div>
                                <input type="date" value="{{$member->joining_date}}" class="form-control"  name="joining_date" placeholder="Enter Joining Date" required>
                                    
                                </div>
                            </div>





                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Is he Present Now</label>

                                <div>
                                    <select class="form-control" id="exampleFormControlSelect1" name="is_employee">
                                        <option value="{{$member->is_employee}}" selected >{{$member->is_employee}}</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>   
                                </div>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Left Job At</label>

                                <div>
                                <input type="date"  value="{{$member->job_left_date}}" class="form-control"  name="job_left_date" placeholder="if he is not in team anymore" >
                                    
                                </div>
                            </div>






                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Current Grade</label>

                                <div>
                                    <select class="form-control" id="current_grade" name="current_grade" onchange="loadStep()">
                                        <option value="{{$member->current_grade}}" selected >Choose Grade</option>
                        @foreach(App\Models\payroll\Grade::all()->where('status','=','Active')->where('deleted','=','No') as $grd)
                                        <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                        @endforeach
                                    </select>   
                                </div>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Current Step</label>

                                <div>
                                    <select class="form-control" id="current_step" name="current_step" onchange="salryGenerate()">
                                    <option value="{{$member->current_step}}" selected >Choose Steps</option>
                        @foreach(App\Models\payroll\Steps::all()->where('status','=','Active')->where('deleted','=','No')->sortByDesc('id') as $stps)
                                        <option value="{{$stps->id}}">{{$stps->step_name}}</option>
                        @endforeach                                         
                                    </select>   
                                </div>
                            </div>



                          

                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Group</label>
                                <div>
                                    <select class="form-control" id="exampleFormControlSelect1" name="group_id">
                                    <option value="{{$member->group_id}}" selected >Choose Group</option>
                        @foreach(App\Models\payroll\Group::all()->where('status','=','Active')->where('deleted','=','No')->sortByDesc('id') as $grps)
                                        <option value="{{$grps->id}}">{{$grps->groupName}}</option>
                        @endforeach                                            
                                    </select>   
                                </div>
                            </div>




                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Salary Sheet</label>

                                <div>
                                    <select class="form-control" id="exampleFormControlSelect1" name="sheet_id" >
                                    <option value="{{$member->sheet_id}}" selected >Choose sheet</option>
                        @foreach(App\Models\payroll\SalarySheet::all()->where('status','=','Active')->where('deleted','=','No') as $sSheet)
                                        <option value="{{$sSheet->id}}">{{$sSheet->sheet_name}}</option>
                        @endforeach                                            
                                    </select>   
                                </div>
                            </div>


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Salary Type</label>
                                <div>
                                    <select class="form-control" id="exampleFormControlSelect1" name="salary_type">
                                    <option value="{{$member->salary_type}}" selected >{{$member->salary_type}}</option>    
                                    <option value="consulate">Consulate</option>    
                                    <option value="scale">Scale</option>                                    
                                    </select>   
                                </div>
                            </div>





                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Working Hour</label>

                                <div>
                                    <input type="text" class="form-control"  name="working_hour" value="{{$member->working_hour}}" >
                                    
                                </div>
                            </div>




                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Referred By<span style="color:red;">(Optional)</span></label>

                                <div>
                                    <input type="text" value="{{$member->referred_by}}" class="form-control"  name="referred_by"  >
                                    
                                </div>
                            </div>



                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Account Number</label>
                                <div>
                                    <input type="text" class="form-control"  name="account_no" value="{{$member->account_no}}" >                  
                                </div>
                            </div>


                            <div class="form-group mb-3 col-md-6">
                                <label for="carousalCaptionOffer" class="form-label">Salary<span style="color:red;">(Optional)</span></label>
                                <div>
                                    <input type="text" value="{{$member->salary}}" class="form-control"  name="salary"  >
                                </div>
                            </div>







                            <div class="col-md-12 row">
                                    <div class="form-group mb-3  col-md-4" >
                                        <label for="carousalCaptionOffer" class="form-label">Laundry:</label>
                                        <div>
                                            <input type="text" class="form-control"  name="laundry" value="{{$member->laundry}}" >                                                
                                        </div>
                                    </div>
                                    <div class="form-group mb-3  col-md-4">
                                        <label for="carousalCaptionOffer" class="form-label">phone Bill:</label>
                                        <div>
                                        <input type="text" class="form-control" name="phone_bill" value="{{$member->phone_bill}}" >                                                
                                        </div>
                                    </div>
                                    <div class="form-group mb-3  col-md-4">
                                        <label for="carousalCaptionOffer" class="form-label">TA/Da:</label>
                                        <div>
                                        <input type="text" class="form-control" name="ta_da" value="{{$member->ta_da}}">                                                 
                                        </div>
                                    </div>
                            </div>


                            <div class="form-group mb-3 col-md-12">
                                <label for="carousalCaptionOffer" class="form-label">Social Site Links</label>
                                <div>
                                    <textarea type="text"  class="form-control"  name="social_links" placeholder="Write your facebook url" >{{$member->social_links}}</textarea>
                                </div>
                            </div>


                            <div class="form-group mb-3 col-md-12">
                                <label for="carousalCaptionOffer" class="form-label">Add Note<span style="color:red;">(Optional)</span></label>

                                <div>
                                    <textarea type="text" value="{{$member->short_note}}" class="form-control"  name="short_note" placeholder="Do you want to add any note?" >{{$member->short_note}}</textarea>
                                    
                                </div>
                            </div>





                            
                           
                           
                            <div class="form-group mb-3 col-md-6">
                                <label for="Bannerstatus" class="form-label">Status</label>  
                                <div>
                                    <select class="form-control"  id="Bannerstatus" name="status" >
                                        <option value="{{$member->status}}" selected >{{$member->status}}</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>                                     
                                </div>
                            </div> 



                            <div class="form-group mb-3 col-md-6">
                                <label for="Memberimage" class="form-label">Member Image</label>
                                <div>
                                    <input type="hidden" name="oldImage" value="{{ $member->member_image }}">
                                    <input type="file" class="form-control" id="member_image" name="member_image"  onchange="loadPreview(this);">
                                    <span style="color:gray;">Image should must be 1600*500 Size</span>                                     
                                </div>
                                <div class="col-sm-5">
                                <img id="preview_img" src="{{asset('upload/employee_image/'.trim($member->member_image))}}" alt="Select image to preview!" style="width:100px;height:100px;" />
                                </div>
                            </div>


                        </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary btn-flat" name="addBanner"><i class="fa fa-save"></i> Save </button>
                            </div>
                        
                        </form>



                             <!-- image preview -->
                        <div class=" form-group mb-3 row">
                        <div class="col-md-3"></div>
                            <div class=" col-md-9">
                                <label for="profile_image">Image Preview</label>
                                <img id="preview_img" src="" alt=" Select image to preview!" class="" width="500" height="200"/>
                            </div>

                        </div>

                        <!-- image preview end -->

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
       // alert(current_grade); 
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