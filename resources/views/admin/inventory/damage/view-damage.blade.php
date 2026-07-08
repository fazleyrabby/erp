@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Damages
@endsection
@section('content')
    <style type="text/css">

    </style>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Damage List</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Damage
                        </a>
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('damage.view') }}"
                        searchPlaceholder="Search damage..."
                        :sortOptions="['damage_products.id' => 'ID', 'damage_products.damage_date' => 'Date', 'damage_products.damage_order_no' => 'Damage No']"
                        :defaultSort="'damage_products.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="manageTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="6%">SL#</th>
                                    <th>Date</th>
                                    <th>Damage Info</th>
                                    <th>Product Info</th>
                                    <th>Other Info</th>
                                    <th>Quantity</th>
                                    <th width="7%" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($damages as $damage)
                                    <tr>
                                        <td>{{ $loop->iteration + ($damages->currentPage() - 1) * $damages->perPage() }}</td>
                                        <td>{{ $damage->damage_date }}</td>
                                        <td><b>Damage No: </b>{{ $damage->damage_order_no }}</td>
                                        <td><b>Name: </b>{{ $damage->name }}<br><b>Code: </b>{{ $damage->code }}</td>
                                        <td><b>Category: </b>{{ $damage->categoryName }}<br><b>Brand: </b>{{ $damage->brandName }}</td>
                                        <td>{{ $damage->damage_quantity }} {{ $damage->unitName }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" onclick="printPurchase({{ $damage->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $damage->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No damage records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $damages->links() }}
                </div>
            </div>

    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add Damage</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                </div>
                <div class="modal-body">
                    <form id="damageForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf

                        <input type="hidden" name="id">
                        <div class="row g-3">
                            <div class="form-group mb-3 col-md-12">
                                <label class="form-label"> Date <span class="text-danger"> * </span></label>
                                <input class="form-control form-control-sm" id="date" type="date" name="date"
                                    value="{{ date('Y-m-d') }}">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label"> Product <span class="text-danger"> * </span></label>
                                <select class="form-control form-control-sm" id="productId" name="productId">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label"> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control form-control-sm" id="warehouse" name="warehouse">
                                </select>
                                <span class="text-danger" id="stock_warehouseError"></span>
                            </div>
                        
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label"> Current Stock</label>
                                <input class="form-control form-control-sm" id="current_stock" type="text" name="current_stock" Disabled>
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label class="form-label"> Quantity <span class="text-danger"> * </span></label>
                                <input class="form-control form-control-sm" id="damage_quantity" type="text" name="damage_quantity">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                        
                            <div class="form-group mb-3 col-md-12">
                                <label class="form-label"> Remarks</label>
                                <input class="form-control form-control-sm" id="remark" type="text" name="remark">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function() {})
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
                        Swal.fire("Saved!", result.success, "success").then(function(){
                          location.reload();
                        });
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
            confirmDeleteSwal({
                url      : "{{ route('damage.delete') }}",
                id       : id,
                itemName : 'Damage',
                onError  : function(response) {
                    Swal.fire("Error!", JSON.stringify(response), "error");
                },
            });
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
