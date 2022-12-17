@extends('admin.master')
@section('title')
Admin Groups -View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
                    <div class="card">
                        <div class="card-header">
                            <h3 style=" float: left;">Group</h3>
                            <a href="#/"  onclick="create()" class="btn btn-primary btn-icon-split float-right"><i class="fas fa-plus"></i>Add Group</a>
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="manageGroupTable" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td width="8%">SL</td>
                                        <td>Group Name</td>
                                        <td>Note</td>
                                        <td width="8%">Status</td>
                                        <td width="8%">Action</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                </table>
                            </div>
                        </div>
        </section>
    </div>
    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
		<form id="createGroupForm" method="POST" enctype="multipart/form-data" action="#/">
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Group</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
					
                    @csrf
                    <div class="form-group row col-md-12">
                        <label for="carousalCaptionOffer">Group Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder=" Write Group Name" required>                                     
                        <span class="text-danger" id="group_nameError"></span>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="carousalCaptionOffer">Remarks</label>
                        <textarea type="text" class="form-control" id="note" name="note" placeholder="Write Short Note" ></textarea>
                        <span class="text-danger" id="noteError"></span>
                    </div>

                </div>
              
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal"><i class="fa fa-close"></i>X Close</button>
                    <button type="submit" class="btn btn-primary float-right" id="saveGroup"><i class="fa fa-save"></i> Save</button>
                </div>
		</form>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
   
    
    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
			<form id="editGroupForm" method="POST" enctype="multipart/form-data" action="#">
                <div class="modal-header">
                    <h3 style="float: left;">Edit Group</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div> 
                <div class="modal-body">
                @csrf
                      <input type="hidden" name="editId" id="editId">
                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label>Group Name</label>
                                <input class="form-control input-sm" id="editGroupName" type="text" name="editGroupName" required="">
                                <span class="text-danger" id="editGroupNameError"></span>
                            </div>
                            <div class="col-md-6">
                                <label> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row col-md-12">
                            <label> Remarks </label>
                            <textarea class="form-control input-sm" id="editNote" type="text" name="editNote" ></textarea>
                            <span class="text-danger" id="editNoteError"></span>
                        </div>
                      
		</div>
		<div class="modal-footer">
                      <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btnUpate float-right" id="editGroup"><i class="fa fa-save"></i> Update</button>
                </div>
		</form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endsection
@section('contentJavaScripts')
    <script>
        function create() {
            reset();
            $("#modal").modal('show');
        }
        $('#modal').on('shown.bs.modal', function() {
            $('#group_name').focus();
        })
        
        var table;
        $(document).ready(function() {
            table = $('#manageGroupTable').DataTable({
                'ajax': "{{url('payroll/getGroup')}}",
                processing:true,
            });
        });



        
        $("#createGroupForm").submit(function (e){
        e.preventDefault();
        clearMessages();
        var  name = $("#group_name").val();
       
        var  note = $("#note").val();
        var _token = $('input[name="_token"]').val();
        var fd = new FormData();
        fd.append('name',name);
        fd.append('note',note);
        fd.append('_token',_token);
        $.ajax({
			url:"{{url('payroll/groupStore')}}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			datatype:"json",
			success:function(result){
                
                //alert(JSON.stringify(result));
                $("#modal").modal('hide');
                Swal.fire("Saved!",result.success,"success");
                table.ajax.reload(null, false);
            }, error: function(response) {
                //alert(JSON.stringify(response));
                $('#group_nameError').text(response.responseJSON.errors.group_name);
                $('#noteError').text(response.responseJSON.errors.note);
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }

        })
    })


    function reset(){
        $("#group_name").val("");
        $("#note").val("");
        clearMessages();
    }
    function editReset(){
		$("#editGroupName").val("");
		$("#editNote").val("");
		$("#editStatus").val("Active");
        editClearMessages();
	}
    function clearMessages(){
		$('#group_nameError').text("");
		$('#noteError').text("");
	}
    function editClearMessages(){
		$('#editGroupNameError').text("");
		$('#editNoteError').text("");
	}


    
    function editGroup(id){
		editReset();
        $.ajax({
            url:"{{route('editGroup')}}",
            method:"GET",
            data:{"id":id},
            datatype:"json",
            success:function(result){
                $("#editModal").modal('show');
                $("#editGroupName").val(result.name);
                $("#editNote").val(result.note);
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



    $("#editGroupForm").submit(function (e){
        e.preventDefault();
        editClearMessages();
        var name = $("#editGroupName").val();
        var note = $("#editNote").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('name',name);
        fd.append('note',note);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);
        $.ajax({
            url:"{{route('groupUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(JSON.stringify(result));
                $("#editModal").modal('hide');
                Swal.fire("Updated Group!",result.success,"success");
                table.ajax.reload(null, false);
            }, error: function(response) {
                //alert(JSON.stringify(response));
                $('#editGroupNameError').text(response.responseJSON.errors.name);
                $('#editNoteError').text(response.responseJSON.errors.code);
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });



    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete Group!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('groupDelete')}}",
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
          Swal.fire("Cancelled", "Your imaginary Group is safe :)", "error");
        }
      })
        
    }
    </script>
@endsection