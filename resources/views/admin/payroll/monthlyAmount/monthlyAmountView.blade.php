@extends('admin.master')
@section('title')
Admin Monthly Amount -View
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Amount</h3>
                            <div class="card-actions">
                                <button type="button" class="btn btn-primary" onclick="create()"><i class="fas fa-plus"></i> Add Monthly Amount</button>
                            </div>
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>
                        <div class="card-body">
                            <x-filter-bar route="{{ route('monthlyAmountIndex') }}" searchPlaceholder="Search monthly amounts..." :sortOptions="['id' => 'ID']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                            <table id="manageMonthlyAmountTable" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td width="6%">SL</td>
                                        <td>Employee Name</td>
                                        <td>Facility Name</td>
                                        <td>Amount</td>
                                        <td>Type</td> 
                                        <td width="8%">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($monthlyAmounts as $i => $monthlyAmount)
                                    <tr>
                                        <td>{{ $monthlyAmounts->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $monthlyAmount->id }}" /></td>
                                        <td>{{ $monthlyAmount->member_name }}</td>
                                        <td>{{ $monthlyAmount->facility_name }}</td>
                                        <td>{{ $monthlyAmount->amount }}</td>
                                        <td>{{ $monthlyAmount->type }}</td>
                                        <td>
                                            <div class="btn-grade">
                                                 <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                     <i class="fas fa-cog"></i>
                                                 </button>
                                                 <div class="dropdown-menu dropdown-menu-end">
                                                     <a class="dropdown-item" href="#" onclick="editMonthlyAmount({{ $monthlyAmount->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                     <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $monthlyAmount->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                                 </div>
                                             </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No monthly amounts found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $monthlyAmounts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Store modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="MonthlyAmountFormStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Monthly Amount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div> 
                <div class="modal-body">
                        @csrf

                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Employee Name</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    <option value="" selected disabled>Choose Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->member_name}}</option>
                                    @endforeach                                   
                                </select>        
                                <span class="text-danger" id="user_idError"></span>
                            </div>                       
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Facility Name</label> 
                                    <input class="form-control" type="text" name="facility_name"  id="facility_name" placeholder="Write Facility Name">                            
                                    <span class="text-danger" id="facility_nameError"></span>         
                            </div>
                        </div>

                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Write Amount" >
                                <span class="text-danger" id="amountError"></span>
                            </div>
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Month Year</label>
                                <select class="form-control" id="month_year" name="month_year">
                                    @php
                                        $inc = 36;
                                        for($i = 0; $i < 12; $i++)
                                        {
                                            echo '<option>'.Date('F-Y', strtotime(Date("Y-m-d").' '.$i.' Month -1 Day')).'</option>';
                                        }                                               
                                    @endphp
                                </select>
                                <span class="text-danger" id="month_yearError"></span>
                            </div>
                        </div>

                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-sm-12">
                                <label for="carousalCaptionOffer">Payment Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="" selected disabled>Choose Payment Type</option>
                                    <option value="Add">Add</option>
                                    <option value="Deduct">Deduct</option>                              
                                </select>
                                <span class="text-danger" id="typeError"></span>
                            </div>
                        </div>
                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-sm-12">
                            <label for="carousalCaptionOffer">Remarks</label>
                            <textarea type="text" class="form-control" id="cause" name="cause" placeholder="Write Short Remarks" ></textarea>
                            <span class="text-danger" id="causeError"></span>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
        </div>
        </div><!-- /.modal-content -->





<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMonthlyAmountForm" >
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Edit Monthly Amount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">

                        <input type="hidden" name="editId" id="editId">

                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Employee Name</label>
                                <select class="form-control" id="editUser_id" name="editUser_id" required>
                                    <option value="" selected disabled>Choose Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->member_name}}</option>
                                    @endforeach                                   
                                </select>        
                                <span class="text-danger" id="editUser_idError"></span>
                            </div>                       
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Facility Name</label>
                                <input type="text" class="form-control" id="editFacility_name" name="editFacility_name"  >                                     
                                <span class="text-danger" id="editFacility_nameError"></span>
                            </div>
                        </div>


                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-md-6">
                            <label for="carousalCaptionOffer">Amount</label>
                                <input type="text" class="form-control" id="editAmount" name="editAmount" >                                     
                                <span class="text-danger" id="editAmountError"></span>
                            </div>
                            <div class="col-md-6">
                            <label for="carousalCaptionOffer">Payment Type</label>
                                <select class="form-control" id="editType" name="editType">
                                    <option value="Add">Add</option>
                                    <option value="Deduct">Deduct</option>                              
                                </select>
                                <span class="text-danger" id="editTypeError"></span>
                            </div>
                        </div>




                        <div class="form-group mb-3 row col-md-12">
                            <div class="col-md-12">
                                <label for="carousalCaptionOffer">Cause</label>
                                <textarea type="text" class="form-control" id="editCause" name="editCause" placeholder="Write Short Cause" ></textarea>
                                <span class="text-danger" id="editCauseError"></span>
                            </div>
                            
                        </div>


                    </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary btnUpate float-right"><i class="fa fa-save"></i> Update</button>
                </div>
            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

