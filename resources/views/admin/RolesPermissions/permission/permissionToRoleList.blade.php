@extends('admin.master')
@section('title')
    Admin Permission To Role
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Give permission</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Give Permissions
                        </button>
                    </div>
                </div>
                @if (Session::has('message'))
                <div class="card-footer text-success text-center">{{ Session::get('message') }}</div>
                @endif
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('permissionToRoleList') }}"
                        searchPlaceholder="Search roles..."
                        :sortOptions="['id' => 'ID', 'name' => 'Name']"
                        :defaultSort="'id'"
                        :defaultDirection="'DESC'"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data_Table" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="15%">Role Name</td>
                                    <td width="72%">Permissions</td>
                                    <td width="8%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>

                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $perm)
                                                <span class="badge mr-1">{{ $perm->name }}</span>
                                               
                                            @endforeach
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                     data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     <i class="fas fa-cog"></i>
                                                 </button>
                                                 <div class="dropdown-menu dropdown-menu-end">

                                                     <!--  <a class="dropdown-item" href="#" onclick="editProduct({{ $role->id }})"><i class="fa fa-edit me-2"></i>Edit</a> -->

                                                     <a class="dropdown-item" href="{{ route('roleDelete', $role->id) }}"
                                                             onclick="return swalConfirmLink(event, this)"
                                                             data-item="Role" data-action="delete"><i
                                                                 class="fas fa-trash me-2"></i> Delete</a>

                                                 </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $roles->links() }}
                </div><!-- Card Content end -->

                <!-- create Model Start -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Give Permission</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('roleToPermissionStore') }}">
                                        @csrf
                                        <div class="row g-3">

                                            <div class="form-group mb-3 col-md-12">
                                                <label for="email" class=" col-form-label">Role Name :<span
                                                        class="text-danger"> * </span></label>
                                                    <select class="form-control" name="role_id" id="role_id"
                                                        onchange="checkRole()">
                                                        <option disabled selected>Select Roles</option>
                                                        @foreach ($allRoles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="form-group mb-3 col-md-12">
                                                <p class="font-weight-bold">Permissions</p>
                                                <div class="form-check ">
                                                    <input class="form-check-input" type="checkbox" id="checkPermissionAll"
                                                        value="1">
                                                    <label class="form-check-label" for="checkPermissionAll">All</label>
                                                </div>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($permission_groups as $group)
                                                    <div class="row" style="border: 1px solid #eaeaea;">
                                                        <div class="col-md-6" style="border-right: 1px solid #eaeaea;">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input"
                                                                    type="checkbox" id="{{ $i }}Management"
                                                                    value="{{ $group->name }}"
                                                                    onclick="checkPermissionByGroup('role-{{ $i }}-management-checkBox',this)">
                                                                <label class="form-check-label text-capitalize"
                                                                    for="checkPermission">{{ $group->name }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="role-{{ $i }}-management-checkBox col-md-6">
                                                            @php
                                                                $permissionss = App\Models\User::getPermissionsByGroupName($group->name);
                                                                $j = 1;
                                                            @endphp
                                                            @foreach ($permissionss as $permission)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permissions[]"
                                                                        id="checkPermission{{ $permission->id }} "
                                                                        value="{{ $permission->name }} ">
                                                                    <label class="form-check-label text-capitalize"
                                                                        for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                                                </div>
                                                                @php
                                                                    $j++;
                                                                @endphp
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <br>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn  btn-primary"><i class="fa fa-save"></i>
                                        Save</button>
                                </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div><!-- create model End -->
                <!-- update Model Start -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="editModel" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Update Permissions</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('permissionUpdate') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="editId" name="editId">
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Permission Name</label>
                                            <input type="text" class="form-control" id="editName" name="editName"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Permission Group</label>
                                            <input type="text" class="form-control" id="editGroup_name"
                                                name="editGroup_name" placeholder="Group Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn  btn-primary"><i
                                                    class="fa fa-save"></i>Save</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!-- update model End -->

            </div>
        <!-- pc-container end -->
@endsection


@section('javascript')
    <script>
        $(function() {
            $("#role_id").select2({
                placeholder: "Select Roles",
                dropdownParent: $("#exampleModal"),
                allowClear: true,
                width: '100%'
            });
        });

        function editProduct(id) {
            $.ajax({
                url: "{{ route('editPermission') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#editModel").modal('show');
                    $("#editId").val(result.id);
                    $("#editName").val(result.name);
                    $("#editGroup_name").val(result.group_name);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }


        $("#checkPermissionAll").click(function() {
            if ($(this).is(':checked')) {
                $("input[type=checkbox]").prop('checked', true);
            } else {
                $("input[type=checkbox]").prop('checked', false);
            }
        });


        function checkPermissionByGroup(className, checkThis) {
            const groupIdName = $("#" + checkThis.id);
            const classCheckBox = $('.' + className + ' input');
            if (groupIdName.is(':checked')) {
                classCheckBox.prop('checked', true);
            } else {
                classCheckBox.prop('checked', false);
            }
        }




        function checkRole() {
            var id = $('#role_id').val();
            $.ajax({
                url: "{{ route('getPermissions') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {},
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }
    </script>
@endsection
