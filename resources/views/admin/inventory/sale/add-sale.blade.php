@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale
@endsection
@section('content')
    
        <section class="content box-border">
            <form id="saleProducts" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left col -->
                    <section class="col-md-12">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <input type="hidden" name="saleType" id="saleType" value="{{ $type }}">
                            <div class="card-header">
                                <h3>
                                    @if ($type == 'walkin_sale')
                                        walkin Sale
                                    @elseif ($type == 'party_sale')
                                        Party Sale
                                    @elseif ($type == 'ts')
                                        Temporary Sale
                                    @elseif ($type == 'FS')
                                        Final Sale
                                    @endif
                                    <a class="btn btn-primary float-right"
                                        href="{{ route('sale.sales', ['type' => $type]) }}">
                                        @if ($type == 'walkin_sale')
                                            Back To Walkin Sale List
                                        @elseif ($type == 'party_sale')
                                            Back To Party Sale List
                                        @elseif ($type == 'ts')
                                            Back To Temporary Sale List
                                        @elseif ($type == 'FS')
                                            Back To Final Sale List
                                        @endif
                                        <i class="fa fa-reply"></i>
                                    </a>
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                        <div class="form-group col-md-12">
                                            <label>Barcode: </label>
                                            <input class="form-control form-control-sm" id="barcode" type="text"
                                                name="barcode" onkeyup="findProduct()">
                                            <span class="text-danger" id="barcodeError"></span>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-2">
                                        <label>Date : <span class="text-danger">*</span></label>
                                        <input type="date" id="saleDate" name="saleDate" class="form-control form-control-sm"
                                            value="{{ todayDate() }}" />
                                    </div>
                                    @if ($type != 'walkin_sale')
                                        <div class="form-group col-md-7">
                                            <label>Party Name : <span class="text-danger">*</span></label>
                                            <select id="customer" name="customer" class="abcd customer" style="width:100%"
                                                required onchange="getCustomerById(this.value, 'Customer');">
                                                <option value='' selected='true'> Select Party </option>
                                                @foreach ($customers as $customer)
                                                    <option value='{{ $customer->id }}'>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="customerNameError"></span>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="hidden" id="customer" name="customer" value="0" />
                                            <label>Phone: </label>
                                            <div class="d-flex">
                                                <input type="text" id="partyPhoneNumber" name="partyPhoneNumber"
                                                    class="form-control form-control-sm" placeholder=" Phone Number" readonly />
                                            </div>
                                            <span class="text-danger" id="partyPhoneNumberError"></span>
                                        </div>
                                    @endif

                                    @if ($type == 'walkin_sale')
                                        <div class="form-group col-md-4">
                                            <input type="hidden" id="customer" name="customer" value="0" />
                                            <label>Phone: <span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" id="partyPhoneNumber" name="partyPhoneNumber"
                                                    onchange="getCustomerInfo(0,'Walkin_Customer')"
                                                    class="form-control form-control-sm" placeholder=" Phone Number"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                <a class="btn btn-primary"
                                                    onclick="getCustomerInfo(0,'Walkin_Customer')"><i
                                                        class="fas fa-sync"></i></a>
                                            </div>
                                            <span class="text-danger" id="partyPhoneNumberError"></span>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-6">
                                        <label>Warehouse: <span class="text-danger">*</span></label>
                                        <select id="warehouse" name="warehouse" class="abcd" style="width:100%" required>
                                           {{--  <option value='' selected='true'> Select Warehouse </option> --}}
                                            @foreach ($warehouses as $warehouse)
                                                <option value='{{ $warehouse->id }}'>
                                                    {{ $warehouse->wareHouseName }}
                                                </option>
                                            @endforeach  
                                        </select>
                                        <span class="text-danger" id="warehouseError"></span>
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        <label>Name: <span class="text-danger">*</span></label>
                                        <input type="text" id="customerName" name="customerName"
                                            class="form-control form-control-sm" />
                                        <span class="text-danger" id="customerNameError"></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address: </label>
                                        <input type="text" id="customerAddress" name="customerAddress"
                                            class="form-control form-control-sm" />
                                        <span class="text-danger" id="customerAddressError"></span>
                                    </div>
                                    <input type="hidden" id="category_id" name="category" value="42">
                                    <div class="form-group col-md-2">
                                        <label>Credit Limit
                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                        <span class="btn btn-secondary float-right viewPurchase" style="height: 53%;"
                                            id="creditLimit">0</span>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Left credit
                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                        <span class="btn btn-secondary float-right viewPurchase" style="height: 53%;"
                                            id="leftCredit">0</span>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Due ({{ Session::get('companySettings')[0]['currency'] }}):
                                        </label><br>
                                        <span class="btn btn-secondary float-right viewPurchase" style="height: 53%;"
                                            id="currentDue">0</span>
                                    </div>
                                    <div class="form-group col-md-6 d-none">
                                        <label>Total With Due
                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                        <span class="btn btn-danger float-right viewPurchase" id="totalWithDue">0</span>
                                    </div>

                                    <div class="form-group col-md-6 d-none">
                                        <label>Category: </label>
                                        <select id="category" name="category" class="form-control form-control-sm">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 d-none">
                                        <label>Brand : </label>
                                        <select id="brand" name="brand" class="form-control form-control-sm">
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                            -->
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Product Search : <span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <select id="products" name="products" class="form-control form-control-sm"
                                                style="width:96%">
                                                <option value=""> Product Search </option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name . ' - ' . $product->code }} </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary input-group-addon"
                                                onclick="showAdvanceSearch();"> <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Cart Details: </label>
                                        <div class="table-responsive">
                                        <table class="table table-vcenter table-bordered text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th style="width: 36%;">Product Info</th>
                                                    <th>Available</th>
                                                    <th style="width: 12%;">Qty</th>
                                                    <th style="width: 14%;">Unit Price</th>
                                                    <th style="width: 12%;">Unit Dis.</th>
                                                    <th style="width: 14%;">Total</th>
                                                    <th style="width: 6%;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="manageCartTable"></tbody>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle"> Discount
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td class="text-end font-weight-bold"> <input type="text"
                                                        id="discount" name="discount" onblur="calculateTotal()"
                                                        class="form-control text-end" /> </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Transport
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td class="text-end font-weight-bold"><input type="text"
                                                        id="transport" name="transport" onblur="calculateTotal()"
                                                        class="form-control text-end" /></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Vat
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td class="text-end font-weight-bold"><input type="text"
                                                        id="vat" name="vat" onblur="calculateTotal()"
                                                        class="form-control text-end" /></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Ait
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td class="text-end font-weight-bold"><input type="text"
                                                        id="ait" name="ait" onblur="calculateTotal()"
                                                        class="form-control text-end" /></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Grand Total
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td class="text-end"><span class="form-control-plaintext text-end fw-bold"
                                                        id="grandSum">0</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Payment Method : </td>
                                                <td>
                                                    <select id="paymentMethod" name="paymentMethod"
                                                        class="form-select text-center">
                                                        <option value="Cash" selected>Cash</option>
                                                    </select>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" class="text-end align-middle">Paid Amount
                                                    {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                <td>
                                                    <input type="text" id="payment" name="payment"
                                                        class="form-control text-end" value="0"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                </td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a class="btn btn-outline-danger" href="#" onclick="clearCart()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                        Clear Cart
                                    </a>
                                    <button type="button" id="checkOutCart" class="btn btn-primary ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><circle cx="12" cy="14" r="2" /><polyline points="14 4 14 8 8 8 8 4" /></svg>
                                        Place Order
                                    </button>
                                </div>
                            </div>
                            <!-- /.card -->

                            <!-- /.card -->
                        </div>
                    </section>
                </div><!-- /.container-fluid -->
        </section>
    </div>
    </form>

    <!-- /.content -->
    <!-- Product Advance Search modal -->
    <div class="modal fade" id="showAdvanceSearchModal">
        <div class="modal-dialog" style="max-width: 90%;" role="document">
            <!-- style, added by Md Hamid -->
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Product Advance Search</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"> x </button>
                </div>
                <div class="modal-body">
                    <form id="productForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
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
                                            <td width="6%">Actions</td>
                                        </tr>
                                    </thead>
                                </table>
                                <!--data listing table-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"> x
                            </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                        <div class="row">
                            <div class="form-group col-md-12">
                                <table border="1" style="font-size: 13px; width:100%;" class="table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>SL. Number</th>
                                            <th>Remaining Qty</th>
                                            <th style="width: 25%">Sale Qty</th>
                                        </tr>
                                    </thead>
                                    <input type="hidden" id="serializProductId" name="serializProductId"
                                        value="">
                                    <input type="hidden" id="serializProductWarehouseId"
                                        name="serializProductWarehouseId" value="">
                                    <tbody id="serializeProductTable" class="text-center">
                                    </tbody>
                                </table>
                                <strong>Total Sale Quantity: <span name="totalStockQuantity"
                                        id="totalStockQuantity"></span></strong><br><span class="text-danger">** Sale
                                    Qty & Total Qty Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->
    </div>
    
@endsection
@section('javascript')
    <script>
        //=========== Start Serialize Product ===========//=
        var countLen = 0;
        var checkSerializeProductQuantity = false;

        function checkSerializProductQuantity() {
            checkSerializeProductQuantity = false;
            countLen = $('input[name*="checkSerialize"]').length;
            if (countLen > 0) {
                showSerializTable(0, 0, "checkSerializeTotalQuantity");
            }
        }

        function showSerializTable(id, warehouseId, txt) {
            $("#serializProductId").val(id);
            $("#serializProductWarehouseId").val(warehouseId);
            let matchQuantity = '';
            let _token = $('input[name="_token"]').val();
            let fd = new FormData();
            if (txt == "checkSerializeTotalQuantity") {
                var totalSaleQuantity = 0;
                $('[name="checkSerialize"]').each(function() {
                    let productAndWarehouse = $(this).val();
                    let tempArray = productAndWarehouse.split(',');
                    totalSaleQuantity += parseInt($("#quantity_" + tempArray[0] + "_" + tempArray[1]).val());
                });
                matchQuantity = "CheckQuantity";
            }
            fd.append('matchQuantity', matchQuantity);
            fd.append('id', id);
            fd.append('warehouseId', warehouseId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('sale.showSerializTable') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (txt == "checkSerializeTotalQuantity") {
                        if (result.totalMatchQuantity == totalSaleQuantity) {
                            checkSerializeProductQuantity = true;
                        }
                    } else {
                        $("#serializeProductTable").html('');
                        $("#serializeProductTable").html(result.displayTable);
                        $("#serialNumsModal").modal("show");
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                    let totalStockQuantity = $("#quantity_" + id + "_" + warehouseId).val();
                    $("#totalStockQuantity").text(totalStockQuantity);
                },
                error: function(response) {
                    alert(JSON.stringify(response))
                    $("#serializeProductTable").text("Something Went Wrong.Please Try Again");
                }
            });
        }

        var serializeProductsId = 0;
        var serializeSaleQuantity = 0;

        function calculateTotalQuantity(saleQty, product_id, warehouse_id, tblSerializeId) {
            var serializeRemainingQty = parseFloat($("#serializeRemainingQty_" + tblSerializeId).text());
            if (saleQty > serializeRemainingQty) {
                $("#stockQuantity_" + tblSerializeId).val('');
                Swal.fire("warning", "Quantity Not Available!", "warning");
                return 0;
            }
            var totalStockQuantity = 0;
            $('[name="stockQuantity"]').each(function() {
                var currentTxtQuantity = $(this).val();
                if (currentTxtQuantity == '') {
                    currentTxtQuantity = 0;
                }
                totalStockQuantity += parseFloat(currentTxtQuantity);
            });
            $("#totalStockQuantity").text(totalStockQuantity);
            $("#quantity_" + product_id + "_" + warehouse_id).val(totalStockQuantity);
            serializeProductsId = tblSerializeId;
            serializeSaleQuantity = saleQty;
        }
        //=========== End Serialize Product ===========//

        // Start Advance Search Product
        var advanceSearchTable;
        var loadSearch = 1

        function showAdvanceSearch() {
            getManageProductTable();
            $("#showAdvanceSearchModal").modal('show');
        }

        function getManageProductTable() {
            if (loadSearch == 1) {
                advanceSearchTable = $('#advanceSearchProductTable').DataTable({
                    'ajax': "{{ route('viewAdvanceSearchProducts', ['page' => 'walkin_sale']) }}",
                    processing: true,
                    destroy: true,
                });
                loadSearch = 0;
            }
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

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }
        $("#warehouse").change(function() {
            $("#products").val(null).trigger("change");
        })

        function selectProducts(productId, warehouseId) {
            var id = productId;
            var warehouseId = warehouseId;
            if (warehouseId != '') {
                var warehouseName = $("#wrhs_name" + warehouseId).text();
                var saleType = $("#saleType").val();
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('id', id);
                fd.append('warehouseId', warehouseId);
                fd.append('warehouseName', warehouseName);
                fd.append('quantity', 1);
                fd.append('saleType', saleType);
                fd.append('_token', _token);
                addToCart(fd);
            } else {
                Swal.fire("warning", "Warehouse must be selected", "warning");
            }
        }
        // End Advance Search Product

        fetchCart();

        $("#warehouse").select2({
            placeholder: "Select warehouse",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        $("#category").select2({
            placeholder: "Select Category",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        $("#brand").select2({
            placeholder: "Select Brand",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        $("#products").select2({
            placeholder: " Product Search ",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        $(document).ready(function() {
            $(".customer").select2({
                placeholder: "Select Party",
                /*dropdownParent: $("#modal"),*/
                allowClear: true,
                width: '100%'
            });
            $(function() {
                $("select").select2();
            });
        });

        function fetchCart() {
            $.ajax({
                url: "{{ route('sale.fetchCart') }}",
                method: "get",
                success: function(result) {
                    $("#manageCartTable").html(result.data.cart);
                    $("#totalAmount").text(result.data.totalAmount);
                    calculateTotal();
                    checkSerializProductQuantity();
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

        $('#save').attr('disabled', true);
        $('#emi').attr('disabled', true);

        var totalPrice = 0;
        var downPayment = 0;
        var duesAmount = 0;
        var noOfTenure = 0;
        var startDate = 0;
        var perTenurAmount = 0;
        var dayMonthYear = 0;
        var emiPaymentDateArray = [];
        var isSave = "";
        $('.myButton').click(function() {
            isSave = $(this).val();
            localStorage.setItem('isSave', isSave);
        });

        function addEMI() {
            totalPrice = parseFloat($('#grandTotal').text());
            if (totalPrice <= 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please, add product to get EMI!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            } else {
                $('#totalAmoutForEMI').text(totalPrice);
                $('#duesAmount').text(totalPrice);
                var payment = parseFloat($("#payment").val());
                $("#downPayment").val(payment);
                duesAmount = totalPrice - downPayment;
                $('#duesAmount').text(duesAmount);
                calculateEMI();
            }
        }

        // calculate EMI 
        function calculateEMI() {
            $("#addEMIModal").modal('show');
            downPayment = $('#downPayment').val();
            duesAmount = totalPrice - downPayment;
            $('#duesAmount').text(duesAmount);

            noOfTenure = $("#noOfTenure").val();
            startDate = $('#startDate').val();
            dayMonthYear = startDate.split('-');

            var year = parseInt(dayMonthYear[0]);
            var month = parseInt(dayMonthYear[1]);
            var day = parseInt(dayMonthYear[2]);

            mumber = parseInt(localStorage.getItem('numberOfTenure'));
            if (mumber > 0) {
                $(".tenurDate").remove();
            }
            localStorage.setItem('numberOfTenure', noOfTenure);
            perTenurAmount = (parseFloat(duesAmount ? duesAmount : totalPrice) / parseInt(noOfTenure)).toFixed(2)
            $('#perTenurAmount').text(perTenurAmount);
            // to show tenure and date//
            for (i = 0; i < noOfTenure; i++) {
                if (month > 12) {
                    year++;
                    month = 1;
                }
                var setDay = 0;
                var setMonth = 0;
                if (day < 10) {
                    setDay = '0' + day;
                } else {
                    setDay = day;
                }
                setMonth = month >= 10 ? month : '0' + month;
                month++;
                ymdFormat = year + '-' + setMonth + '-' + setDay;
                emiPaymentDateArray[i] = ymdFormat;
                // $( ".dynamic" ).append("<div class='form-group border border-primary tenurDate' id='tenurDate'><label class='col-form-label p-2'>"+(i+1)+". Amount: <span id='perTenurAmount'>"+perTenurAmount+"</span>, Tenure date: <span id='dayMonthYear'>"+ (year) +"-"+ (setMonth) +"-"+(setDay)+"</span></label></div>" );
                $(".dynamic").append("<tr class='tenurDate' id='tenurDate'><th scope='row'>" + (i + 1) +
                    ". </th><td> Amount: <span id='perTenurAmount'>" + perTenurAmount +
                    " </span></td><td> Tenure date: <span id='dayMonthYear'> " + (year) + "-" + (setMonth) + "-" + (
                        setDay) + "</span></td></tr>");

            }
            // to enable EMI save button//
            if (noOfTenure && startDate) {
                $('#save').attr('disabled', false);
            }
        }
        $("#category").change(function() {
            var categoryId = $("#category").val();
            loadBrands(categoryId);
            //loadProducts(categoryId,'');
        })
        $("#brand").change(function() {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            var warehouseId = $("#warehouse").val();
            loadProducts(categoryId, brandId, warehouseId);
        })

        function loadBrands(categoryId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', categoryId);
            fd.append('type', "sale");
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

        function loadProducts(categoryId, brandId, warehouseId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('categoryId', categoryId);
            fd.append('brandId', brandId);
            fd.append('warehouseId', warehouseId);
            fd.append('type', "sale");
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
                    let current_stock = '';
                    for (var i = 0; i < result.length; i++) {
                        if (warehouseId) {
                            current_stock = result[i]["currentStock"];
                        } else {
                            current_stock = result[i]["current_stock"];
                        }
                        productData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            " ( available-" + current_stock + " )</option>";
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
            var totalAmount = 0;
            var i = 0;
            $('span[id^="totalPrice_"]').each(function() {
                totalAmount += parseFloat($(this).text());
                i = i + 1;
            });
            $("#totalAmount").text(totalAmount);
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
            var vat = 0;
            if (parseFloat($("#vat").val()) >= 0) {
                vat = parseFloat($("#vat").val());
            } else {
                $("#vat").val("0");
                vat = 0;
            }
            var ait = 0;
            if (parseFloat($("#ait").val()) >= 0) {
                ait = parseFloat($("#ait").val());
            } else {
                $("#ait").val("0");
                ait = 0;
            }
            var grandTotal = parseFloat(totalAmount) + parseFloat(transport) + parseFloat(vat) + parseFloat(ait) -
                parseFloat(discount);
            $("#grandTotal").text(totalAmount);
            $("#grandSum").text(grandTotal);

            var currentDue = parseFloat($("#currentDue").text());
            var totalWithDue = parseFloat(grandTotal) + parseFloat(currentDue);
            $("#totalWithDue").text(totalWithDue);
        }

        function findProduct() {
            $("#barcodeError").text("");
            var barcode = $("#barcode").val();
            var warehouseId = $("#warehouse").val();
            var warehouseName = $("#warehouse option:selected").text();
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
                alert('Warehouse must be selected');
            }
        }
        $("#products").change(function() {
            $("#barcodeError").text("");
            var id = $("#products").val();
            var warehouseId = $("#warehouse").val();
            if (id != '') {
                if (warehouseId != '') {
                    if (id != '') {
                        var warehouseName = $("#warehouse option:selected").text();
                        var saleType = $("#saleType").val();
                        var _token = $('input[name="_token"]').val();
                        var fd = new FormData();
                        fd.append('id', id);
                        fd.append('saleType', saleType);
                        fd.append('warehouseId', warehouseId);
                        fd.append('warehouseName', warehouseName);
                        fd.append('quantity', 1);
                        fd.append('_token', _token);
                        addToCart(fd);
                    }
                } else {
                    Swal.fire("warning", "Warehouse must be selected", "warning");
                }
            }
        })

        function getCustomerById(id, customer_type) {
            getCustomerInfo(id, customer_type);
        }

        function getCustomerInfo(id, customer_type) {
            if (id == undefined) {
                id = 0;
            }
            let partyPhoneNumber = $("#partyPhoneNumber").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('partyPhoneNumber', partyPhoneNumber);
            fd.append('customer_type', customer_type);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('sale.supplierDue') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    let isEpmty = Object.keys(result).length;
                    if (isEpmty > 0) {
                        $("#currentDue").text(result['current_due']);
                        $("#customerName").val(result['name']);
                        $("#customerAddress").val(result['address']);
                        $("#customer").val(result['id']);
                        $("#partyPhoneNumber").val(result['contact']);
                        $("#creditLimit").text(result['credit_limit']);
                        $("#leftCredit").text(result['credit_limit'] - result['current_due']);
                        $("#customerName").prop('disabled', true);
                        $("#customerAddress").prop('disabled', true);
                    } else {
                        $("#customerName").prop('disabled', false);
                        $("#customerAddress").prop('disabled', false);
                        $("#currentDue").text(0);
                        $("#customerName").val("");
                        $("#customerAddress").val("");
                        $("#currentDue").text(0);
                        $("#customer").val(0);
                        $("#creditLimit").text(0);
                        $("#leftCredit").text(0);
                    }
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
        }

        function addToCart(fd) {
            $.ajax({
                url: "{{ route('sale.addToCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.data == "Success") {
                        fetchCart();
                        if (result.productType == "serialize") {
                            let productId = result.productId;
                            let warehouseId = result.warehouseId;
                            showSerializTable(productId, warehouseId);
                        }

                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.data,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
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

        function updateCart(id, warehouse_id, product_type) {
            var product_quantity = $('#quantity_' + id + '_' + warehouse_id).val();
            var unitPrice = $('#unitPrice_' + id + '_' + warehouse_id).val();
            var discount = $('#discountPrice_' + id + '_' + warehouse_id).val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            // Serialize Product
            if (product_type == true) {
                fd.append('product_type', true);
                fd.append('serializeProductsId', serializeProductsId);
                fd.append('serializeSaleQuantity', serializeSaleQuantity);
            }
            fd.append('quantity', product_quantity);
            fd.append('unitPrice', unitPrice);
            fd.append('discount', discount);
            fd.append('_token', _token);
            fd.append('id', id);
            fd.append('warehouse_id', warehouse_id);
            $.ajax({
                url: "{{ route('sale.updateCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.data == "Success") {
                        fetchCart();
                    } else {
                        alert("Error To update cart");
                    }
                },
                beforeSend: function() {
                    //$('#loading').show();
                },
                complete: function() {
                    // $('#loading').hide();
                },
                error: function(response) {}
            })
        }

        function loadCartandUpdate(id, warehouse_id, product_type) {
            // Check Available Quantity
            let available_qty = parseFloat($('#available_qty_' + id + '_' + warehouse_id).text());
            let quantity = parseFloat($('#quantity_' + id + '_' + warehouse_id).val());
            let check_type = $('#product_type_' + id + '_' + warehouse_id).val();
            if (available_qty < quantity && check_type != "service") {
                Swal.fire({
                    title: 'Error!',
                    text: 'This Quantity Not available for sale',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('#quantity_' + id + '_' + warehouse_id).val(0);
                return 0;
            }
            // End Available Quantity
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
                        url: "{{ route('sale.clearCart') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            $("#barcode").focus();
                            if (result == "Success") {
                                calculateTotal();
                                fetchCart();
                                clearSalesForm();
                                clearErrorMessage();
                                window.localStorage.removeItem('isSave');
                            } else {
                                //alert(JSON.stringify(response));
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            Swal.fire("Cancelled", "Error! Please try again:)", "error");
                        }
                    })
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
                        url: "{{ route('sale.removeProduct') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            $("#barcode").focus();
                            if (result.data == "Success") {
                                fetchCart();
                                calculateTotal();
                            } else {
                                Swal.fire("Cancelled", "Error Occured", "error");
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
        //Save Sale Products
        $("#checkOutCart").click(function(e) {
            var category=$('#category_id').val();
            var saleType = $("#saleType").val();
            var date = $("#saleDate").val();
            var customer_id = parseInt($("#customer").val());
            var totalAmount = $("#grandTotal").text();
            var discount = $("#discount").val();
            var carrying_cost = $("#transport").val();
            var vat = $("#vat").val();
            var ait = $("#ait").val();
            var grand_total = $("#grandSum").text();
           
            var previous_due = $("#currentDue").text();
            var total_with_due = parseFloat($("#totalWithDue").text());
            var current_payment = parseFloat($("#payment").val());
            var payment_method = $("#paymentMethod").val();
            var current_balance = parseFloat(total_with_due) - parseFloat(current_payment);
            var totalDue = grand_total - current_payment;
            var _token = $('input[name="_token"]').val();
            
            var fd = new FormData();
            // Check EMI Yes/No
            var isSave = localStorage.getItem('isSave');
            if (isSave == "save") {
                dayMonthYears = $('span[id^="dayMonthYear"]').text().length;
                fd.append('totalPrice', totalPrice);
                fd.append('downPayment', downPayment);
                fd.append('duesAmount', duesAmount);
                fd.append('noOfTenure', parseInt(noOfTenure));
                fd.append('startDate', startDate);
                fd.append('perTenurAmount', perTenurAmount);
                fd.append('dayMonthYear', dayMonthYear);
                fd.append('dayMonthYears', dayMonthYears);
                fd.append('emiPaymentDateArray', emiPaymentDateArray);

                emiPaymentDateArray = Object.values(emiPaymentDateArray);

            } else {
                fd.append('noOfTenure', 0);
                //--Check Credit Limit
                var creditLimit = $("#creditLimit").text();
                var leftCredit = $("#leftCredit").text();
                if ((totalDue > leftCredit) && ($("#saleType").val() != 'walkin_sale')) {
                    Swal.fire({
                        title: 'Credit-limit Crossed!',
                        text: 'Sorry, can not cross Credit-limit !',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return 0;
                }
            }
            //--Check Product & Customer Select or Not
            let product_id = '';
            let product_ids = $('input[name="ids[]"]').val();
            if (product_ids != undefined) {
                product_id = -1;
            }
            //--End Check Product & Supplier Select or Not

            //Just For Validation
            fd.append('product', product_id); // product_id as product_name
            //End Just For Validation
            fd.append('saleType', saleType);
            fd.append('date', date);
            fd.append('customer_id', customer_id);
            fd.append('total_amount', totalAmount);
            fd.append('discount', discount);
            fd.append('carrying_cost', carrying_cost);
            fd.append('vat', vat);
            fd.append('category', category);
            fd.append('ait', ait);
            fd.append('grand_total', grand_total);
            fd.append('previous_due', previous_due);
            fd.append('payment_method', payment_method);
            fd.append('total_with_due', total_with_due);
            fd.append('current_payment', current_payment);
            fd.append('current_balance', current_balance);
            fd.append('totalDue', totalDue);
            // If Customer Not Exist
            var customerName = $("#customerName").val();
            var customerAddress = $("#customerAddress").val();
            var partyPhoneNumber = $("#partyPhoneNumber").val();
            fd.append('customerName', customerName);
            fd.append('customerAddress', customerAddress);
            fd.append('partyPhoneNumber', partyPhoneNumber);

            fd.append('_token', _token);
            clearErrorMessage();

            // Start Check Serialize Product Quantity 
            if (countLen > 0) {
                if (checkSerializeProductQuantity == false) {
                    Swal.fire('Error!', 'Please select serilize product qty properly!', 'error');
                    return 0;
                }
            }
            // End Check Serialize Product Quantity 
            $.ajax({
                url: "{{ route('sale.checkOutCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    
                    clearSalesForm();
                    fetchCart();
                    window.localStorage.removeItem('isSave');
                    calculateTotal();
                    // Redirect After Click OK--//
                    let saleId = result['saleId'];
                    Swal.fire({
                        title: "Saved !",
                        text: result.success,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (saleType == 'ts') {
                                printTsSales(saleId);
                            } else {
                                printSale(saleId);
                            }
                        }
                    });
                    //--End Redirect After Click OK--//
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    
                    Swal.fire('Error!', 'Error: Please check again or contact with administrator',
                        'error');
                    $('#partyPhoneNumberError').text(response.responseJSON.errors.partyPhoneNumber);
                    $('#customerNameError').text(response.responseJSON.errors.customerName);
                    $('#customerAddressError').text(response.responseJSON.errors.customerAddress);
                    $('#categoryError').text(response.responseJSON.errors.category);
                }
            })
        });

        function clearSalesForm() {
            $("#products").val(null).trigger("change");
            $("#warehouse").val(null).trigger("change");
            $("#supplier").val("");
            $("#total_amount").text("0");
            $("#discount").val("0");
            $("#transport").val("0");
            $("#vat").val("0");
            $("#ait").val("0");
            $("#grandTotal").text("0");
            $("#currentDue").text("0");
            $("#totalWithDue").text("0");
            $("#payment").val("0");
            //EMI clear
            $('#downPayment').val(0);
            $("#noOfTenure").val(0);
            $('#perTenurAmount').text('');
            $('#startDate').val('');
            $('#category').val('').trigger('change');
            $(".tenurDate").remove();
            // Customer Info
            $('#customer').val(null).trigger('change');
            $("#customerName").val('');
            $("#customerAddress").val('');
            $("#partyPhoneNumber").val('');
        }

        function clearErrorMessage() {
            $('#productError').text('');
            $('#partyPhoneNumberError').text('');
            $('#customerNameError').text('');
            $('#customerAddressError').text('');
        }

        function printSale(id) {
            var url = '{{ route('sale.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }

        function printTsSales(id) {
            var url = '{{ route('sale.tsInvoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }
    </script>
@endsection
