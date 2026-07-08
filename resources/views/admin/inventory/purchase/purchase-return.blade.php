@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase View
@endsection
@section('content')
<style>
    .table td, .table th {padding: .5rem;vertical-align: top;border-top: 1px solid #dee2e6;}
</style>
    
        
                <!-- Main row -->
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                        <div class="col-md-12 text-center">
                            <h4><u>Purchase Return </u></h4>
                        </div>
                    <form id="purchaseProducts" enctype="multipart/form-data">
                        @csrf
                            <div class="col-md-12">
                                <div class="row g-3">
                                <input type="hidden" name="purchaseId" id="purchaseId" value="{{ $purchase->id }}">
                                <input type="hidden" name="purchaseNo" id="purchaseNo"
                                    value="{{ $purchase->purchase_no }}">
                                <input type="hidden" name="supplierId" id="supplierId"
                                    value="{{ $purchase->supplier_id }}">
                                <input type="hidden" name="discount" id="discount" value="{{ $purchase->discount }}">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="purchaseReturnDate"> Purchase Return Date</label>
                                        <input type="text" readonly class="form-control" name="DatePurchaseReturn"
                                            id="DatePurchaseReturn" aria-describedby="emailHelp" value="{{ todayDate() }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="supplierName"> Supplier Name</label>
                                        <input type="text" readonly class="form-control" name="supplierName" id="supplierName"
                                            aria-describedby="emailHelp" value="{{ $purchase->supplier_name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="purchaseCode"> Invoice No</label>
                                        <input type="text" readonly class="form-control" name="purchaseCode" id="purchaseCode"
                                            aria-describedby="emailHelp" value="{{ $purchase->purchase_no }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="purchaseDate"> Purchase Date</label>
                                        <input type="text" readonly class="form-control" name="purchaseDate" id="purchaseDate"
                                            aria-describedby="emailHelp" value="{{ $purchase->date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="purchaseDate"> Warehouse <span class="text-danger">*</span></label>
                                       <select id="warehouse" name="warehouse" class="form-control" style="width:100%" Required >
                                           <option value='' selected='true'> Return Warehouse </option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value='{{ $warehouse->id }}'>
                                                    {{ $warehouse->wareHouseName }}
                                                </option>
                                            @endforeach
                                       </select>
                                       <span class="text-danger" id="warehouseError"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="managePurchaseTable" width="100%" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td>SN</td>
                                            <td>Item Name</td>
                                            <td>Unit Price</td>
                                            <td>Quantity</td>
                                            <td>Returned Quantity</td>
                                            <td>Return Quantity</td>
                                            <td>Remaining Quantity</td>
                                            <td>Total</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($purchaseProducts as $purchaseProduct)
                                            <tr>
                                                <td scope="row">{{ ++$i }}</td>
                                                <input type="hidden" id="itemCode{{ $purchaseProduct->id }}"
                                                    value="{{ $purchaseProduct->product_id }}">
                                                <td>{{ $purchaseProduct->name.' - ' .$purchaseProduct->wareHouseName}}</td>
                                                <td id="unitPrice{{ $purchaseProduct->id }}">
                                                    {{ $purchaseProduct->unit_price }}</td>
                                                <td id="quantity{{ $purchaseProduct->id }}">
                                                    {{ $purchaseProduct->quantity }}</td>
                                                <td id="returnedQty{{ $purchaseProduct->id }}">
                                                    {{ $returnedQtyArray[$i - 1] }}</td>
                                                <td>
                                                    <input type="number" min="0" class="form-control"
                                                        name="purchaseReturn"
                                                        id="purchaseReturn{{ $purchaseProduct->id }}"
                                                        oninput="purchaseRemain({{ $purchaseProduct->id }})"
                                                        aria-describedby="emailHelp" value="" placeholder="Qty">
                                                </td>
                                                <td id="remainQty{{ $purchaseProduct->id }}">
                                                    {{ $purchaseProduct->quantity - $returnedQtyArray[$i - 1] }}</td>
                                                <input type="hidden" id="purchaseProductId{{ $purchaseProduct->id }}"
                                                    value="{{ $purchaseProduct->id }}">
                                                <td id="total{{ $purchaseProduct->id }}">0</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="7" style="text-align: center;">Grand total </td>
                                            <td id="grandTotal">0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </form>
                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-md" onclick="purchaseReturn()"> <i
                                    class="fas fa-save"></i> Return purchase </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('purchase.index') }}" class="btn btn-secondary btn-md" role="button"
                                aria-pressed="true"><i class="fas fa-undo me-1"></i>Back </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('purchase.return.list') }}" class="btn btn-info btn-md" role="button"
                                aria-pressed="true"><i class="fas fa-list me-1"></i>purchase return list </a>
                            </div>
                        </div>
                    </div>

                </div>
        @endsection
