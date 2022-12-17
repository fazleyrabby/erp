@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} Units
@endsection
@section('content')

<style type="text/css">

    h3{
        color: #66a3ff;
    }
</style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                        <div class="card-header">
                            <h3 style="float:left;"> Unit List </h3>
                            <a class="btn btn-primary float-right" onclick="create()"><i class="fa fa-plus circle"></i> Add Unit</a>
                            <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i class="fas fa-sync"></i> Refresh</a>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="col-md-12">


                                <!--data listing table-->
                                <table id="manageUnitTable" width="100%" class="table table-bordered table-hover ">
                                    <thead>
                                        <tr>
                                            <td width="8%">SL.</td>
                                            <td>Unit Name</td>
                                            <td width="8%">Status</td>
                                            <td width="8%">Action</td>
                                        </tr>
                                    </thead>
                                </table>
                                <!--data listing table-->

                            </div>


                        </div>
                        <!-- /.card -->
                    </div>
        </section>
    </div>
<!-- /.content-wrapper -->

<!-- modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header float-left">
                <h4 class="modal-title float-left"> Add Unit</h4>
				<button type="button" class="close"data-dismiss="modal" aria-hidden="true"><i class="fas fa-window-close" ></i></button>                
            </div> 
            <div class="modal-body">
                <form id="unitForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label> Unit Name <span class="text-danger"> * </span></label>
                        <input class="form-control input-sm" id="name" type="text" name="name" >
                        <span class="text-danger" id="nameError"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">X Close</button>
                        <button type="submit" class="btn btn-primary btnSave" id="saveCategory"><i class="fa fa-save"></i> Save</button>
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
                <h4 class="modal-title">Edit Unit</h4>
				<button type="button" class="close"data-dismiss="modal" aria-hidden="true"><i class="fas fa-window-close" ></i></button>                
            </div> 
            <div class="modal-body">
                <form id="editUnitForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="editId" id="editId">
                    <div class="form-group">
                        <label> Unit Name <span class="text-danger"> * </span></label>
                        <input class="form-control input-sm" id="editName" type="text" name="editName" required="">
                        <span class="text-danger" id="editNameError"></span>
                    </div>
                    <div class="form-group">
                        <label> Status</label>
                        <select id="editStatus" name="editStatus" class="form-control input-sm">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">X Close</button>
                        <button type="submit" class="btn btn-primary btnUpate" id="editCategory"><i class="fa fa-save"></i> Update</button>
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
		$("#modal").modal('show');
		//$(".btnSave").show();
    }
	$('#modal').on('shown.bs.modal', function() {
		$('#name').focus();
	})
	$('#editModal').on('shown.bs.modal', function() {
		$('#editName').focus();
	})
	var table;
	$(document).ready(function() {
		table = $('#manageUnitTable').DataTable({
			'ajax': "{{route('units.getUnits')}}",
			processing:true,
		});
	})
	$("#unitForm").submit(function (e){
		e.preventDefault();
		clearMessages();
		var unitName = $("#name").val();
		var _token = $('input[name="_token"]').val();
		var fd = new FormData();
		fd.append('name',unitName);
		fd.append('_token',_token);
		$.ajax({
			url:"{{ route('units.store') }}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			success:function(result){
				$("#modal").modal('hide');
				Swal.fire("Saved!",result.success,"success");
				table.ajax.reload(null, false);
			}, error: function(response) {
				$('#nameError').text(response.responseJSON.errors.name);
			}, beforeSend: function () {
				$('#loading').show();
			},complete: function () {
				$('#loading').hide();
			}
		})
	})
	function clearMessages(){
		$('#nameError').text("");
	}
	function editClearMessages(){
		$('#editNameError').text("");
	}
	function reset(){
		$("#name").val("");
	}
	function editReset(){
		$("#editName").val("");
	}
	function editUnit(id){
		editReset();
		$.ajax({
			url:"{{route('units.edit')}}",
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
			}, beforeSend: function () {
				$('#loading').show();
			},complete: function () {
				$('#loading').hide();
			}
		});
	}

	$("#editUnitForm").submit(function (e){
		e.preventDefault();
		editClearMessages();
		var unitName = $("#editName").val();
		var Status  =$("#editStatus").val();
		var _token = $('input[name="_token"]').val();
		var id = $("#editId").val();
		var fd = new FormData();
		fd.append('name',unitName);
		fd.append('status',Status);
		fd.append('id',id);
		fd.append('_token',_token);
		$.ajax({
			url:"{{route('units.update')}}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			success:function(result){
				$("#editModal").modal('hide');
				Swal.fire("Updated!",result.success,"success");
				table.ajax.reload(null, false);
			}, error: function(response) {
				$('#editNameError').text(response.responseJSON.errors.name);
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
			confirmButtonText: "Yes, delete Unit!",
			closeOnConfirm: false
		}).then((result) => {
			if (result.isConfirmed) {
				var _token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url:"{{route('units.delete')}}",
					method: "POST",
					data: {"id":id, "_token":_token},
					success: function (result) {
						Swal.fire("Deleted!",result.success,"success");
						table.ajax.reload(null, false);						
					}, error: function(response) {
						Swal.fire("Error!",response,"error");
					}, beforeSend: function () {
						$('#loading').show();
					},complete: function () {
						$('#loading').hide();
					}
				});
			}else{
				Swal.fire("Cancelled", "Your imaginary Unit is safe :)", "error");
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
			$("#unitForm").trigger('submit');
		}else{
			alert("Not Calling");
		}
	});
	Mousetrap.bind('ctrl+shift+u', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#editUnitForm").trigger('submit');
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