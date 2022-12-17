@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Warehous Transfer
@endsection
@section('content')
    <style type="text/css">
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    width: 100%;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

</style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                                <h3 style="float:left;"> Warehouse Transfer List </h3>
                                <a class="btn btn-outline-success float-right" onclick="create()"><i
                                        class="fa fa-plus circle"></i> Warehouse Transfer</a>
                                <a class="btn btn-outline-success" style="margin-left:20px;" onclick="reloadDt()"><i
                                        class="fas fa-sync"></i> Refresh </a>
                            </div><!-- /.card-header -->
                    <div class="card-body">
                                    <!--data listing table-->
                                    <div class="table-responsive">
                                        <table id="manageWarehouseTable" width="100%"
                                            class="table table-bordered table-hover ">
                                            <thead>
                                                <tr>
                                                    <td width="5%">SL</td>
                                                    <td width="30%">Product Name</td>
                                                    <td width="30%">Warehouse From</td>
                                                    <td width="30%">Warehouse To</td>
                                                    <td width="11%">Transfer Quantity</td>
                                                    <td width="7%">Actions</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <!--data listing table-->
                                    </div>
                            </div>
                            <!-- /.card -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal fade" id="warehouseTranferModal" >
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left">  WareHouse Transfer </h4>
                    <button type="button" class="close"data-dismiss="modal" aria-hidden="true"><i class="fas fa-window-close" ></i></button>                </div>
                <div class="modal-body">
                    <form id="warehouseForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
                            <fieldset class="scheduler-border">
            					<legend class="scheduler-border">WareHouse From</legend>
            					<div class="row">
                					<div class="form-group col-md-3">
                					    <label for="transferDate">Transfer Date <span class="text-danger">*</span></label> 
                                        <input type="date" id="transferDate" name="transferDate" class="form-control input-sm" value="{{ todayDate() }}" />
                                    </div>
                                    <div class="form-group col-md-9">
                                        <label for="product"> Select Product <span class="text-danger">*</span></label> 
                                        <div class="d-flex">
                                            <select id="productId" name="productId" class="form-control input-sm" style="width:96%">
                                                 <option value=""> Product Search </option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"> {{ $product->name . ' - ' . $product->code }} </option>
                                                    @endforeach
                                                
                                            </select>
                                             <button type="button" class="btn btn-primary input-group-addon" onclick="showAdvanceSearch();"> <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                					<div class="form-group col-md-6">
                                        <label for="wareHouseID">Select Warehouse <span class="text-danger">*</span></label> 
                                        <select class="form-control" name="wareHouseID" id="wareHouseID" style="width:100%;" required onchange="load_current_stock()">
                                            <option value=""> Warehouse Search </option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}"> {{ $warehouse->wareHouseName }} </option>
                                                    @endforeach
                                        </select>
                                        <select class="form-control" name="wareHouseStock" id="wareHouseStock" style="display:none;">
                                            
                                        </select>
                                    </div>
                					<div class="form-group col-md-3">
            						    <label for="currentStock">Current Stock</label>  
                                        <input type="text" class="form-control" id="currentStock" name="currentStock" placeholder=" Current Stock " readonly>
            						</div>
                					<div class="form-group col-md-3">
            						    <label for="remainingStock">Remaining Stock</label>  
                                        <input type="text" class="form-control" id="remainingStock" name="remainingStock" placeholder=" Remaining Stock " readonly>
            						</div>
        						</div>
					        </fieldset>
					        
					        <fieldset class="scheduler-border">
            					<legend class="scheduler-border">WareHouse To</legend>
            					<div class="row">
                					<div class="form-group col-md-6">
                					    <label for="transferWareHouse">Select Warehouse <span class="text-danger">*</span></label> 
                                        <select class="form-control" name="transferWareHouse" id="transferWareHouse" style="width:100%;" required>
                                           <option value=""> Warehouse Search </option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"> {{ $warehouse->wareHouseName }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label for="transferStock">Transfer Quantity <span class="text-danger">*</span></label>  
                                        <input type="text" class="form-control" id="transferStock" name="transferStock" placeholder=" Transfer Quantity " oninput="remainingStockCalculation()">
                                    </div>
        						</div>
					        </fieldset>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x Close</button>
                            <button type="submit" class="btn btn-primary " id="saveWarehouse"><i class="fa fa-save"></i> Transfer Warehouse</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    

