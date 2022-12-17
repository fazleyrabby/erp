@extends('admin.master')
@section('title')
    Admin Bill Pay
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Bill Pay</h3>
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('billPayStore')}}" method="post">
                        @csrf
                    <div class="row ">
                        <div class="col-md-3">
                            <label class="form-label">Pay to</label> <span class="text-danger">*</span>
                            <select type="date" class="form-control" name="vendor_id" id="vendor_id" onchange="getBillData()">
                                <option value="0"selected>Choose Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="vendor_idError">{{ $errors->has('vendor_id') ? $errors->first('vendor_id') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Date</label> <span class="text-danger">*</span>
                            <input type="date" class="form-control" name="payment_date">
                            <span class="text-danger" id="payment_dateError">{{ $errors->has('payment_date') ? $errors->first('payment_date') : '' }}</span>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Reference No</label>
                            <input type="text" class="form-control" name="reference" placeholder="Reference ">
                            <span class="text-danger" id="referenceError">{{ $errors->has('reference') ? $errors->first('reference') : '' }}</span>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <label class="form-label">Payment method</label>
                            <select type="text" class="form-control" name="payment_method" id="payment_method" onchange="getAccountStatus()">
                                <option value="0"selected >Select Payment Method</option>
                                <option value="{{$cashId->id}}">Cash</option>
                                @foreach($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                               
                            </select>
                            <span class="text-danger" id="payment_methodError">{{ $errors->has('payment_method') ? $errors->first('payment_method') : '' }}</span>
                        </div>

                        <div class="col-md-3" style="display:none;" id="div_account_status">
                            <label class="form-label" >Accounts</label>
                            <select type="text" class="form-control" name="account_status" id="account_status" onchange="getAmount()">
                                <option value="0" selected disabled>Select Bank</option>
                            </select>
                            <span class="text-danger" id="account_statusError">{{ $errors->has('account_status') ? $errors->first('account_status') : '' }}</span>
                        </div>
                        
                        <div class="col-md-3" style="display:none;" id="div_credit_amount">
                            <label class="form-label">Credit Amount</label>
                            <input type="text" class="form-control" name="credit_amount" id="credit_amount" readonly>
                            <span class="text-danger" id="credit_amountError">{{ $errors->has('credit_amount') ? $errors->first('credit_amount') : '' }}</span>
                        </div>
                        <div class="col-md-3" style="display:none;" id="div_transaction_id">
                            <label class="form-label">Transaction No</label>
                            <input type="text" class="form-control" name="transaction_id" id="transaction_id" >
                            <span class="text-danger" id="transaction_idError">{{ $errors->has('transaction_id') ? $errors->first('transaction_id') : '' }}</span>
                        </div>
                    </div>
                        
                    
                        
                        
                    
                    <div class="table-responsive ">
                        <table class="table table-bordered table-hover dataTable no-footer m-1"  width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="20%" class="text-center">Due date</td>
                                    <td width="20%" class="text-center">Total</td>
                                    <td width="20%" class="text-center">Due</td>
                                    <td width="15%" class="text-center">Amount</td>
                                    <td width="5%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody id="manageBillPayTable"></tbody>
                            <tfoot id="manageBillPayTableFoot"></tfoot>
                                
                            
                        </table>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            
                            
                            <button type="submit" class="btn btn-primary float-right  m-2" id="saveBtn" style="display:none;"> <i class="fas fa-save"></i> Save</button>
                            <button  class="btn btn-danger float-right  m-2" id="saveErrorBtn" disabled style="display:none;"> <i class="fas fa-save"></i> Save</button>
                            <span class="text-danger"id="saveErrorSpan" style="display:none;">You dont have enough credit..</span>
                        </div>
                    </div>
                    </form>
                </div><!-- Card Content end -->
               
               
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')
    <script>

        $(".ddl_account").each(function() {
            select2Class($(this));
        });

   

        function select2Class(ddl_account){
			$(ddl_account).select2({
                placeholder: "Select COA",
                allowClear: true,
                width:'100%'
		    });
			$('#vendor_id').select2({
                placeholder: "Select supplier",
                allowClear: true,
                width:'100%'
		    });
			$('#payment_method').select2({
                placeholder: "Select payment method",
                allowClear: true,
                width:'100%'
		    });
        }



        function getBillData(){
            var vendor_id=$('#vendor_id').val();
           // alert(vendor_id);
            $.ajax({
                url:"{{route('getBillData')}}",
                method:"GET",
                data:{"vendor_id":vendor_id},
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                   $('#manageBillPayTable').html(result.output);
                   $('#manageBillPayTableFoot').html(result.tfoot);
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }, error: function(response) {
                    //alert(JSON.stringify(response));
                }
            });
        }







        function getAccountStatus(){
            var payment_method=$('#payment_method').val();
            $.ajax({
                url:"{{route('getAccountStatus')}}",
                method:"GET",
                data:{"payment_method":payment_method},
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                    $('#div_account_status').show();
                    $('#div_account_id').show();
                    $('#div_credit_amount').show();
                    $('#div_transaction_id').show();
                    $('#account_status').html(result.status);
                    $('#credit_amount').val(result.credit_amount); 
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }, error: function(response) {
                    //alert(JSON.stringify(response));
                }
            });
        }






        function getAmount(){
            var account_status=$('#account_status').val();
            //alert( account_status);
            $.ajax({
                url:"{{route('getAmount')}}",
                method:"GET",
                data:{"account_status":account_status},
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                    $('#credit_amount').val(result); 
                    totalBalance();
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }, error: function(response) {
                    //alert(JSON.stringify(response));
                }
            });
           }





       /*  function dueTotal(){
            var amount=$('#amount').val();
            var due= $('#dueAmount').text();
            var dueTotal= due - amount;
            $('#dueAmount').text(dueTotal);
            $('#dueAmountInput').val(dueTotal);
        } */




        function totalBalance(){
            var totalAmount = 0;  
            $("input[name='amount[]']").each(function() {
                totalAmount += parseFloat($(this).val());
            });
            $("#amountTotal").val(totalAmount);


            var credit_amount=$('#credit_amount').val();

            if(totalAmount >= credit_amount){
                $('#saveErrorSpan').show();
                $('#saveErrorBtn').show();
                $('#saveBtn').hide();
            }else{
                $('#saveBtn').show();
                $('#saveErrorSpan').hide();
                $('#saveErrorBtn').hide();
            }
        }   

            
        
        



        var rowIndex = 2;
        $("#btn_addRow").click(function (e){
            e.preventDefault();
            var trData = $("#manageJournalTable tbody tr:last").html();
            $("#manageJournalTable tbody").append("<tr class='row"+rowIndex+"'>"+trData+"</tr>");
            var ddl = $("#manageJournalTable tbody tr:last td:nth-child(2) select");
            $(ddl).next().remove();
            var ind = $(".ddl_account").length-2;
            select2Class($(".ddl_account").eq(ind));
            select2Class($(".ddl_account").eq(ind+1));
            rowIndex++;
        })

        



        var i = 0;
        function remove_btn(remove_row){
            var rowNo = $('#manageJournalTable tbody tr').length;
            //$(remove_row).parent().parent().remove();
            if(rowNo > 1){
                $(remove_row).parent().parent().remove();
            }else{
                Swal.fire("Last 1 rows cannot be removed");
                //alert("Not possible to remove last 2 rows");
            }
            totalBalance();
        }


       

    </script>
@endsection
