@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} User
@endsection
@section('User Management')
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users List</h3>
            <div class="card-actions">
                <a class="btn btn-primary" onclick="create()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add User
                </a>
                <a class="btn btn-outline-secondary" onclick="location.reload()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                    Refresh
                </a>
            </div>
        </div>
        <div class="card-body">
            <x-filter-bar
                route="{{ route('users.') }}"
                searchPlaceholder="Search users..."
                :sortOptions="['users.id' => 'ID', 'users.name' => 'Name', 'users.email' => 'Email']"
                :defaultSort="'users.id'"
                :defaultDirection="'DESC'"
            />
            <div class="table-responsive">
                <table class="table table-vcenter table-bordered">
                    <thead>
                        <tr>
                            <th width="6%">SL#</th>
                            <th>Image</th>
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Dep./Des.</th>
                            <th width="8%">Status</th>
                            <th width="8%">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    @if($user->image && $user->image != 'no_image.png')
                                        <img style="width:70px;" src="{{ url('upload/user_images/'.$user->image) }}" alt="{{ $user->name }}" />
                                    @else
                                        <img style="width:70px;" src="{{ url('upload/no_image.png') }}" alt="{{ $user->name }}" />
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}<br>{{ $user->mobile_no }}</td>
                                <td>Dep: {{ $user->department }}<br>Des: {{ $user->designation }}<br><b>Role:</b> {{ $user->role }}</td>
                                <td>
                                    @if($user->status == 'Active')
                                        <i class="fas fa-check-circle" style="color:green; font-size:16px;"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:red; font-size:16px;"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             <i class="fas fa-cog"></i>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <a class="dropdown-item" href="#" onclick="userEdit({{ $user->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                             <a class="dropdown-item" href="#" onclick="confirmDelete({{ $user->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                         </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add User</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <form id="userForm" method="POST" action="#">
                    <div class="modal-body">
                        <div class="row">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="form-group col-md-4">
                                <label> Full Name</label>
                                <input class="form-control input-sm" id="name" type="text" name="name"
                                    placeholder="Full name">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email </label>
                                <input class="form-control input-sm" type="email" id="email" name="email"
                                    placeholder="Email">
                                <span class="text-danger" id="emailError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mobile No</label>
                                <input class="form-control input-sm" type="text" id="mobile_no" name="mobile_no"
                                    placeholder="Mobile number">
                                <span class="text-danger" id="mobileError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Password</label>
                                <input class="form-control input-sm" type="password" id="password" name="password"
                                    placeholder="password">
                                <span class="text-danger" id="passwordError"></span>
                            </div>


                            <div class="form-group col-md-4">
                                <label>Designation</label>
                                <input class="form-control input-sm" id="designation" type="text" name="designation"
                                    placeholder="designation">
                                <span class="text-danger" id="designationError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Department</label>
                                <input class="form-control input-sm" id="department" type="text" name="department"
                                    placeholder="department">
                                <span class="text-danger" id="departmentError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address</label>
                                <input class="form-control input-sm" id="address" type="text" name="address"
                                    placeholder="address">
                                <span class="text-danger" id="addressError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Roles</label>
                                <select name="role" id="role" class="form-control input-sm">
									<option value="" selected="selected">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="rolesError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Image</label>
                                <input type="file" name="image" id="image" name="image"
                                    class="form-control form-control-sm">
                                <span class="text-danger">image size should must be lower then 2048 X 1848</span>
                                <span class="text-danger" id="imageError"></span>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="">Signature:</label>
                                <input type="file" id="signature" name="signature" class="form-control form-control-sm">
                                <span class="text-danger" id="signatureError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <img id="showImage" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 70px;height: 80px; border:1px solid #000000">
                            </div>

                            <div class="form-group col-md-6">
                                <img id="showSignature" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 70px;height: 80px; border:1px solid #000000">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x Close</button>
                        <button type="submit" class="btn btn-primary btnSave" id="saveCategory"><i
                                class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <form id="editUserForm" method="POST" enctype="multipart/form-data" action="#">
                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <input type="hidden" name="editId" id="editId">
                            <div class="form-group col-md-4">
                                <label> Full Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editName" type="text" name="name" required="" />
                                <span class="text-danger" id="editNameError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" type="text" id="editEmail" name="email" required="" />
                                <span class="text-danger" id="editEmailError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mobile No <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" type="text" id="editMobile" name="mobile_no"
                                    required="" />
                                <span class="text-danger" id="editMobileError"></span>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Designation</label>
                                <input class="form-control input-sm" id="editDesignation" type="text" name="designation" />
                                <span class="text-danger" id="editDesignationError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Department</label>
                                <input class="form-control input-sm" type="text" id="editDepartment" name="department" />
                                <span class="text-danger" id="editDepartmentError"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Address</label>
                                <input class="form-control input-sm" id="editAddress" type="text" name="address" />
                                <span class="text-danger" id="editAddressError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Roles <span class="text-danger"> * </span></label>
                                <select name="role_name" id="role_name" class="form-control input-sm">
									<option value="" selected="selected">Select Role</option>                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="role_nameError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Edit Image</label>
                                <input type="file" name="editImage" id="editImage" class="form-control form-control-sm" />
                                <span class="text-danger">image size should must be lower then 2048 X 1848</span>
                                <span class="text-danger" id="editImageError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Edit Signature:</label>
                                <input type="file" id="editSignature" name="editSignature"
                                    class="form-control form-control-sm">
                                <span class="text-danger" id="editSignatureError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <img id="editShowImage" src="{{ url('upload/no_image.png') }}"
                                    style="width: 70px;height: 80px; border:1px solid #000000">
                            </div>

                            <div class="form-group col-md-6">
                                <img id="editShowSignature" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 70px;height: 80px; border:1px solid #000000">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x Close</button>
                        <button type="submit" class="btn btn-primary btnUpate" id="editCategory"><i
                                class="fa fa-save"></i> Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#role").select2({
                placeholder: "Select Role",
                dropdownParent: $("#modal"),
                allowClear: true,
                width: '100%'
            });
            $("#role_name").select2({
                placeholder: "Select Role",
                dropdownParent: $("#editModal"),
                allowClear: true,
                width: '100%'
            });
        });

        function create() {
            reset();
            resetMessages();
            $("#modal").modal('show');
        }
        $('#modal').on('shown.bs.modal', function() {
            $('#name').focus();
        })
        $('#editModal').on('shown.bs.modal', function() {
            $('#editName').focus();
        })
        function reset() {
            $("#name").val("");
            $("#email").val("");
            $("#designation").val("");
            $("#department").val("");
            $("#usertype_id").val("");
            $("#mobile_no").val("");
            $("#address").val("");
            $("#password").val("");
            $("#image").val("")
            $('#showImage').attr('src', "");
        }

        function resetMessages() {
            $("#nameError").text("");
            $("#emailError").text("");
            $("#designationError").text("");
            $("#departmentError").text("");
            $("#userTypeError").text("");
            $("#mobileError").text("");
            $("#addressError").text("");
            $("#passwordError").text("");
            $("#imageError").text("");
        }

        function resetEditMessages() {
            $("#editNameError").text("");
            $("#editEmailError").text("");
            $("#editDesignationError").text("");
            $("#editDepartmentError").text("");
            $("#editUserTypeError").text("");
            $("#editMobileError").text("");
            $("#editAddressError").text("");
            $("#editImageError").text("");
        }

        $("#userForm").submit(function(e) {
            e.preventDefault();
            var name = $("#name").val();
            var email = $("#email").val();
            var mobile_no = $("#mobile_no").val();
            var address = $("#address").val();
            var designation = $("#designation").val();
            var department = $("#department").val();
            var password = $("#password").val();
            var usertype_id = -999;
            var role = $("#role").val();
            var image = $('#image')[0].files[0];
            var signature = $('#signature')[0].files[0];
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('name', name);
            fd.append('email', email);
            fd.append('mobile_no', mobile_no);
            fd.append('address', address);
            fd.append('designation', designation);
            fd.append('department', department);
            fd.append('password', password);
            fd.append('role', role);
            fd.append('usertype_id', usertype_id);
            fd.append('image', image);
            fd.append('signature', signature);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('users.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(result) {
                    $("#modal").modal('hide');
                    location.reload();
                    Swal.fire("Saved!", result, "success");
                },
                error: function(response) {
                    $('#nameError').text(response.responseJSON.errors.name);
                    $('#emailError').text(response.responseJSON.errors.email);
                    $("#designationError").text(response.responseJSON.errors.designation);
                    $("#departmentError").text(response.responseJSON.errors.department);
                    $("#userTypeError").text(response.responseJSON.errors.usertype_id);
                    $("#mobileError").text(response.responseJSON.errors.mobile_no);
                    $("#addressError").text(response.responseJSON.errors.address);
                    $("#passwordError").text(response.responseJSON.errors.password);
                    $("#imageError").text(response.responseJSON.errors.image);
                    $("#signatureError").text(response.responseJSON.errors.signature);
                    $("#rolesError").text(response.responseJSON.errors.role);

                },
                complete: function() {
                    $("#loading").hide();
                }

            })
        })

        function userEdit(id) {
            resetEditMessages();
            $.ajax({
                url: "{{ route('users.edit') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#editModal").modal('show');
                    $("#editName").val(result.name);
                    $("#editEmail").val(result.email);
                    $("#editDesignation").val(result.designation);
                    $("#editDepartment").val(result.department);
                    $("#editUsertype").val(result.usertype_id);
                    $("#editMobile").val(result.mobile_no);
                    $("#editAddress").val(result.address);
                    $("#editAddress").val(result.address);
                    $("#role_name").val(result.role).trigger('change');
                    var imageString = '{{ asset('upload/user_images') }}' + "/" + result.image;
                    $('#editShowImage').attr('src', imageString);
                    $('#editImage').val('');
                    var signatureString = '{{ asset('upload/user_signatures') }}' + "/" + result.signature;
                    $('#editShowSignature').attr('src', signatureString);
                    $('#editSignature').val('');
                    $("#editId").val(result.id);
                    if (result.status != "") {
                        $("#editStatus").val(result.status);
                    } else {
                        $("#editStatus").val("Inactive");
                    }
                }
            });
        }

        $("#editUserForm").submit(function(e) {
            e.preventDefault();
            var name = $("#editName").val();
            var email = $("#editEmail").val();
            var designation = $("#editDesignation").val();
            var department = $("#editDepartment").val();
            var role = $("#role_name").val();
            var mobile_no = $("#editMobile").val();
            var address = $("#editAddress").val();
            var status = $("#editStatus").val();
            var userImage = $('#editImage')[0].files[0];
            var signature = $('#editSignature')[0].files[0];
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('name', name);
            fd.append('email', email);
            fd.append('designation', designation);
            fd.append('department', department);
            fd.append('mobile_no', mobile_no);
            fd.append('address', address);
            fd.append('role', role);
            fd.append('image', userImage);
            fd.append('signature', signature);
            fd.append('status', status);
            fd.append('id', id);
            fd.append('_token', _token);
            //alert(role);
            $.ajax({
                url: "{{ route('users.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(result) {
					///alert(JSON.stringify(result))
                    $("#editModal").modal('hide');
                    location.reload();
                    Swal.fire("Updated!", result.success, "success");
                },
                error: function(response) {
					//alert(JSON.stringify(response))
                    $('#editNameError').text(response.responseJSON.errors.name);
                    $('#editEmailError').text(response.responseJSON.errors.email);
                    $("#editDesignationError").text(response.responseJSON.errors.designation);
                    $("#editDepartmentError").text(response.responseJSON.errors.department);
                    $("#editUserTypeError").text(response.responseJSON.errors.usertype_id);
                    $("#editMobileError").text(response.responseJSON.errors.mobile_no);
                    $("#editAddressError").text(response.responseJSON.errors.address);
                    $("#editImageError").text(response.responseJSON.errors.image);
                    $("#editSignatureError").text(response.responseJSON.errors.signature);
                    $("#role_nameError").text(response.responseJSON.errors.role);
                },
                complete: function() {
                    $("#loading").hide();
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
                confirmButtonText: "Yes, delete User!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('users.delete') }}",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            Swal.fire(" Deleted! ", result.success, "success");
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary User is safe :)", "error");
                }
            })

        }

        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
            $('#editImage').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#editShowImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });



        $(document).ready(function() {
            $('#signature').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showSignature').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
            $('#editSignature').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#editShowSignature').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });


        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {

            } else {
                create();
            }
        });

        function reloadDt() {
            if ($('#modal.in, #modal.show').length) {

            } else if ($('#editModal.in, #editModal.show').length) {

            } else {
                location.reload();
            }
        }
        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            reloadDt();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#userForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editUserForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('esc', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editModal").modal('hide');
            } else if ($('#modal.in, #modal.show').length) {
                $('#modal').modal('hide');
            }
        });
    </script>
@endsection
