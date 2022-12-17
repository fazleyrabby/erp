@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Damages
@endsection
@section('content')
    <style type="text/css">

    </style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 style="float:left;"> Damage List </h3>
                    <a class="btn btn-cyan float-right" onclick="create()"><i
                            class="fa fa-plus circle"></i>Add Damage</a>
                    <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i
                            class="fas fa-sync"></i> Refresh </a>
                </div><!-- /.card-header -->
                <div class="card-body">
                                    <div class="table-responsive">
                                        <!--data listing table-->
                                        <table id="manageTable" width="100%" class="table table-bordered table-hover ">
                                            <thead>
                                                <tr>
                                                    <td  width="6%">SL#</td>
                                                    <td>Date</td>
                                                    <td>Damage Info</td>
                                                    <td>Product Info</td>
                                                    <td>Other Info</td>
                                                    <td>Quantity</td>
                                                    <td  width="7%">Action</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <!--data listing table-->
                                    </div>
                            </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add Damage</h4>
                    <button type="button" class="close"data-dismiss="modal" aria-hidden="true"><i class="fas fa-window-close" ></i></button>                </div>
                <div class="modal-body">
                    <form id="damageForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf

                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label> Date <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="date" type="date" name="date"
                                    value="{{ date('Y-m-d') }}">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="col-md-6">
                                <label> Product <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="productId" name="productId">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="col-md-6">
                                <label> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="warehouse" name="warehouse">
                                </select>
                                <span class="text-danger" id="stock_warehouseError"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label> Current Stock</label>
                                <input class="form-control input-sm" id="current_stock" type="text" name="current_stock"
                                    Disabled>
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="col-md-6">
                                <label> Quantity <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="damage_quantity" type="text"
                                    name="damage_quantity">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label> Remarks</label>
                            <input class="form-control input-sm" id="remark" type="text" name="remark">
                            <span class="text-danger" id="nameError"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btnSave" id="saveDamage"><i
                                    class="fa fa-save"></i> Save Damage</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#productId").select2({
                placeholder: "Select Product",
                dropdownParent: $("#modal"),
                allowClear: true,
                width: '100%'
            });

            $("#warehouse").select2({
                placeholder: "Select warehouse",
                dropdownParent: $("#modal"),
                allowClear: true,
                width: '100%'
            });
        });

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
        var table;
        $(document).ready(function() {
            table = $('#manageTable').DataTable({
                'ajax': "{{ route('damage.getDamage') }}",
                processing: true,
            });
        })
        $("#productId").change(function() {
            var productId = $("#productId").val();
            if (productId != "") {
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('product_id', productId);
                fd.append('_token', _token);
                $.ajax({
                    url: "{{ route('getWarehouseByProductID') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        let viewWarehouse = '<option value="" selected>Select Warehouse</option>';
                        for (warehouse of result) {
                            viewWarehouse += '<option value="' + warehouse.id + '" >' + warehouse
                                .wareHouseName + '</option>';
                        }
                        $("#warehouse").html(viewWarehouse);
                    },
                    error: function(response) {
                        Swal.fire("Error!", result.response, "error");
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                })
            } else {
                $("#current_stock").val(0);
            }
        });
        $("#warehouse").change(function() {
            var productId = $("#productId").val();
            var warehouse_id = $("#warehouse").val();
            if (productId != "") {
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('product_id', productId);
                fd.append('warehouse_id', warehouse_id);
                fd.append('_token', _token);
                $.ajax({
                    url: "{{ route('getStockByProductWarehouse') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $("#current_stock").val(result);
                    },
                    error: function(response) {
                        alert(JSON.stringify(response))
                        Swal.fire("Error!", result.response, "error");
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                })
            } else {
                $("#current_stock").val(0);
            }
        });

        $("#damageForm").submit(function(e) {
            e.preventDefault();
            clearMessages();
            var products_id = $("#productId").val();
            var damage_quantity = $("#damage_quantity").val();
            var current_stock = $("#current_stock").val();
            var warehouse_id = $("#warehouse").val();
            var remarks = $("#remark").val();
            var damage_date = $("#date").val();
            var _token = $('input[name="_token"]').val();
            if (parseInt(damage_quantity) <= parseInt(current_stock)) {
                var fd = new FormData();
                fd.append('products_id', products_id);
                fd.append('warehouse_id', warehouse_id);
                fd.append('damage_quantity', damage_quantity);
                fd.append('remarks', remarks);
                fd.append('damage_date', damage_date);
                fd.append('_token', _token);
                $.ajax({
                    url: "{{ route('damage.store') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $("#modal").modal('hide');
                        Swal.fire("Saved!", result.success, "success");
                        table.ajax.reload(null, false);
                        reset();
                    },
                    error: function(response) {
                        alert(JSON.stringify(response));
                        //$('#nameError').text(response.responseJSON.errors.name);
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire("Error!", "Damage stock can't be greater than Current Stock", "error");

            }
        })

        function clearMessages() {
            $('#nameError').text("");
        }

        function reset() {
            $("#name").val("");
            $("#damageForm")[0].reset();
            $('#productId').val(null).trigger('change');
            $('#warehouse').val(null).trigger('change');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Damage!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('damage.delete') }}",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            //alert(JSON.stringify(result));
                            Swal.fire("Deleted!", result.success, "success");
                            table.ajax.reload(null, false);
                        },
                        error: function(response) {
                            Swal.fire("Error!", JSON.stringify(response), "error");
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary Unit is safe :)", "error");
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
                $("#unitForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editUnitForm").trigger('submit');
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

        function printPurchase(id) {
            window.open("{{ url('damage/invoice/') }}" + "/" + id);
        }
    </script>
@endsection
