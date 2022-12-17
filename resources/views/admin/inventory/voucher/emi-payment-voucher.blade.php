@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name']}} EMI
@endsection
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid ">
      <!-- Small boxes (Stat box) -->
      <!-- Main row -->
	  <form id="saleProducts" method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Left col -->
        <section class="col-md-12">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="card">
            <div class="card-header">
              <h3> EMI Payment Voucher
                <a class="btn btn-success float-right" href="{{url('voucher/payment/paidEmi')}}"> <i class="fa fa-plus-circle"></i> view Paid EMI Voucher</a>
              </h3>
            </div><!-- /.card-header -->
            <div class="card-body">  
				<div class="row">
					@csrf
					@if(Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
					<div class="form-group col-md-12">
                          <label>Barcode: </label>
                          <input class="form-control input-sm" id="barcode" type="text" name="barcode" onkeyup="findProduct()" >
                          <span class="text-danger" id="barcodeError"></span>
                    </div>
					@endif
					<div class="form-group col-md-6">
							<input type="hidden"  id="sale_id" value="">
							<label>Customer: </label>
							<select id="customer" name="customer" class="form-control input-sm">
								<option value="">Select Customer</option>
								@foreach($customers as $customer)
								<option value="{{$customer->id}}">{{$customer->name}}</option>
								@endforeach
							</select>
						</div>
					<div class="form-group col-md-6">
                        <label>Invoice: </label>
                        <select id="invoice" name="invoice" onchange="getInvoice()" class="form-control input-sm">
							<option value="">Select Invoice </option>
						</select>
                    </div>
					<div class="form-group col-md-12">
						<div style="font-weight: 900;" class="text-center bg-info text-light pt-2">
							<label class="font-weight-bold">EMI Details Information : </label>
							<label class="font-weight-bold">Total Amount For Tenure Payment : <span id="totalTenureAmount"></span> </label>
						</div>

						<table border="1" class="table-striped " style="width:100%;text-align:center; ">
							<thead>
							  <tr>
								<th>
									<input class="allCheck" type="checkbox">
								</th>
								<th>SL</th>
								<th>Payment Date</th>
								<th>Amount</th>
								<th>Is Paid</th>
								<th>Actions</th>
							  </tr>
							</thead>
							<tbody id="manageEmiTable"></tbody>
						  </table>
						</table>
                    </div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-success float-left" href="#" onclick="clearCart()"> <i class="fa fa-plus-circle"></i> Clear Cart </a>
						<!-- <button type="button" id="checkOutCart" class="btn btn-success float-right"><i class="fa fa-plus-circle"> checkOut Cart </i> </button> -->
						<button type="button" id="saveEmiPayment" onclick="addPayment()" class="btn btn-success  mr-2 float-right"><i class="fa fa-plus-circle "> Add Payment </i> </button>
						<button type="button" id="saveEmiPayment" onclick="paymentNow()" class="btn btn-primary  mr-2 float-right"><i class="fa fa-plus-circle"> Payment Now </i> </button>
					</div>
				</div>
			</div>
			<div class="form-group col-md-12 mt-2">
						<div style="font-weight: 900;" class="text-center bg-warning text-dark pt-2">
							<label class="font-weight-bold">EMI Deleted Details Information : </label>						</div>
						<table border="1" class="table-striped" style="width:100%;text-align:center;">
							<thead>
							  <tr>
								<th>SL</th>
								<th>Payment Date</th>
								<th>Amount</th>
								<th>Is_Paid</th>
							  </tr>
							</thead>
							<tbody id="manageEmiDeletedTable"></tbody>
						  </table>
						</table>
			</div>
          <!-- /.card -->

          <!-- /.card -->
        </div>
		</section>
	</div><!-- /.container-fluid -->
  </section>
  </form>
 <!-- modal -->
 <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header float-left">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                    </button>
                    <h4 class="modal-title float-left"> EMI Payment</h4>
                </div> 
                <div class="modal-body">
                <form id="emiPaymentForm" method="POST" action="#">
                  <div class="row">
					  @csrf
					  <input type="hidden" name="saleId" id="saleId">
					  <div class="form-group col-md-12">
						  <label> Total Amount </label>
						  <input class="form-control input-sm" id="totalAmount" type="text" name="totalAmount" value="" readonly>
						  <span class="text-danger" id="totalAmountError"></span>
					  </div>
					  <div class="form-group col-md-6">
						  <label> Payment  </label>
						  <input class="form-control input-sm"  type="number" id="emiPayment" name="emiPayment" value="0" required>
						  <span class="text-danger" id="emiPaymentError"></span>
					  </div> 
					  <div class="form-group col-md-6">
						  <label> Payment Date (mm-dd-yyyy)  </label>
						  <input class="form-control input-sm"  type="date" id="paymentDate" name="paymentDate" value="{{date('Y-m-d')}}" >
						  <span class="text-danger" id="emiPaymentError"></span>
					  </div> 
					  <div class="form-group col-md-6">
						  <label>Due(s)</label>
						  <input class="form-control input-sm" id="emiDues" type="emiDues" name="emiDues" readonly>
						  <span class="text-danger" id="emiDuesError"></span>
					  </div> 
					  <div class="form-group col-md-6">
						  <label> Payment Type</label>
						  <select  name="paymentType" id="paymentType" class="form-control input-sm">
							<option value="Due">Due</option>
							<option value="Adjustment">Adjustment</option>
						  </select>
						  <span class="text-danger" id="userTypeError"></span>
					  </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btnSave">Save</button>
                
					</div> 
			    </form>
               <!-- modal body -->
              </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('javascript')
	<script>

		$(function () {
			$("select").select2();
		});
	// get Customers who have EMI
	$("#customer").change(function () {
		var partyId = $("#customer").val();
		$("#manageEmiTable").html('');
		$("#manageEmiDeletedTable").html('');
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
	});

	// get EMI against a Customer ID
	var paidYesIds = [];
	var UnpaidIds = [];
	function getInvoice() {
		var saleId = $("#invoice").val();
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
					 if(result[i]["is_paid"]  == "Yes" || result[i]["is_paid"]  == "Adjusted" ){
						paidYesIds[i] = result[i]["id"];
					}else{
						UnpaidIds[i] = result[i]["id"];
					}
					let button = '';
					let paymentStatus = '';
					let paymentDeletedStatus = '';
					if(result[i]["deleted"] == "Yes" ){
						button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="doSomething('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card"></i> Do Something </a></li></li></li><li class="action">';
						paymentDeletedStatus = '<i class="fas fa-times-circle" style="color:red; font-size:16px;" title="Active"></i>';
					} else if(result[i]["is_paid"] == "No"){
						button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="pay('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card "></i> Pay </a></li></li></li><li class="action">';
						 paymentStatus = '<i class="fa fa-envelope" aria-hidden="true" ></i>';
					}else{
						button = '<td style="width: 12%;"><div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i>  <span class="caret"></span></button><ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu"><li class="action"  onclick="doSomething('+result[i]["id"]+')"  ><a  class="btn" ><i class="fas fa-credit-card"></i> Do Something </a></li></li></li><li class="action">';
						paymentStatus = '<i class="fas fa-check-circle" style="color:green; font-size:16px;" title="Active"></i>';
					}
					button += '</li></li></ul></div></td>';
					var checkbox = '';
					if(result[i]["is_paid"] == "No" ){
						if(result[i]["deleted"] == "Yes"){
							serial2++;
							saleDeletedEmiData += "<tr class='text-center'><th style='color:red;' scope='row'>"+(serial2)+"</th><td style='color:red;'>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+" style='color:red;'>"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+" style='color:red;'>"+paymentDeletedStatus;
						}
						else{
							serial++;
							checkbox = '<input class="testCheck" id='+result[i]["id"]+'  type="checkbox" value='+result[i]["id"]+' id="isChecked">';
							saleEmiData += "<tr class='text-center'><td>"+checkbox+"</td><th scope='row'>"+(serial)+"</th><td>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+">"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+">"+paymentStatus+button;
						}
					}else{
						serial++;
						checkbox = '<input class="testCheck" id='+result[i]["id"]+'  type="checkbox" value='+result[i]["id"]+' id="isChecked" disabled>';
						saleEmiData += "<tr class='text-center'><td>"+checkbox+"</td><th scope='row'>"+(serial)+"</th><td>"+result[i]["tenure_payment_date"]+"</td><td id="+'tenuraAount'+result[i]["id"]+">"+result[i]["per_tenur_amount"]+"</td><td id="+result[i]["is_paid"]+">"+paymentStatus+button;
					}
					
					saleEmiData += "</tr>";
				}
				$("#manageEmiTable").html(saleEmiData);
				$("#manageEmiDeletedTable").html(saleDeletedEmiData);
				// to disable checkbox if EMI paid
				/*paidYesIds.forEach(myFunction);
					function myFunction(item, index) {
						//$('#'+item).prop('checked', true);
						$('#'+item).prop('disabled', true);
					}*/
				$("#totalTenureAmount").text(totalTenureAmount);
				$("#sale_id").val(saleId);
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

	// Select and Deselect all
	$('.allCheck').click(function(){
            if($(this).is(":checked")){
                console.log("All Checked.");
				$('input[type=checkbox]').prop('checked', true);
            }else{
                console.log("All  Unchecked.");
				$('input[type=checkbox]').prop('checked',false);
            }
			// to disable checkbox if EMI paid
			paidYesIds.forEach(myFunction);
			function myFunction(item, index) {
						$('#'+item).prop('disabled', true);
						$('#'+item).prop('checked',false);
					}
    });

	// check if all checkbox is Checked or Not	
	$(document).on("click",".testCheck",function() {
			UnpaidIds.forEach(myFunction);
					function myFunction(item, index) {
						if ($(item).attr('checked')) {
							$('.allCheck').prop('checked', true);
					}else{
						$('.allCheck').prop('checked', false);
					}
			}
	})	

	// show EMI payment form
	var emidIds = [];
	function addPayment() {
		$(':checkbox:checked').each(function(i){
			emidIds[i] = parseInt($(this).val());
        });
		if(emidIds.length<=0 || emidIds == "NaN"){
			Swal.fire("INCORRECT!","Please Select","error");
			return 0;
		}
		$("#modal").modal('show');
		$("#emiPayment").val(0);
		let saleId = $("#sale_id").val();
		let tenureAmount = 0;
        $(':checkbox:checked').each(function(i){
			emidIds[i] = $(this).val();
			if(emidIds[i] == "on"){				
				tenureAmount+=0;
			} else{
				tenureAmount+= parseFloat($("#tenuraAount"+emidIds[i]).text());
			}
        });
		$("#totalAmount").val(tenureAmount);
		$("#emiDues").val(tenureAmount);

	}

	// EMI payment calculation
	var totalAmount = 0;		
	$("#emiPayment").change(function () {
		 totalAmount = parseFloat($("#totalAmount").val());		
		 let emiPayment = parseFloat($("#emiPayment").val());
		 emiDues = 0;
		emiDues = totalAmount - emiPayment;
		$("#emiDues").val(emiDues);
	});

	$("#emiPaymentForm").submit(function (e){
		e.preventDefault();
		$("#modal").modal('hide');
		let emiPayment = parseFloat($("#emiPayment").val());
		var emiDuesAmount = $("#emiDues").val();
		let saleId = $("#invoice").val();
		var _token = $('input[name="_token"]').val();
		let paymentDate = $("#paymentDate").val();
		let paymentType = $("#paymentType").val();
		let voucherNo = $("#invoice :selected").text();
		let customerId = $("#customer").val();
		var emidIdsArray = [];
		$(':checkbox:checked').each(function(i){
			emidIdsArray[i] = parseInt($(this).val());
        });
		// remove NaN from Array if Exists
		emidIdsArray = emidIdsArray.filter(function (n) {
				return n || n === 0;
			});
		if(emiPayment<=0 || emidIdsArray.length<=0){
			Swal.fire("Wrong Input!","Please Enter A Valid Amount","error");
			return 0;
		}
		var fd = new FormData();
		fd.append('saleId',saleId);
		fd.append('customerId',customerId);
		fd.append('voucherNo',voucherNo);
		fd.append('emidIds',emidIdsArray);
		fd.append('totalAmount',totalAmount);
		fd.append('emiPayment',emiPayment);
		fd.append('emiDuesAmount',emiDuesAmount);
		fd.append('paymentType',paymentType);
		fd.append('paymentDate',paymentDate);
		fd.append('voucherNo',voucherNo);
		fd.append('_token',_token);

		Swal.fire({
            title: "Are you sure ?",
            text: "Confirm Payment!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#008000",
            confirmButtonText: "Yes, Confirm!",
            closeOnConfirm: false
        }).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url:"{{url('voucher/emiPaymentStore')}}",
					method:"POST",
					data:fd,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$("#loading").show();
					},
					success:function(result){
						$("#modal").modal('hide');
						 // table.ajax.reload(null, false);
						 //alert(JSON.stringify($emiPayment));
						Swal.fire("Saved!",result,"success");
						 // update EMI Table
						getInvoice();
						 // reset EMI payment form
						$('.allCheck').prop('checked', false); // Unchecks it
						$('#emiPaymentForm').trigger("reset");
					}, 
					error: function(response) {
						//$('#totalAmountError').text(response.responseJSON.errors.emidIds);
					},
					complete: function() {
						$("#loading").hide();
					}
		
				})	
			}
			else{
				Swal.fire("Cancelled", "Payment cancelled!", "error");
			}
        })
	})

	function pay(ids){
		let emiIdsArray = ids;
		let customerId = $("#customer").val();
		let _token = $('input[name="_token"]').val();
		if(emiIdsArray.length>0){
			// remove NaN from Array
		 	emiIdsArray = emiIdsArray.filter(function (n) {
				return n || n === 0;
			});
		}
		let fd = new FormData();
		fd.append('emiIdsArray',emiIdsArray);
		fd.append('customerId',customerId);
		fd.append('_token',_token);

			Swal.fire({
            title: "Are you sure ?",
            text: "Payment Confirm!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#008000",
            confirmButtonText: "Yes, Confirm!",
            closeOnConfirm: false
        }).then((result) => {
			if (result.isConfirmed) {
					$.ajax({
					url:"{{url('voucher/sale/payEmiStore')}}",
					method:"POST",
					data:fd,
					contentType: false,
					processData: false,
					success:function(result){
							Swal.fire("Paid!",result,"success");
							// update EMI Table
							getInvoice();
				}, beforeSend: function () {
					$('#loading').show();
				},complete: function () {
					$('#loading').hide();
				}, error: function(response) {
						alert(JSON.stringify(response));
				}
			})
			}
			else{
				Swal.fire("Cancelled", "Payment cancelled!", "error");
			}
        })
	}

	// both Multiple OR Single payment
	var emidIds = [];
	function paymentNow() {
		$(':checkbox:checked').each(function(i){
			emidIds[i] = parseInt($(this).val());
        });
		if(emidIds.length<=0 || emidIds == "NaN"){
			Swal.fire("Please Select Payment!","Thank You","error");
			return 0;
		}
		console.log(emidIds);
		pay(emidIds);
	}

	function clearCart(){
		Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, clear cart!",
            closeOnConfirm: false
        }).then((result) => {
			if (result.isConfirmed) {
				$("#customer").val("");
				$("#customer").change();
			}else{
			  Swal.fire("Cancelled", "You are  safe :)", "error");
			}
		})
	}
	
</script>
@endsection