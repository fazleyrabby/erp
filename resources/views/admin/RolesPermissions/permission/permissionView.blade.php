@extends('admin.master')
@section('title')
    Admin Permission List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Permission
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                                data-target="#exampleModal" data-whatever="@getbootstrap"><i class="fa fa-plus-circle"></i>
                                Add
                                Permissions</button>
                    </h3>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="data_Table" width="100%">
                                <thead>
                                    <tr class="bg-light">
                                        <td width="5%" class="text-center">Sl</td>
                                        <td width="40%" class="text-center">Group</td>
                                        <td width="45%" class="text-center">Name</td>
                                        <td width="8%" class="text-center">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="text-center">{{ $i++ }}</td>

                                            <td class="text-center">{{$permission->group_name}}</td>

                                            <td class="text-center">{{ $permission->name }}</td>

                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        <i class="fas fa-cog"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right"
                                                        style="border: 1px solid gray;" permission="menu">

                                                        <li type="button" class="btn "
                                                            onclick="editProduct({{ $permission->id }})">
                                                            <i class="fa fa-edit"></i>Edit
                                                        </li>

                                                        <li class="action"><a
                                                                href="{{ route('permissionDelete', $permission->id) }}"
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
                    </div>
                </div><!-- Card Content end -->

                <!-- create Model Start -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="fas fa-window-close"></i></button>
                                </div>
                                <form action="{{ route('permissionStore') }}" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-group">
                                            <label class="col-form-label">Permission Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Group Name</label>
                                            <input type="text" class="form-control" id="group_name" name="group_name"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
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
                                    <h5 class="modal-title" id="exampleModalLabel">Add Banner</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                            class="fas fa-window-close"></i></button>
                                </div>

                                <form action="{{ route('permissionUpdate') }}" method="post"
                                    enctype="multipart/form-data">
                                    <div class="modal-body">
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
                                            <label class="col-form-label">Group Name</label>
                                            <input type="text" class="form-control" id="editGroup_name"
                                                name="editGroup_name" placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn  btn-secondary mr-auto" data-dismiss="modal">x
                                            Close</button>
                                        <button type="submit" class="btn  btn-primary"><i class="fa fa-save"></i>
                                            Update</button>
                                    </div>
                                </form>
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



        function editProduct(id) {
            $.ajax({
                url: "{{ route('editPermission') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
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
    </script>
@endsection
