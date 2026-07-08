@extends('admin.master')
@section('title')
    Admin Role List
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Roles</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Role
                        </button>
                    </div>
                </div>
                @if (Session::has('message'))
                <div class="card-footer text-success text-center">{{ Session::get('message') }}</div>
                @endif
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('rolesView') }}"
                        searchPlaceholder="Search roles..."
                        :sortOptions="['id' => 'ID', 'name' => 'Name']"
                        :defaultSort="'id'"
                        :defaultDirection="'DESC'"
                    />

                    <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="data_Table" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%" class="text-center">Sl</th>
                                    <th width="72%" class="text-center">Name</th>
                                    <th width="8%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>

                                        <td class="text-center">{{ $role->name }}</td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm"
                                                     data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     <i class="fas fa-cog"></i>
                                                 </button>
                                                 <div class="dropdown-menu dropdown-menu-end">

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
            </div><!-- Card end -->

            <!-- create Modal Start -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('roleStore') }}" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Role Name">
                                    <span
                                        class="text-danger">{{ $errors->has('title') ? $errors->first('title') : '' }}</span>
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
            </div><!-- create modal End -->
        <!-- pc-container end -->
@endsection


@section('javascript')
@endsection
