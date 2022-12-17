@extends('admin.master')
@section('title')
Admin Grade -View
@endsection
@section('content')
<style type="text/css">

    h3{
        color: #66a3ff;
    }
</style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 style="float:left;"> Grade </h3>
                    <a class="btn btn-primary float-right" onclick="create()"><i class="fa fa-plus circle"></i> Add Grade</a>
                   
                </div><!-- /.card-header -->

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="manageGradeTable" width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td width="6%">SL</td>
                                <td>Grade Name</td>
                                <td>Note</td>
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
            <form id="GradeFormStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Grade</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
                    
                        @csrf
                        <div class="form-group col-md-12">
                            <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Grade Name</label>
                            <input type="text" class="form-control" id="grade_name" name="grade_name" placeholder=" Write Grade Name" required>                                     
                            <span class="text-danger" id="grade_nameError"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Remarks</label>
                            <textarea type="text" class="form-control" id="note" name="note" placeholder="Write Short Note" ></textarea>
                            <span class="text-danger" id="noteError"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-primary " id="saveGrade"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editGradeForm" method="POST" enctype="multipart/form-data" action="#">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Grade</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="editId" id="editId">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Grade Name</label>
                            <input class="form-control input-sm" id="editGradeName" type="text" name="editGradeName" required="">
                            <span class="text-danger" id="editGradeNameError"></span>
                        </div>
                        <div class="col-md-6">
                            <label> Status</label>
                            <select id="editStatus" name="editStatus" class="form-control input-sm">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label> Remarks</label>
                        <textarea class="form-control input-sm" id="editNote" type="text" name="editNote"  ></textarea>
                        <span class="text-danger" id="editNoteError"></span>
                    </div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary btnUpate" id="editGroup"><i class="fa fa-save"></i> Update</button>
                </div>
            </form>
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

            /*get data*/
            var table;
                $(document).ready(function() {
                    table = $('#manageGradeTable').DataTable({
                        'ajax': "{{route('getGradeData')}}",
                        processing:true,
                    });
                });


                    /* store data*/
            $('#GradeFormStore').submit(function(e){
                e.preventDefault();
                clearMessages();
                var grade_name = $("#grade_name").val();
                var note = $("#note").val();
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('grade_name',grade_name);
                fd.append('note',note);
                fd.append('_token',_token);
                $.ajax({
                    url:"{{url('payroll/gradeStore')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    datatype:"json",
                    success:function(result){
                        $("#modal").modal('hide');
                        Swal.fire("Saved!",result.success,"success");
                        table.ajax.reload(null, false);
                    }, error: function(response) {
                        //alert(JSON.stringify(response));
                        $('#grade_nameError').text(response.responseJSON.errors.grade_name);
                        $('#noteError').text(response.responseJSON.errors.note);
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }

                })
            });

                    





        function reset(){
        $("#grade_name").val("");
        $("#note").val("");
        clearMessages();
    }
    function editReset(){
		$("#editGradeName").val("");
		$("#editNote").val("");
		$("#editStatus").val("Active");
        editClearMessages();
	}
    function clearMessages(){
		$('#grade_nameError').text("");
		$('#noteError').text("");
	}
    function editClearMessages(){
		$('#editGradeNameError').text("");
		$('#editNoteError').text("");
	}


    
    function editGrade(id){
		editReset();
        $.ajax({
            url:"{{route('editGrade')}}",
            method:"GET",
            data:{"id":id},
            datatype:"json",
            success:function(result){
                $("#editModal").modal('show');
                $("#editGradeName").val(result.grade_name);
                $("#editNote").val(result.note);
                $("#editId").val(result.id);
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

    $("#editGradeForm").submit(function (e){
        e.preventDefault();
        editClearMessages();
        var grade_name = $("#editGradeName").val();
        var note = $("#editNote").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();
        var fd = new FormData();
        fd.append('grade_name',grade_name);
        fd.append('note',note);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);

        $.ajax({
            url:"{{route('gradeUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(result);
                $("#editModal").modal('hide');
                Swal.fire("Updated Grade!",result.success,"success");
                table.ajax.reload(null, false);
            }, error: function(response) {
                $('#editGradeNameError').text(response.responseJSON.errors.name);
                $('#editNoteError').text(response.responseJSON.errors.code);
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
            confirmButtonText: "Yes, delete Grade!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('gradeDelete')}}",
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
          Swal.fire("Cancelled", "Your imaginary Grade is safe :)", "error");
        }
      })
        
    }
               



    </script>


@endsection