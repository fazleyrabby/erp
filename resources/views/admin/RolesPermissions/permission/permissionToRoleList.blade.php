@extends('admin.master')
@section('title')
    Admin Permission To Role
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Give permission
                        <button type="button" class="btn  btn-primary float-right" data-toggle="modal"
                            data-target="#exampleModal" data-whatever="@getbootstrap"><i class="fa fa-plus circle"></i>
                            Give Permissions</button>
                        <h3>
                            <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
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
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>

                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $perm)
                                                <span class="badge mr-1">{{ $perm->name }}</span>
                                               
                                            @endforeach
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="fas fa-cog"></i> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">

                                                    <!--  <li type="button" class="btn " onclick="editProduct({{ $role->id }})"><i class="fa fa-edit"></i>Edit</li> -->

                                                    <li class="action"><a href="{{ route('roleDelete', $role->id) }}"
                                                            class="btn"
                                                            onclick="return confirm('Are you sure you want to delete this item?');"><i
                                                                class="fas fa-trash"></i> Delete </a></li>

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- Card Content end -->

                <!-- create Model Start -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Give Permission</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="fas fa-window-close"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('roleToPermissionStore') }}">
                                        @csrf
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <label for="email" class=" col-form-label">Role Name :<span
                                                        class="text-danger"> * </span></label>
                                                <select class="form-control" name="role_id" id="role_id"
                                                    onchange="checkRole()">
                                                    <option disabled selected>Select Roles</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
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
                                    <button type="button" class="btn  btn-secondary mr-auto" data-dismiss="modal">x
                                        Close</button>
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
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">X</button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('permissionUpdate') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="editId" name="editId">
                                        <div class="form-group">
                                            <label class="col-form-label">Permission Name</label>
                                            <input type="text" class="form-control" id="editName" name="editName"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Permission Group</label>
                                            <input type="text" class="form-control" id="editGroup_name"
                                                name="editGroup_name" placeholder="Group Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="button" class="btn  btn-secondary mr-auto"
                                                data-dismiss="modal">x
                                                Close</button>
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
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')
    <script>
        $(document).ready(function() {
            $('#data_Table').DataTable({
                responsive: true
            });
        });

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
