@extends('admin.master')
@section('title')
{{Session::get("companySettings")[0]['name'].' Voucher '.$type}}
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title text-capitalize">
                    @if($type == 'Payment')
                        Payment Voucher List
                    @elseif($type == 'Payment Received')
                        Received Voucher List
                    @elseif($type == 'Discount')
                        Discount Voucher List
                    @endif
                </h3>
                <div class="card-actions">
                    @if($type == 'Payment')
                    <a class="btn btn-primary" onclick="create('{{$type}}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Payment Voucher
                    </a>
                    @elseif($type == 'Payment Received')
                    <a class="btn btn-primary" onclick="create('{{$type}}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Received Voucher
                    </a>
                    @elseif($type == 'Discount')
                    <a class="btn btn-primary" onclick="create('{{$type}}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Discount Voucher
                    </a>
                    @endif
                    <a class="btn btn-outline-secondary" onclick="location.reload()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                        Refresh
                    </a>
                </div>
            </div>
            <div class="card-body">
                <x-filter-bar
                    route="{{ url('voucher/'.$type) }}"
                    searchPlaceholder="Search voucher..."
                    :sortOptions="['payment_vouchers.id' => 'ID', 'payment_vouchers.voucherNo' => 'Voucher No', 'payment_vouchers.paymentDate' => 'Date']"
                    :defaultSort="'payment_vouchers.id'"
                    :defaultDirection="'DESC'"
                />
                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">SL</th>
                                <th width="10%" class="text-center">Issue Date</th>
                                <th width="5%" class="text-center">Voucher Info</th>
                                <th width="20%" class="text-center">Party Info</th>
                                <th width="20%" class="text-center">Invoice Info</th>
                                <th width="7%" class="text-center">Method</th>
                                <th width="28%" class="text-center">Remarks</th>
                                <th width="5%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vouchers as $voucher)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}<input type="hidden" name="id" value="{{ $voucher->id }}" /></td>
                                    <td class="text-center">{{ $voucher->paymentDate }}</td>
                                    <td class="text-center">{{ $voucher->voucherNo }}</td>
                                    <td><b>Party: </b>{{ $voucher->name }}<br><b>Contact: </b>{{ $voucher->contact }}<br><b>Alt. Contact: </b>{{ $voucher->alternate_contact }}</td>
                                    <td>
                                        <b>Invoice: </b>{{ $voucherType }}-{{ $voucher->invoiceNo }}<br>
                                        <b>{{ $amountStatus }}: </b>{{ $voucher->amount }}
                                    </td>
                                    <td class="text-center">{{ $voucher->payment_method }}</td>
                                    <td>{{ $voucher->remarks }}</td>
                                    <td class="text-center">
                                        @php
                                            $showActions = true;
                                            if ($voucher->voucherType == 'Payable' || $voucher->voucherType == 'Party Payable') {
                                                $showActions = false;
                                            } elseif (($voucher->voucherType == 'Payment' && $voucher->purchase_id != '') || ($voucher->voucherType == 'Payment Received' && $voucher->sales_id != '')) {
                                                $showActions = false;
                                            }
                                        @endphp
                                        @if($showActions)
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#" onclick="printPaymentReceivedVoucher({{ $voucher->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $voucher->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No vouchers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $vouchers->links() }}
            </div>
            </div>
        </section>
    </div>

    <!-- modal -->