@endsection

@section('javascript')
    <script>
        $("#productId").select2({
            placeholder: " Product Search ",
            allowClear: true,
            width: '100%'
        });
        $("#wareHouseID").select2({
            placeholder: " Warehouse Search ",
            allowClear: true,
            width: '100%'
        });
        $("#transferWareHouse").select2({
            placeholder: " Warehouse Search ",
            allowClear: true,
            width: '100%'
        });
        $("#productId").change(function (e){
            var product_id = $("#productId").val();
            if(product_id != ""){
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('product_id',product_id);
                fd.append('_token',_token);
                $.ajax({
                    url: "{{ route('warehouse.list.product') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $("#wareHouseID").html(result);
                        $("#wareHouseID").val('').trigger('change');
                        load_current_stock();
                    },
                    error: function(response) {
                        Swal.fire("Error", "Error to calculate current stock", "error");
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                })
            }
        })
        function load_current_stock(){
            var warehouseId = $("#wareHouseID").val();
            var productId = $("#productId").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('warehouse_id', warehouseId);
            fd.append('product_id', productId);
            fd.append('_token',_token);
            if(warehouseId != "" && productId != ""){
                $.ajax({
                    url: "{{ route('warehouse.stock') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $("#currentStock").val(result);
                        $("#remainingStock").val(result);
                    },
                    error: function(response) {
                        Swal.fire("Error", "Error to calculate current stock "+JSON.stringify(response), "error");
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }

                })
            }else{
                $("#wareHouseID").val(0);
                $("#productId").val(0);
                //Swal.fire("Error", "Warehouse and product must be selected to calculate current stock", "error");
            }
        }
        function remainingStockCalculation(){
            var currentStock = parseFloat($("#currentStock").val());
            var transferStock = parseFloat($("#transferStock").val());
            var remainingStock = currentStock-transferStock;
            if(remainingStock < 0){
                $("#transferStock").val(0);
                $("#remainingStock").val(currentStock);
                Swal.fire("Error", "More then stock value must not be transfered", "error");
            }else{
                $("#remainingStock").val(remainingStock);
            }
        }
        function create() {
            $("#warehouseTranferModal").modal('show');
        }

        $('#warehouseTranferModal').on('shown.bs.modal', function() {
            $('#warehouseName').focus();
        })
        $('#editWarehouseModal').on('shown.bs.modal', function() {
            $('#editwarehouseName').focus();
        })
        var table;
        $(document).ready(function() {
            table = $('#manageWarehouseTable').DataTable({
                'ajax': "{{ route('warehouseTransfer.view') }}",
                processing: true,
            });
        });

        $("#warehouseForm").submit(function(e) {
            e.preventDefault();
            var warehouseFrom = $("#wareHouseID").val();
            var warehouseTo = $("#transferWareHouse").val();
            var transferDate = $("#transferDate").val();
            var product = $("#productId").val();
            var currentStock = $("#remainingStock").val();
            var transferStock = $("#transferStock").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('warehouseFrom', warehouseFrom);
            fd.append('warehouseTo', warehouseTo);
            fd.append('transferDate', transferDate);
            fd.append('product', product);
            fd.append('currentStock', currentStock);
            fd.append('transferStock', transferStock);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('warehouseTransfer.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#warehouseTranferModal").modal('hide');
                    Swal.fire("Warehouse saved!", result.success, "success");
                    $("#warehouseForm")[0].reset();

                    table.ajax.reload(null, false);
                },
                error: function(response) {
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
                        url: "{{ route('warehouseTransfer.delete') }}",
                        method: "POST",
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
