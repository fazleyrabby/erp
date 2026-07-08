@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name'].' Voucher '.$type}}
@endsection
@section('content')
    
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">EMI Voucher List (Paid)</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="{{url('voucher/payment/addEmiVoucher')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add EMI Voucher
                        </a>
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ url('voucher/payment/paidEmi') }}"
                        searchPlaceholder="Search EMI voucher..."
                        :sortOptions="['sales.id' => 'ID', 'sales.sale_no' => 'Sale No', 'sales.date' => 'Date']"
                        :defaultSort="'sales.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Voucher Info</th>
                                        <th>Customer Info</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}<input type="hidden" name="id" value="{{ $customer->sale_no }}" /></td>
                                            <td>
                                                Sale No#: {{ $customer->sale_no }}<br>
                                                Sale Date: {{ $customer->date }}<br>
                                                Total Tenure: {{ $customer->no_of_tenure }}
                                            </td>
                                            <td>
                                                <span id="customerInfo"><b>Customer Name: </b>{{ $customer->name }}<br><b>Contact: </b>{{ $customer->contact }}<br></span>
                                                <b>Alt. Contact: </b>{{ $customer->alternate_contact }}
                                            </td>
                                            <td>
                                                <b>Total: </b>{{ $customer->total_amount }}<br>
                                                <b>Discount: </b>{{ $customer->discount }}<br>
                                                <b>GrandTotal: </b>{{ $customer->grand_total }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary text-light" onclick="viewDetails({{ $customer->sale_id }})">
                                                        <i class="fas fa-lg fa-info-circle"></i> View Details
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No paid EMI vouchers found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ $customers->links() }}
                </div>
            </div>
        <!-- modal -->
	<div class="modal fade bd-example-modal-lg" id="modalForCompletedEmiView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	<form id="voucherForm" method="POST" enctype="multipart/form-data" action="#">
			<div class="modal-header">
        <h4 class="modal-title float-left"> EMI {{$type}} Voucher</h4><br>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="form-group mb-3 col-md-6">
						<div id="customerView">

						</div>
					</div>
					<!-- table -->
					<div class="form-group mb-3 col-md-12">
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
						<div class="form-group mb-3 col-md-12 mt-2">
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
				  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
				<!-- <button type="submit" class="btn btn-primary btnSave" id="saveVoucher">Save</button> -->
			</div>
		  </form>
    </div>
  </div>
</div><!-- / modal -->

@endsection

@section('javascript')

<script>


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
						paymentDeletedStatus = '<i class="fas fa-times-circle" style="color:red; font-size:16px;" title="Active"></i>';
					} else if(result[i]["is_paid"] == "No"){
						 paymentStatus = '<i class="fa fa-envelope" aria-hidden="true" ></i>';
					}else{
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
