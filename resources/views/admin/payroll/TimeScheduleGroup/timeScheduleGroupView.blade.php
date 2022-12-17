@extends('admin.master')
@section('title')
Admin Time Schedule -Add
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content box-border" >    
        <div class="card ">
            <div class="card-header">
                <h3 style=" float: left;">Create Time Schedule Group</h3>
                <h3 class="text-center text-success">{{Session::get('message')}}</h3>
            </div>
            <div class="card-body">
                <form id="timeScheduleAddForm">
                        @csrf
                    <div class="row ">
                        <div class="form-group  col-md-6">
                            <label >Group Name:</label>
                            <select class="form-control" id="group_id" name="group_id" required>
                                <option value=""selected disabled>Choose Group</option>
                                @foreach($groups as $grps)
                                <option value="{{$grps->id}}">{{$grps->name}}</option>
                                @endforeach                                            
                            </select>  
                            <span class="text-danger" id="group_nameError"></span>
                        </div>
                        <div class="form-group  col-md-6">
                            <label >Time From:</label>
                            <input type="text" class="form-control " id="time_from" name="time_from" placeholder="Enter time from">
                            <span class="text-danger" id="time_fromError"></span>
                        </div>
                        <div class="form-group  col-md-6">
                            <label >Time To:</label>
                            <input type="text" class="form-control " id="time_to" name="time_to" placeholder="Enter time to">
                            <span class="text-danger" id="time_toError"></span>
                        </div>
                        <div class="form-group  col-md-6">
                            <label >Working Hour:</label>
                            <input type="text" class="form-control " id="working_hour" name="working_hour" placeholder="Enter working hour">
                            <span class="text-danger" id="working_hourError"></span>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary float-right" type="submit"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>

                <div class="table" style="padding-top:10px;">
                    <div class="col-sm-6">
                        <h3>Time Schedule List</h3>
                    </div>
                    <table  id="timeScheduleTable" class="table table-bordered table-striped" >
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Group Name</th>
                                <th>Time From</th>                                     
                                <th>Time To</th>
                                <th>Working Hour</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

            </div>     
        </div>
    </section>
    </div>

<!-- edit modal -->
<div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
			<form id="scheduleGroupEditForm" >
            @csrf
                <div class="modal-header">
                   
                    <h4 class="modal-title">Edit Schedule Group</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
                
					<div class="row">	
                                         
                      <input type="hidden" name="editId" id="editId">
                     
                    <div class="form-group col-md-6">
                        <label for="carousalCaptionOffer" >Group Name </label>
                        <select class="form-control" id="edit_group_id" name="edit_group_id" required>
                            <option value="" selected disabled>Choose Group</option>
                            @foreach($groups as $grps)
                            <option value="{{$grps->id}}">{{$grps->name}}</option>
                            @endforeach                                            
                        </select>  
                        <span class="text-danger" id="editgroup_nameError"></span>   
                    </div>


                    <div class="form-group col-md-6">
                        <label for="carousalCaptionOffer" >Work Hour</label>
                        <input type="text" class="form-control"  id="edit_work_hour" name="edit_work_hour"  required>  
                        <span class="text-danger" id="edit_work_hourError"></span>  
                    </div>

                    <div class="form-group col-md-6">
                        <label for="carousalCaptionOffer" >Time From</label>
                        <input type="text" class="form-control"  id="edit_time_from" name="edit_time_from" required>  
                        <span class="text-danger" id="edittime_fromError"></span>                      
                    </div>

                    <div class="form-group col-md-6">
                          <label for="carousalCaptionOffer" >Time To</label>
                            <input class="form-control input-sm" id="edit_time_to" type="text" name="edit_time_to" required>
                            <span class="text-danger" id="edittime_toError"></span>
                    </div>

                      <div class="form-group col-md-6">
                          <label  for="carousalCaptionOffer" > Status</label>
                            <select id="editStatus" name="editStatus" class="form-control input-sm" >
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                      </div>
				 </div>
              </div>
			  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btnUpate float-right"><i class="fa fa-save"></i> Update</button>
                 </div>
				 </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection



