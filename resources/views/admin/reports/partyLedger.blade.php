@extends('admin.master')
@section('title')
    Admin party ledger
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3>Party Ledger</h3>
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Choose Vendor</label>
                            <select type="date" class="form-control" name="vendor_id" id="vendor_id">
                                <option value="0"selected>Choose Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="vendor_idError">{{ $errors->has('vendor_id') ? $errors->first('vendor_id') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" id="date_from" >
                            <span class="text-danger" id="date_fromError">{{ $errors->has('date_from') ? $errors->first('date_from') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" id="date_to">
                            <span class="text-danger" id="date_toError">{{ $errors->has('date_to') ? $errors->first('date_to') : '' }}</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">.</label><br>
                            <button class="btn btn-primary" onclick="generateVoucher()">Generate</button>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer"  width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="10%" class="text-center">Transaction Date</td>
                                    <td width="20%" class="text-center">Voucher Title</td>
                                    <td width="5%" class="text-center">Transaction No</td>
                                    <td width="25%" class="text-center">Particulars</td>
                                    <td width="10%" class="text-center">Debit</td>
                                    <td width="10%" class="text-center">Credit</td>
                                    <td width="15%" class="text-center">Balance</td>
                                </tr>
                            </thead>
                            <tbody id="manageVoucherTable"></tbody>
                            <tbody id="manageVoucherTotal"></tbody>
                        </table>
                        
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12 " id="getVoucherButton"></div>
                    </div>
                    
                </div><!-- Card Content end -->
               
               
        <!-- pc-container end -->
@endsection


@section('javascript')
    <script>
        $("#vendor_id").each(function() {
            select2Class($(this));
        });
        
        function select2Class(vendor_id){
			$('#vendor_id').select2({
                placeholder: "Select vendor",
                allowClear: true,
                width:'100%'
		    });
        }

     
            
               
        function generateVoucher(){
            var vendor_id=$('#vendor_id').val();
            var date_from=$('#date_from').val();
            var date_to=$('#date_to').val();
            var _token = $('input[name="_token"]').val();

            var fd = new FormData();
                fd.append('vendor_id',vendor_id);
                fd.append('date_from',date_from);
                fd.append('date_to',date_to);
                fd.append('_token',_token);

            $.ajax({
                url:"{{route('generateVoucher')}}",
                method:"POST",
                data:fd,
                contentType: false,
                processData: false,
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                   $('#manageVoucherTable').html(result.table)
                   $('#manageVoucherTotal').html(result.total)
                   $('#getVoucherButton').html(result.button)
                },error:function(response) {
                    //alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }

            })
        }
           
        





        function generateVoucherPdf(){
            var vendor_id=$('#vendor_id').val();
            var date_from=$('#date_from').val();
            var date_to=$('#date_to').val();
            window.open("{{url('account/vouchers/pdf/')}}"+"/"+vendor_id+"/"+date_from+"/"+date_to);
          
            
        }





    </script>
@endsection