<div class="modal fade" id="modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="voucherForm" method="POST" enctype="multipart/form-data" action="#">
			<div class="modal-header">
				@if($type == 'Payment')
				<h4 class="modal-title float-left"> Add Payment Voucher</h4>
				@elseif($type == 'Payment Received')
				<h4 class="modal-title float-left"> Add Received Voucher</h4>
				@elseif($type == 'Discount')
				<h4 class="modal-title float-left"> Add Discount Voucher</h4>
				@endif

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">

				<div class="row">
			  		@csrf

				  <input type="hidden" name="id">
				  @if($type == 'Payment')
					<div class="form-group col-md-4">
                          <label > Party Name</label><br>
                          <select  name="party_id" id="party_id" class="form-control " onchange="getSupplierDue()">
                              <option value="0" selected disabled>Select Party</option>
                              @foreach($suppliers as $party)
                              <option value="{{$party->id}}">{{$party->name}}</option>
                              @endforeach
                          </select>
                          <span class="text-danger" id="party_idError"></span>
                      </div>
				@else
					<div class="form-group col-md-4">
                          <label > Party Name 45454</label><br>
                          <select  name="party_id" id="party_id" class="form-control " onchange="getproject()">
                              <option value="0" selected disabled>Select Party</option>
                              @foreach($parties as $party)
                              <option value="{{$party->id}}">{{$party->name}}</option>
                              @endforeach
                          </select>
                          <span class="text-danger" id="party_idError"></span>
                      </div>

				@endif


				@if($type == 'Payment')
				<div class="form-group col-md-4 d-none">
					  <label>Project </label>
					  <select class="form-control" id="project_id" name="project_id" onchange="loadOrder()">
							<option value='0' selected='true'> Select Project </option>
							<option value='' ></option>

					  </select>
					  <span class="text-danger" id="project_idError"></span>
				  </div>
				  <div class="form-group col-md-4 d-none" >

					  <label >Work Order </label>
					  <select class="form-control " id="work_order_id" name="work_order_id" onchange="loadDue()" >
					  		<option value="">Select Work Order</option>

					  </select>
				  </div>
				@else
				  <div class="form-group col-md-4 d-none" >
					  <label>Project </label>
					  <select class="form-control" id="project_id" name="project_id" onchange="loadOrder()">
							<option value='0' selected='true'> Select Project </option>
					  </select>
					  <span class="text-danger" id="project_idError"></span>
				  </div>
				  <div class="form-group col-md-4 d-none" >

					  <label >Work Order </label>
					  <select class="form-control " id="work_order_id" name="work_order_id" onchange="loadDue()" >
					  		<option value="">Select Work Order</option>
					  </select>
				  </div>
  				@endif


				  <div class="form-group col-md-4">
					  <label >Date </label>
					  <input class="form-control" id="paymentDate" type="date" name="paymentDate" value="{{  todayDate()  }}" />
					  <span class="text-danger" id="paymentDateError"></span>
				  </div>

				  <div class="form-group col-md-4">

					  <label>Due </label>
					  <span id="currentDue" name="currentDue" class="btn-success form-control"></span>
					  <span class="text-danger" id="dueError"></span>
				  </div>
				  @if($type != 'Discount')
				  <div class="form-group col-md-4">

					  <label >Payment Method </label>
					  <select id="payment_method" name="payment_method" class="form-control input-sm" >
						<option value="">Select Payment Method</option>
						<option value="Cash" selected>Cash</option>

					  </select>
				  </div>
				  @elseif($type == 'Discount')
				  <input type="hidden" id="payment_method" name="payment_method" value="Discount">
				  @endif

				  <div class="form-group col-md-4">

					  <label >Amount ({{Session::get("companySettings")[0]['currency']}}) </label>
					  <input class="form-control  input-sm" id="amount" type="text" name="amount" placeholder="Write Amount" maxlength = "10" onkeyup="digitCheck()">
					  <span class="text-danger" id="creditError"></span>
				  </div>

				  <div class="form-group col-md-12">

					  <label >Remarks:</label>
					  <textarea class="form-control  input-sm" id="remark" type="text" name="remark" placeholder="Add Remarks" ></textarea>

					  <span class="text-danger" id="remarksError"></span>
				  </div>
				  <input type="hidden" name="type" id="type" value="{{$type}}" />
				</div>

		  		<div class="modal-footer">
				  	<button type="button" class="btn btn-secondery mr-auto" data-bs-dismiss="modal">X Close</button>
					<button type="submit" class="btn btn-primary btnSave" id="saveVoucher"><i class="fa fa-save"></i> Save</button>
			  	</div>
			</div>
		  </form>
		</div>
	</div>
</div>
@endsection

