@extends('admin.master')
@section('title')
Admin Monthly Attendence
@endsection
@section('content')

    
            <div class="card">
                <div class="card-header">
                    <h3 style=" float: left;">Monthly Attendence</h3> 
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-sm-3">
                            <label class="form-label">Employee</label>
                            <select class="form-select form-select-sm" id="employee_id" required>
                                <option value="" selected disabled>Choose Employee</option>
                                @foreach($teams as $team)
                                    <option value="{{$team->id}}">{{$team->member_name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{$errors->has('employee_id')?$errors->first('employee_id'):''}}</span>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control form-control-sm" id="date_from" value="{{ date('Y-m-01') }}"> 
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control form-control-sm" id="date_to" value="{{ date('Y-m-d') }}"> 
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="generateAttendence()">Generate Attendence</button>
                        </div>
                    </div>
                    <div class="header mt-4" id="header"></div>
                    <div class="table-responsive mt-3" >
                        <table id="attendenceTable" class="table table-vcenter table-bordered table-striped"></table>
                    </div>
                </div>
            </div>
                
        @endsection



@section('contentJavaScripts')

<script>


     $(document).ready(function() {
        $('#attendenceTable').DataTable();
    }); 


    function generateAttendence(){
        var employee_id=$('#employee_id').val();
        var date_from=$('#date_from').val();
        var date_to=$('#date_to').val();
       
        $.ajax({
            url: "{{route('getMonthlyAttendence')}}",
               method:"GET",
               data:{"employee_id":employee_id,"date_from":date_from,"date_to":date_to},
               success:function(result){
                    //alert(JSON.stringify(result));
                    $('#attendenceTable').html(result.data);
                    $('#header').html(result.header);
                   
               }, error: function(response) {
                    //alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
            }) 
    }
  




        


</script>

@endsection