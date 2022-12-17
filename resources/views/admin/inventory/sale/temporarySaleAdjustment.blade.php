@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale Return
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <div class="row ">
                        <div class="col-md-12 ">
                            <h4 class="text-center"> <u>Temporary Sale Adjustment</u> </h4>
                        </div>
                    </div>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="saleNo"> Date</label>
                                    <input type="date" class="form-control" name="date" id="date"
                                        aria-describedby="emailHelp" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customerId"> Customer</label>
                                    <select id="customer" name="customer" class="form-control input-sm"
                                        onchange="getTemporarySale(this.value);">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->name . ' - ' . $customer->code }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="customerError"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="warehouse"> Return WareHouse </label>
                                    <select id="warehouse" name="warehouse" class="form-control input-sm">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->wareHouseName . ' - ' . $warehouse->wareHouseAddress }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="warehouseError"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="project">  Name</label>
                                    <input type="text" class="form-control" name="project" id="project"
                                        aria-describedby="project" placeholder=" Name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="requisitionNo"> Requisition No</label>
                                    <input type="text" class="form-control" name="requisitionNo" id="requisitionNo"
                                        aria-describedby="emailHelp" placeholder=" Requisition No">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="remarks"> Remarks</label>
                                    <input type="text" class="form-control" name="remark" id="remark"
                                        aria-describedby="emailHelp" placeholder="Remarks Here">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table width="100%" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">SN</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Total Quantity</th>
                                                <th scope="col">Sold Quantity</th>
                                                <th scope="col">Returned Quantity</th>
                                                <th scope="col">Sale Quantity</th>
                                                <th scope="col">Unit Price</th>
                                                <th scope="col">Return Quantity</th>
                                                <th scope="col">Remaining Quantity</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="temporarySaleTable">
                                            
                                        </tbody>
                                        <tr>
                                            <td colspan="9" class="text-right">Total Price : </td>
                                            <td id="totalPrice">00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right">Discount : </td>
                                            <td><input type="text" style="width:100%" name="discount" id="discount" oninput="calculateDiscount()" value="0" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right">Grand Total : </td>
                                            <td id="grandTotal">00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right">Paid Amount : </td>
                                            <td><input type="text" style="width:100%;text-right;" name="paidAmount" id="paidAmount" value="0" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <a href="#/" class="btn btn-primary btn-md" onclick="addjustment()" role="button"
                                    aria-pressed="true"><i class="fas fa-save"></i> Adjustment</a>
                                <a href="{{ route('sale.sales', ['type'=>'ts']) }}" class="btn btn-dark btn-md" role="button"
                                    aria-pressed="true"><i class="fas fa-undo"></i> Back </a>
                                <a href="{{ route('sale.return.list', ['type'=>'ts']) }}" class="btn btn-info btn-md" role="button"
                                    aria-pressed="true"><i class="fas fa-list"></i> Sale return list </a>
                            </div>
                        </div>
                    </form>

                </div>

                <!-- /.card -->
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
    </div>
    <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        $("#customer").select2({
            placeholder: "Select customer",
            allowClear: true,
            width: '100%'
        });
        $("#warehouse").select2({
            placeholder: "Select customer",
            allowClear: true,
            width: '100%'
        });

        function getTemporarySale(id) {
            $.ajax({
                url: "{{ route('sale.getTemporarySale') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#temporarySaleTable").html(result);
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

        function confirmDelete(id) {
            alert(id);
        }

        function sale(id) {
            let unitPrice = parseInt($('#unitPrice' + id).val());
            let soldQty = $('#soldQty' + id).val();
            let returnedQty = parseInt($('#returnedQty_'+id).text());
            let totalQty = parseInt($('#totalQty' + id).val());
            let sale = $('#sale_' + id).val();
            let returnItem =  $('#temRetrun_' + id).val();
            if(soldQty == "" || soldQty == NaN){
                soldQty = 0;
            }
            if(sale == "" || sale == NaN){
                sale = 0;
            }
            if(returnItem == "" || returnItem == NaN){
                returnItem = 0;
            }
            let currentQty = (totalQty - (parseInt(soldQty) + returnedQty));
            var grandTotal = 0;
            var usedItem = parseInt(sale)+parseInt(returnItem);
            if (currentQty >= usedItem) {
                totalPrice = sale*unitPrice;
                remainingQty = parseInt(currentQty)-parseInt(usedItem);
                grandTotal = totalPrice;
                $('#total_' + id).text(totalPrice);
                $('#remainingQty_' + id).text(remainingQty);
            } else{
                Swal.fire({
                    title: 'Error!',
                    text: 'Can not return and sale Qty ('+currentQty+') more than sale ('+usedItem+')',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('#sale_' + id).val('');
                $('#remainingQty_' + id).text(parseInt(currentQty)-parseInt(returnItem));
                $('#total_' + id).text(0);
            }
            
            var total = 0;
            $("td[id^='total_']").each(function() {
                var totalPrice = $(this).html();
                if(totalPrice != "" && totalPrice != 0){
                    total += parseFloat(totalPrice);
                }
            });
            $("#totalPrice").html(total);
            var len = $("#discount").val().length;
            var discount = 0;
            if(len > 1){
                if($("#discount").val().substring(len-1,len) == "%"){
            		discount = parseFloat((total * parseFloat($("#discount").val())/100).toFixed(2));
            	}else{
            		discount = parseFloat($("#discount").val());
            	}
            }
            $("#grandTotal").html(parseFloat(total) - parseFloat(discount));
            $("#paidAmount").val(0);
        }

        function temRetrun(id) {
            let unitPrice = parseInt($('#unitPrice' + id).val());
            let soldQty = $('#soldQty' + id).val();
            if(soldQty == "" || soldQty == NaN){
                soldQty = 0;
            }
            let returnedQty = parseInt($('#returnedQty_'+id).text());
            let totalQty = parseInt($('#totalQty' + id).val());
            let sale = $('#sale_' + id).val();
            let returnItem =  $('#temRetrun_' + id).val();
            if(sale == "" || sale == NaN){
                sale = 0;
            }
            if(returnItem == "" || returnItem == NaN){
                returnItem = 0;
            }
            let currentQty = (totalQty - (parseInt(soldQty) + returnedQty));
            var grandTotal = 0;
            var usedItem = parseInt(sale)+parseInt(returnItem);
            if (currentQty >= usedItem) {
                totalPrice = sale*unitPrice;
                remainingQty = parseInt(currentQty)-parseInt(usedItem);
                grandTotal = totalPrice;
                $('#total_' + id).text(totalPrice);
                $('#remainingQty_' + id).text(remainingQty);
            } else{
                Swal.fire({
                    title: 'Error!',
                    text: 'Can not return Qty more than sale',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('#temRetrun_' + id).val('');
                $('#remainingQty_' + id).text(parseInt(currentQty)-parseInt(sale));
                $('#total_' + id).text(sale*unitPrice);
            }
            
            var total = 0;
            $("td[id^='total_']").each(function() {
                var totalPrice = $(this).html();
                if(totalPrice != "" && totalPrice != 0){
                    total += parseFloat(totalPrice);
                }
            });
            $("#totalPrice").html(total);
            var len = $("#discount").val().length;
            var discount = 0;
            if(len > 1){
                if($("#discount").val().substring(len-1,len) == "%"){
            		discount = parseFloat((total * parseFloat($("#discount").val())/100).toFixed(2));
            	}else{
            		discount = parseFloat($("#discount").val());
            	}
            }
            $("#grandTotal").html(parseFloat(total) - parseFloat(discount));
            $("#paidAmount").val(0);
        }
        function addjustment(){
            var _token = $('input[name="_token"]').val();
            var date = $("#date").val();
            var customer = $("#customer").val();
            var warehouse = $("#warehouse").val();
            var project = $("#project").val();
            var requisitionNo = $("#requisitionNo").val();
            var remarks = $("#remark").val();
            var totalPrice = $("#totalPrice").html();
            var discount = 0;
            var len = $("#discount").val().length;
            if(len > 1){
                if($("#discount").val().substring(len-1,len) == "%"){
            		discount = parseFloat((total * parseFloat($("#discount").val())/100).toFixed(2));
            	}else{
            		discount = parseFloat($("#discount").val());
            	}
            }
            
            var grandTotal = $("#grandTotal").html();
            var paidAmount = $("#paidAmount").val();
            
            //FSales Data
            var TSproductsId=[];
            var saleQuantity=[];
            var unitPrice=[];
            var productTotal=[];
            var i = 0;
            $('input[id^="sale_"]').each(function() {
                var sQuantity = $(this).val();
                if(sQuantity > 0){
                    var res = $(this).attr('id').split("_");
                    
                    var id = res[res.length-1];
                    TSproductsId[i] = id;
                    saleQuantity[i] = sQuantity;
                    
                    unitPrice[i] = $("#unitPrice"+id).val();
                    productTotal[i] = $("#total_"+id).text();
                    i = i + 1;
                }
            });
            var TSReturnproductsId=[];
            var returnQutantity=[];
            i = 0;
            $('input[id^="temRetrun_"]').each(function() {
                var rQuantity = $(this).val();
                if(rQuantity > 0){
                    var res = $(this).attr('id').split("_");
                    var id = res[res.length-1];
                    TSReturnproductsId[i] = id;
                    returnQutantity[i] = rQuantity;
                    i = i + 1;
                }
            });
            var remainingZeroProductId=[];
            i = 0;
            $('td[id^="remainingQty_"]').each(function() {
                var remainingQty = $(this).text();
                if(remainingQty == "0"){
                    var res = $(this).attr('id').split("_");
                    var id = res[res.length-1];
                    remainingZeroProductId[i] = id;
                    i = i + 1;
                }
            });
            
            var fd = new FormData();
            fd.append('_token', _token);
            fd.append('date', date);
            fd.append('customer', customer);
            fd.append('warehouse', warehouse);
            fd.append('project', project);
            fd.append('requisitionNo', requisitionNo);
            fd.append('remarks', remarks);
            fd.append('totalPrice', totalPrice);
            fd.append('discount', discount);
            fd.append('grandTotal', grandTotal);
            fd.append('paidAmount', paidAmount);
            fd.append('remainingZeroProductId', remainingZeroProductId);
            fd.append('TSproductsId', TSproductsId);
            fd.append('saleQuantity', saleQuantity);
            fd.append('unitPrice', unitPrice);
            fd.append('productTotal', productTotal);
            fd.append('TSReturnproductsId', TSReturnproductsId);
            fd.append('returnQutantity', returnQutantity);
            $.ajax({
                url: "{{ route('sale.saveTSAdjustment') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
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
                            getTemporarySale(customer);
                        }
                    });
                    //$("#temporarySaleTable").html(result);
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
        function calculateDiscount(){
            var totalAmount = $("#totalPrice").text();
            var discount = $("#discount").val();
            if(discount == '')
                discount = 0;
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
            var grandTotal = totalAmount - discount;
            $("#grandTotal").text(grandTotal);
        }
    </script>
@endsection
