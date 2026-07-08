@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} Units
@endsection
@section('content')


    
        <div class="card">
    <div class="card-header">
        <h3 class="card-title">Unit List</h3>
        <div class="card-actions">
            <a class="btn btn-primary" onclick="create()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Unit
            </a>
            <a class="btn btn-outline-secondary" onclick="location.reload()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                Refresh
            </a>
        </div>
    </div>
    <div class="card-body">
        <x-filter-bar
            route="{{ route('units.view') }}"
            searchPlaceholder="Search units..."
            :sortOptions="['id' => 'ID', 'name' => 'Name', 'status' => 'Status']"
            :defaultSort="'id'"
            :defaultDirection="'DESC'"
        />

        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th width="8%">SL.</th>
                        <th>Unit Name</th>
                        <th width="8%">Status</th>
                        <th width="8%" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>
                                @if($unit->status == 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 00-2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 00-1.066 -2.573c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 001.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c.996 .608 2.296 .07 2.572 -1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="editUnit({{ $unit->id }})"><i class="fas fa-edit me-1"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="confirmDelete({{ $unit->id }})"><i class="fas fa-trash-alt me-1"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No units found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $units->links() }}
    </div>
</div>
    <!-- modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Unit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
                <form id="unitForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="id">
                    <div class="form-group mb-3">
                        <label class="form-label">Unit Name <span class="text-danger"> * </span></label>
                        <input class="form-control" id="name" type="text" name="name" >
                        <span class="text-danger" id="nameError"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveCategory"><i class="fa fa-save me-1"></i>Save</button>
                </form> </div>
            </div>
        </div>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Unit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
                <form id="editUnitForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="editId" id="editId">
                    <div class="form-group mb-3">
                        <label class="form-label">Unit Name <span class="text-danger"> * </span></label>
                        <input class="form-control" id="editName" type="text" name="editName" required="">
                        <span class="text-danger" id="editNameError"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select id="editStatus" name="editStatus" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editCategory"><i class="fa fa-save me-1"></i>Update</button>
                </form> </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')

 <script>

    function create() {
		reset();
		$("#modal").modal('show');
    }
	$('#modal').on('shown.bs.modal', function() {
		$('#name').focus();
	})
	$('#editModal').on('shown.bs.modal', function() {
		$('#editName').focus();
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
				Swal.fire("Saved!",result.success,"success").then(function(){
				  location.reload();
				});
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
				Swal.fire("Updated!",result.success,"success").then(function(){
				  location.reload();
				});
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
		confirmDeleteSwal({
			url      : "{{route('units.delete')}}",
			id       : id,
			itemName : 'Unit',
			onError  : function(response) {
				Swal.fire("Error!", response, "error");
			},
		});
    }
	Mousetrap.bind('ctrl+shift+n', function(e) {
		e.preventDefault();
		if($('#modal.in, #modal.show').length){
			
		}else{
			create();
		}
	});
	Mousetrap.bind('ctrl+shift+r', function(e) {
		e.preventDefault();
		location.reload();
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
