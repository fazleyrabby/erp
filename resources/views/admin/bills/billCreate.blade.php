@extends('admin.master')
@section('title')
    Admin Add Bills
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Add Bills</h3>
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('billStore')}}" method="post">
                        @csrf
                    <div class="row ">
                        <div class="col-md-3">
                            <label class="form-label">Pay to</label> <span class="text-danger">*</span>
                            <select type="date" class="form-control" name="vendor_id" id="vendor_id">
                                <option value="0"selected>Choose Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="vendor_idError">{{ $errors->has('vendor_id') ? $errors->first('vendor_id') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bill Date</label> <span class="text-danger">*</span>
                            <input type="date" class="form-control" name="bill_date">
                            <span class="text-danger" id="bill_dateError">{{ $errors->has('bill_date') ? $errors->first('bill_date') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Due Date</label> <span class="text-danger">*</span>
                            <input type="date" class="form-control" name="due_date">
                            <span class="text-danger" id="due_dateError">{{ $errors->has('due_date') ? $errors->first('due_date') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference</label>
                            <input type="text" class="form-control" name="reference" placeholder="Reference ">
                            <span class="text-danger" id="referenceError">{{ $errors->has('reference') ? $errors->first('reference') : '' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bill Address</label>
                            <textarea type="text" class="form-control" name="address" placeholder="Type address"></textarea>
                            <span class="text-danger" id="addressError">{{ $errors->has('address') ? $errors->first('address') : '' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Particulars</label>
                            <textarea type="text" class="form-control" name="particulars" placeholder="Type particulars"></textarea>
                            <span class="text-danger" id="particularsError">{{ $errors->has('particulars') ? $errors->first('particulars') : '' }}</span>
                        </div>
                    </div>
                        
                    
                        
                        
                    
                    <div class="table-responsive ">
                        <table class="table table-bordered table-hover dataTable no-footer m-1" id="manageJournalTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="30%" class="text-center">Account</td>
                                    <td width="30%" class="text-center">Particulars</td>
                                    <td width="15%" class="text-center">Amount</td>
                                    <td width="5%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row0">
                                    <td>#</td>
                                    <td>
                                        <select class="form-control ddl_account" name="account[]">
                                            <option value="0" selected>Choose COA</option>
                                            @php 
                                                $status='';
                                            @endphp
                                            @foreach($coas as $coa)
                                            @php
                                                if($coa->parent_id == '0' && $coa->unused == 'No'){
                                                    $status='';
                                                }elseif($coa->parent_id !== '0' && $coa->unused == 'No'){
                                                    $status='..';
                                                }else{
                                                    $status='....';
                                                }
                                            @endphp
                                            <option  value="{{$coa->id}}">{{$status}} {{$coa->name}} - {{$coa->code}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="accountError">{{ $errors->has('account') ? $errors->first('account') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="particular[]" placeholder="Particulars">
                                        <span class="text-danger" id="particularError">{{ $errors->has('particular') ? $errors->first('particular') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="amount[]" value="0" oninput="totalBalance()" style="text-align:right;">
                                        <span class="text-danger" id="amountError">{{ $errors->has('amount') ? $errors->first('amount') : '' }}</span>
                                    </td>
                                    <td>
                                        <label style="display:none;">.</label><br><br>
                                        <a href="#/" class="text-danger" onclick="remove_btn(this)"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>#</td>
                                    <td colspan="2" style="text-align:right;">Total:</td>
                                    <td>
                                        <input type="text" class="form-control" name="amountTotal" id="amountTotal"   style="text-align:right;">
                                        <span class="text-danger" id="amountTotalError">{{ $errors->has('amountTotal') ? $errors->first('amountTotal') : '' }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <button class="btn btn-primary m-2" id="btn_addRow"> <i class="fas fa-plus"></i> Add row</button>
                            <button type="submit" class="btn btn-primary float-right  m-2"> <i class="fas fa-save"></i> Save</button>
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
                    $('#div_credit_amount').show();
                    $('#div_transaction_id').show();
                    $('#account_status').val(result.status);
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
     




                
               

        function totalBalance(){
            var totalAmount = 0;  
            $("input[name='amount[]']").each(function() {
                totalAmount += parseFloat($(this).val());
            });
            $("#amountTotal").val(totalAmount);
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
