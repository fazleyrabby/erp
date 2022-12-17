@extends('admin.master')
@section('title')
 Admin Transaction views
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
              <h3 style="float:left;"> Transaction List </h3>
                <a class="btn btn-primary float-right" onclick="create()"> <i class="fa fa-plus circle"></i> Add Transaction</a>
            </div><!-- /.card-header -->
            <div class="card-body">
               <div class="col-md-12">
            

            <!--data listing table-->
            <div class="table-responsive">
            <table id="manageTransactionTable" width="100%" class="table table-bordered table-hover ">
                <thead>
                <tr>
                    <td width="5%">SL</td>
                    <td width="10%">Transaction No</td>
                    <td width="25%">Balance Transferred To</td>
                    <td width="15%">Amount</td>
                    <td width="20%">Remarks</td>
                    <td width="10%">Transacion Date</td>
                    <td width="10%">Status</td>
                    <td width="5%">Actions</td>
                </tr>
                </thead>
                <tbody id="tableViewTransaction">

                </tbody>
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
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
				
                <div class="modal-header">
                <h4 class="modal-title float-left"> Add Transaction</h4>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                   
                </div> 
                <div class="modal-body">
					<div class="row">
                        
                      <div class="form-group col-md-12">
                        <p> <b>Transfer From</b></p>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Payment Method</label>
                                <select class="form-control input-sm" id="transfer_from_payment_method" type="text" name="transfer_from" onchange="getTransferFromSource()">
                                <option value=""selected disabled>Select payment method</option>
                                @foreach($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                                </select>
                                <span class="text-danger" id="transfer_fromError"></span>
                            </div>
                            <div class="col-md-3">
                                <label>Source</label>
                                <select class="form-control input-sm" id="transfer_from_source" type="text" name="source" onchange="getTransferFromAcNo()"></select>
                                <span class="text-danger" id="transfer_fromError"></span>
                            </div>
                            <div class="col-md-3">
                                <label>Account No</label>
                                <select class="form-control input-sm" id="transfer_from_ac_no" type="text" name="ac_no" onchange="getTransferFromAmount()">
                                </select>
                                <span class="text-danger" id="transfer_fromError"></span>
                            </div>
                            <div class="col-md-3">
                                <label>  Balance</label><br>
                                <span class="text-danger" id="transfer_fromError"></span>
                                <span class="text-success" style="display:none;" id="transfer_fromAmountText"> <span id="transfer_fromAmount"></span>{!!Session::get('companySettings')[0]['currency']!!}</span>
                            </div>
                        </div>
                         
                      </div>
                      <br>
                      <div class="form-group col-md-12">
                            <p><b>Transfer To</b></p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Payment Method</label>
                                    <select class="form-control input-sm" id="tbl_coa_to_id" type="text" name="tbl_coa_to_id"  onchange="getTransferToSource()">
                                    </select>
                                    <span class="text-danger" id="tbl_coa_to_idError"></span>
                                </div>
                                <div class="col-md-3">
                                    <label>Source</label>
                                    <select class="form-control input-sm" id="transfer_to_source" type="text" name="source" onchange="getTransferToAcNo()"></select>
                                    <span class="text-danger" id="transfer_toError"></span>
                                </div>
                                <div class="col-md-3">
                                    <label>Account No</label>
                                    <select class="form-control input-sm" id="transfer_to_ac_no" type="text" name="ac_no" onchange="getTransferToAmount()">
                                    </select>
                                    <span class="text-danger" id="transfer_toError"></span>
                                </div>
                                <div class="col-md-3">
                                    <label>  Balance</label><br>
                                    <span class="text-danger" id="transfer_fromError"></span>
                                    <span class="text-success" style="display:none;" id="transfer_toAmountText"> <span id="transfer_toAmount"></span>{!!Session::get('companySettings')[0]['currency']!!}</span>
                                </div>
                            </div>
                        </div>

                      <div class="form-group col-md-6">
                          <label>Amount</label>
                          <input class="form-control input-sm" id="amount" type="text" name="amount" placeholder="Amount" onkeyup="checkTransferLimit()">
                          <span class="text-danger" id="amountError"></span>
                      </div>
                      <div class="form-group col-md-6">
                          <label>Transaction Date</label>
                          <input class="form-control input-sm" id="transaction_date" type="date" name="transaction_date" >
                          <span class="text-danger" id="transaction_dateError"></span>
                      </div>
                      <div class="form-group col-md-12">
                          <label>Remarks</label>
                          <textarea class="form-control input-sm" id="remarkss" type="text" name="remarkss" ></textarea>
                          <span class="text-danger" id="remarksError"></span>
                      </div>
                  </div>
				
              </div>
			  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x Close</button>
                    <button type="submit" class="btn btn-secondary"  disabled id="saveBtnOnPage"><i class="fas fa-save"></i> Save</button>
                    <div id="checkAmounttext"></div>
                    <div id="saveButton"></div>
                  </div>
			  
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

   
@endsection

@section('javascript')

  <script>


$(function() {
            $("#transfer_from").select2({
                width:'100%',
                placeholder: "Select transfer from"
            });
            $("#transfer_to").select2({
                width:'100%',
                placeholder: "Select transfer to"
            });
            $("#transfer_from_payment_method").select2({
                width:'100%',
                placeholder: "Select Payment Method"
            });
            $("#transfer_from_source").select2({
                width:'100%',
                placeholder: "Select Source"
            });
            $("#transfer_from_ac_no").select2({
                width:'100%',
                placeholder: "Select Source"
            });
        });





      function create() {
          reset();
          $("#modal").modal('show');
      }
	$('#modal').on('shown.bs.modal', function() {
		$('#name').focus();
	})
	$('#editModal').on('shown.bs.modal', function() {
		$('#editName').focus();
	})





	var table;
	$(document).ready(function() {
		table = $('#manageTransactionTable').DataTable({
			'ajax': "{{route('getTransactions')}}",
			processing:true,
		});
	});






    function getTransferFromSource(){
        var payment_method=$('#transfer_from_payment_method').val();
        $.ajax({
            url:"{{route('getTransferFromSource')}}",
            method:"GET",
            data:{"payment_method":payment_method},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_from_source').html(result);
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }


    function getTransferToSource(){
        var payment_method=$('#tbl_coa_to_id').val();
        $.ajax({
            url:"{{route('getTransferToSource')}}",
            method:"GET",
            data:{"payment_method":payment_method},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_to_source').html(result);
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }



    function getTransferFromAcNo(){
        var source=$('#transfer_from_source').val();
        $.ajax({
            url:"{{route('getTransferFromAcNo')}}",
            method:"GET",
            data:{"source":source},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_from_ac_no').html(result);
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }


    function getTransferToAcNo(){
        var source1=$('#transfer_from_source').val();
        var source2=$('#transfer_to_source').val();
        $.ajax({
            url:"{{route('getTransferToAcNo')}}",
            method:"GET",
            data:{"source1":source1,"source2":source2},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_to_ac_no').html(result);
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }




    function getTransferFromAmount(){
        checkTransferLimit();
        var transfer_from=$('#transfer_from_ac_no').val();
        $.ajax({
            url:"{{route('getFromAmount')}}",
            method:"GET",
            data:{"transfer_from":transfer_from},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_fromAmount').text(result.fromAmount);
                $('#transfer_fromAmountText').show();
                $('#tbl_coa_to_id').html(result.data);
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }


    




    function getTransferToAmount(){
        var transfer_to=$('#transfer_to_ac_no').val();
        $.ajax({
            url:"{{route('getToAmount')}}",
            method:"GET",
            data:{"transfer_to":transfer_to},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $('#transfer_toAmount').text(result.toAmount);
                $('#transfer_toAmountText').show();
                $('#tbl_coa_to_idError').hide();
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }



    function checkTransferLimit(){
        var amount=$('#amount').val();
        var transfer_from=$('#transfer_from_ac_no').val();
        $.ajax({
            url:"{{route('checkTransferLimit')}}",
            method:"GET",
            data:{"amount":amount,'transfer_from':transfer_from},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
               $('#saveButton').html(result.button);
               $('#checkAmounttext').html(result.text);
               $('#saveBtnOnPage').hide();
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }, error: function(response) {
                //alert(JSON.stringify(response));
            }
        });
    }







    function saveTransactions(){
		var  transfer_from = $("#transfer_from_ac_no").val();
        var  tbl_coa_to_id = $("#transfer_to_ac_no").val();
        var  amount = $("#amount").val();
        var  transaction_date = $("#transaction_date").val();
        var  remarks = $("#remarkss").val();
        var  from_amount_bofore_transaction = $("#transfer_fromAmount").text();
        var  to_amount_bofore_transaction = $("#transfer_toAmount").text();
        var _token = $('input[name="_token"]').val();
     
		var fd = new FormData();
            fd.append('transfer_from',transfer_from);
            fd.append('tbl_coa_to_id',tbl_coa_to_id);
            fd.append('transaction_date',transaction_date);
            fd.append('amount',amount);
            fd.append('remarks',remarks);
            fd.append('from_amount_bofore_transaction',from_amount_bofore_transaction);
            fd.append('to_amount_bofore_transaction',to_amount_bofore_transaction);
            fd.append('_token',_token);


		$.ajax({
			url:"{{route('transactionStore')}}",
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
               
			},   beforeSend: function () {
				$('#loading').show();
			},	complete: function () {
				$('#loading').hide();
			},   error: function(response) {
                alert(JSON.stringify(response));
				$('#transfer_fromError').text(response.responseJSON.errors.transfer_from);
                $('#tbl_coa_to_idError').text(response.responseJSON.errors.tbl_coa_to_id);
                $('#amountError').text(response.responseJSON.errors.amount);
                $('#transaction_dateError').text(response.responseJSON.errors.transaction_date);
                 $('#remarksError').text(response.responseJSON.errors.remarks);
                    //Swal.fire("Error !", response.responseText, "error"); 
                    //alert(JSON.stringify(response));
			}
		})
	}




  



	function clearMessages(){
		$('#transfer_fromError').text("");
		$('#transfer_toError').text("");
		$('#amountError').text("");
		$('#transaction_dateError').text("");
		$('#remarksError').text("");
	}
	function editClearMessages(){
		$('#editNameError').text("");
		$('#editcodeError').text("");
		$('#editaddressError').text("");
		$('#editContactError').text("");
		$('#editAlternateError').text("");
		$('#editCreditError').text("");
		$('#editPartyError').text("");
	}
	function reset(){
		$("#transfer_from").val("");
		$("#tbl_coa_to_id").val("");
		$("#amount").val("");
		$("#transaction_date").val("");
		$("#remarkss").val("");
		
	}
	
    

    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete transaction!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('transactionDelete')}}",
                method: "POST",
                data: {"id":id, "_token":_token},
                success: function (result) {
                    //alert(JSON.stringify(result));
                    Swal.fire("Done!",result.success,"success");
                    table.ajax.reload(null, false);
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }, error: function(response) {
                    //alert(JSON.stringify(response));
			    }
            });
        }else{
          Swal.fire("Cancelled", "Your imaginary transaction is safe :)", "error");
        }
      })
        
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