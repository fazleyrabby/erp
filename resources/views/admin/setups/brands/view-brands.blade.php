@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} Brands
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Brand List</h3>
        <div class="card-actions">
            <a class="btn btn-primary" onclick="create()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Brand
            </a>
            <a class="btn btn-outline-secondary" onclick="location.reload()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                Refresh
            </a>
        </div>
    </div>
    <div class="card-body">
        <x-filter-bar
            route="{{ route('brands.view') }}"
            searchPlaceholder="Search brands..."
            :sortOptions="['id' => 'ID', 'name' => 'Name', 'status' => 'Status']"
            :defaultSort="'id'"
            :defaultDirection="'DESC'"
        />

        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th width="6%">SL#</th>
                        <th>Brand Name</th>
                        <th>Image</th>
                        <th width="8%">Status</th>
                        <th width="8%" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>
                                @if($brand->image && $brand->image != 'no_image.png')
                                    <img src="{{ url('upload/brand_images/'.$brand->image) }}" alt="{{ $brand->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($brand->status == 'Active')
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
                                        <li><a class="dropdown-item" href="#" onclick="editBrand({{ $brand->id }})"><i class="fas fa-edit"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="confirmDelete({{ $brand->id }})"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No brands found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $brands->links() }}
    </div>
</div>

<!-- modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
                <form id="brandForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="id">
                    <div class="form-group mb-3">
                        <label class="form-label">Brand Name <span class="text-danger"> * </span></label>
                        <input class="form-control" id="name" type="text" name="name" >
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Brand Logo</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <span class="text-danger" id="imageError"></span>
                        </div>
                        <div class="col-md-4">
                            <img id="showImage" src="{{asset('upload/no_image.png')}}" style="width: 70px;height: 80px; border:1px solid #000000">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveCategory"><i class="fa fa-save"></i> Save</button>
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
                <h4 class="modal-title">Edit Brand</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
                <form id="editBrandForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="editId" id="editId">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Brand Name <span class="text-danger"> * </span></label>
                            <input class="form-control" id="editName" type="text" name="editName" required="">
                            <span class="text-danger" id="editNameError"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select id="editStatus" name="editStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Edit Logo</label>
                            <input type="file" name="editImage" id="editImage" class="form-control">
                            <span class="text-danger" id="editImageError"></span>
                        </div>
                        <div class="col-md-4" >
                            <img id="editShowImage" src="{{url('upload/no_image.png')}}" style="width: 70px;height: 80px; border:1px solid #000000" /><br>
                                <a href="#" onclick="removeImage()" style="margin-left:20px;"> <i class="fas fa-trash-alt"></i> Remove Image</a>
                            <input type="hidden" id="removeImage" name="removeImage" value="" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editCategory"><i class="fa fa-save"></i> Update</button>
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
		
        $("#brandForm").submit(function (e){
          e.preventDefault();
          clearMessages();
          var brandName = $("#name").val();
          var brandImage = $('#image')[0].files[0];
          var _token = $('input[name="_token"]').val();
          var fd = new FormData();
          fd.append('name',brandName);
          fd.append('image',brandImage);
          fd.append('_token',_token);
          $.ajax({
                url:"{{ route('brands.store')}}",
                method:"POST",
                data:fd,
                contentType: false,
                processData: false,
                success:function(result){
                  $("#modal").modal('hide');
                  Swal.fire("Brand Saved!",result.success,"success").then(function(){
                    location.reload();
                  });
              }, error: function(response) {
                $('#nameError').text(response.responseJSON.errors.name);
                $('#imageError').text(response.responseJSON.errors.image);
              }, beforeSend: function () {
                  $('#loading').show();
              },complete: function () {
                  $('#loading').hide();
              }

          })
        })
        function clearMessages(){
          $('#nameError').text("");
          $('#imageError').text("");
        }
        function editClearMessages(){
          $('#editNameError').text("");
          $('#editImageError').text("");
        }
        function reset(){
          $("#name").val("");
          $("#image").val("")
          $('#showImage').attr('src',"");
        }
		function editReset(){
			$("#editName").val("");
			$("#editImage").val("");
			$('#editShowImage').attr('src',"");
			$("#removeImage").val("")
		}
        function editBrand(id){
			editReset();
			editClearMessages();
          $.ajax({
              url:"{{route('brands.edit')}}",
              method:"GET",
              data:{"id":id},
              datatype:"json",
              success:function(result){
                $("#editModal").modal('show');
                $("#editName").val(result.name);
                var imageString = '{{asset("upload/brand_images")}}'+"/"+result.image;
                $('#editShowImage').attr('src',imageString);
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

        $("#editBrandForm").submit(function (e){
          e.preventDefault();
          editClearMessages();
          var brandName = $("#editName").val();
          var Status  =$("#editStatus").val();
		  var removeImage = $("#removeImage").val();
          var brandImage = $('#editImage')[0].files[0];
          var _token = $('input[name="_token"]').val();
          var id = $("#editId").val();
          var fd = new FormData();
          fd.append('name',brandName);
          fd.append('image',brandImage);
		  fd.append('removeImage',removeImage);
          fd.append('status',Status);
          fd.append('id',id);
          fd.append('_token',_token);
          $.ajax({
              url:"{{route('brands.update')}}",
              method:"POST",
              data:fd,
              contentType: false,
              processData: false,
              success:function(result){
                $("#editModal").modal('hide');
                  Swal.fire("Updated brand!",result.success,"success").then(function(){
                    location.reload();
                  });
              }, error: function(response) {
                $('#editNameError').text(response.responseJSON.errors.name);
                $('#editImageError').text(response.responseJSON.errors.image);
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
            confirmButtonText: "Yes, delete Brand!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('brands.delete')}}",
                method: "POST",
                data: {"id":id, "_token":_token},
                success: function (result) {
                    Swal.fire("Done!","Brand was succesfully deleted!","success").then(function(){
                      location.reload();
                    });
                }, error: function(response) {
                  Swal.fire("Cancelled", result, "error");
                  $('#editNameError').text(response.responseJSON.errors.name);
                  $('#editImageError').text(response.responseJSON.errors.image);
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });
        }else{
          Swal.fire("Cancelled", "Your imaginary Brand is safe :)", "error");
        }
      })
        
    }
	function removeImage(){
		Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this image file after save!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, remove image!",
            closeOnConfirm: false
        }).then((result) => {
			if (result.isConfirmed) {
				$("#removeImage").val("1");
				$("#editShowImage").attr('src',"");
			}else{
			  Swal.fire("Cancelled", "Your image is safe :)", "error");
			}
        })
	}

	$(document).ready(function(){
	  $('#image').change(function(e){
		var reader =new FileReader();
		reader.onload =function(e){  
		  $('#showImage').attr('src',e.target.result);
		}
		reader.readAsDataURL(e.target.files['0']);
	  });
	  
	  $('#editImage').change(function(e){
		var reader =new FileReader();
		reader.onload =function(e){  
		  $('#editShowImage').attr('src',e.target.result);
		  $("#removeImage").val("");
		}
		reader.readAsDataURL(e.target.files['0']);
	  });
	});

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
			$("#brandForm").trigger('submit');
		}else{
			alert("Not Calling");
		}
	});
	Mousetrap.bind('ctrl+shift+u', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#editBrandForm").trigger('submit');
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
