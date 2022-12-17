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
    <div class="container-fluid">
        <section class="content box-border">
          <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
            <div class="card-header">
				@if($type == 'Payment')
              	<h3 style="float:left;"> Payment Voucher List </h3>
				@elseif($type == 'Payment Received')
				<h3 style="float:left;"> Received Voucher List </h3>
				@elseif($type == 'Discount')
				<h3 style="float:left;"> Discount Voucher List </h3>
				@endif

				@if($type == 'Payment')
				<a class="btn btn-primary float-right" onclick="create('{{$type}}')"><i class="fa fa-plus circle"></i> Add Payment Voucher</a>
				@elseif($type == 'Payment Received')
				<a class="btn btn-primary float-right" onclick="create('{{$type}}')"><i class="fa fa-plus circle"></i> Add Received Voucher</a>
				@elseif($type == 'Discount')
				<a class="btn btn-primary float-right" onclick="create('{{$type}}')"><i class="fa fa-plus circle"></i> Add Discount Voucher</a>
				@endif
               
				<a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i class="fas fa-sync"></i> Refresh </a>
            </div><!-- /.card-header -->
            <div class="card-body">
               <div class="col-md-12"><!--data listing table-->
                    <div class="table-responsive">
                        <table id="manageVoucherTable" width="100%" class="table table-bordered table-hover ">
                            <thead>
                            <tr>
                                <td width="5%" class="text-center">SL</td>
            					<td width="10%" class="text-center">Issue Date</td>
                                <td width="5%" class="text-center">voucher Info</td>
                                <td width="20%" class="text-center">Party Info</td>
                                <td width="20%" class="text-center">Invoice Info</td>
                                <td width="7%" class="text-center">Amount</td>
                                <td width="28%" class="text-center">Remarks</td>
                                <td width="5%"  class="text-center">Actions</td>
                            </tr>
                            </thead>
                            <tbody id="tableViewParty">
            
                            </tbody>
                        </table>
                    </div>
                    <!--data listing table-->
                </div>
            </div>
          <!-- /.card -->
            </div>
          <!-- /.card -->
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
			    
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-window-close"></i></button>
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
				  	<button type="button" class="btn btn-secondery mr-auto" data-dismiss="modal">X Close</button>
					<button type="submit" class="btn btn-primary btnSave" id="saveVoucher"><i class="fa fa-save"></i> Save</button>
			  	</div>
			</div>
		  </form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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

	var table;
	$(document).ready(function() {
		table = $('#manageVoucherTable').DataTable({
			'ajax': "{{url('voucher/view/'.$type)}}",
			processing:true,
			
		});
	});





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
                table.ajax.reload(null, false);
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
        Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete Voucher!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('voucher.delete')}}",
                method: "POST",
                data: {"id":id, "_token":_token},
                success: function (result) {
					alert(JSON.stringify(result));
                    Swal.fire("Done!",result.success,"success");
                    table.ajax.reload(null, false);
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });
        }else{
          Swal.fire("Cancelled", "Your imaginary Voucher is safe :)", "error");
        }
      })
        
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
	function reloadDt(){
		if($('#modal.in, #modal.show').length){
			
		}else if($('#editModal.in, #editModal.show').length){
			
		}
		else{
			table.ajax.reload(null, false);
		}
	}
	Mousetrap.bind('ctrl+shift+r', function(e) {
		e.preventDefault();
		reloadDt();
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