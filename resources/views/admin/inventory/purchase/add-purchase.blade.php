@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase
@endsection
@section('content')
    
        <section class="content box-border">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Purchase Products</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="{{ route('purchase.index') }}">
                            <i class="fa fa-reply me-2"></i> Back To Purchase List
                        </a>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row g-3">
                            @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                <div class="form-group mb-3 col-md-6">
                                    <label>Barcode: </label>
                                    <input class="form-control form-control-sm" id="barcode" type="text" name="barcode"
                                        onkeyup="findProduct()">
                                    <span class="text-danger" id="barcodeError"></span>
                                </div>
                            @endif
                            <div class="form-group mb-3 col-md-2">
                                <label>Date: <span class="text-danger">*</span></label>
                                <input type="date" id="purchaseDate" name="purchaseDate" class="form-control form-control-sm"
                                    value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label>Warehouse: <span class="text-danger">*</span></label>
                                <select id="warehouse" name="warehouse" class="abcd" style="width:100%" required>
                                    <option value=''> Select Warehouse </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value='{{ $warehouse->id }}' selected='true'>
                                            {{ $warehouse->wareHouseName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="warehouseError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label>Supplier Name: <span class="text-danger">*</span></label>
                                <select id="supplier" name="supplier" class="form-control form-control-sm">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="supplierIdError"></span>
                            </div>

                            <input type="hidden" id="category_id" name="category" value="30">
                            
                            <div class="form-group mb-3 col-md-2">
                                <label class="labelPurchase">Previous Due
                                    ({{ Session::get('companySettings')[0]['currency'] }}): </label><br>
                                <span class="btn btn-secondary float-right viewPurchase" id="currentDue"
                                    style="display: none">0</span>
                                <span class="btn btn-secondary float-right viewPurchase" style="height: 55%;"
                                    id="currentDueDisplay">0</span>
                            </div>
                            <div class="form-group mb-3 col-md-2 d-none">
                                <label class="labelPurchase">Total Due
                                    ({{ Session::get('companySettings')[0]['currency'] }}): </label><br>
                                <span class="btn btn-danger viewPurchase" id="totalWithDueView">0</span>
                                <span class="btn btn-danger viewPurchase" id="totalWithDue" hidden="hidden"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6 d-none">
                                <label>Category: </label>
                                <select id="category" name="category" class="abcd" style="width:100%">
                                    <option value='0' selected='true'> Select Category </option>
                                    @foreach ($categories as $category)
                                        <option value='{{ $category->id }}'>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-6 d-none">
                                <label>Brand: </label>
                                <select id="brand" name="brand" class="form-control form-control-sm" style="width:100%">
                                    <option value="">Select Brand</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label class="form-label">Product Search : <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select id="products" name="products" class="form-control form-control-sm">
                                        <option value=""> Product Search </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name . ' - ' . $product->code  . ' - ' . $product->brand_name}} </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" onclick="showAdvanceSearch();">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <span class="text-danger" id="productIdError"></span>
                            </div>


                            <div class="form-group mb-3 col-md-12"><br>
                                <label>Cart Details: </label>
                                <div class="table-responsive">
                                <table class="table table-vcenter table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Product Info</th>
                                            <th>Available</th>
                                            <th style="width: 12%;">Qty</th>
                                            <th style="width: 14%;">Unit Price</th>
                                            <th style="width: 14%;">Total</th>
                                            <th style="width: 6%;">Actions</th>
                                        </tr>

                                    </thead>
                                    <tbody id="manageCartTable"></tbody>
                                    <tr>
                                        <td colspan="5" class="text-end align-middle"> Discount
                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                        <td class="text-end font-weight-bold"> <input type="text" class="form-control text-end"
                                                id="discount" name="discount" onblur="calculateTotal()" /> </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end align-middle">Transport
                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                        <td class="text-end font-weight-bold"><input type="text"
                                                class="form-control text-end only-number" id="transport" name="transport"
                                                onblur="calculateTotal()" /></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end align-middle">Grand Total
                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                        <td class="text-end"><span class="form-control-plaintext text-end fw-bold"
                                                id="grandTotal"> 0 </span></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end align-middle">Payment Method : </td>
                                        <td>
                                            <select id="paymentMethod" name="paymentMethod"
                                                class="form-select text-center">
                                                <option value="Cash" selected>Cash</option>
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end align-middle">Paid Amount
                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                        <td><input type="text" class="form-control text-end only-number" value="0"
                                                id="payment" name="payment" /></td>
                                        <td></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <a class="btn btn-outline-danger" href="#" onclick="clearCart()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            Clear Cart
                        </a>
                        <a class="btn btn-primary ms-auto" href="#" onclick="checkOutCart()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><circle cx="12" cy="14" r="2" /><polyline points="14 4 14 8 8 8 8 4" /></svg>
                            Save Purchase
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- /.container-fluid -->

    <div class="modal fade" id="showAdvanceSearchModal">
        <div class="modal-dialog" style="max-width: 90%;" role="document">
            <!-- style, added by Md Hamid -->
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Product Advance Search</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row g-3">
                            <!--data listing table-->
                            <div class="table-responsive">
                                <table id="advanceSearchProductTable" width="100%"
                                    class="table table-bordered table-hover ">
                                    <thead>
                                        <tr>
                                            <td width="5%">SL</td>
                                            <td width="18%">Product Info</td>
                                            <td width="18%">Product Info</td>
                                            <td width="21%">Speccification</td>
                                            <td width="10%">Price</td>
                                            <td width="20%">Stock</td>
                                            <td width="9%">Actions</td>
                                        </tr>
                                    </thead>
                                </table>
                                <!--data listing table-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Product Advance Search modal -->

    <!-- Start Serialize Product Modal -->
    <div class="modal fade" id="serialNumsModal">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Serialize Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body card-body">
                    <form id="serializeProductForm">
                        <div class="row g-3">
                            <div class="form-group mb-3 col-md-12">
                                <table border="1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Number Of Pics</th>
                                            <th>Serial Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <input type="hidden" id="serializProductId" name="serializProductId"
                                        value="">
                                    <input type="hidden" id="serializProductWarehouseId"
                                        name="serializProductWarehouseId" value="">
                                    <tbody id="serializeProductTable" class="text-center">
                                    </tbody>
                                </table>
                                <strong>Total Purchase Quantity: <span name="totalStockQuantity"
                                        id="totalStockQuantity"></span></strong><br><span class="text-danger">** Purchase
                                    Qty & Total Qty Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x
                                Close</button>
                            <button type="button" class="btn btn-success " onclick="addRow();"> <span
                                    class="glyphicon glyphicon-plus"
                                    style="font-size: 18px; font-weight:800;"><strong>+</strong></span>
                                Add Row </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->

    <!-- /.content -->
    </div>

    