@section('javascript')
    <script>
        $("#warehouse").select2({
            placeholder: " Select Warehouse ",
            allowClear: true,
            width: '100%'
        });
        var purchaseProductIds = [];

        function purchaseRemain(id) {
            unitPrice = $('#unitPrice' + id).text();
            quantity = $('#quantity' + id).text();
            returnQty = $('#purchaseReturn' + id).val();
            returnedQty = $('#returnedQty' + id).text();

            remainingQty = parseInt(quantity) - parseInt(returnQty) - parseInt(returnedQty);
            if (remainingQty >= 0) {
                $("#remainQty" + id).text(remainingQty);

                total = returnQty * unitPrice;
                $("#total" + id).text(total);
            } else {
                $('#purchaseReturn' + id).val('');
                total = 0;
                $("#total" + id).text(total);

                $("#remainQty" + id).text(quantity-returnedQty);
                Swal.fire({
                    title: 'Error!',
                    text: 'Can not return Qty more than Purchase',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
            }

            let grandTotal = 0;
            var i = 0;
            $('input[id^="purchaseProductId"]').each(function() {
                purchaseProductIds[i] = $(this).val();
                grandTotal = grandTotal + parseFloat($("#total" + purchaseProductIds[i]).text());
                i = i + 1;
            });
            $("#grandTotal").text(grandTotal);
        }

        function purchaseReturn() {
            var purchaseId = $("#purchaseId").val();
            var warehouse = $("#warehouse").val();
            var purchaseNo = $("#purchaseNo").val();
            var supplierId = $("#supplierId").val();
            var discount = $("#discount").val();
            var purchaseDate = $("#purchaseDate").val();
            var purchaseReturnDate = $("#DatePurchaseReturn").val();

            var purchaseProductId = $("#purchaseProductId").val();
            var totalReturn = $("#total" + purchaseProductId).text();

            var _token = $('input[name="_token"]').val();
            var purchaseProductIds = [];
            var itemCodes = []; //product_id
            var Quantities = [];
            var returnedQuantities = [];
            var returnQuantities = [];
            var remainQuantities = [];
            var unitPrices = [];
            var totals = [];
            var grandTotal = 0;

            var i = 0;
            $('input[id^="purchaseProductId"]').each(function() {
                purchaseProductIds[i] = $(this).val();
                i = i + 1;
            });
            i = 0;
            $('input[id^="unitPrice"]').each(function() {
                unitPrices[i] = parseFloat($(this).val());
                i = i + 1;
            });
            i = 0;
            $('input[id^="purchaseReturn"]').each(function() {
                returnQuantities[i] = parseInt($(this).val());
                i = i + 1;
            });

            purchaseProductIds.forEach(myFunction);

            function myFunction(value, index, array) {
                itemCodes[index] = parseInt($("#itemCode" + value).val());
                unitPrices[index] = parseFloat($("#unitPrice" + value).text());
                Quantities[index] = parseFloat($("#quantity" + value).text());
                remainQuantities[index] = parseFloat($("#remainQty" + value).text());
                returnedQuantities[index] = parseFloat($("#returnedQty" + value).text());
                totals[index] = parseFloat($("#total" + value).text());
                grandTotal += parseFloat(totals[index] ? totals[index] : 0);

            }
            if (isNaN(grandTotal) || grandTotal == 0) {
                Swal.fire("Error: ", "Please Check Required Field ! ", "error");
                return 0;
            }
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('grandTotal', grandTotal);
            fd.append('warehouse', warehouse);
            fd.append('purchaseId', purchaseId);
            fd.append('purchaseNo', purchaseNo);
            fd.append('supplierId', supplierId);
            fd.append('discount', discount);
            fd.append('purchaseDate', purchaseDate);
            fd.append('purchaseReturnDate', purchaseReturnDate);
            fd.append('purchaseProductIds', purchaseProductIds);
            fd.append('itemCodes', itemCodes);
            // fd.append('productIds', itemCodes); // product id
            fd.append('Quantities', Quantities);
            fd.append('returnedQuantities', returnedQuantities);
            fd.append('returnQuantities', returnQuantities);
            fd.append('remainQuantities', remainQuantities);
            fd.append('unitPrices', unitPrices);
            fd.append('totals', totals);
            fd.append('grandTotal', grandTotal);
            fd.append('_token', _token);

            $.ajax({
                url: "{{ route('purchase.savePurchaseReturn') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    Swal.fire(
                        "Saved !", result.success, "success"
                    ).then((result) => {
                        window.location = "{{ route('purchase.return.list') }}";
                    });
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $('#warehouseError').text(response.responseJSON.errors.warehouse);
                }
            })
        }

        function printPurchase(id) {
            window.open("{{ url('purchase-return/invoice/') }}" + "/" + id);
        }
    </script>
@endsection