@section('contentJavaScripts')
<script>

           

                /*get data*/
            var table;
                $(document).ready(function() {
                    table = $('#timeScheduleTable').DataTable({
                        'ajax': "{{route('getScheduleGroupData')}}",
                        processing:true,
                    }); 
                });

                
 
        
                /* store data*/
                $('#timeScheduleAddForm').submit(function(e){
                    e.preventDefault();
                    
                        var group_id = $("#group_id").val();
                        var time_from = $("#time_from").val();
                        var time_to = $("#time_to").val();
                        var working_hour = $("#working_hour").val();
                        var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                        fd.append('group_id',group_id);
                        fd.append('time_from',time_from);
                        fd.append('time_to',time_to);
                        fd.append('working_hour',working_hour);
                        fd.append('_token',_token);
                    
                    $.ajax({
                        url:"{{route('scheduleDataAdd')}}", 
                        method:"POST",
                        data:fd,
                        contentType: false,
                        processData: false,
                        datatype:"json",
                        success:function(result){
                        
                        Swal.fire("Saved!",result.success,"success");
                        window.location.reload();  
                        }, 
                        error: function(response) {
                            //alert(JSON.stringify(response));
                            $('#group_nameError').text(response.responseJSON.errors.group_id);
                            $('#time_fromError').text(response.responseJSON.errors.time_from);
                            $('#time_toError').text(response.responseJSON.errors.time_to);
                            $('#working_hourError').text(response.responseJSON.errors.working_hour);

                        }, beforeSend: function () {
                            $('#loading').show();
                        },complete: function () {
                            $('#loading').hide();
                        }
                    })
                });





            function editScheduleGroup(id){
		
                $.ajax({
                    url:"{{route('editScheduleGroup')}}",
                    method:"GET",
                    data:{"id":id},
                    datatype:"json",
                    success:function(result){
                        $("#editModal").modal('show');
                        $("#edit_group_id").val(result.group_id);
                        $("#edit_time_from").val(result.time_from);
                        $("#edit_time_to").val(result.time_to);
                        $("#edit_work_hour").val(result.working_hour);
                        $("#editId").val(result.id);
                       
                        if(result.status != ""){
                            $("#editStatus").val(result.status);
                        }else{
                            $("#editStatus").val("Inactive");
                        }
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                });
            }



        $("#scheduleGroupEditForm").submit(function (e){
            e.preventDefault();
            var group_id = $("#edit_group_id").val();
         
            var time_from = $("#edit_time_from").val();
            var time_to = $("#edit_time_to").val();
            var working_hour = $("#edit_work_hour").val();
            var status = $("#editStatus").val();
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();

                fd.append('group_id',group_id);
                fd.append('time_from',time_from);
                fd.append('time_to',time_to);
                fd.append('working_hour',working_hour);
                
                fd.append('status',status);
                fd.append('id',id);
                fd.append('_token',_token);
        $.ajax({
            url:"{{route('scheduleGroupUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
               
                $("#editModal").modal('hide');
                Swal.fire("Updated Step!",result.success,"success");
                table.ajax.reload(null, false);
             }, error: function(response) {
                //alert(JSON.stringify(response));
                $('#editgroup_nameError').text(response.responseJSON.errors.group_id);
                $('#edittime_fromError').text(response.responseJSON.errors.time_from);
                $('#edittime_toError').text(response.responseJSON.errors.time_to);
                $('#edit_work_hourError').text(response.responseJSON.errors.working_hour);
               
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });


        function confirmDelete(id) {
           // alert(id);
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Sheet!",
                closeOnConfirm: false
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url:"{{route('scheduleDataDelete')}}",
                    method: "POST",
                    data: {"id":id, "_token":_token},
                    success: function (result) {
                        Swal.fire("Done!",result.success,"success");
                        table.ajax.reload(null, false);
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                });
            }else{
            Swal.fire("Cancelled", "Your imaginary Sheet is safe :)", "error");
            }
        })            
        }

</script>
@endsection 