@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} UserType
@endsection
@section('UserType Management')
    
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Usertype List</h3>
        <div class="card-actions">
            <a class="btn btn-primary" onclick="create()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Usertype
            </a>
            <a class="btn btn-outline-secondary" onclick="location.reload()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                Refresh
            </a>
        </div>
    </div>
    <div class="card-body">
        <x-filter-bar
            route="{{ route('users.usertype.view') }}"
            searchPlaceholder="Search usertypes..."
            :sortOptions="['usertype' => 'Usertype', 'user_count' => 'Total Users']"
            :defaultSort="'id'"
            :defaultDirection="'DESC'"
        />
        <div class="table-responsive">
            <table class="table table-vcenter table-bordered">
                <thead>
                    <tr>
                        <th width="6%">SL#</th>
                        <th>Usertype</th>
                        <th width="12%">Total Users</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usertypes as $usertype)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + ($usertypes->currentPage() - 1) * $usertypes->perPage() }}</td>
                            <td>{{ $usertype->usertype }}</td>
                            <td class="text-center">{{ $usertype->user_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No usertypes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $usertypes->links() }}
    </div>
</div>

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <button type="button" class="close"
                            data-bs-dismiss="modal" aria-hidden="true">
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
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">x Close</button>
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
                            data-bs-dismiss="modal" aria-hidden="true">
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
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">x Close</button>
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
				location.reload();
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
				location.reload();
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
						location.reload();
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
			location.reload();
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