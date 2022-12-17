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
                                    <h3> Temporary Sale Ledger </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <h4 style="color: gray;text-align: center;"> Method Wise Cash Ledger Wise Reports </h4>
				                            <h5 style="text-align: center;">**Date wise payment method details view & Print As PDF **</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    
                                        <form  class="form-horizontal" method="POST">
                                            @csrf
                                            <div class="row">
                    							<div class="col-md-1"></div>
                							    <div class="col-md-2">
                    								<a href="{{url('report/tsledgerallpdf')}}" target="_blank" class="btn btn-default btn-flat pull-left" style="margin-top: 19%;width: 100%;"><i class="fa fa-file-pdf-o" style="color: red;"></i>  TS ALL </a>
                    							</div>
                							    <div class="col-md-6">
                							        <label for="categoryName" class="control-label">TS Customer Name :</label>
                    								
                    								<select class="form-control" id="add_ts" name="add_ts"  style="width:100%;" required>
                                                        <option value="" selected>~~ Select TS Name ~~</option>
                                                        @foreach($tsParties as $tsPartie)
                                                            <option value="{{$tsPartie->id}}" selected> {{$tsPartie->partyName.' - '.$tsPartie->partyAddress}} </option>
                                                        @endforeach
                                                        ?>
                                                    </select>
                                                </div>
                                                    
                    							<input name="min" id="startDate" name="startDate" type="hidden" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd" />					
                        							
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
        $("#add_ts").select2( {
        	placeholder: "Select TS Party",
        	allowClear: true
    	} );
        function showMyData(){  
        	//alert('Generate Reports For This Date: '+$('#salesType').val());
        	var id = $('#add_ts').val();
        	if(id ==''){
                alert('Please select Party select-box'); return false;
            }
            else{
			    var fd = new FormData();
                fd.append('id', $('#add_ts').val());
                fd.append('_token', $('input[name="_token"]').val());
        	    $.ajax({ 
        			type: "POST",
        			url: "{{ route('tsledgerparty') }}",
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