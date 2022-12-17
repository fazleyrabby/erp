@extends('admin.master')
@section('title')
Admin Attendence -Add
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content box-border">
            <div class="card">
                <div class="card-header">
                
                    <h3 style=" float: left;">Add Attendence</h3> 
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="attendence-card" >
                        <span><strong>Entry Time</strong></span>
                        <br><br>
                        <form action="{{route('attendenceStore')}}" method="POST">
                            @csrf
                            <input type="hidden" name="month_year" value="{{Date('F-Y')}}">
                            <div class="row">
                                <div class="form-group  col-sm-5">
                                    <label >Employee:</label>
                                    <select class="form-control" id="employee_id"  name="employee_id" required>
                                    <option value="" selected disabled>Choose Employee</option>
                                    @foreach($teams as $team)
                                    <option value="{{$team->id}}">{{$team->member_name}}</option>
                                    @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->has('employee_id')?$errors->first('employee_id'):''}}</span>
                                </div>
                                <div class="form-group  col-sm-5">
                                    <label  >Date:</label>
                                    <input type="text" class="form-control " id="date" name="date" value="{{ date('Y-m-d') }}" readonly> 
                                </div>
                                <div class="col-md-2">
                                    <label  >.</label><br>
                                    <button class="btn btn-primary " type="submit" > Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <h3 style=" float: left;" >Attendence List</h3> 
                    <div class="table-responsive" >
                        <table  id="attendenceTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="20%">Employee Name</th>
                                    <th width="10%">Date</th>                                     
                                    <th width="15%">Working Hours</th>
                                    <th width="15%">Entry Time</th>
                                    <th width="15%">Departure Time</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Machine ID</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php 
                                $sl=0;
                                $color='';
                                $incolor='';
                                $outcolor='';
                                @endphp

                                @foreach($attendences as $attendence)

                                    @php 
                                        if($attendence->shift_status == 'Completed'){
                                            $color='text-success';
                                        }else{
                                            $color='text-danger';
                                        } 

                                        if($attendence->time_in_status == 'Late'){
                                            $incolor='text-danger';
                                        }else{
                                            $incolor='text-success';
                                        }


                                        if($attendence->time_out_status == 'Early'){
                                            $outcolor='text-danger';
                                        }else{
                                            $outcolor='text-success';
                                        }
                                    @endphp
                                    
                                    
                                <tr>
                                    <td class="text-center">{{++$sl}}</td>
                                    <td>{{$attendence->member_name}}</td>
                                    <td class="text-center">{{$attendence->date}}</td>
                                    <td style="text-align:center;">{{$attendence->working_hour}} </td>
                                    <td class="text-center">{{$attendence->time_in}} ({{$attendence->time_in_difference}} <span class="{{$incolor}}">{{$attendence->time_in_status}})</span></td>
                                    <td class="text-center">{{$attendence->time_out}} ({{$attendence->time_out_difference}} <span class="{{$outcolor}}">{{$attendence->time_out_status}})</span></td>
                                    <td class="{{$color}} text-center" >{{$attendence->shift_status}}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
                
        </section>
    </div>

@endsection



@section('contentJavaScripts')

<script>


    $(document).ready(function() {
        $('#attendenceTable').DataTable();
    });


    
    function getEntryDate(){
        var employee_id=$('#employee_id').val();
        var date=$("#date").val();
        if(employee_id!='0'  &&  date !='0'){

            $.ajax({
            url: "{{route('getEntryData')}}",
               method:"GET",
               data:{"employee_id":employee_id,"date":date},
               success:function(result){
                    $("#entry_time").val(result);
                //alert(JSON.stringify(result));
               }, error: function(response) {
                   // alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
            })

            }
        }




        


</script>

@endsection