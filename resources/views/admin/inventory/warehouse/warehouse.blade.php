@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} WareHouse
@endsection
@section('content')
    <style type="text/css">


    </style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">WareHouse List</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Warehouse
                        </a>
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('warehouse.view') }}"
                        searchPlaceholder="Search warehouses..."
                        :sortOptions="['id' => 'ID', 'wareHouseName' => 'Name']"
                        :defaultSort="'id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="manageWarehouseTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="30%">Warehouse Name</th>
                                    <th width="31%">Description / Notes</th>
                                    <th width="11%">Status</th>
                                    <th width="7%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($warehouses as $warehouse)
                                    <tr>
                                        <td>{{ $loop->iteration + ($warehouses->currentPage() - 1) * $warehouses->perPage() }}</td>
                                        <td>{{ $warehouse->wareHouseName }}</td>
                                        <td>{{ $warehouse->wareHouseAddress }}</td>
                                        <td>
                                            @if($warehouse->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" onclick="editWarehouse({{ $warehouse->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $warehouse->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No warehouses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $warehouses->links() }}
                </div>
                <!-- /.card -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="warehouseModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add Warehouse</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="warehouseForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Warehouse Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="warehouseName" type="text" name="warehouseName"
                                    placeholder=" Warehouse name">
                                <span class="text-danger" id="warehouseNameError"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Description / Notes <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="description" type="text" name="description"
                                    placeholder=" description">
                                <span class="text-danger" id="descriptionError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary " id="saveWarehouse"><i class="fa fa-save"></i>
                                Save Warehouse
                            </button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- edit modal -->
    <div class="modal fade" id="editWarehouseModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Edit Warehouse</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editWarehouseForm" method="POST" action="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="warehouseEditId" name="warehouseEditId">
                            <div class="form-group col-md-12">
                                <label>Warehouse Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editWarehouseName" type="text"
                                    name="editWarehouseName" value="">
                                <span class="text-danger" id="editWarehouseNameError"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Description / Notes <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editdDscription" type="text" name="editdDscription"
                                    value="">
                                <span class="text-danger" id="editDescriptionError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Status </label>
                                <select id="editStatus" name="editStatus " class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select> <br>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary " id="saveWarehouse"><i class="fa fa-save"></i>
                                Save Warehouse</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')
    <script>
        function create() {
            $("#warehouseModal").modal('show');
        }

        $('#warehouseModal').on('shown.bs.modal', function() {
            $('#warehouseName').focus();
        })
        $('#editWarehouseModal').on('shown.bs.modal', function() {
            $('#editwarehouseName').focus();
        })
        $(document).ready(function() {});

        $("#warehouseForm").submit(function(e) {
            e.preventDefault();
            var warehouseName = $("#warehouseName").val();
            var description = $("#description").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('warehouseName', warehouseName);
            fd.append('description', description);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('warehouse.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    //alert(JSON.stringify(result));
                    $("#warehouseModal").modal('hide');
                    Swal.fire("Warehouse saved!", result.success, "success").then(function(){
                      location.reload();
                    });
                    $("#warehouseForm").trigger("reset");
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#warehouseNameError').text(response.responseJSON.errors.warehouseName);
                    $("#descriptionError").text(response.responseJSON.errors.description);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        });

        function editWarehouse(id) {
            $.ajax({
                url: "{{ route('warehouse.edit') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#editWarehouseModal").modal('show');
                    $("#warehouseEditId").val(result.id);
                    $("#editWarehouseName").val(result.wareHouseName);
                    $("#editdDscription").val(result.wareHouseAddress);
                    $("#editStatus").val(result.status).trigger("change");
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

        $("#editWarehouseForm").submit(function(e) {
            e.preventDefault();
            const id = $("#warehouseEditId").val();
            const warehouseName = $("#editWarehouseName").val();
            const description = $("#editdDscription").val();
            const status = $("#editStatus").val();
            const _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('warehouseName', warehouseName);
            fd.append('description', description);
            fd.append('status', status);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('warehouse.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#editWarehouseModal").modal('hide');
                    Swal.fire("Updated Warehouse!", result.success, "success").then(function(){
                      location.reload();
                    });
                    $("#editWarehouseForm").trigger("reset");
                },
                error: function(response) {
                    $('#editWarehouseNameError').text(response.responseJSON.errors.warehouseName);
                    $("#editDescriptionError").text(response.responseJSON.errors.description);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Warehouse!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('warehouse.delete') }}",
                        method: "GET",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            Swal.fire("Done!", result.success, "success").then(function(){
                              location.reload();
                            });
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary record is safe :)", "error");
                }
            })

        }


        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {

            } else {
                create();
            }
        });

        function reloadDt() {
            location.reload();
        }
        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            reloadDt();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#productForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editProductForm").trigger('submit');
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
