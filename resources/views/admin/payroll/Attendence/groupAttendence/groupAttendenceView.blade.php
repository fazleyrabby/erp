@extends('admin.master')
@section('title')
Admin Group Attendence
@endsection
@section('content')

    
            <div class="card">
                <div class="card-header">
                
                    <h3 style=" float: left;">Monthly Attendence</h3> 
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label">Group</label>
                            <select class="form-select form-select-sm" id="group_id"  name="group_id" onchange="getMonthYear()">
                            <option value="" selected disabled>Choose group</option>
                            @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                            </select>
                            <span class="text-danger" id="group_idError"></span>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label">Month Year</label>
                            <select class="form-select form-select-sm" id="month_year" name="month_year" onchange="getDatesFromTo()">
                                <option value="" selected>Choose Month Year</option>
                            </select>
                            <span class="text-danger" id="month_yearError"></span>
                        </div>
                    </div>
                    
                     
                    <div class="table-responsive" id="d_table">
                       
                    </div>
                </div>
            </div>
                
        @endsection



@section('contentJavaScripts')

<script>


    $(document).ready(function() {
        $('#attendenceTable').DataTable();
    });




    function getMonthYear(){
        var group_id=$('#group_id').val();
        $.ajax({
            url: "{{route('getGroupMonthYear')}}",
               method:"GET",
               data:{"group_id":group_id},
               success:function(result){
                //alert(JSON.stringify(result));
                $('#month_year').html(result);
               }, error: function(response) {
                //alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
            })
    }
    

    function getDatesFromTo(){
        var month_year=$('#month_year').val();
        var group_id=$('#group_id').val();
        $.ajax({
            url: "{{route('getMonthYearDatesFromTo')}}",
               method:"GET",
               data:{"month_year":month_year,"group_id":group_id},
               success:function(result){
                    //alert(JSON.stringify(result));
                    $('#d_table').html(result);
                    $(document).ready(function() {
                        $('#attendenceTable').DataTable();
                    });
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