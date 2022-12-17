@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Transport Information
@endsection
@section('content')
    <style type="text/css">


    </style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 style="float:left;"> Transport Information </h3>
                    <a class="btn btn-primary float-right" onclick="create()"><i class="fa fa-plus circle"></i>
                        Transport</a>
                    <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i
                            class="fas fa-sync"></i> Refresh </a>
                </div><!-- /.card-header -->

                <div class="card-body">
                    <div class="col-md-12">


                        <!--data listing table-->
                        <div class="table-responsive">
                            <table id="manageBankAccountInfoTable" width="100%" class="table table-bordered table-hover ">
                                <thead>
                                    <tr>
                                        <td width="5%">SL</td>
                                        <td width="30%">Transport Name</td>
                                        <td width="31%">Address</td>
                                        <td width="31%">Contact</td>
                                        <td width="11%">Status</td>
                                        <td width="7%">Actions</td>
                                    </tr>
                                </thead>
                            </table>
                            <!--data listing table-->
                        </div>

                    </div>


                </div>
                <!-- /.card -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="transportModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add New Transport Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    <form id="transportForm" method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Transport Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="transportName" type="text" name="transportName"
                                    placeholder=" Transport Name">
                                <span class="text-danger" id="transportNameError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Person </label>
                                <input class="form-control  input-sm" id="contactPerson" type="text" name="contactPerson"
                                    placeholder=" Contact Person">
                                <span class="text-danger" id="contactPersonError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Number <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="contactNumber" type="text" name="contactNumber"
                                    placeholder=" Contact Number">
                                <span class="text-danger" id="contactNumberError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Email </label>
                                <input class="form-control  input-sm" id="contactEmail" type="email" name="contactEmail"
                                    placeholder=" Contact Email">
                                <span class="text-danger" id="contactEmailError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="address" type="text" name="address"
                                    placeholder=" Address">
                                <span class="text-danger" id="addressError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Remarks </label>
                                <textarea class="form-control input-sm" rows="2" id="remark" type="text" name="remark"
                                    placeholder=" Remarks"></textarea>
                                <span class="text-danger" id="remarksError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x Close</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    <form id="editTransportForm" method="POST" action="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="editTransportInfoId" name="editTransportInfoId">
                            <div class="form-group col-md-6">
                                <label>Transport Name <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editTransportName" type="text"
                                    name="editTransportName" placeholder=" Transport Name">
                                <span class="text-danger" id="editTransportNameError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Person </label>
                                <input class="form-control  input-sm" id="editContactPerson" type="text"
                                    name="editContactPerson" placeholder=" Contact Person">
                                <span class="text-danger" id="editContactPersonError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Number <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editContactNumber" type="text"
                                    name="editContactNumber" placeholder=" Contact Number">
                                <span class="text-danger" id="editContactNumberError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Contact Email </label>
                                <input class="form-control  input-sm" id="editContactEmail" type="email"
                                    name="editContactEmail" placeholder=" Contact Email">
                                <span class="text-danger" id="editContactEmailError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address <span class="text-danger"> * </span></label>
                                <input class="form-control  input-sm" id="editAddress" type="text" name="editAddress"
                                    placeholder=" Address">
                                <span class="text-danger" id="editAddressError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Remarks </label>
                                <textarea class="form-control input-sm" rows="2" id="editRemark" type="text" name="editRemark"
                                    placeholder=" Remarks"></textarea>
                                <span class="text-danger" id="editRemarksError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Status <span class="text-danger"> * </span></label>
                                <select id="editStatus" name="editStatus " class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select> <br>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="submit" class="btn btn-primary " id="saveTransportInfo"><i
                                    class="fa fa-save"></i>
                                update Transport</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /. edit modal -->
@endsection

@section('javascript')
    <script>
        function create() {
            $("#transportModal").modal('show');
            clearErroMessage();
        }

        $('#transportModal').on('shown.bs.modal', function() {
            $('#accountNumber').focus();
        })
        $('#editTransportModal').on('shown.bs.modal', function() {
            $('#editAccountNumber').focus();
        })
        var table;
        $(document).ready(function() {
            table = $('#manageBankAccountInfoTable').DataTable({
                'ajax': "{{ route('transport.getTransports') }}",
                processing: true,
            });
        });

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
                    table.ajax.reload(null, false);
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
                    table.ajax.reload(null, false);
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
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Transport!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('transport.delete') }}",
                        method: "GET",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            Swal.fire("Done!", result.success, "success");
                            table.ajax.reload(null, false);
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

        function reloadDt() {
            if ($('#modal.in, #modal.show').length) {

            } else if ($('#editModal.in, #editModal.show').length) {

            } else {
                table.ajax.reload(null, false);
            }
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
