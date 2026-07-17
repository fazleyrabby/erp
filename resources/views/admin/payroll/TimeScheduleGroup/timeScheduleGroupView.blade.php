@extends('admin.master')
@section('title')
Admin Time Schedule -Add
@endsection
@section('content')
    
        <section class="content box-border" >    
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title">Time Schedule Group</h3>
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('timeScheduleGroupIndex') }}" searchPlaceholder="Search schedule groups..." :sortOptions="['id' => 'ID', 'schedule_name' => 'Name']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <form id="timeScheduleAddForm">
                            @csrf
                        <div class="row ">
                            <div class="form-group mb-3  col-md-6">
                                <label >Group Name:</label>
                                <select class="form-control" id="group_id" name="group_id" required>
                                    <option value=""selected disabled>Choose Group</option>
                                    @foreach($groups as $grps)
                                    <option value="{{$grps->id}}">{{$grps->name}}</option>
                                    @endforeach                                            
                                </select>  
                                <span class="text-danger" id="group_nameError"></span>
                            </div>
                            <div class="form-group mb-3  col-md-6">
                                <label >Time From:</label>
                                <input type="text" class="form-control " id="time_from" name="time_from" placeholder="Enter time from">
                                <span class="text-danger" id="time_fromError"></span>
                            </div>
                            <div class="form-group mb-3  col-md-6">
                                <label >Time To:</label>
                                <input type="text" class="form-control " id="time_to" name="time_to" placeholder="Enter time to">
                                <span class="text-danger" id="time_toError"></span>
                            </div>
                            <div class="form-group mb-3  col-md-6">
                                <label >Working Hour:</label>
                                <input type="text" class="form-control " id="working_hour" name="working_hour" placeholder="Enter working hour">
                                <span class="text-danger" id="working_hourError"></span>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary float-right" type="submit"><i class="fas fa-save me-1"></i>Save</button>
                            </div>
                        </div>
                    </form>

                    <div class="table" style="padding-top:10px;">
                        <table class="table table-bordered table-striped table-hover" >
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
                            <tbody>
                                @forelse ($items as $i => $group)
                                <tr>
                                    <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $group->id }}" /></td>
                                    <td>{{ $group->groupName }}</td>
                                    <td>{{ $group->time_from }}</td>
                                    <td>{{ $group->time_to }}</td>
                                    <td>{{ $group->working_hour }}</td>
                                    <td>
                                        @if ($group->status == 'Active')
                                        <center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $group->status }}"></i></center>
                                        @else
                                        <center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $group->status }}"></i></center>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-grade">
                                             <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 <i class="fas fa-cog"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                 <a class="dropdown-item" href="#" onclick="editScheduleGroup({{ $group->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                 <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $group->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                             </div>
                                         </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No schedule groups found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $items->links() }}
                        </div>
                    </div>

                </div>     
            </div>
        <!-- edit modal -->
<div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
			<form id="scheduleGroupEditForm" >
            @csrf
                <div class="modal-header">
                   
                    <h4 class="modal-title">Edit Schedule Group</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                
					<div class="row g-3">	
                                         
                      <input type="hidden" name="editId" id="editId">
                     
                    <div class="form-group mb-3 col-md-6">
                        <label for="carousalCaptionOffer" >Group Name </label>
                        <select class="form-control" id="edit_group_id" name="edit_group_id" required>
                            <option value="" selected disabled>Choose Group</option>
                            @foreach($groups as $grps)
                            <option value="{{$grps->id}}">{{$grps->name}}</option>
                            @endforeach                                            
                        </select>  
                        <span class="text-danger" id="editgroup_nameError"></span>   
                    </div>


                    <div class="form-group mb-3 col-md-6">
                        <label for="carousalCaptionOffer" >Work Hour</label>
                        <input type="text" class="form-control"  id="edit_work_hour" name="edit_work_hour"  required>  
                        <span class="text-danger" id="edit_work_hourError"></span>  
                    </div>

                    <div class="form-group mb-3 col-md-6">
                        <label for="carousalCaptionOffer" >Time From</label>
                        <input type="text" class="form-control"  id="edit_time_from" name="edit_time_from" required>  
                        <span class="text-danger" id="edittime_fromError"></span>                      
                    </div>

                    <div class="form-group mb-3 col-md-6">
                          <label for="carousalCaptionOffer" >Time To</label>
                            <input class="form-control input-sm" id="edit_time_to" type="text" name="edit_time_to" required>
                            <span class="text-danger" id="edittime_toError"></span>
                    </div>

                      <div class="form-group mb-3 col-md-6">
                          <label  for="carousalCaptionOffer" > Status</label>
                            <select id="editStatus" name="editStatus" class="form-control input-sm" >
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                      </div>
				 </div>
              </div>
			  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btnUpate float-right"><i class="fa fa-save me-1"></i>Update</button>
                 </div>
				 </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection



@section('contentJavaScripts')
<script>

           



                
 
        
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
                location.reload();
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
            confirmDeleteSwal({
                url      : "{{route('scheduleDataDelete')}}",
                id       : id,
                itemName : 'Sheet',
            });
        }

</script>
@endsection 