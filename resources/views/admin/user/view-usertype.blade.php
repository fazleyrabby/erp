@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} UserType
@endsection
@section('UserType Management')
    
@endsection
@section('content')

<style type="text/css">
  
  h3{
    color:  #009999;
  }
</style>
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
   <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-md-12">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="card">
            <div class="card-header">
				<h3 class="float-left"> Usertype List </h3>

				<a class="btn btn-outline-success float-right" onclick="create()"><i class="fa fa-plus circle"></i> Add Usertype</a>
				<a class="btn btn-outline-success" style="margin-left:20px;" onclick="reloadDt()"><i class="fas fa-sync"></i> Refresh</a>
            </div><!-- /.card-header -->
            <div class="card-body">
               <div class="col-md-12">
            <!--data listing table-->
            <table id="userTypeTable"  width="100%" class="table table-bordered table-hover" >
                <thead>
                <tr>
					<td width="6%" class="text-center">SL.</td>
					<td  width="78%"  >Usertype </td>
					<td  width="8%" class="text-center">Status</td>
					<td  width="8%" class="text-center">ACTION</td>
                </tr>
                </thead>
            </table>
            <!--data listing table-->

        </div>
              

          </div>
          <!-- /.card -->

          <!-- /.card -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                    </button>
                    <h4 class="modal-title float-left"> Add Usertype</h4>
                </div> 
                <div class="modal-body">
                <form id="userTypeForm" method="POST" enctype="multipart/form-data" action="#">
                  @csrf
                 
                      <input type="hidden" name="id">
                      <div class="form-group">
                          <label> UserType Name</label>
                          <input class="form-control input-sm" id="name" type="text" name="name" autofocus="autofocus">
                          <span class="text-danger" id="nameError"></span>
                      </div>
                    
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">x Close</button>
                      <button type="submit" class="btn btn-primary btnSave" id="saveCategory">Save</button>
                 </form> </div>
              </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	

    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                    </button>
                    <h4 class="modal-title">Edit Usertype</h4>
                </div> 
                <div class="modal-body">
                <form id="edituserTypeForm" method="POST" enctype="multipart/form-data" action="#">
                  @csrf
                 
                      <input type="hidden" name="editId" id="editId">
                      <div class="form-group">
                          <label> Name</label>
                          <input class="form-control input-sm" id="editName" type="text" name="editName" required="">
						  <span class="text-danger" id="editNameError"></span>
                      </div>
                      <div class="form-group">
                          <label> Status</label>
                          <select id="editStatus" name="editStatus" class="form-control input-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                          </select>
						  <span class="text-danger" id="editStatusError"></span>
                      </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">x Close</button>
                      <button type="submit" class="btn btn-primary btnUpate" id="editUsertype">Update</button>
                 </form> </div>
              </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')

<script>


	function create() {
		reset();
		$("#nameError").text("");
		$("#modal").modal('show');
	}
	$('#modal').on('shown.bs.modal', function() {
	  $('#name').focus();
	})
	$('#editModal').on('shown.bs.modal', function() {
	  $('#editName').focus();
	})
	var table;
	$(document).ready(function() {
		table = $('#userTypeTable').DataTable({
			'ajax': "{{route('viewUserTypes')}}",
			processing:true,
		});
	})
		
	
	$("#userTypeForm").submit(function (e){
		e.preventDefault();
		$("#nameError").text("");
		var userTypeName = $("#name").val();
		var _token = $('input[name="_token"]').val();
		var fd = new FormData();
		fd.append('name',userTypeName);
		fd.append('_token',_token);
		$.ajax({
			url:"{{url('usertype/store')}}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$("#loading").show();
			},
			success:function(result){
				$("#modal").modal('hide');
				table.ajax.reload(null, false);
				Swal.fire("Done!",result.success,"success");
				
			},
			complete: function() {
				$("#loading").hide();
			},			  
			error: function(response) {
				$('#nameError').text(response.responseJSON.errors.name);
				$('#name').focus();
			}

		})
	})

	function reset(){
		$("#name").val("");
	}
	function editUsertype(id){
		$("#editNameError").text("");
		$("#editStatusError").text("");
		$.ajax({
			url:"{{route('editUsertype')}}",
			method:"GET",
			data:{"id":id},
			datatype:"json",
			success:function(result){
				$("#editModal").modal('show');
				$("#editName").val(result.name);
				$("#editId").val(result.id);
				if(result.status != ""){
					$("#editStatus").val(result.status);
				}else{
					$("#editStatus").val("Inactive");
				}
			}
		});
	}

	$("#edituserTypeForm").submit(function (e){
		e.preventDefault();
		$("#editNameError").text("");
		$("#editStatusError").text("");
		var userTypeName = $("#editName").val();
		var Status  =$("#editStatus").val();
		var _token = $('input[name="_token"]').val();
		var id = $("#editId").val();
		var fd = new FormData();
		fd.append('name',userTypeName);
		fd.append('status',Status);
		fd.append('id',id);
		fd.append('_token',_token);
		$.ajax({
			url:"{{route('updateUserType')}}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$("#loading").show();
			},
			success:function(result){
				$("#editModal").modal('hide');
				Swal.fire("Done!",result.success,"success");
				table.ajax.reload(null, false);
			},
			complete: function() {
				$("#loading").hide();
			},	
			error: function(response) {
				$('#editNameError').text(response.responseJSON.errors.name);
				$('#editStatusError').text(response.responseJSON.errors.status);
				$('#editName').focus();
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
			confirmButtonText: "Yes, delete Usertype!",
			closeOnConfirm: false
		}).then((result) => {
			if (result.isConfirmed) {
				var _token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url:"{{route('userTypeDelete')}}",
					method: "POST",
					data: {"id":id, "_token":_token},
					beforeSend: function() {
						$("#loading").show();
					},
					success: function (result) {
						Swal.fire("Done!",result.success,"success");
						table.ajax.reload(null, false);
					},
					complete: function() {
						$("#loading").hide();
					}, 
					error: function(response) {
						$('#nameError').text(response);
					}
				});
			}else{
			  Swal.fire("Cancelled", "Your imaginary UserType is safe :)", "error");
			}
		})
	}
	Mousetrap.bind('ctrl+shift+n', function(e) {
		e.preventDefault();
		if($('#modal.in, #modal.show').length){
			
		}else{
			create();
			
		}
	});
	function reloadDt(){
		if($('#modal.in, #modal.show').length){
			
		}else if($('#editModal.in, #editModal.show').length){
			
		}
		else{
			table.ajax.reload(null, false);
		}
	}
	Mousetrap.bind('ctrl+shift+r', function(e) {
		e.preventDefault();
		reloadDt();
	});
	Mousetrap.bind('ctrl+shift+s', function(e) {
		e.preventDefault();
		if($('#modal.in, #modal.show').length){
			$("#userTypeForm").trigger('submit');
		}else{
			alert("Not Calling");
		}
	});
	Mousetrap.bind('ctrl+shift+u', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#edituserTypeForm").trigger('submit');
		}else{
			alert("Not Calling");
		}
	});
	Mousetrap.bind('esc', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#editModal").modal('hide');
		}else if($('#modal.in, #modal.show').length){
			$('#modal').modal('hide');
		}
	});

</script>
@endsection