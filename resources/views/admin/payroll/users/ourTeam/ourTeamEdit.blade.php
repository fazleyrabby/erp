@extends('admin.master')
@section('title')
Admin Create Team Member
@endsection
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Edit team Member</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                       
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header py-3">
                            <span class="text">Edit Team Member</span>
                        </div>
                        <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        <div class="row g-3">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">

                                <form method="POST"  action="{{route('UpdateOurTeam')}}">

                                    @csrf
                                    <input type="hidden"  name="member_id" value="{{$member->member_id}}"  >
                                <div class="form-group mb-3 row">
                                    <label for="Memberimage" class="col-sm-3 col-form-label">Member Image</label>

                                    <div class="col-sm-9">
                                        <input type="file"  class="form-control" name="member_image"   onchange="loadPreview(this);"  accept="image/*">
                                        <!-- <input type="file" name="profile_image" id="profile_image"  onchange="loadPreview(this);" class="form-control"> -->
                                        <span style="color:gray;">Image should must be 1600*500 Size</span>                                     
                                    </div>
                                    <div class="col-sm-5">
                                    <img src = "{{ asset('/frontEnd/images/team/'.trim($member->member_image)) }}"  width="150" height="150" />
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Member Name</label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->member_name}}" class="form-control"  name="member_name" placeholder=" Write Member Name" required>
                                        
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Leader</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="leader_id">
                                        <option value="{{$member->leader_id}}" selected >Choose Leader</option>
                            @foreach(App\Models\PayRoll\OurTeam::where('deleted','=','No')->get() as $leader)
                                            <option value="{{$leader->member_id}}">{{$leader->member_name}}</option>
                            @endforeach                                            
                                        </select>   
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Priority</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="priority" value="{{$member->priority}}" >                  
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Desingnation</label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->member_desingnation}}" class="form-control"  name="member_desingnation" placeholder=" Write Member Desingnation" required>
                                        
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Mobile Number</label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->mobile_number}}" class="form-control"  name="mobile_number" placeholder="018XXXXXXXX" required>
                                        
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Address</label>

                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control"  name="address" placeholder="Write Address" required>{{$member->address}}</textarea>
                                        
                                    </div>
                                </div>





                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Job Location</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="job_location" value="{{$member->job_location}}" >
                                        
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Education</label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->member_education}}" class="form-control"  name="member_education" placeholder="Write About Your Educational Background Please" >
                                        
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Description</label>

                                    <div class="col-sm-9">
                                        <textarea type="text"  class="form-control"  name="description" placeholder="Write something about you" >{{$member->description}}</textarea>
                                        
                                    </div>
                                </div>


                                
                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Social Site Links</label>

                                    <div class="col-sm-9">
                                        <textarea type="text"  class="form-control"  name="social_links" placeholder="Write your facebook url" >{{$member->social_links}}</textarea>
                                        
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Joining Date</label>

                                    <div class="col-sm-9">
                                    <input type="date" value="{{$member->joining_date}}" class="form-control"  name="joining_date" placeholder="Enter Joining Date" required>
                                        
                                    </div>
                                </div>





                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Is he Present Now</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="is_employee">
                                            <option value="{{$member->is_employee}}" selected >{{$member->is_employee}}</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>   
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Left Job At</label>

                                    <div class="col-sm-9">
                                    <input type="date"  value="{{$member->job_left_date}}" class="form-control"  name="job_left_date" placeholder="if he is not in team anymore" >
                                        
                                    </div>
                                </div>






                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Current Grade</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="current_grade" name="current_grade" onchange="loadStep()">
                            @foreach(App\Models\PayRoll\Grade::all()->where('status','=','Active')->where('deleted','=','No') as $grd)
                                @if($member->current_grade == $grd->id)
                                    <option value="{{$grd->id}}" selected>{{$grd->grade_name}}</option>
                                @else
                                    <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                                @endif
                                
                                            
                            @endforeach
                                        </select>   
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Current Step</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="current_step" name="current_step" onchange="salryGenerate()">
                            @foreach(App\Models\PayRoll\Steps::all()->where('status','=','Active')->where('grade_id','=',$member->current_grade)->where('deleted','=','No')->sortByDesc('id') as $stps)
                                @if($member->current_step == $stps->id)
                                    <option value="{{$stps->id}}" selected>{{$stps->step_name}}</option>
                                @else
                                     <option value="{{$stps->id}}">{{$stps->step_name}}</option>
                                @endif
                                           
                            @endforeach                                         
                                        </select>   
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary</label>

                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control"  name="salary" id="salary" value="{{$member->salary}}"  >
                                        
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Group</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="group_id">
                            @foreach(App\Models\PayRoll\Group::all()->where('status','=','Active')->where('deleted','=','No')->sortByDesc('id') as $grps)
                                @if($member->group_id == $grps->id)
                                    <option value="{{$grps->id}}" selected="">{{$grps->group_name}}</option>
                                @else
                                     <option value="{{$grps->id}}">{{$grps->group_name}}</option>
                                @endif
                                            
                            @endforeach                                            
                                        </select>   
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary Sheet</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="sheet_id" >
                            @foreach(App\Models\PayRoll\SalarySheet::all()->where('status','=','Active')->where('deleted','=','No') as $sSheet)
                                @if($member->sheet_id == $sSheet->id)
                                    <option value="{{$sSheet->id}}" selected>{{$sSheet->sheet_name}}</option>
                                @else
                                     <option value="{{$sSheet->id}}">{{$sSheet->sheet_name}}</option>
                                @endif
                                            
                            @endforeach                                            
                                        </select>   
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="salary_type">
                                        <option value="{{$member->salary_type}}" selected >{{$member->salary_type}}</option> 
                                        @if($member->salary_type == 'consulate')
                                            <option value="consulate" selected>Consulate</option>    
                                        @else
                                            <option value="consulate">Consulate</option> 
                                        @endif
                                        
                                        @if($member->salary_type == 'scale')
                                            <option value="scale" selected>Scale</option>   
                                        @else
                                            <option value="scale">Scale</option> 
                                        @endif
                                        </select>   
                                    </div>
                                </div>





                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Working Hour</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="working_hour" value="{{$member->working_hour}}" >
                                        
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Referred By<span style="color:red;">(Optional)</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->referred_by}}" class="form-control"  name="referred_by"  >
                                        
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Account Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="account_no" value="{{$member->account_no}}" >                  
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary<span style="color:red;">(Optional)</span></label>

                                    <div class="col-sm-9">
                                        <input type="text" value="{{$member->salary}}" class="form-control"  name="salary"  >
                                    </div>
                                </div>







                                <div class="row" style="padding-top:50px;padding-bottom:50px;">
                                        <div class="form-group mb-3 row col-md-4" >
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">Laundry:</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"  name="laundry" value="{{$member->laundry}}" >                                                
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row col-md-4">
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">phone Bill:</label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="phone_bill" value="{{$member->phone_bill}}" >                                                
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row col-md-4">
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">TA/Da:</label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="ta_da" value="{{$member->ta_da}}">                                                 
                                            </div>
                                        </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Add Note<span style="color:red;">(Optional)</span></label>

                                    <div class="col-sm-9">
                                        <textarea type="text" value="{{$member->short_note}}" class="form-control"  name="short_note" placeholder="Do you want to add any note?" >{{$member->short_note}}</textarea>
                                        
                                    </div>
                                </div>





                                
                               
                               
                                <div class="form-group mb-3 row">
                                    <label for="Bannerstatus" class="col-sm-3 col-form-label">Status</label>  
                                    <div class="col-sm-9">
                                        <select class="form-control"  id="Bannerstatus" name="status" >
                                            <option value="{{$member->status}}" selected >{{$member->status}}</option>
                                            @if($member->status == 'Active')
                                                <option value="Active" selected>Active</option>
                                            @else
                                                <option value="Active">Active</option>
                                            @endif
                                            @if($member->status == 'Inactive')
                                                <option value="Inactive" selected>In-active</option>
                                            @else
                                                <option value="Inactive">In-active</option>
                                            @endif
                                            
                                        </select>                                     
                                    </div>
                                </div> 

                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary btn-flat" name="addBanner"><i class="fa fa-save me-1"></i>Save </button>
                                </div>
                            </form>



                                 <!-- image preview -->
                            <div class=" form-group mb-3 row">
                            <div class="col-md-3"></div>
                                <div class=" col-md-9">
                                    <label for="profile_image">Image Preview</label>
                                    <!-- <img id="preview_img" src="http://w3adda.com/wp-content/uploads/2019/09/No_Image-128.png" class="" width="600px" height="200"/> -->
                                    <img id="preview_img" src="" alt=" Select image to preview!" class="" width="500" height="200"/>
                                </div>

                            </div>

                            <!-- image preview end -->

                            </div>
                            <div class="col-md-2"></div>
                           
                        </div>
                    </div>
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
                    .width(500)
                    .height(200);
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