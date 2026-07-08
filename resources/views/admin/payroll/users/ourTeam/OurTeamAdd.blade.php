@extends('admin.master')
@section('title')
Admin Create Team Member
@endsection
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Create team Member</h3>
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
                            <span class="text">Add Team Member</span>
                        </div>
                        <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        <div class="row g-3">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">

                                <form method="POST"  action="{{ route('storeTeamMember') }}">

                                    @csrf
                                <div class="form-group mb-3 row">
                                    <label for="Memberimage" class="col-sm-3 col-form-label">Member Image</label>

                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="member_image"   onchange="loadPreview(this);"  accept="image/*">
                                        <!-- <input type="file" name="profile_image" id="profile_image"  onchange="loadPreview(this);" class="form-control"> -->
                                        <span style="color:gray;">Image should must be 1600*500 Size</span>
                                        <span class="text-danger">{{$errors->has('member_image')?$errors->first('member_image'):''}}</span>
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Member Name</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="member_name" placeholder=" Write Member Name" required>
                                        <span class="text-danger">{{$errors->has('member_name')?$errors->first('member_name'):''}}</span>
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Leader</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="leader_id">
                                        <option value="0" selected >Choose Leader</option>
                            @foreach(App\Models\PayRoll\OurTeam::where('deleted','=','No')->get() as $leader)
                                            <option value="{{$leader->member_id}}">{{$leader->member_name}}</option>
                            @endforeach                                            
                                        </select>   
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Priority</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="priority" >                  
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Desingnation</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="member_desingnation" placeholder=" Write Member Desingnation" required>
                                        <span class="text-danger">{{$errors->has('member_desingnation')?$errors->first('member_desingnation'):''}}</span>
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Mobile Number</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="mobile_number" placeholder="018XXXXXXXX" required>
                                        <span class="text-danger">{{$errors->has('mobile_number')?$errors->first('mobile_number'):''}}</span>
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Address</label>

                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control"  name="address" placeholder="Write Address" required></textarea>
                                        <span class="text-danger">{{$errors->has('address')?$errors->first('address'):''}}</span>
                                    </div>
                                </div>








                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Job Location</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="job_location"  >
                                        
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Education</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="member_education" placeholder="Write About Your Educational Background Please" >
                                        <span class="text-danger">{{$errors->has('member_education')?$errors->first('member_education'):''}}</span>
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Description</label>

                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control"  name="description" placeholder="Write something about you" ></textarea>
                                        <span class="text-danger">{{$errors->has('description')?$errors->first('description'):''}}</span>
                                    </div>
                                </div>


                                
                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Social Site Links</label>

                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control"  name="social_links" placeholder="Write your facebook url" ></textarea>
                                        
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Joining Date</label>

                                    <div class="col-sm-9">
                                    <input type="date" class="form-control"  name="joining_date" placeholder="Enter Joining Date" required>
                                        
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Is he Present Now</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="is_employee">
                                            <option value="0" selected disabled>Choose</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select> 
                                        <span class="text-danger">{{$errors->has('is_employee')?$errors->first('is_employee'):''}}</span>  
                                    </div>
                                </div>


                                

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Left Job At</label>

                                    <div class="col-sm-9">
                                    <input type="date" class="form-control"  name="job_left_date" placeholder="if he is not in team anymore" >
                                        
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Current Grade</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="current_grade" onchange="loadStep()" name="current_grade">
                                            <option value="0" selected >Choose Grade</option>
                            @foreach(App\Models\PayRoll\Grade::all()->where('status','=','Active')->where('deleted','=','No') as $grd)
                                            <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                            @endforeach
                                        </select>   
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Current Step</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="current_step" onchange="salryGenerate()" name="current_step">
                                        <option value="0" selected >Choose Steps</option>
                                                               
                                        </select>   
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary</label>

                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control"  name="salary" id="salary"  >
                                        <span class="text-danger">{{$errors->has('salary')?$errors->first('salary'):''}}</span>
                                    </div>
                                </div>

                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Group</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="group_id">
                                        <option value="0" selected >Choose Group</option>
                            @foreach(App\Models\PayRoll\Group::all()->where('status','=','Active')->where('deleted','=','No')->sortByDesc('id') as $grps)
                                            <option value="{{$grps->id}}">{{$grps->group_name}}</option>
                            @endforeach                                            
                                        </select>   
                                        <span class="text-danger">{{$errors->has('group_id')?$errors->first('group_id'):''}}</span>
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary Sheet</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="sheet_id">
                                        <option value="0" selected >Choose sheet</option>
                            @foreach(App\Models\PayRoll\SalarySheet::all()->where('status','=','Active')->where('deleted','=','No') as $sSheet)
                                            <option value="{{$sSheet->id}}">{{$sSheet->sheet_name}}</option>
                            @endforeach                                            
                                        </select>   
                                        <span class="text-danger">{{$errors->has('sheet_id')?$errors->first('sheet_id'):''}}</span>
                                    </div>
                                </div>





                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Working Hour</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="working_hour" placeholder="Write working hour" >
                                        <span class="text-danger">{{$errors->has('working_hour')?$errors->first('working_hour'):''}}</span>
                                    </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Referred By<span style="color:red;">(Optional)</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="referred_by"  >                  
                                    </div>
                                </div>


                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Salary Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exampleFormControlSelect1" name="salary_type">
                                            <option value="0" selected disabled>Choose Salary Type</option>    
                                            <option value="consulate">Consulate</option>    
                                            <option value="scale">Scale</option>                                    
                                        </select>   
                                        <span class="text-danger">{{$errors->has('salary_type')?$errors->first('salary_type'):''}}</span>
                                    </div>
                                </div>



                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Account Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"  name="account_no"  >   
                                        <span class="text-danger">{{$errors->has('account_no')?$errors->first('account_no'):''}}</span>               
                                    </div>
                                </div>


                              



                                <div class="row" style="padding-top:50px;padding-bottom:50px;">
                                        <div class="form-group mb-3 row col-md-4" >
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">Laundry:</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"  name="laundry"  >                                                
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row col-md-4">
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">phone Bill:</label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="phone_bill"  >                                                
                                            </div>
                                        </div>
                                        <div class="form-group mb-3 row col-md-4">
                                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">TA/Da:</label>
                                            <div class="col-sm-7">
                                            <input type="text" class="form-control" name="ta_da" >                                                 
                                            </div>
                                        </div>
                                </div>




                                <div class="form-group mb-3 row">
                                    <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Add Note<span style="color:red;">(Optional)</span></label>

                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control"  name="short_note" placeholder="Do you want to add any note?" ></textarea>
                                        <span class="text-danger">{{$errors->has('short_note')?$errors->first('short_note'):''}}</span>
                                    </div>
                                </div>





                                
                               
                               
                                <div class="form-group mb-3 row">
                                    <label for="Bannerstatus" class="col-sm-3 col-form-label">Status</label>

                                    <div class="col-sm-9">
                                        <select class="form-control" id="Bannerstatus" name="status" >
                                            <option value="" selected>- Select One -</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">In-active</option>
                                        </select>
                                       
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