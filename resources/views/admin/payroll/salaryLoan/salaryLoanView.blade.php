@extends('admin.master')
@section('title')
Admin Loan Salary -View
@endsection
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Loan Salary</h3>
                            <div class="card-actions">
                                <a href="{{route('addSalaryLoan')}}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Loan</a>
                            </div>
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>
                        <div class="card-body">
                            <x-filter-bar route="{{ route('loanIndex') }}" searchPlaceholder="Search loans..." :sortOptions="['id' => 'ID', 'amount' => 'Amount']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                            <table id="manageLoanTable" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td width="6%">SL</td>
                                        <td>Employee Name</td>
                                        <td>Amount</td>
                                        <td>Tenure</td>
                                        <td>Installment</td>
                                        <td>Interest(%)</td>
                                        <td>Issue Date</td>
                                        <td width="8%">Status</td>
                                        <td width="8%">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($loans as $i => $loan)
                                    <tr>
                                        <td>{{ $loans->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $loan->id }}" /></td>
                                        <td>{{ $loan->member_name }}</td>
                                        <td>{{ $loan->amount }}</td>
                                        <td>{{ $loan->tenure }}</td>
                                        <td>{{ $loan->installment }}</td>
                                        <td>{{ $loan->percent }}%</td>
                                        <td>{{ $loan->applicable_from }}</td>
                                        <td class="text-center">
                                            @if ($loan->status == 'Active')
                                                <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $loan->status }}"></i>
                                            @else
                                                <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $loan->status }}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-grade">
                                                 <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     <i class="fas fa-cog"></i>
                                                 </button>
                                                 <div class="dropdown-menu dropdown-menu-end">
                                                     <a class="dropdown-item" href="#" onclick="editLoanAmount({{ $loan->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                     <a class="dropdown-item" href="#" onclick="tenureData({{ $loan->id }})"><i class="fas fa-calendar-alt me-2"></i> Tenure Data</a>
                                                     <a class="dropdown-item" href="#/" onclick="generatePdf({{ $loan->id }})"><i class="fas fa-file-pdf me-2"></i> Generate PDF</a>
                                                     <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $loan->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                                 </div>
                                             </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No loans found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $loans->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editLoanForm" >
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Edit Loan Amount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">	
                        <input type="hidden" name="editId" id="editId">
                        <input type="hidden" class="form-control" name="editUser_id" id="editUser_id">       
                       
                  
                    <div class="form-group mb-3 row col-md-12">
                        <input type="hidden" class="form-control" id="editAmount" name="editAmount" >                                     
                        <span class="hidden-danger" id="editAmountError"></span>
                    </div>


                    <div class="form-group mb-3 row">
                        <div class="col-sm-8">
                            <input type="hidden" class="form-control" id="editTenure" name="editTenure" >                                     
                            <span class="hidden-danger" id="editTenureError"></span>
                        </div>
                    </div>



                    <div class="form-group mb-3 row">
                        <label for="carousalCaptionOffer" class="col-sm-4 col-form-label">Issue Date </label>
                        <div class="col-sm-8"> 
                            <input type="date" class="form-control" id="editApplicable_from" name="editApplicable_from"  >                          
                            <span class="text-danger" id="editApplicable_fromError"></span>
                        </div>
                    </div>


                    <div class="form-group mb-3 row">
                        <label for="carousalCaptionOffer" class="col-sm-4 col-form-label">Cause</label>
                        <div class="col-sm-8">
                            <textarea type="text" class="form-control" id="editCause" name="editCause" placeholder="Write Short Cause" ></textarea>
                            <span class="text-danger" id="editCauseError"></span>
                        </div>
                    </div>


                </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
            <button type="submit" class="btn btn-primary btnUpate"><i class="fa fa-save me-1"></i>Update</button>
        </div>
        </form></div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->








<!-- Tenure Table Modal -->
<div class="modal fade" id="tenureModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
               
                <h4 class="modal-title">Tenure Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table  class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>                                                                           
                            <th>Month Year</th>                                      
                            <th>Installment(Taka)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="table_tenureData">
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



@endsection