@section('javascript')

  <script>

	$(function () {
			//$("#party_id").select2();
			$("#party_id").select2({
				placeholder: "Select Project",
				dropdownParent: $("#modal"),
				allowClear: true,
				width:'100%'
			});
			$("#project_id").select2({
				placeholder: "Select Project",
				dropdownParent: $("#modal"),
				allowClear: true,
				width:'100%'
			});
			$("#work_order_id").select2({
				placeholder: "Select Project",
				dropdownParent: $("#modal"),
				allowClear: true,
				width:'100%'
			});


		});


		function digitCheck(){
			var amount = $('#amount').val();
				if (amount.length >= 10) {
					Swal.fire("Sorry", "Amount can't be more then 10 digit");
				}
		}



		function getSupplierDue(){
			var partyId=$('#party_id').val();
			//alert(partyId);
				$.ajax({
					url:"{{route('getSupplierDue')}}",
					method:"GET",
					data:{"partyId":partyId},
					datatype:"json",
					success:function(result){
					//alert(JSON.stringify(result));
					$("#currentDue").text(result.current_due);
					}, beforeSend: function () {
					$('#loading').show();
					},complete: function (){
					$('#loading').hide();
					}
				});
			}



		function getproject(){
                var partyId=$('#party_id').val();
                  $.ajax({
                      url:"{{route('getProjects')}}",
                      method:"GET",
                      data:{"partyId":partyId},
                      datatype:"json",
                      success:function(result){
                        //alert(JSON.stringify(result));
                        $("#project_id").html(result);
                      }, beforeSend: function () {
                      $('#loading').show();
                      },complete: function (){
                      $('#loading').hide();
                      }
                    });
              }


		function loadOrder(){
			var project_id = $("#project_id").val();
			//alert(project_id);
			if(project_id!='0'){
				$.ajax({
					url: "{{route('loadWorkOrder')}}",
					method:"GET",
					data:{"project_id":project_id},
					success:function(result){
						$("#work_order_id").html(result);
						//alert(JSON.stringify(result));

					}, error: function(response) {
							//alert(JSON.stringify(response));

						}, beforeSend: function () {
							$('#loading').show();
						},complete: function () {
							$('#loading').hide();
					}
				})
				}else{
					$("#work_order").html('');
				}
			}





      function create(type) {
        reset();
	  if(type == "Payment"){
			loadParty('Supplier');
		}else if(type == "Discount"){
			loadParty($("input[name='partyType']:checked").val());
		}else{
			loadParty('Customer');
		}
        $("#modal").modal('show');
      }
	  $("input[name='partyType']").change(function (){
		  loadParty($("input[name='partyType']:checked").val());
	  })


	  function loadParty(){

		//alert(type);
		var work_order_id = $("#work_order_id").val();
		$.ajax({
            url:"{{route('loadParties')}}",
            method:"GET",
			data:{"work_order_id":work_order_id,},
           // datatype:"json",
            success:function(result){
				//alert(JSON.stringify(result));
				//$("#party_id").html(result);
				/* for(var i = 0; i < result.length; i++){
					partyResult += "<option value='"+result[i]['id']+"'>"+result[i]['name']+" - "+result[i]['code']+"</option>";
				} */
				//$("#party_id").html(partyResult);
			}, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
		});
	}





	function loadDue(){
					var work_order_id = $("#work_order_id").val();
					//alert(party_id)
					if(work_order_id!='0'){
						$.ajax({
							url: "{{route('loadPartyDue')}}",
							method:"GET",
							data:{"work_order_id":work_order_id},
							datatype:"json",
							success:function(result){
								//  alert(JSON.stringify(result));
								//alert(result.current_due);
								$("#currentDue").text(result);
								//$("#currentDue").text(result.due);
								//$("#currentDue").val(result['current_due']);


							}, error: function(response) {
									//alert(JSON.stringify(response));

								}, beforeSend: function () {
									$('#loading').show();
								},complete: function () {
									$('#loading').hide();
							}
						})
						}else{
							$("#work_order").html('');
						}
				}





	$("#party_id").change(function (){
		var partyId = $("#party_id").val();
		if(partyId != ""){
			var _token = $('input[name="_token"]').val();
			var fd = new FormData();
			fd.append('id',partyId);
			fd.append('_token',_token);
			$.ajax({
				url:"{{url('purchase/supplierDue')}}",
				method:"POST",
				data:fd,
				contentType: false,
				processData: false,
				datatype:"json",
				success:function(result){
					$("#currentDue").text(result);
			  }, beforeSend: function () {
				  $('#loading').show();
			  },complete: function () {
				  $('#loading').hide();
			  }, error: function(response) {
				  $("#barcodeError").text("No such product available in your system");
					//alert(JSON.stringify(response));
			  }
			})
		}else{
			$("#currentDue").text("");
		}
	})

	$('#modal').on('shown.bs.modal', function() {
		$('#name').focus();
	})

	$('#editModal').on('shown.bs.modal', function() {
		$('#editName').focus();
	})




    $("#voucherForm").submit(function (e){
        e.preventDefault();
        clearMessages();
		var  project_id = $("#project_id").val();
		var  work_order_id = $("#work_order_id").val();
        var  party_id = $("#party_id").val();
        var  amount = $("#amount").val();
        var  payment_method = $("#payment_method").val();
        var  paymentDate = $("#paymentDate").val();
        var  type = $("#type").val();
        var  remarks = $("#remark").val();
        var  partyType = $("input[name='partyType']:checked").val()
        ///var  currentDue = $("#currentDue").val();
        var _token = $('input[name="_token"]').val();
        var fd = new FormData();
        fd.append('party_id',party_id);
		fd.append('project_id',project_id);
		fd.append('work_order_id',work_order_id);
        fd.append('amount',amount);
        fd.append('payment_method',payment_method);
        fd.append('paymentDate',paymentDate);
        fd.append('type',type);
        fd.append('partyType',partyType);
        fd.append('remarks',remarks);

        //fd.append('currentDue',currentDue);
        fd.append('_token',_token);
        $.ajax({
			url:"{{url('voucher/store')}}",
			method:"POST",
			data:fd,
			contentType: false,
			processData: false,
			datatype:"json",
			success:function(result){
				//alert(JSON.stringify(result));
                $("#modal").modal('hide');
                Swal.fire("Saved!",result.success,"success");
                location.reload();
				reset();
            }, error: function(response) {
				 //alert(JSON.stringify(response));
                $('#nameError').text(response.responseJSON.errors.name);
                $('#codeError').text(response.responseJSON.errors.code);
                $('#addressError').text(response.responseJSON.errors.address);
                $('#contactError').text(response.responseJSON.errors.contact);
                $('#creditError').text(response.responseJSON.errors.credit_limt);
				$('#partyError').text(response.responseJSON.errors.party_type);
				$('#remarksError').text(response.responseJSON.errors.remarks);
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }

        })
    })
	function clearMessages(){
		$('#nameError').text("");
		$('#codeError').text("");
		$('#addressError').text("");
		$('#contactError').text("");
		$('#creditError').text("");
		$('#partyError').text("");
	}
	function reset(){
		console.log("reset");
		$("#currentDue").val("");
		$("#amount").val("");
		$("#currentDue").text('0');
		$("#remark").val("");
		$("#contact").val("");
		$("#alternate_contact").val("");
		$("#credit_limt").val("");
	}

    function confirmDelete(id) {
        confirmDeleteSwal({
            url      : "{{route('voucher.delete')}}",
            id       : id,
            itemName : 'Voucher',
            onSuccess: function(result) {
                Swal.fire("Done!", result.success, "success");
                location.reload();
            },
        });
    }



	function printPaymentReceivedVoucher(id){
		//alert(id);
		window.open("{{url('voucher/invoice/')}}"+"/"+id);
	}


	Mousetrap.bind('ctrl+shift+n', function(e) {
		e.preventDefault();
		if($('#modal.in, #modal.show').length){

		}else{
			create();
		}
	});
	Mousetrap.bind('ctrl+shift+r', function(e) {
		e.preventDefault();
		location.reload();
	});
	Mousetrap.bind('ctrl+shift+s', function(e) {
		e.preventDefault();
		if($('#modal.in, #modal.show').length){
			$("#partyForm").trigger('submit');
		}else{
			//alert("Not Calling");
		}
	});
	Mousetrap.bind('ctrl+shift+u', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#editPartyForm").trigger('submit');
		}else{
			//alert("Not Calling");
		}
	});
	Mousetrap.bind('esc', function(e) {
		e.preventDefault();
		if($('#editModal.in, #editModal.show').length){
			$("#editModal").modal('hide');
		}else if($('#modal.in, #modal.show').length){
			$('#modal').modal('hide');
		}
	});

    </script>


 
@endsection