@endsection




@section('contentJavaScripts')

<script>
            /*Modal Show*/
            function create() {
            reset();
            $("#modal").modal('show');
            }
            $('#modal').on('shown.bs.modal', function() {
                $('#user_id').focus();
            })

                /* store data*/
                $('#MonthlyAmountFormStore').submit(function(e){
                    e.preventDefault();
                    clearMessages() ; 
                    var user_id = $("#user_id").val();
                    var amount = $("#amount").val();
                    var facility_name = $("#facility_name").val();
                    var type = $("#type").val();
                    var cause = $("#cause").val();
                    var month_year = $("#month_year").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('user_id',user_id);
                    fd.append('amount',amount);
                    fd.append('facility_name',facility_name);
                    fd.append('type',type);
                    fd.append('cause',cause);
                    fd.append('month_year',month_year);
                    fd.append('_token',_token);
                    
                    $.ajax({
                    url:"{{route('monthlyAmountStore')}}",
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
                    }, 
                    error: function(response) {
                        alert(JSON.stringify(response));
                        $('#user_idError').text(response.responseJSON.errors.user_id);
                        $('#amountError').text(response.responseJSON.errors.amount);
                        $('#typeError').text(response.responseJSON.errors.type);
                        $('#facility_nameError').text(response.responseJSON.errors.facility_name);
                        $('#causeError').text(response.responseJSON.errors.cause);
                        
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            });





        function reset(){
            $("#facility_name").val("");
            $("#amount").val("");
            $("#user_id").val("");
            $("#type").val("");
            $("#cause").val("");
            
            clearMessages();
            }


        function clearMessages(){
            $('#facility_nameError').text("");
            $('#amountError').text("");
            $('#user_idError').text("");
            $('#typeError').text("");
            $('#causeError').text("");
            
        }



    function editReset(){
		$("#editFacility_name").val("");
		$("#editAmount").val("");
		$("#editUser_id").val("Active");
        $("#editType").val("Active");
        $("#editCause").val("Active");
        
        editClearMessages();
	}
    function editClearMessages(){
		$('#editFacility_nameError').text("");
		$('#editAmountError').text("");
        $('#editUser_idError').text("");
        $('#editTypeError').text("");
        $('#editCauseError').text("");
        
	}




                /*Edit */
                function editMonthlyAmount(id){
                    editReset();
                $.ajax({
                    url:"{{route('editMonthlyAmount')}}",
                    method:"GET",
                    data:{"id":id},
                    datatype:"json",
                    success:function(result){
                    
                        $("#editModal").modal('show');               
                        $("#editUser_id").val(result.user_id);
                        $("#editFacility_name").val(result.facility_name);
                        $("#editAmount").val(result.amount);
                        $("#editType").val(result.type);
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
        $("#editMonthlyAmountForm").submit(function (e){
        e.preventDefault();
        editClearMessages();
        var user_id = $("#editUser_id").val();
        var facility_name = $("#editFacility_name").val();
        var amount = $("#editAmount").val();
        var type = $("#editType").val();
        var cause = $("#editCause").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('facility_name',facility_name);
        fd.append('amount',amount);
        fd.append('user_id',user_id);
        fd.append('type',type);
        fd.append('cause',cause);
        fd.append('id',id);
        fd.append('_token',_token);

        $.ajax({
            url:"{{route('AmountDataUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(result);
                $("#editModal").modal('hide');
                Swal.fire("Updated Amount Data!",result.success,"success");
                location.reload();
            }, error: function(response) {
                $('#editFacility_nameError').text(response.responseJSON.errors.facility_name);
                $('#editAmountError').text(response.responseJSON.errors.amount);
                $('#editUser_idError').text(response.responseJSON.errors.user_id);
                $('#editTypeError').text(response.responseJSON.errors.type);
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
            url      : "{{route('amountDelete')}}",
            id       : id,
            itemName : 'amount',
        });
    }



</script>

@endsection