@section('contentJavaScripts')

 <script>



            function reset(){
           
            $("#amount").val("");
            $("#user_id").val("");
            $("#applicable_from").val("");
            $("#cause").val("");
            
            clearMessages();
            }


        function clearMessages(){
           
            $('#amountError').text("");
            $('#user_idError').text("");
            $('#applicable_fromError').text("");
            $('#causeError').text("");
            
        }



        function editReset(){
		
		$("#editAmount").val("");
		$("#editUser_id").val("Active");
        $("#editApplicable_from").val("Active");
        $("#editCause").val("Active");
         
        editClearMessages();
	}
        function editClearMessages(){
            $('#editTenureError').text("");
            $('#editAmountError').text("");
            $('#editUser_idError').text("");
            $('#editApplicable_fromError').text("");
            $('#editCauseError').text("");
            
        }
                /*Edit */
                function editLoanAmount(id){
                   editReset();
                $.ajax({
                    url:"{{route('loanEdit')}}",
                    method:"GET",
                    data:{"id":id},
                    datatype:"json",
                    success:function(result){
                    
                        $("#editModal").modal('show');               
                        $("#editUser_id").val(result.user_id);
                        $("#editTenure").val(result.tenure);
                        $("#editAmount").val(result.amount);
                        $("#editApplicable_from").val(result.applicable_from);
                        $("#editCause").val(result.cause);
                        $("#editId").val(result.id);
                        
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                            }
                        });
                    }






        /*--------Update --------*/
        $("#editLoanForm").submit(function (e){
                e.preventDefault();
                editClearMessages();
                
                var user_id = $("#editUser_id").val();
                var tenure = $("#editTenure").val();
                var amount = $("#editAmount").val();
                var applicable_from = $("#editApplicable_from").val();
                var cause = $("#editCause").val();
                var _token = $('input[name="_token"]').val();
                var id = $("#editId").val();
                
                var fd = new FormData();
                
                fd.append('amount',amount);
                fd.append('user_id',user_id);
                fd.append('applicable_from',applicable_from);
                fd.append('tenure',tenure);
                fd.append('cause',cause);
                fd.append('id',id);
                fd.append('_token',_token);
                
                $.ajax({
                    url:"{{route('loanUpdate')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    success:function(result){
                        
                        $("#editModal").modal('hide');
                        Swal.fire("Updated Amount Data!",result.success,"success");
                        location.reload();
                    }, error: function(response) {
                        $('#editTenureError').text(response.responseJSON.errors.tenure);
                        $('#editAmountError').text(response.responseJSON.errors.amount);
                        $('#editUser_idError').text(response.responseJSON.errors.user_id);
                        $('#editApplicable_fromError').text(response.responseJSON.errors.applicable_from);
                        $('#editCauseError').text(response.responseJSON.errors.cause);
                      
                        
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            });





        /*delete*/
        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{route('loanDelete')}}",
                id       : id,
                itemName : 'Loan',
            });
        }


               
     
             /*tenure list */
             function tenureData(loan_id){
                 
                var tableData="";
                $.ajax({
                    url:"{{route('tenureView')}}",
                    method:"GET",
                    data:{"loan_id":loan_id},
                    datatype:"json",
                    success:function(result){
                        for(var i = 0; i < result.length; i++)
                        {
                            tableData += "<tr><td>"+(i+1)+"</td><td>"+result[i].month_year+"</td><td>"+result[i].installment+"</td><td>"+result[i].loan_status+"</td><td>" 
                                            +"<div class='btn-group'>"
                                                +"<button class='btn btn-primary dropdown-toggle btn-sm' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"
                                                    +"<i class='fas fa-cog'></i>"
                                                +"</button>"
                                                +"<div class='dropdown-menu dropdown-menu-end'>"
                                                    +"<a class='dropdown-item' href='#' onclick='changeStatus("+result[i].id+",\"Pending\")'> Pending </a>"
                                                    +"<a class='dropdown-item' href='#' onclick='changeStatus("+result[i].id+",\"Paid\")'> Paid    </a>"
                                                    +"<a class='dropdown-item' href='#' onclick='changeStatus("+result[i].id+",\"Reject\")'> Reject  </a>"
                                                +"</div>"
                                            +"</div>"
                                                +"</td></tr>";
                        }
                        $("#table_tenureData").html(tableData); 
                        $("#tenureModal").modal('show'); 

                       /* $("#tenureModal").modal('show'); 
                        $("#tableId").text(result.user_id);                      
                        $("#tableMonth_year").text(result.month_year); 
                        $("#tableInstallment").text(result.installment);
                        $("#tableStatus").text(result.status); */
                        
                       
                        
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                            }
                        });
                    }
            







                    function generatePdf(id){
                       
                       /*  alert(id); */
                        window.open("{{URL('payroll/tenure/Amount/generate/pdf')}}"+'/'+id);
                    }


                    function changeStatus(id,status){
                        var _token = $('meta[name="csrf-token"]').attr('content');
                       // alert(tenure_id);
                        $.ajax({
                            url:"{{route('tenurePending')}}",
                            method:"GET",
                            data:{"id":id,"status":status,"_token":_token},
                            datatype:"json",
                            success:function(result){
                                    Swal.fire("Done!",result.success,"success");
                                    tenureData(result.loan_id)
                                },  beforeSend: function () {
                                    $('#loading').show();
                                },  complete: function () {
                                    $('#loading').hide();
                                }                             
                            });
                        }


    </script>

@endsection