@endsection
@section('javascript')
    <script>
        //dropdown with search box
        $(function() {
            $("select").select2();
        });
        var loadSearch = 1
        $("#products").select2({
            placeholder: " Product Search ",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });

        var advanceSearchTable;

        function showAdvanceSearch() {
            getManageProductTable();
            $("#showAdvanceSearchModal").modal('show');
        }

        function warehouseWiseStock(id) {
            $.ajax({
                url: "{{ route('warehouseWiseStock') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    if (result) {
                        $("#" + id).html(result);
                    } else {
                        $("#" + id).html('Stock : 0');
                    }
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
        }

        //=========== Start Product Advance Search ===========//
        function selectProducts(productId, warehouseId) {
            var id = productId;
            var warehouseId = warehouseId;
            var warehouseName = $("#wrhs_name" + warehouseId).text();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('warehouseId', warehouseId);
            fd.append('warehouseName', warehouseName);
            fd.append('quantity', 1);
            fd.append('_token', _token);
            addToCart(fd);
        }

        function selectProductWOWarehouse(productId) {
            var id = productId;
            var warehouseId = $("#warehouse").val();
            if (warehouseId == '') {
                Swal.fire("warning", "Warehouse must be selected", "warning");
            } else {
                var warehouseName = $("#warehouse option:selected").text();
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('id', id);
                fd.append('warehouseId', warehouseId);
                fd.append('warehouseName', warehouseName);
                fd.append('quantity', 1);
                fd.append('_token', _token);
                addToCart(fd);
            }
        }

        function getManageProductTable() {
            if (loadSearch == 1) {
                advanceSearchTable = $('#advanceSearchProductTable').DataTable({
                    'ajax': "{{ route('viewAdvanceSearchProducts', ['page' => 'Purchase']) }}",
                    processing: true,
                    destroy: true,
                });
                loadSearch = 0;
            }
        }
        //=========== End Product Advance Search ===========//

        fetchCart();

        function fetchCart() {
            $.ajax({
                url: "{{ route('purchase.fetchCart') }}",
                method: "get",
                success: function(result) {
                    $("#manageCartTable").html(result.data.cart);
                    $("#totalAmount").text(result.data.totalAmount);
                    calculateTotal();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#products").text("No such product available in your system 1 " + JSON
                        .stringify(
                            response));
                }
            })
        }

        //=========== Start Serialize Product ===========//

        function showSerializTable(id, warehouseId) {
            $("#serializProductId").val(id);
            $("#serializProductWarehouseId").val(warehouseId);
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('warehouseId', warehouseId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('purchase.showSerializTable') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.displayTable) {
                        $("#serializeProductTable").html('');
                        $("#serializeProductTable").html(result.displayTable);
                        $("#serialNumsModal").modal("show");
                        calculateTotalQuantity();
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#serializeProductTable").text("Something Went Wrong.Please Try Again");
                }
            });
        }

        function generateSerialNo(num) {
            let len = $('input[name^=serialNo]').length;
            serialNo = parseInt(num);
            if (num > 0) {
                for (let i = 1; i <= len; i++) {
                    $(".serialNo" + i).val((serialNo + i));
                }
            }
        }

        function calculateTotalQuantity() {
            var totalStockQuantity = 0;
            $('[name="stockQuantity"]').each(function() {
                var currentTxtQuantity = $(this).val();
                if (currentTxtQuantity == '') {
                    currentTxtQuantity = 0;
                }
                totalStockQuantity += parseFloat(currentTxtQuantity);
            });
            $("#totalStockQuantity").text(totalStockQuantity);
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            $("#quantity_" + id + "_" + warehouseId).val(totalStockQuantity);
        }

        function addRow() {
            var trId = $('#serializeProductTable tr:last').attr('id');
            trId = parseInt(trId.substring(3)) + 1;
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            let serialNo = parseInt($(".serialNo" + (trId - 1)).val()) + 1;
            let rows = '';
            rows += '<tr id="row' + trId + '">' +
                '<td>' + (trId + 1) + '</td>' +
                '<td><input class="form-control form-control-sm stockQuantity' + trId +
                '" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()" onblur="loadCartandUpdate(' +
                id + ',' + warehouseId + ',' + true + ')"></td>';
            rows +=
                '<td><input class="form-control form-control-sm serialNo' + trId +
                '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required value=' + serialNo +
                '><td><a href="#" onclick="removeRow(' +
                trId + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
            $("#serializeProductTable").append(rows);
        }

        function removeRow(rowNumber) {
            $('#row' + (rowNumber)).remove();
            $("#serializeProductTable").find('tr').each(function(i, el) {
                $(el).find("td").eq(0).text(i + 1);
            });
            id = $("#serializProductId").val();
            warehouseId = $("#serializProductWarehouseId").val();
            let product_type = true;
            calculateTotalQuantity();
            updateCart(id, warehouseId, product_type);
        }
        //=========== End Serialize Product ===========//

        $("#category").change(function() {
            var categoryId = $("#category").val();
            loadBrands(categoryId);
            loadProducts(categoryId, '');
        }) 
        $("#brand").change(function() {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            loadProducts(categoryId, brandId);
        })

        function loadBrands(categoryId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', categoryId);
            fd.append('type', "purchase");
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('brands/categoryWiseView') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var brandData = "<option value=''>Select Brand</option>";
                    for (var i = 0; i < result.length; i++) {
                        brandData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            "</option>";
                    }
                    if (brandData == "<option value=''>Select Brand</option>") {
                        brandData = "<option value=''>No Available Brand</option>";
                    }
                    $("#brand").html(brandData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            })
        }

        function loadProducts(categoryId, brandId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('categoryId', categoryId);
            fd.append('brandId', brandId);
            fd.append('type', "purchase");
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('products/brandAndCategoryWise') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var productData = "<option value=''>Select Product</option>";
                    for (var i = 0; i < result.length; i++) {
                        productData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] + "(" +
                            result[i]["current_stock"] + ")" + "</option>";
                    }
                    if (productData == "<option value=''>Select Product</option>") {
                        productData = "<option value=''>No Available Product</option>";
                    }
                    $("#products").html(productData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            })
        }

        function calculateTotal() {
            var totalAmount = $("#totalAmount").text();
            if (totalAmount == '')
                totalAmount = 0;
            var i = 0;
            var discount = 0;
            var payChar = $("#discount").val().substr(-1);
            if (payChar == "%") {
                discount = (totalAmount / 100) * parseFloat($("#discount").val());
            } else {
                if (parseFloat($("#discount").val()) >= 0) {
                    discount = parseFloat($("#discount").val());
                } else {
                    $("#discount").val("0");
                    discount = 0;
                }
            }
            var transport = 0;
            if (parseFloat($("#transport").val()) >= 0) {
                transport = parseFloat($("#transport").val());
            } else {
                $("#transport").val("0");
                transport = 0;
            }
            var grandTotal = parseFloat(totalAmount) + parseFloat(transport) - parseFloat(discount);
            $("#grandTotal").text(grandTotal);
            var currentDue = parseFloat($("#currentDue").text());
            var totalWithDue = parseFloat(grandTotal) - parseFloat(currentDue);
            var totalWithDueView = parseFloat(grandTotal) - parseFloat(currentDue);
            $("#totalWithDue").text(totalWithDue);
            $("#totalWithDueView").text(totalWithDueView);
        }

        function findProduct() {
            $("#barcodeError").text("");
            var barcode = $("#barcode").val();
            var warehouseId = $("#warehouse").val();
            var warehouseName = $("#warehouse option:selected").text();;
            var _token = $('input[name="_token"]').val();
            if (warehouseId != '') {
                if (barcode.length >= 6) {
                    var result = confirm("Want to add?");
                    if (result) {
                        var fd = new FormData();
                        fd.append('barcode', barcode);
                        fd.append('warehouseId', warehouseId);
                        fd.append('warehouseName', warehouseName);
                        fd.append('quantity', 1);
                        fd.append('_token', _token);
                        addToCart(fd);
                    }
                }
            } else {
                Swal.fire("warning", "Warehouse must be selected", "warning");
            }
        }


        $("#products").change(function() {
            $("#barcodeError").text("");
            var id = $("#products").val();
            if (!id) {
                return 0;
            }
            var warehouseId = $("#warehouse").val();
            var warehouseName = $("#warehouse option:selected").text();;
            var _token = $('input[name="_token"]').val();
            if (warehouseId != '') {
                var fd = new FormData();
                fd.append('id', id);
                fd.append('warehouseId', warehouseId);
                fd.append('warehouseName', warehouseName);
                fd.append('quantity', 1);
                fd.append('_token', _token);
                addToCart(fd);
            } else {
                Swal.fire("warning", "Warehouse must be selected", "warning");
            }
        });

        $("#supplier").change(function() {
            var supplierId = $("#supplier").val();
            if (!supplierId) {
                return 0;
            }
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', supplierId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('purchase.supplierDue') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    $("#currentDue").text(result);
                    $("#currentDueDisplay").text(-result);
                    calculateTotal();
                    $("#payment").focus();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system");
                }
            })
        });

        function addToCart(fd) {
            $.ajax({
                url: "{{ route('purchase.addToCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.data == "Success") {
                        fetchCart();
                        if (result.product_type == "serialize") {
                            let productId = result.productId;
                            let warehouseId = result.warehouseId;
                            showSerializTable(productId, warehouseId);
                        }
                    } else {
                        alert(JSON.stringify(result));
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    alert(JSON.stringify(response))
                    $("#barcodeError").text("No such product available in your system");
                }
            });
        }

        function updateCart(id, warehouse_id, product_type = null) {
            var product_quantity = $('#quantity_' + id + '_' + warehouse_id).val();
            var unitPrice = $('#unitPrice_' + id + '_' + warehouse_id).val();
            var _token = $('input[name="_token"]').val();
            let len = $('input[name^=stockQuantity]').length;
            if (len == 1) {
                $('.stockQuantity0').val(product_quantity);
            }
            var fd = new FormData();
            if (product_type == true) {
                var stockQuantities = new Array();
                let i = 0;
                $('[name="stockQuantity"]').each(function() {
                    let quantity = $(this).val();
                    if (quantity != '') {
                        stockQuantities[i] = quantity;
                        i++;
                    }
                });
                var serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                    return $(serialNo).val();
                }).get();
                fd.append('product_type', product_type);
                fd.append('serialNumbers', serialNumbers);
                fd.append('stockQuantities', stockQuantities);
            }
            fd.append('quantity', product_quantity);
            fd.append('unitPrice', unitPrice);
            fd.append('warehouseId', warehouse_id);
            fd.append('_token', _token);
            fd.append('id', id);
            $.ajax({
                url: "{{ route('purchase.updateCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.data == "Success") {
                        fetchCart();
                        if (product_type == "updateQty") {
                            showSerializTable(id, warehouse_id);
                        }
                    } else {
                        alert("Error To update cart");
                    }
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                }
            });
        }

        function loadCartandUpdate(id, warehouse_id, product_type = null) {
            updateCart(id, warehouse_id, product_type);
        }

        function clearCart() {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear cart!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#barcode").val("");
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ route('purchase.clearCart') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            $("#barcode").focus();
                            if (result.data == "Success") {
                                fetchCart();
                                clearSalesForm();
                            } else {
                                alert(JSON.stringify(response));
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            })
        }

        function removeCartProduct(id, warehouse_id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, remove cart data!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('id', id);
                    fd.append('warehouse_id', warehouse_id);
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ route('purchase.removeProduct') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            $("#barcode").focus();
                            if (result.data == "Success") {
                                fetchCart();
                                $("#products").val(null).trigger("change");
                            } else {
                                alert(JSON.stringify(response));
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        }
                    })
                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            })
        }






        function checkOutCart() {
            var date = $("#purchaseDate").val();
            var supplier_id = $("#supplier").val();
            var totalAmount = $("#totalAmount").text();
            var category = $("#category_id").val();
            var discount = $("#discount").val();
            var carrying_cost = $("#transport").val();
            var grand_total = $("#grandTotal").text();
            var previous_due = $("#currentDue").text();
            var payment_method = $("#paymentMethod :Selected").text();
            var total_with_due = parseFloat($("#totalWithDue").text());
            var current_payment = parseFloat($("#payment").val());
            var current_balance = -parseFloat(previous_due) + parseFloat(grand_total) - parseFloat(current_payment);
            var _token = $('input[name="_token"]').val();

            var fd = new FormData();
            //--Check Product & Supplier Select or Not
            var product_id = '';
            var product_ids = $('input[name="ids[]"]').val();
            if (product_ids != undefined) {
                product_id = -1;
            }
            //--End Check Product & Supplier Select or Not
            //Just For Validation
            fd.append('product', product_id); //-product_id as product_name
            fd.append('supplier', supplier_id); //-supplier_id as supplier_name
            //End Just For Validation
            fd.append('date', date);
            fd.append('supplier_id', supplier_id);
            fd.append('total_amount', totalAmount);
            fd.append('discount', discount);
            fd.append('category', category);
            fd.append('carrying_cost', carrying_cost);
            fd.append('grand_total', grand_total);
            fd.append('previous_due', previous_due);
            fd.append('total_with_due', total_with_due);
            fd.append('current_payment', current_payment);
            fd.append('current_balance', current_balance);
            fd.append('payment_method', payment_method);
            fd.append('_token', _token);
            clearErrorMessage();
            $.ajax({
                url: "{{ route('purchase.checkOutCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    let purchaseId = result.purchaseId;
                    clearSalesForm();
                    fetchCart();
                    //--Redirect After Click OK--//
                    if (result.Success) {
                        Swal.fire({
                            title: "Saved !",
                            text: result.success,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                printPurchase(purchaseId);
                            }
                        });
                    } else {
                        Swal.fire("Error: ", "Please Check Required Field ! ", "error");
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    Swal.fire("Error: ", "Please Check Required Field ! ", "error");
                    $('#productIdError').text(response.responseJSON.errors.product);
                    $('#supplierIdError').text(response.responseJSON.errors.supplier);
                    $('#categoryError').text(response.responseJSON.errors.category);
                }
            })
        }

        $("#warehouse").change(function() {
            $("#products").val(null).trigger("change");
        })

        function clearSalesForm() {
            $("#supplier").val(null).trigger("change");
            $("#products").val(null).trigger("change");
            $("#warehouse").val(null).trigger("change");
            $("#total_amount").text("0");
            $("#discount").val("0");
            $("#transport").val("0");
            $("#grandTotal").text("0");
            $("#currentDue").text("0");
            $("#totalWithDue").text("0");
            $("#payment").val("0");
        }

        $('.only-number').keyup(function(e) {
            if (/\D/g.test(this.value)) {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });

        function clearErrorMessage() {
            $('#productIdError').text('');
            $('#supplierIdError').text('');
        }

        function printPurchase(id) {
            var url = '{{ route('purchase.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }
    </script>
@endsection
