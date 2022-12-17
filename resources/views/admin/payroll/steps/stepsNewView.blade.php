@extends('admin.master')
@section('title')
Admin Steps -View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                        <div class="card-header">
                            <h3 style="float: left;">Steps </h3>
                            <a href="#/" onclick="create()"  class="btn btn-primary btn-icon-split float-right"><i class="fas fa-plus"></i>Add Step</a> 
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="manageStepTable" width="100%" class="table table-bordered table-striped ">

                                <thead>
                                    <tr>
                                        <td width="6%">ID</td>

                                        <td>Steps Name</td>
                                        <td>Salary(Taka)</td>
                                        <td>Grade</td>
                                        <td>Note</td>
                                        <td>Sequence</td>
                                        <td width="8%">Status</td>
                                        <td width="8%">Action</td>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                            </table>
                        </div>
                    </div>
        </section>
    </div>





<!-- modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="stepFormAdd"  enctype="multipart/form-data">
                @csrf    
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Step</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
                    <div class="form-group row col-md-12">
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Sequence</label>
                                <input type="text" class="form-control" id="sequence" name="sequence" placeholder=" Write step sequence" required> <br>                                    
                                <span class="text-danger" id="sequenceError"></span>
                        </div>
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Grade Name</label>
                                <select class="form-control" id="grade_id" name="grade_id">
                                    <option value="" selected disabled>Choose Grade</option>
                                    @foreach($grades as $grd)
                                    <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                                    @endforeach                                   
                                </select>
                                <span class="text-danger" id="grade_idError"></span>
                        </div>                       
                    </div>
                    <div class="form-group row col-md-12">
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Step Name</label>
                            <input type="text" class="form-control" id="step_name" name="step_name" placeholder=" Write step Name" required> <br>                                    
                            <span class="text-danger" id="step_nameError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Salary(Taka) </label>
                            <input type="text" class="form-control"  id="salary_amount" name="salary_amount" placeholder=" Write Numeric Amount" required>  
                            <span class="text-danger" id="salary_amountError"></span>                                   
                        </div>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="carousalCaptionOffer">Remarks</label>
                        <textarea type="text" class="form-control" id="note" name="note" placeholder="Write Short Note" ></textarea>
                        <span class="text-danger" id="noteError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal"><i class="fa fa-close"></i>X Close</button>
                    <button type="submit" class="btn btn-primary float-right" ><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
            
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->












<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="stepEditForm" >
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Edit Step</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
                    <input type="hidden" name="editId" id="editId">

                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Sequence </label>
                                <input type="text" class="form-control"  id="editSequence" name="editSequence" >  
                                <span class="text-danger" id="editSequenceError"></span>                                   
                            </div>
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Grade Name</label>
                                <select class="form-control" id="editGrade_id" name="editGrade_id">
                                    <option value="" selected disabled>Choose Grade</option>
                                    @foreach($grades as $grd)
                                    <option value="{{$grd->id}}">{{$grd->grade_name}}</option>
                                    @endforeach                                   
                                </select>
                                <span class="text-danger" id="editGrade_idError"></span>
                            </div>                       
                        </div>

                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer" >Step Name</label>
                                <input class="form-control input-sm" id="editStepName" type="text" name="editStepName" required="">
                                <span class="text-danger" id="editStepNameError"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer" >Basic(Taka)</label>
                                <input type="text" class="form-control"  id="editSalary_amount" name="editSalary_amount"  required>  
                                <span class="text-danger" id="editSalary_amountError"></span>                                   
                            </div>
                        </div>
                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer"> Note</label>
                                <textarea class="form-control input-sm" id="editNote" type="text" name="editNote" ></textarea>
                                <span class="text-danger" id="editNoteError"></span>
                            </div>
                            <div class="col-md-6">
                                <label  for="carousalCaptionOffer"> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary btnUpate float-right"><i class="fa fa-save"></i> Update</button>
                </div>
            </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection



@section('contentJavaScripts')
<script>

        /*Modal Show*/
        function create() {
                reset();
                $("#modal").modal('show');
                }
                $('#modal').on('shown.bs.modal', function() {
                    $('#grade_name').focus();
                })

        /* table Data*/
        var table;
                $(document).ready(function() {
                    table = $('#manageStepTable').DataTable({
                        'ajax': "{{route('getSteps')}}",
                        processing:true,
                    });
                });





        /* store data*/
        $('#stepFormAdd').submit(function(e){
                    e.preventDefault();
                    clearMessages();              
                    var step_name = $("#step_name").val();
                    var salary_amount = $("#salary_amount").val();
                    var grade_id = $("#grade_id").val();
                    var note = $("#note").val();
                    var sequence = $("#sequence").val();
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('step_name',step_name);
                    fd.append('sequence',sequence);
                    fd.append('salary_amount',salary_amount);
                    fd.append('grade_id',grade_id);
                    fd.append('note',note);
                    fd.append('_token',_token);
                    $.ajax({
                    url:"{{route('stepStore')}}",
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
                    }, 
                    error: function(response) {
                        //alert(JSON.stringify(response));
                        $('#step_nameError').text(response.responseJSON.errors.step_name);
                        $('#salary_amountError').text(response.responseJSON.errors.salary_amount);
                        $('#grade_idError').text(response.responseJSON.errors.grade_id);
                        $('#noteError').text(response.responseJSON.errors.note);
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }

                })
            });

        /*Reset*/
        function reset(){
        $("#step_name").val("");
        $("#sequence").val("");
        $("#salary_amount").val("");
        $("#note").val("");
        clearMessages();
        }

        function clearMessages(){
		$('#step_nameError').text("");
        $('#salary_amountError').text("");
        $('#sequenceError').text("");
		$('#noteError').text("");
	    }







        function editStep(id){
		
        $.ajax({
            url:"{{route('editStep')}}",
            method:"GET",
            data:{"id":id},
            datatype:"json",
            success:function(result){
                $("#editModal").modal('show');
                $("#editStepName").val(result.step_name);
                $("#editSalary_amount").val(result.salary_amount);
                $("#editGrade_id").val(result.grade_id);
                $("#editNote").val(result.note);
                $("#editId").val(result.id);
                $("#editSequence").val(result.priority);
                if(result.status){
					$("#editStatus").val(result.status);
                }else{
					$("#editStatus").val("Inactive");
                }
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }
        });
    }











    $("#stepEditForm").submit(function (e){
        e.preventDefault();
        var step_name = $("#editStepName").val();
        var salary_amount = $("#editSalary_amount").val();
        var sequence = $("#editSequence").val();
        var grade_id = $("#editGrade_id").val();
        var note = $("#editNote").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();
        var fd = new FormData();
        fd.append('step_name',step_name);
        fd.append('salary_amount',salary_amount);
        fd.append('sequence',sequence);
        fd.append('grade_id',grade_id);
        fd.append('note',note);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);
        $.ajax({
            url:"{{route('stepUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(result);
                $("#editModal").modal('hide');
                Swal.fire("Updated Step!",result.success,"success");
                table.ajax.reload(null, false);
             }, error: function(response) {
                $('#editStepNameError').text(response.responseJSON.errors.step_name);
                $('#editGrade_idError').text(response.responseJSON.errors.grade_id);
                $('#editSalary_amountError').text(response.responseJSON.errors.salary_amount);
                $('#editSequenceError').text(response.responseJSON.errors.sequence);
                $('#editNoteError').text(response.responseJSON.errors.note); 
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });


    
       












    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete step!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('stepDelete')}}",
                method: "POST",
                data: {"id":id, "_token":_token},
                success: function (result) {
                    Swal.fire("Done!",result.success,"success");
                    table.ajax.reload(null, false);
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });
        }else{
          Swal.fire("Cancelled", "Your imaginary Step is safe :)", "error");
        }
      })
        
    }
    




    </script>
@endsection