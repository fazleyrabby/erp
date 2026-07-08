@extends('admin.master')
@section('title')
Admin Leave Management -View
@endsection
@section('content')

    
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Leave list</h3>
                <div class="card-actions">
                    <button type="button" class="btn btn-primary" onclick="create()"><i class="fas fa-plus"></i> Add Leave</button>
                </div>
                <h3 class="text-center text-success">{{Session::get('message')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <x-filter-bar route="{{ route('leaveIndex') }}" searchPlaceholder="Search leaves..." :sortOptions="['id' => 'ID', 'leave_type' => 'Leave Type']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Leave Start</th>
                            <th>Leave End</th>
                            <th>Leave Reason</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i => $leave)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $leave->id }}" /></td>
                            <td>{{ $leave->member_name }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->leave_start_date }}</td>
                            <td>{{ $leave->leave_end_date }}</td>
                            <td>{{ $leave->leave_reason }}</td>
                            <td>{{ $leave->admin_remarks }}</td>
                            <td>
                                @if ($leave->leave_status == 'Pending')
                                <span class="badge badge-warning">{{ $leave->leave_status }}</span>
                                @elseif ($leave->leave_status == 'Approved')
                                <span class="badge badge-success">{{ $leave->leave_status }}</span>
                                @else
                                <span class="badge badge-danger">{{ $leave->leave_status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-grade">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fas fa-cog"></i>
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-end">
                                         <a class="dropdown-item" href="#" onclick="editLeave({{ $leave->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                         <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $leave->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                     </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No leaves found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    <!-- modal -->
 <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
				<form id="leaveStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Leave</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
					<div class="row g-3">
                    @csrf
                    <div class="form-group mb-3 col-md-6">
                        <label> Employee Name </label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="0" selected disabled> Choose employee</option>
                        @foreach($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->member_name}}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3 col-md-6">
                        <label> Leave Type </label>
                        <select class="form-control" id="leave_type" name="leave_type" >
                            <option value="0" selected disabled> Choose leave type</option>
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Earn Leave">Earn Leave</option>
                            <option value="Duty Leave">Duty Leave</option>
                        </select>
                    </div>



                    <div class="form-group mb-3 col-md-6">
                        <label>Leave Start Date </label>
                        <input type="date" class="form-control"  name="leave_start_date" id="leave_start_date" required>
                    </div>

                    <div class="form-group mb-3 col-md-6">
                        <label>Leave End Date </label>
                        <input type="date" class="form-control"  name="leave_end_date" id="leave_end_date" >
                    </div>
                    <div class="form-group mb-3 col-md-12">
                        <label> Leave Reason </label>
                        <textarea type="text" class="form-control"  name="leave_reason" id="leave_reason" placeholder="Add Leave Reason"></textarea>
                    </div>

                    <div class="form-group mb-3 col-md-12">
                        <label> Admin Remarks </label>
                        <textarea type="text" class="form-control"  name="admin_remarks" id="admin_remarks" placeholder="Add note"></textarea>
                    </div>

                </div>
              </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
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
                <form id="leaveEdit">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Leave</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> 
                    <div class="modal-body">
                    
                        <div class="row g-3">
                            @csrf
                            <input type="hidden" name="editId" id="editId">
                        
                            <div class="form-group mb-3 col-md-6">
                                <label>Leave Type </label>
                                <select class="form-control" id="edit_leave_type" name="edit_leave_type" >
                                    <option value="0" selected disabled> Choose leave type</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Medical Leave">Medical Leave</option>
                                    <option value="Earn Leave">Earn Leave</option>
                                    <option value="Duty Leave">Duty Leave</option>
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label> Leave Start Date </label>
                                <input type="date" class="form-control"  name="edit_leave_start_date" id="edit_leave_start_date" >
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Leave End Date </label>
                                <input type="date" class="form-control"  name="edit_leave_end_date" id="edit_leave_end_date" >
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Leave Status</label>
                                <select class="form-control" id="edit_leave_status" name="edit_leave_status" required>
                                    <option value="0" selected disabled> Choose status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Reject">Reject</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label> Leave Reason </label>
                                <textarea type="text" class="form-control"  name="edit_leave_reason" id="edit_leave_reason" placeholder="Add note"></textarea>
                            </div>

                            <div class="form-group mb-3 col-md-12">
                                <label> Admin Remarks </label>
                                <textarea type="text" class="form-control"  name="edit_admin_remarks" id="edit_admin_remarks" placeholder="Add note"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btnUpate" id="editGroup"><i class="fa fa-save"></i> Update</button>
                    </div>
                </form>
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
                        $('#employee_id').focus();
                    })


                
                /* store data*/
            $('#leaveStore').submit(function(e){
                    e.preventDefault();
                    
                    var employee_id = $("#employee_id").val();
                    var leave_start_date = $("#leave_start_date").val();
                    var leave_end_date = $("#leave_end_date").val();
                    var leave_reason = $("#leave_reason").val();
                    var admin_remarks = $("#admin_remarks").val();
                    var leave_type = $("#leave_type").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('employee_id',employee_id);
                    fd.append('leave_start_date',leave_start_date);
                    fd.append('leave_end_date',leave_end_date);
                    fd.append('leave_reason',leave_reason);
                    fd.append('admin_remarks',admin_remarks);
                    fd.append('leave_type',leave_type);
                    fd.append('_token',_token);
                    
                    $.ajax({
                    url:"{{route('leaveStore')}}",
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
                       // alert(JSON.stringify(response));
                       
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            });



            




                function editLeave(id){
                   // alert(id);
                    $.ajax({
                        url:"{{route('leaveEdit')}}",
                        method:"GET",
                        data:{"id":id},
                        datatype:"json",
                        success:function(result){
                         // alert(JSON.stringify(result));
                            //alert(result[0],[id]);
                            $("#editModal").modal('show');
                            $("#edit_leave_start_date").val(result.leave_start_date);
                            $("#edit_leave_type").val(result.leave_type);
                            $("#edit_leave_end_date").val(result.leave_end_date);
                            $("#edit_leave_reason").val(result.leave_reason);
                            $("#edit_admin_remarks").val(result.admin_remarks);edit_leave_status
                            $("#edit_leave_status").val(result.leave_status);
                            $("#editId").val(result.id);
                            
                            /* if(result.status != ""){
                                $("#editStatus").val(result.status);
                            }else{
                                $("#editStatus").val("Inactive");
                            } */
                        }, error: function(response) {
                            alert(JSON.stringify(response)); 
                        }, beforeSend: function () {
                            $('#loading').show();
                        },complete: function () {
                            $('#loading').hide();
                        }
                    });
                }




        $("#leaveEdit").submit(function (e){
        e.preventDefault();

            
            var leave_start_date = $("#edit_leave_start_date").val();
            var leave_type = $("#edit_leave_type").val();
            var leave_end_date = $("#edit_leave_end_date").val();
            var admin_remarks = $("#edit_admin_remarks").val();
            var leave_reason  = $("#edit_leave_reason").val();
            var leave_status  = $("#edit_leave_status").val();
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();

                fd.append('leave_start_date',leave_start_date);
                fd.append('leave_type',leave_type);
                fd.append('leave_end_date',leave_end_date);
                fd.append('leave_reason',leave_reason);
                fd.append('admin_remarks',admin_remarks);
                fd.append('leave_status',leave_status);
                fd.append('id',id);
                fd.append('_token',_token);
        $.ajax({
            url:"{{route('leaveUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
                success:function(result){
                
                $("#editModal").modal('hide');
                Swal.fire("Updated info!",result.success,"success");
                location.reload();
             }, error: function(response) {
                alert(JSON.stringify(response));
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });


    function confirmDelete(id){
        confirmDeleteSwal({
            url      : "{{route('leaveDelete')}}",
            id       : id,
            itemName : 'Leave',
            onError  : function(response) {
                alert(JSON.stringify(response));
            },
        });
    }
</script>
@endsection