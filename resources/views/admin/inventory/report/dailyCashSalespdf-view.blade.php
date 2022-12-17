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
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Day Wise Cash Sales Reports View & Print </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <h4 style="color: gray;text-align: center;">Day Wise Cash (All Hand Cash) Reports </h4>
					                        <h5 style="text-align: center;">**Date wise ALL HAND CASH details view & Print As PDF **</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    
                                        <form  class="form-horizontal" method="POST">
                                            @csrf
                							<div class="row">
                							    <div class="col-md-5">
                							        <label for="categoryName" class="control-label">Select One :</label>
                    								
                    								<select class="form-control" id="cashSalesType" name="cashSalesType"  style="width:100%;" required>
                                                        <option value="" selected>~~ Select Sales Option ~~</option>
                                                        <option value="All"> All Sales </option>
                                                        <option value="Party"> Party & FS Sales</option>
                                                        <option value="WalkinCustomer"> WI Sales</option>
                                                     </select>
                                                    
                							    </div>
                							    <div class="col-md-3">
                    								<label for="categoryName" class="control-label">Start Date :</label>
                    								<input name="min" id="startDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select Start date" name="startDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                    							<div class="col-md-3">
                    								<label for="categoryName" class="control-label">End Date :</label>
                    								<input name="min" id="endtDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select Start date" name="endDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                							    <div class="col-md-1">
                    								<a href="#" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="showMyData();" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search"></i> Search </a>
                    								
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
        function showMyData(){  
        	//alert('Generate Reports For This Date: '+$('#salesType').val());
        	var party = $('#cashSalesType').val();
        	if(party ==''){
                alert('Please select Sales Type select-box'); return false;
            }
            else{
			    var fd = new FormData();
                fd.append('cName', $('#cashSalesType').val());
                fd.append('startDate', $('#startDate').val());
                fd.append('endtDate', $('#endtDate').val());
                fd.append('_token', $('input[name="_token"]').val());
        	    $.ajax({ 
        			type: "POST",
        			url: "{{ route('dailyCashSaleReport') }}",
        			data: fd,
                    contentType: false,
                    processData: false,
                    datatype: "json",
        			 beforeSend: function () {
                            $('#loading').show();
                        },
        			 success: function(data){
        				 alert(data);
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