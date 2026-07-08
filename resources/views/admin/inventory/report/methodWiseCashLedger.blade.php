@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Asset
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div id="msg_error"></div>
                <form id="saleProducts" method="POST">
                    <div class="row g-3">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Method Wise Cash Ledger Reports View & Print </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="form-group mb-3 col-md-12">
                                            <h4 style="color: gray;text-align: center;"> Method Wise Cash Ledger Wise Reports </h4>
				                            <h5 style="text-align: center;">**Date wise payment method details view & Print As PDF **</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    
                                        <form  class="form-horizontal" method="POST">
                                            @csrf
                							<div class="row g-3">
                							    <div class="col-md-5">
                							        <label for="categoryName" class="control-label">Paymet Method :</label>
                    								
                    								<select class="form-control" id="add_paymentMethod" name="add_paymentMethod"  style="width:100%;" required>
                                                    <option value="All" selected> All </option>
                                                    @foreach($paymentMethods as $paymentMethod)
                                                        <option value="{{$paymentMethod->methodName}}" selected> {{$paymentMethod->methodName}} </option>
                                                    @endforeach
                                                    </select>
                                                    
                							    </div>
                							    <div class="col-md-3">
                    								<label for="categoryName" class="control-label">Start Date :</label>
                    								<input name="min" id="startDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select Start date" name="startDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                    							<div class="col-md-3">
                    								<label for="categoryName" class="control-label">End Date :</label>
                    								<input name="min" id="endtDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select End date" name="endtDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                							    <div class="col-md-1">
                    								<button type="button" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="showMyData();" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search"></i> Search </button>
                    								
                    							</div>
                    					    </div>
                						</form><br><br>
                						<!--input type="submit" id="btndisplay" value="show" onclick="showMyData();"-->
                						<div id="myDiv"></div>
                						 <br><br>
                                    </div>
                                <!-- /.card -->

                                <!-- /.card -->
                            </div>
                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->

                        <!-- /.row (main row) -->

                    </div><!-- /.container-fluid -->
        </section>
        </form>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@section('javascript')
    <script>
        $("#add_paymentMethod").select2( {
        	placeholder: "Select Payment Method",
        	allowClear: true
    	} );
        function showMyData(){  
        	//alert('Generate Reports For This Date: '+$('#salesType').val());
        	var party = $('#add_paymentMethod').val();
        	if(party ==''){
                alert('Please select Payment Method select-box'); return false;
            }
            else{
			    var fd = new FormData();
                fd.append('cName', $('#add_paymentMethod').val());
                fd.append('startDate', $('#startDate').val());
                fd.append('endtDate', $('#endtDate').val());
                fd.append('_token', $('input[name="_token"]').val());
        	    $.ajax({ 
        			type: "POST",
        			url: "{{ route('methodwisereceivedreport') }}",
        			data: fd,
                    contentType: false,
                    processData: false,
                    datatype: "json",
        			 beforeSend: function () {
                            $('#loading').show();
                        },
        			 success: function(data){
        				 //alert(data);
        				 alert(JSON.stringify(data));
        				//$("#loader").load(" #loader");
        				 $("#myDiv").html(data);
        			 },
        			 complete: function () {
                            $('#loading').hide();
                        }
        	});
        	}
        	}
    </script>
@endsection