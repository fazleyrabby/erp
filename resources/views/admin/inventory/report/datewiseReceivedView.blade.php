@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Asset
@endsection
@section('content')
    
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
                                    <h3> Date wise Received Balance </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="form-group mb-3 col-md-12">
                                            <h4 style="color: gray;text-align: center;">Date Wise Cash Sales Wise Reports </h4>
					                        <h5 style="text-align: center;">**Date wise ALL HAND CASH summary view & Print As PDF**</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    
                                        <form  class="form-horizontal" method="POST">
                                            @csrf
                                            <div class="row g-3">
                    							<div class="col-md-2"></div>
                							    <div class="col-md-3">
                    								<label for="categoryName" class="control-label">Start Date :</label>
                    								<input name="min" id="startDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select Start date" name="startDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                    							<div class="col-md-3">
                    								<label for="categoryName" class="control-label">End Date :</label>
                    								<input name="min" id="endtDate" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select Start date" name="endDate" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
                    							</div>
                							    <div class="col-md-1">
                    								<button type="button" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="showMyData();" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search me-1"></i>Search </button>
                    								
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
    

@endsection
@section('javascript')
    <script>
        $("#add_ts").select2( {
        	placeholder: "Select TS Party",
        	allowClear: true
    	} );
        function showMyData(){  
        	//alert('Generate Reports For This Date: '+$('#salesType').val());
        	var from = $('#startDate').val();
        	var to = $('#endtDate').val();
        	
        	if(from ==''){
                alert('Please select Date first'); return false;
            }
            else{
			    var fd = new FormData();
                fd.append('from', from);
                fd.append('to', to);
                fd.append('_token', $('input[name="_token"]').val());
        	    $.ajax({ 
        			type: "POST",
        			url: "{{ route('datewiseReceivedView') }}",
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