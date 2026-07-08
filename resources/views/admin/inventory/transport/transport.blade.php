@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Transport Information
@endsection
@section('content')
    <style type="text/css">

    </style>
    
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transport Information</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Transport
                        </a>
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('transport.view') }}"
                        searchPlaceholder="Search transport..."
                        :sortOptions="['tbl_transportinfo.id' => 'ID', 'tbl_transportinfo.transportName' => 'Name']"
                        :defaultSort="'tbl_transportinfo.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="manageTransportTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th width="30%">Transport Name</th>
                                    <th width="31%">Address</th>
                                    <th width="31%">Contact</th>
                                    <th width="11%">Status</th>
                                    <th width="7%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transports as $transport)
                                <tr>
                                    <td>{{ $loop->iteration + ($transports->currentPage() - 1) * $transports->perPage() }}</td>
                                    <td>{{ $transport->transportName }}</td>
                                    <td>{{ $transport->address }}</td>
                                    <td><b>Contact Person: </b>{{ $transport->contactPerson }}<br><b>Contact No: </b>{{ $transport->contactNo }}</td>
                                    <td>
                                        @if ($transport->status == 'Active')
                                        <i class="fas fa-check-circle text-success" style="font-size:16px;"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger" style="font-size:16px;"></i>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#" onclick="editTransportInfo({{ $transport->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $transport->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No transport records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $transports->links() }}
                    </div>
                </div>
            </div>
        <!-- modal -->
    <div class="modal fade" id="transportModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add New Transport Info</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="transportForm" method="POST" action="#">
                        @csrf
                        <div class="row g-3">
                            <div class="form-group mb-3 col-md-6">
                                <label>Transport Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="transportName" type="text" name="transportName"
                                    placeholder=" Transport Name">
                                <span class="text-danger" id="transportNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Person </label>
                                <input class="form-control  input-sm" id="contactPerson" type="text" name="contactPerson"
                                    placeholder=" Contact Person">
                                <span class="text-danger" id="contactPersonError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Number <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="contactNumber" type="text" name="contactNumber"
                                    placeholder=" Contact Number">
                                <span class="text-danger" id="contactNumberError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Email </label>
                                <input class="form-control  input-sm" id="contactEmail" type="email" name="contactEmail"
                                    placeholder=" Contact Email">
                                <span class="text-danger" id="contactEmailError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Address <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="address" type="text" name="address"
                                    placeholder=" Address">
                                <span class="text-danger" id="addressError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Remarks </label>
                                <textarea class="form-control input-sm" rows="2" id="remark" type="text" name="remark"
                                    placeholder=" Remarks"></textarea>
                                <span class="text-danger" id="remarksError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x Close</button>
                            <button type="submit" class="btn btn-primary " id="savebankAccountInfo"><i
                                    class="fa fa-save"></i>
                                Save Transport</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- edit modal -->
    <div class="modal fade" id="editTransportModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Update Transport Info</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTransportForm" method="POST" action="#">
                        @csrf
                        <div class="row g-3">
                            <input type="hidden" id="editTransportInfoId" name="editTransportInfoId">
                            <div class="form-group mb-3 col-md-6">
                                <label>Transport Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editTransportName" type="text"
                                    name="editTransportName" placeholder=" Transport Name">
                                <span class="text-danger" id="editTransportNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Person </label>
                                <input class="form-control  input-sm" id="editContactPerson" type="text"
                                    name="editContactPerson" placeholder=" Contact Person">
                                <span class="text-danger" id="editContactPersonError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Number <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editContactNumber" type="text"
                                    name="editContactNumber" placeholder=" Contact Number">
                                <span class="text-danger" id="editContactNumberError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Email </label>
                                <input class="form-control  input-sm" id="editContactEmail" type="email"
                                    name="editContactEmail" placeholder=" Contact Email">
                                <span class="text-danger" id="editContactEmailError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Address</label>
                                <textarea class="form-control input-sm" rows="2" id="editAddress" type="text"
                                    name="editAddress" placeholder=" Address"></textarea>
                                <span class="text-danger" id="editAddressError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Remarks</label>
                                <textarea class="form-control input-sm" id="editRemark" type="text" name="editRemark"
                                    placeholder=" Remarks"></textarea>
                                <span class="text-danger" id="editRemarkError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Status</label>
                                <select class="form-control input-sm" id="editStatus" name="editStatus">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x Close</button>
                            <button type="submit" class="btn btn-primary " id="updatebankAccountInfo"><i
                                    class="fa fa-save"></i>
                                Update Transport</button>
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
            clearErroMessage();
            $("#transportModal").modal('show');
            $("#transportName").focus();
            $("#transportForm").trigger("reset");
        }

        $('#transportModal').on('shown.bs.modal', function() {
            $('#transportName').focus();
        })
        $('#editTransportModal').on('shown.bs.modal', function() {
            $('#editTransportName').focus();
        })

        $("#transportForm").submit(function(e) {
            e.preventDefault();
            var transportName = $("#transportName").val();
            var contactPerson = $("#contactPerson").val();
            var contactNumber = $("#contactNumber").val();
            var contactEmail = $("#contactEmail").val();
            var address = $("#address").val();
            var remarks = $("#remark").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('transportName', transportName);
            fd.append('contactPerson', contactPerson);
            fd.append('contactNumber', contactNumber);
            fd.append('contactEmail', contactEmail);
            fd.append('address', address);
            fd.append('remarks', remarks);
            fd.append('_token', _token);
            clearErroMessage();
            $.ajax({
                url: "{{ route('transport.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#transportModal").modal('hide');
                    Swal.fire("Transport saved!", result.success, "success");
                    $("#transportForm").trigger("reset");
                    location.reload();
                },
                error: function(response) {
                    $('#transportNameError').text(response.responseJSON.errors.transportName);
                    $('#contactNumberError').text(response.responseJSON.errors.contactNumber);
                    $('#addressError').text(response.responseJSON.errors.address);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        });

        function editTransportInfo(id) {
            $.ajax({
                url: "{{ route('transport.edit') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    clearErroMessage('editError');
                    $("#editTransportModal").modal('show');
                    $("#editTransportInfoId").val(result.id);
                    $("#editTransportName").val(result.transportName);
                    $("#editContactPerson").val(result.contactPerson);
                    $("#editContactNumber").val(result.contactNo);
                    $("#editContactEmail").val(result.email);
                    $("#editAddress").val(result.address);
                    $("#editRemark").val(result.remarks);
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

        $("#editTransportForm").submit(function(e) {
            e.preventDefault();
            const id = $("#editTransportInfoId").val();
            const transportName = $("#editTransportName").val();
            const contactPerson = $("#editContactPerson").val();
            const contactNumber = $("#editContactNumber").val();
            const contactEmail = $("#editContactEmail").val();
            const address = $("#editAddress").val();
            const remarks = $("#editRemark").val();
            const status = $("#editStatus").val();
            const _token = $('input[name="_token"]').val();

            var fd = new FormData();
            fd.append('id', id);
            fd.append('transportName', transportName);
            fd.append('contactPerson', contactPerson);
            fd.append('contactNumber', contactNumber);
            fd.append('contactEmail', contactEmail);
            fd.append('address', address);
            fd.append('remarks', remarks);
            fd.append('status', status);
            fd.append('_token', _token);
            clearErroMessage("editError");
            $.ajax({
                url: "{{ route('transport.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#editTransportModal").modal('hide');
                    Swal.fire("Updated Transport Info!", result.success, "success");
                    $("#editTransportForm").trigger("reset");
                    location.reload();
                },
                error: function(response) {
                    $('#editTransportNameError').text(response.responseJSON.errors.transportName);
                    $('#editContactNumberError').text(response.responseJSON.errors.contactNumber);
                    $('#editAddressError').text(response.responseJSON.errors.address);
                    $("#editStatusError").text(response.responseJSON.errors.status);
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
            confirmDeleteSwal({
                url      : "{{ route('transport.delete') }}",
                id       : id,
                itemName : 'Transport',
            });
        }

        function clearErroMessage(type) {
            if (type == 'editError') {
                $('#editTransportNameError').text('');
                $('#editContactNumberError').text('');
                $('#editAddressError').text('');
            } else {
                $('#transportNameError').text('');
                $('#contactNumberError').text('');
                $('#addressError').text('');
            }
        }


        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {

            } else {
                create();
            }
        });

        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            location.reload();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#transportForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editTransportForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('esc', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editTransportModal").modal('hide');
            } else if ($('#modal.in, #modal.show').length) {
                $('#transportModal').modal('hide');
            }
        });
    </script>
@endsection