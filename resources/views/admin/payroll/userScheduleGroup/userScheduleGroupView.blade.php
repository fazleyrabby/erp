@extends('admin.master')
@section('title')
Admin Time Schedule Group -View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <!-- card header -->
                <div class="card-header">
                    <h3 class="card-title">Time Schedule Group</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()"><i class="fas fa-plus"></i> Add Time Schedule</button>
                    </div>
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                </div>
                <!-- /.card-body -->
                <div class="card-body">
                    <x-filter-bar route="{{ route('userTimeGroupIndex') }}" searchPlaceholder="Search user schedules..." :sortOptions="['id' => 'ID']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Employee</th>
                                <th>Schedule Group</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $i => $schedule)
                            <tr>
                                <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $schedule->id }}" /></td>
                                <td>{{ $schedule->member_name }}</td>
                                <td>{{ $schedule->groupName }}</td>
                                <td>{{ $schedule->start_date }}</td>
                                <td>{{ $schedule->end_date }}</td>
                                <td>{{ $schedule->note }}</td>
                                <td>
                                    @if ($schedule->status == 'Active')
                                    <center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $schedule->status }}"></i></center>
                                    @else
                                    <center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $schedule->status }}"></i></center>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-grade">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#" onclick="editTimeSchedule({{ $schedule->id }})"><i class="fas fa-exchange-alt me-2"></i> Edit </a>
                                            <a class="dropdown-item" href="#" onclick="confirmDelete({{ $schedule->id }})"><i class="fas fa-edit me-2"></i> Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No user schedule groups found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>            
        </section>
    </div>

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
				<form id="scheduleGroupFormStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Time Schedule</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
					<div class="row">
                        <div class="form-group col-md-6">
                            <label  > Employee Name </label>
                            <select class="form-control" id="employee_id" name="employee_id" required>
                                <option value="0" selected disabled> Choose employee</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->member_name}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label> Schedule Group Name </label>
                            <select class="form-control" id="schedule_group_id" name="schedule_group_id" required>
                                <option value="0" selected disabled> Choose Group Schedule</option>
                            @foreach($schedules as $schedule)
                                <option value="{{$schedule->id}}">{{$schedule->name}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label> Start Date </label>
                                <input type="date" class="form-control"  name="start_date" id="start_date" required>
                        </div>

                    <div class="form-group col-md-6">
                        <label> End Date </label>
                        <input type="date" class="form-control"  name="end_date" id="end_date" >
                    </div>

                    <div class="form-group col-md-12">
                        <label> Note </label>
                        <textarea type="text" class="form-control"  name="note" id="note" placeholder="Add note"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                     <button type="submit" class="btn btn-primary " id="saveSheet"><i class="fa fa-save"></i> Save</button>
                </div>

			  </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Time Schedule</h4>
                    <button type="button" class="close"
                            data-bs-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-body">
					<div class="row">
                        <input type="hidden" name="editId" id="editId">
                        <div class="form-group row">
                            <label  class="col-sm-5 col-form-label"> Schedule Group Name </label>
                            <div class="col-sm-7">
                                <select class="form-control" id="edit_schedule_group_id" name="edit_schedule_group_id" required>
                                    <option value="0" selected disabled> Choose Group Schedule</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{$schedule->id}}">{{$schedule->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-sm-5 col-form-label"> Start Date </label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control"  name="edit_start_date" id="edit_start_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-sm-5 col-form-label"> End Date </label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control"  name="edit_end_date" id="edit_end_date" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label  class="col-sm-5 col-form-label"> Note </label>
                            <div class="col-sm-7">
                                <textarea type="text" class="form-control"  name="edit_note" id="edit_note" placeholder="Add note"></textarea>
                            </div>
                        </div>
				    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btnUpate" id="editGroup"><i class="fa fa-save"></i> Update</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection


@section('contentJavaScripts')


<script>
            /*Modal Show*/
            function create() {
                $("#modal").modal('show');
                }
                $('#modal').on('shown.bs.modal', function() {
                    $('#sheet_name').focus();
            })







            /* store data*/
            $('#scheduleGroupFormStore').submit(function(e){
                    e.preventDefault();
                    
                    var employee_id = $("#employee_id").val();
                    var schedule_group_id = $("#schedule_group_id").val();
                    var start_date = $("#start_date").val();
                    var end_date = $("#end_date").val();
                    var note = $("#note").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('employee_id',employee_id);
                    fd.append('schedule_group_id',schedule_group_id);
                    fd.append('start_date',start_date);
                    fd.append('end_date',end_date);
                    fd.append('note',note);
                    fd.append('_token',_token);
                    
                    $.ajax({
                    url:"{{route('getUserScheduleGroupStore')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    datatype:"json",
                    success:function(result){
                       // alert(JSON.stringify(result));
                    $("#modal").modal('hide');
                    Swal.fire("Saved!",result.success,"success");
                    location.reload();                    
                    }, 
                    error: function(response) {
                      //  alert(JSON.stringify(response));
                       
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            });



            function editTimeSchedule(id){
                $.ajax({
                    url:"{{route('getUserScheduleGroupEdit')}}",
                    method:"GET",
                    data:{"id":id},
                    datatype:"json",
                    success:function(result){
                        alert(JSON.stringify(result));
                        $("#editModal").modal('show');
                        $("#edit_schedule_group_id").val(result.schedule_group_id);
                        $("#edit_start_date").val(result.start_date);
                        $("#edit_end_date").val(result.end_date);
                        $("#edit_note").val(result.note);
                        $("#editId").val(result.id);
                        
                        if(result.status != ""){
                            $("#editStatus").val(result.status);
                        }else{
                            $("#editStatus").val("Inactive");
                        }
                    }, error: function(response) {
                        alert(JSON.stringify(response)); 
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                });
            }





            



        $("#scheduleGroupFormEdit").submit(function (e){
                e.preventDefault();

            var schedule_group_id  = $("#edit_schedule_group_id").val();
            var start_date = $("#edit_start_date").val();
            var end_date = $("#edit_end_date").val();
            var note = $("#edit_note").val();
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();

                fd.append('schedule_group_id',schedule_group_id);
                fd.append('start_date',start_date);
                fd.append('end_date',end_date);
                fd.append('note',note);
                fd.append('id',id);
                fd.append('_token',_token);
        $.ajax({
            url:"{{route('userScheduleGroupUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
                success:function(result){
                
                $("#editModal").modal('hide');
                Swal.fire("Updated info!",result.success,"success");
                location.reload();
             }, error: function(response) {
                //alert(JSON.stringify(response));
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });





        function confirmDelete(id){
          //   alert(id);
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Schedule Group!",
                closeOnConfirm: false
            }).then((result) => {
            if (result.isConfirmed) {
                var _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url:"{{route('userScheduleGroupDelete')}}",
                    method: "POST",
                    data: {"id":id, "_token":_token},
                    success: function (result) {
                       // alert(JSON.stringify(result));
                        Swal.fire("Done!",result.success,"success");
                        location.reload();
                    }, 
                    error: function(response) {
                //alert(JSON.stringify(response));
            },beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                });
            }else{
            Swal.fire("Cancelled", "Your imaginary Schedule Group is safe :)", "error");
            }
        })         
        }
</script>
@endsection