@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name'].' Voucher '.$type}}
@endsection
@section('content')

<style type="text/css">
  
  h3{
    color: #66a3ff;
  }
</style>
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
   <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-md-12">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="card">
            <div class="card-header"> 
              <h3 style="float:left;">  EMI Voucher List (Paid)</h3>
			  <a href="{{url('voucher/payment/addEmiVoucher')}}" class="btn btn-outline-success float-right" ><i class="fa fa-plus circle"></i> Add EMI Voucher </a>

			  <a class="btn btn-outline-success" style="margin-left:20px;" onclick="reloadDt()"><i class="fas fa-sync"></i> Refresh </a>
            </div><!-- /.card-header -->
            <div class="card-body">
               <div class="col-md-12">
            

            <!--data listing table-->
            <div class="table-responsive">
            <table id="manageVoucherTable" class="table table-bordered table-hover table-striped ">
                <thead>
                <tr>
                    <td>SL</td>
                    <td>voucher Info</td>
                    <td>Customer Info</td>
                    <td>Amount</td>
                    <td>Actions</td>
                </tr>
                </thead>
                
            </table>
            </div>
            <!--data listing table-->

        </div>
              

          </div>
          <!-- /.card -->

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

    <!-- modal -->
	<div class="modal fade bd-example-modal-lg" id="modalForCompletedEmiView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	<form id="voucherForm" method="POST" enctype="multipart/form-data" action="#">
			<div class="modal-header">
        <h4 class="modal-title float-left"> EMI {{$type}} Voucher</h4><br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-6">
						<div id="customerView">
							
						</div>
					</div> 
					<!-- table -->
					<div class="form-group col-md-12">
							<div style="font-weight: 900;" class="text-center bg-info text-light pt-2">
								<label class="font-weight-bold">EMI Details Information : </label>
								<label class="font-weight-bold">Total Amount For Tenure Payment : <span id="totalTenureAmount"></span> </label>
							</div>

							<table border="1" class="table-striped table-hover table-info" style="width:100%;text-align:center;">
								<thead>
								<tr>
									<th>SL</th>
									<th>Payment Date</th>
									<th>Amount</th>
									<th>Is Paid</th>
									<!-- <th>Actions</th> -->
								</tr>
								</thead>
								<tbody id="manageCartTable"></tbody>
							</table>
							</table>
						</div>
			  			<!--deleted table -->
						<div class="form-group col-md-12 mt-2">
									<div style="font-weight: 900;" class="text-center bg-warning text-dark pt-2">
										<label class="font-weight-bold">EMI Deleted Details Information : </label>						</div>
									<table border="1" class="table-striped table-hover table-info" style="width:100%;text-align:center;">
										<thead>
										<tr>
											<th>SL</th>
											<th>Payment Date</th>
											<th>Amount</th>
											<th>Is_Paid</th>
										</tr>
										</thead>
										<tbody id="manageCartDeletedTable"></tbody>
									</table>
									</table>
						</div>
			 
			  </div>
		  </div>
		  	 <div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!-- <button type="submit" class="btn btn-primary btnSave" id="saveVoucher">Save</button> -->
			</div>
		  </form>
    </div>
  </div>
</div><!-- / modal -->

@endsection

@section('javascript')

<script>


	var table;
	$(document).ready(function() {
		table = $('#manageVoucherTable').DataTable({
			'ajax': "{{url('voucher/sale/getPaidEmi')}}",
			processing:true,
		});
	});

	/* function getInvoice(id){
		console.log(id);
		var partyId = id;
		$("#manageCartTable").html('');
		$("#manageCartDeletedTable").html('');
		$.ajax({
			url:"{{url('voucher/sale/getEmiInvoice')}}",
			method:"GET",
			data:{'partyId':partyId},
			success:function(result){
				// alert(JSON.stringify(result));
				var emiData = "<option value=''>Select Invoice </option>";
				for(var i=0; i < result.length; i++){
					emiData += "<option value='"+result[i]["id"]+"'>"+result[i]["sale_no"]+"</option>";
				}
				$("#invoice").html(emiData);
		  }, beforeSend: function () {
			  $('#loading').show();
		  },complete: function () {
			  $('#loading').hide();
		  }, error: function(response) {
			  $("#barcodeError").text("No such Invoice available in your system");
				//alert(JSON.stringify(response));
		  }
		})

		$("#modalForCompletedEmiView").modal('show');
	} */


	// get EMI with sale ID
	var paidYesIds = [];
	var UnpaidIds = [];
	function viewDetails(id) {
		let customerInfo = $("#customerInfo").html();
		let saleId =  id;
		console.log("saleId="+saleId);
		$.ajax({
			url:"{{url('voucher/sale/fetchEMI')}}",
			method:"GET",
			data:{'saleId':saleId},
			success:function(result){
				var saleEmiData = "";
				var saleDeletedEmiData = "";
				let saleId = "";
				let totalTenureAmount = 0;
				let serial = 0;
				let serial2 = 0;
				for(var i=0; i < result.length; i++){
					 saleId  = result[i]["id"] ;
					 if(result[i]["deleted"]=='No')
					 	totalTenureAmount += parseFloat(result[i]["per_tenur_amount"]);
					 if(result[i]["is_paid"]  == "Yes"){
						paidYesIds[i] = result[i]["id"];
					}else{
						UnpaidIds[i] = result[i]["id"];
					}
					let button = '';
					let paymentStatus = '';
					let paymentDeletedStatus = '';
					if(result[i]["deleted"] == "Yes" ){
						//button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="doSomething('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card"></i> Do Something </a></li></li></li><li class="action">';
						paymentDeletedStatus = '<i class="fas fa-times-circle" style="color:red; font-size:16px;" title="Active"></i>';
					} else if(result[i]["is_paid"] == "No"){
						//button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="doSomething('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card "></i> doSomething </a></li></li></li><li class="action">';
						 paymentStatus = '<i class="fa fa-envelope" aria-hidden="true" ></i>';
					}else{
						//button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="doSomething('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card"></i> Do Something </a></li></li></li><li class="action">';
						paymentStatus = '<i class="fas fa-check-circle" style="color:green; font-size:16px;" title="Active"></i>';
					}
					button += '</li></li></ul></div></td>';
					if(result[i]["is_paid"] == "No" ){
						if(result[i]["deleted"] == "Yes"){
							serial2++;
							saleDeletedEmiData += "<tr class='text-center'><th scope='row' style='color:red;'>"+(serial2)+"</th><td style='color:red;'>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+" style='color:red;'>"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+" style='color:red;'>"+paymentDeletedStatus;
						}
						else{
							serial++;
							saleEmiData += "<tr class='text-center'><th scope='row'>"+(serial)+"</th><td>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+">"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+">"+paymentStatus+button;
						}
					}else{
						serial++;
						saleEmiData += "<tr class='text-center'><th scope='row'>"+(serial)+"</th><td>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+">"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+">"+paymentStatus+button;
					}
					
					saleEmiData += "</tr>";
				}
				$("#modalForCompletedEmiView").modal('show');
				$("#manageCartTable").html(saleEmiData);
				$("#manageCartDeletedTable").html(saleDeletedEmiData);
				$("#totalTenureAmount").text(totalTenureAmount);
				$("#sale_id").val(saleId);
				$("#customerView").html(customerInfo);
		  }, beforeSend: function () {
			  $('#loading').show();
		  },complete: function () {
			  $('#loading').hide();
		  }, error: function(response) {
			  $("#barcodeError").text("No such Invoice available in your system");
				//alert(JSON.stringify(response));
		  }
		})
	}
	




</script>


 
@endsection