@extends('admin.master')
@section('title')
    Admin Permission List
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permission</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Permissions
                        </button>
                    </div>
                </div>
                @if (Session::has('message'))
                <div class="card-footer text-success text-center">{{ Session::get('message') }}</div>
                @endif
                @if (Session::has('error'))
                <div class="card-footer text-danger text-center">{{ Session::get('error') }}</div>
                @endif
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('permissionView') }}"
                        searchPlaceholder="Search permissions..."
                        :sortOptions="['id' => 'ID', 'name' => 'Name', 'group_name' => 'Group']"
                        :defaultSort="'id'"
                        :defaultDirection="'DESC'"
                    />

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
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration + ($permissions->currentPage() - 1) * $permissions->perPage() }}</td>

                                            <td class="text-center">{{$permission->group_name}}</td>

                                            <td class="text-center">{{ $permission->name }}</td>

                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                         data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                         <i class="fas fa-cog"></i>
                                                     </button>
                                                     <div class="dropdown-menu dropdown-menu-end">

                                                         <a class="dropdown-item" href="#"
                                                             onclick="editProduct({{ $permission->id }})">
                                                             <i class="fa fa-edit me-2"></i>Edit
                                                         </a>

                                                         <a class="dropdown-item"
                                                                 href="{{ route('permissionDelete', $permission->id) }}"
                                                                 onclick="return swalConfirmLink(event, this)"
                                                                 data-item="Permission" data-action="delete"><i
                                                                     class="fas fa-trash me-2"></i> Delete</a>

                                                     </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{ $permissions->links() }}
                </div><!-- Card Content end -->

                <!-- create Model Start -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('permissionStore') }}" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Permission Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="col-form-label">Group Name</label>
                                            <input type="text" class="form-control" id="group_name" name="group_name"
                                                placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn  btn-secondary mr-auto" data-bs-dismiss="modal">x
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('permissionUpdate') }}" method="post"
                                    enctype="multipart/form-data">
                                    <div class="modal-body">
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
                                            <label class="col-form-label">Group Name</label>
                                            <input type="text" class="form-control" id="editGroup_name"
                                                name="editGroup_name" placeholder="Name">
                                            <span
                                                class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                            Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- update model End -->
            </div>
        <!-- pc-container end -->
@endsection


@section('javascript')
    <script>



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
