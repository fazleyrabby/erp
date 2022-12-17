@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Service
@endsection
@section('content')

    <style type="text/css">
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            width: 100%;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }
    </style>

    <div class="content-wrapper">
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
                                    Service Centre
                                    <a class="btn btn-primary float-right" href="{{ route('sale.service.SaleOrders') }} ">
                                        Back To Service Orders
                                        <i class="fa fa-reply"></i>
                                    </a>
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body ">
                                <div class="row mx-auto">
                                    @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                        <div class="form-group col-md-12">
                                            <label>Barcode: </label>
                                            <input class="form-control input-sm" id="barcode" type="text"
                                                name="barcode" onkeyup="findProduct()">
                                            <span class="text-danger" id="barcodeError"></span>
                                        </div>
                                    @endif
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Service Receieve Section</legend>
                                        <div class="row">
                                            <div class="form-group col-md-4 d-none">
                                                <label>Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="saleDate" name="saleDate"
                                                    class="form-control input-sm" value="{{ todayDate() }}" />
                                            </div>
                                            @if ($type != 'walkin_sale')
                                                <div class="form-group col-md-7">
                                                    <label>Party Name : <span class="text-danger">*</span></label>
                                                    <select id="customer" name="customer" class="abcd customer"
                                                        style="width:100%" required
                                                        onchange="getCustomerById(this.value, 'Customer');">
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
                                                    <label>Phone: 11</label>
                                                    <div class="d-flex">
                                                        <input type="text" id="partyPhoneNumber" name="partyPhoneNumber"
                                                            class="form-control input-sm" placeholder=" Phone Number"
                                                            readonly />
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
                                                            class="form-control input-sm" placeholder=" Phone Number"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                        <a class="btn btn-primary"
                                                            onclick="getCustomerInfo(0,'Walkin_Customer')"><i
                                                                class="fas fa-sync"></i></a>
                                                    </div>
                                                    <span class="text-danger" id="partyPhoneNumberError"></span>
                                                </div>
                                            @endif
                                            <div class="form-group col-md-4">
                                                <label>Name: <span class="text-danger">*</span></label>
                                                <input type="text" id="customerName" name="customerName"
                                                    class="form-control input-sm" placeholder=" Name" />
                                                <span class="text-danger" id="customerNameError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Address: </label>
                                                <input type="text" id="customerAddress" name="customerAddress"
                                                    class="form-control input-sm" placeholder=" Address" />
                                                <span class="text-danger" id="customerAddressError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Defect Reported:</label>
                                                <input type="text" id="defectReported" name=" Defect Reported"
                                                    class="form-control input-sm" placeholder=" Defect Reported"
                                                    value="Unknown" />
                                                <span class="text-danger" id="defectReportedError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Work Approval Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="workApprovalDate" name="workApprovalDate"
                                                    class="form-control input-sm" value="{{ todayDate() }}" />
                                                <span class="text-danger" id="workApprovalDateError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Expected Delivery Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="expectedDeliveryDate"
                                                    name="expectedDeliveryDate" class="form-control input-sm"
                                                    value="{{ todayDate() }}" />
                                                <span class="text-danger" id="expectedDeliveryDateError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Brand: <span class="text-danger">*</span></label>
                                                <input type="text" id="brand" name="brand"
                                                    class="form-control input-sm" placeholder=" Brand" />
                                                <span class="text-danger" id="brandError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Model: <span class="text-danger">*</span></label>
                                                <input type="text" id="model" name="model"
                                                    class="form-control input-sm" placeholder=" Model" />
                                                <span class="text-danger" id="modelError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="hidden" id="category" name="category" value="42">
                                                {{-- <label>COA Heads: <span class="text-danger">*</span></label>
                                                <select type="text" id="category" name="category"
                                                    class="form-control input-sm" placeholder=" Category" >
                                                    <option value="" selected>Select COA</option>
                                                        @foreach ($coas as $coa)
                                                        <option value="{{$coa->id}}">{{$coa->name}}</option>
                                                        @endforeach
                                                </select>
                                                <span class="text-danger" id="categoryError"></span> --}}
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Item: <span class="text-danger">*</span></label>
                                                <input type="text" id="item" name="item"
                                                    class="form-control input-sm" placeholder=" Item" />
                                                <span class="text-danger" id="itemError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Quantity: <span class="text-danger">*</span></label>
                                                <input type="text" id="quantity" name="quantity"
                                                    class="form-control input-sm" placeholder=" Quantity" />
                                                <span class="text-danger" id="quantityError"></span>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label>Due ({{ Session::get('companySettings')[0]['currency'] }}):
                                                        </label><br>
                                                        <span class="btn btn-secondary float-right viewPurchase"
                                                            style="height: 53%;" id="currentDue">0</span>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Manufacturing SI. No: </label>
                                                <input type="text" id="manufacturingSiNo" name="manufacturingSiNo"
                                                    class="form-control input-sm" placeholder="  Manufacturing SI. No" />
                                                <span class="text-danger" id="manufacturingSiNoError"></span>
                                                <br>
                                                <div class="form-group col-md-12">
                                                    <label>Accessories Recieved <span class="text-danger">*</span>
                                                        (<small>Please put tick mark below</small>):</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Guard">
                                                        <label class="form-check-label" for="inlineCheckbox1">
                                                            Guard</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Chuck">
                                                        <label class="form-check-label" for="inlineCheckbox2">
                                                            Chuck</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Handle">
                                                        <label class="form-check-label" for="inlineCheckbox2">
                                                            Handle</label>
                                                    </div>
                                                    <br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Flange">
                                                        <label class="form-check-label" for="inlineCheckbox2">
                                                            Flange</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Grinding Disc">
                                                        <label class="form-check-label" for="inlineCheckbox2"> Grinding
                                                            Disc</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="accessoriesRecieved" value="Box">
                                                        <label class="form-check-label" for="inlineCheckbox2">Box</label>
                                                    </div>
                                                    <br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="otherAccessoriesCheck" id="otherAccessoriesCheck"
                                                            value="Other Accessories">
                                                        <label class="form-check-label" for="inlineRadio1">Other
                                                            Accessories</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" name="otherAccessories"
                                                            id="otherAccessories" class="form-control input-sm"
                                                            placeholder=" Value 1, Value2..." disabled />
                                                        <span class="text-danger" id="brandError"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6 d-none">
                                                <label>Total With Due
                                                    ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                <span class="btn btn-danger float-right viewPurchase"
                                                    id="totalWithDue">0</span>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Customer Communication Section</legend>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Warehouse: <span class="text-danger">*</span></label>
                                                <select id="warehouse" name="warehouse" class="abcd"
                                                    style="width:100%" required>

                                                    @foreach ($warehouses as $warehouse)
                                                        <option value='{{ $warehouse->id }}' selected>
                                                            {{ $warehouse->wareHouseName }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <span class="text-danger" id="warehouseError"></span>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label>Product Search : <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select id="products" name="products" class="form-control input-sm"
                                                        style="width:96%">
                                                        <option value=""> Product Search </option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ $product->name . ' - ' . $product->code }} </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-primary input-group-addon"
                                                        onclick="showAdvanceSearch();"> <i
                                                            class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Service Note</label><br>
                                                <textarea class="form-control" name="service_note" id="service_note" width="100%" rows="1"
                                                    placeholder="Service note.."></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Cart Details: </label>
                                                <table border="1" style="font-size: 13px; width:100%;"
                                                    class="table-bordered">
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
                                                        <td colspan="6" class="text-right"> Discount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"> <input type="text"
                                                                id="discount" name="discount" onblur="calculateTotal()"
                                                                class="input-sm text-right" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Transport
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="transport" name="transport" onblur="calculateTotal()"
                                                                class="input-sm text-right" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Vat
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="vat" name="vat" onblur="calculateTotal()"
                                                                class="input-sm text-right" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Ait
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="ait" name="ait" onblur="calculateTotal()"
                                                                class="input-sm text-right" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Grand Total
                                                            {{ Session::get('companySettings')[0]['currency'] }} :
                                                        </td>
                                                        <td class="text-right"><input type="text"
                                                                class="input-sm  text-right viewPurchase" id="grandSum"
                                                                value="0" readonly>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Payment Method : </td>
                                                        <td>
                                                            <select id="paymentMethod" name="paymentMethod"
                                                                class="form-control text-center">
                                                                <option value="Cash" selected>Cash</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Paid Amount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td>
                                                            <input type="text" id="payment" name="payment"
                                                                class="input-sm text-right" value="0"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr id="statusTR" style="display:none;">
                                                        <td td colspan="6" class="text-right">Status</td>
                                                        <td>
                                                            <select id="status" name="status" class="form-control"
                                                                width="100%">
                                                                <option value="0">Select a status</option>
                                                                <option value="Servicing">Servicing</option>
                                                                <option value="Cancelled">Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-2">
                                        <a class="check-out-btn float-left" href="#" onclick="clearCart()"> <i
                                                class="fa fa-trash"></i> <span class="check-out-text"> Clear Cart</span>
                                        </a>
                                    </div>
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">
                                        <button type="button" id="checkOutCart"
                                            class="check-out-btn my_button float-right btn-block"><i class="fa fa-save">
                                            </i> <span class="check-out-text"> Place Order</span> </button>
                                    </div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> x </button>
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
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> x
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
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
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
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        $("#otherAccessoriesCheck").click(function(e) {
            let checked = $(this).attr("checked");
            if (!checked) {
                $(this).attr("checked", true);
                $("#otherAccessories").attr("disabled", false);
            } else {
                $(this).removeAttr("checked");
                $(this).prop("checked", false);
                $("#otherAccessories").attr("disabled", true);
                $("#otherAccessories").val('');
            }
        });



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
                url: "{{ route('sale.showSerializTable') }}",
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

            allowClear: true,
            width: '100%'
        });
        /* $("#category").select2({
            placeholder: "Select Category",
            allowClear: true,
            width: '100%'
        }); */
        /*$("#brand").select2({
            placeholder: "Select Brand",
            allowClear: true,
            width: '100%'
        }); */
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
                url: "{{ route('sale.service.fetchCart') }}",
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
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            })
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
            //loadProducts(categoryId, brandId, warehouseId);
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
            });
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
            $("#totalAmount").text(grandTotal);
            $("#grandSum").val(grandTotal);

            if (grandTotal > '0') {
                $('#statusTR').show();
            } else {
                $('#statusTR').hide();
            }
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
                //alert('Warehouse must be selected');
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
                        var _token = $('input[name="_token"]').val();
                        var fd = new FormData();
                        fd.append('id', id);
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
                        $("#currentDue").text(result.current_due);
                        $("#customerName").val(result.name);
                        $("#customerAddress").val(result.address);
                        $("#customer").val(result.id);
                        $("#partyPhoneNumber").val(result.contact);
                        $("#creditLimit").text(result.credit_limit);
                        $("#leftCredit").text(result.credit_limit - result.current_due);
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
                url: "{{ route('sale.service.addToCart') }}",
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
                    //alert(JSON.stringify(response))
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
                url: "{{ route('sale.service.updateCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    if (result.exceed == 'exceeded') {
                        Swal.fire("Sorry", "Unit price cannot be lower then minimum price ", "error");
                    }
                    if (result.data == "Success") {
                        fetchCart();
                    } else {
                        //alert("Error To update cart");
                    }
                },
                beforeSend: function() {
                    //$('#loading').show();
                },
                complete: function() {
                    // $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                }
            })
        }

        function loadCartandUpdate(id, warehouse_id, product_type) {
            // Check Available Quantity
            let available_qty = parseFloat($('#available_qty_' + id + '_' + warehouse_id).text());
            let quantity = parseFloat($('#quantity_' + id + '_' + warehouse_id).val());
            let check_type = $('#product_type_' + id + '_' + warehouse_id).val();
            /* if (available_qty < quantity && check_type != "service") {
                Swal.fire({
                    title: 'Error!',
                    text: 'This Quantity Not available for sale',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('#quantity_' + id + '_' + warehouse_id).val(0);
                return 0;
            } */
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
                        url: "{{ route('sale.service.clearCart') }}",
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
                        url: "{{ route('sale.service.removeProduct') }}",
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
                            //alert(JSON.stringify(response));
                        }
                    })
                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            })
        }
        //Save Order Sale Products
        $("#checkOutCart").click(function(e) {
            var category = $('#category').val();
            var partyPhoneNumber = $("#partyPhoneNumber").val();
            var customerName = $("#customerName").val();
            var defectReported = $("#defectReported").val();
            var brand = $("#brand").val();
            var model = $("#model").val();
            var item = $("#item").val();
            var quantity = $("#quantity").val();
            if (category == '') {
                Swal.fire('Error!', 'Category is requied', 'error');
            } else if (partyPhoneNumber == '') {
                Swal.fire('Error!', 'Phone Number is requied', 'error');
            } else if (customerName == '') {
                Swal.fire('Error!', 'Customer name is requied', 'error');
            } else if (brand == '') {
                Swal.fire('Error!', 'Brand is requied', 'error');
            } else if (model == '') {
                Swal.fire('Error!', 'Item model is requied', 'error');
            } else if (item == '') {
                Swal.fire('Error!', 'Item name is requied', 'error');
            } else if (quantity == '') {
                Swal.fire('Error!', 'Item quantity is requied', 'error');
            } else {
                // Start Service Centre
                var accessoriesRecievedArray = [];
                $.each($("input[name='accessoriesRecieved']:checked"), function() {
                    accessoriesRecievedArray.push($(this).val());
                });

                var expectedDeliveryDate = $("#expectedDeliveryDate").val();
                var workApprovalDate = $("#workApprovalDate").val();
                var manufacturingSiNo = $("#manufacturingSiNo").val();
                var accessoriesRecieved = accessoriesRecievedArray.toString();
                var otherAccessories = $("#otherAccessories").val();
                var service_note = $("#service_note").val();
                var fd = new FormData();
                fd.append('defectReported', defectReported);
                fd.append('workApprovalDate', workApprovalDate);
                fd.append('expectedDeliveryDate', expectedDeliveryDate);
                fd.append('manufacturingSiNo', manufacturingSiNo);
                fd.append('quantity', quantity);
                fd.append('brand', brand);
                fd.append('model', model);
                fd.append('category', category);
                fd.append('item', item);
                fd.append('accessoriesRecieved', accessoriesRecieved);
                fd.append('otherAccessories', otherAccessories);
                // End Service Centre

                // If Customer Not Exist

                var customerAddress = $("#customerAddress").val();

                var saleType = $("#saleType").val();
                var date = $("#saleDate").val();
                var customer_id = parseInt($("#customer").val());
                var totalAmount = 0;
                var i = 0;
                $('span[id^="totalPrice_"]').each(function() {
                    totalAmount += parseFloat($(this).text());
                    i = i + 1;
                });
                //alert(totalAmount);
                //var total_amount = $("#totalAmount").text();
                var discount = $("#discount").val();
                var carrying_cost = $("#transport").val();
                var vat = $("#vat").val();
                var ait = $("#ait").val();
                var payment_method = $("#payment_method").val();
                var current_payment = $("#payment").val();

                var grand_total = $("#grandSum").val();
                // alert(grand_total);
                var previous_due = $("#currentDue").text();
                var status = $("#status").val();
                //alert(status);
                var total_with_due = parseFloat($("#totalWithDue").text());

                var current_balance = parseFloat(total_with_due) - parseFloat(current_payment);
                var totalDue = grand_total - current_payment;
                var _token = $('input[name="_token"]').val();
                fd.append('saleType', saleType);
                fd.append('date', date);
                fd.append('customer_id', customer_id);
                fd.append('totalAmount', totalAmount);
                fd.append('discount', discount);
                fd.append('carrying_cost', carrying_cost);
                fd.append('vat', vat);
                fd.append('ait', ait);
                fd.append('grand_total', grand_total);
                fd.append('previous_due', previous_due);
                fd.append('payment_method', 'payment_method');
                fd.append('total_with_due', total_with_due);
                fd.append('current_payment', current_payment);
                fd.append('current_balance', current_balance);
                fd.append('totalDue', totalDue);
                fd.append('status', status);
                fd.append('customerName', customerName);
                fd.append('customerAddress', customerAddress);
                fd.append('partyPhoneNumber', partyPhoneNumber);
                fd.append('service_note', service_note);
                fd.append('_token', _token);
                clearErrorMessage();
                $.ajax({
                    url: "{{ route('sale.service.checkOutCart') }}",
                    method: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    datatype: "json",
                    success: function(result) {
                        //alert(JSON.stringify(result));
                        clearSalesForm();
                        fetchCart();
                        calculateTotal();
                        // Redirect After Click OK--//
                        let saleOrderId = result.saleOrderId;
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
                                    printTsSales(saleOrderId);
                                } else {
                                    orderInvoice(saleOrderId);
                                    viewSaleOrders();
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
                        //alert(JSON.stringify(response));
                        $('#loading').hide();
                        Swal.fire('Error!', 'Error: Please check again or contact with administrator',
                            'error');
                        $('#partyPhoneNumberError').text(response.responseJSON.errors.partyPhoneNumber);
                        $('#customerNameError').text(response.responseJSON.errors.customerName);
                        $('#customerAddressError').text(response.responseJSON.errors.customerAddress);
                        $('#defectReportedError').text(response.responseJSON.errors.defectReported);
                        $('#brandError').text(response.responseJSON.errors.brand);
                        $('#modelError').text(response.responseJSON.errors.model);
                        $('#itemError').text(response.responseJSON.errors.item);
                        $('#categoryError').text(response.responseJSON.errors.category);
                        $('#quantityError').text(response.responseJSON.errors.quantity);
                    }
                })
            }

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
            $("#totalAmount").text("0");
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
            $('#otherAccessoriesCheck').attr('checked', false);
            $("#otherAccessories").attr("disabled", true);
            $("#otherAccessories").val('');

            $('#saleProducts')[0].reset();
        }

        function clearErrorMessage() {
            $('#productError').text('');
            $('#partyPhoneNumberError').text('');
            $('#customerNameError').text('');
            $('#customerAddressError').text('');
        }

        function orderInvoice(id) {
            var url = '{{ route('sale.service.orderInvoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }

        function printTsSales(id) {
            var url = '{{ route('sale.tsInvoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }

        function viewSaleOrders() {
            var url = '{{ route('sale.service.SaleOrders') }}';
            window.location.href = url;
        }
    </script>
@endsection
