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
                    <h3 class="card-title">Grade</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()"><i class="fa fa-plus circle"></i> Add Grade</button>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('gradeIndex') }}" searchPlaceholder="Search grades..." :sortOptions="['id' => 'ID', 'grade_name' => 'Name']" :defaultSort="'id'" :defaultDirection="'DESC'" />
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
                        <tbody>
                            @forelse ($grades as $i => $grade)
                            <tr>
                                <td>{{ $grades->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $grade->id }}" /></td>
                                <td>{{ $grade->grade_name }}</td>
                                <td>{{ $grade->note }}</td>
                                <td class="text-center">
                                    @if ($grade->status == 'Active')
                                        <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $grade->status }}"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $grade->status }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-grade">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             <i class="fas fa-cog"></i>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <a class="dropdown-item" href="#" onclick="editGrade({{ $grade->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                             <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $grade->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                         </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No grades found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $grades->links() }}
                    </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                    
                        @csrf
                        <div class="form-group mb-3 col-md-12">
                            <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Grade Name</label>
                            <input type="text" class="form-control" id="grade_name" name="grade_name" placeholder=" Write Grade Name" required>                                     
                            <span class="text-danger" id="grade_nameError"></span>
                        </div>
                        <div class="form-group mb-3 col-md-12">
                            <label for="carousalCaptionOffer" class="col-sm-3 col-form-label">Remarks</label>
                            <textarea type="text" class="form-control" id="note" name="note" placeholder="Write Short Note" ></textarea>
                            <span class="text-danger" id="noteError"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="editId" id="editId">
                    <div class="form-group mb-3 row">
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
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
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
                        location.reload();
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
                location.reload();
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
        confirmDeleteSwal({
            url      : "{{route('gradeDelete')}}",
            id       : id,
            itemName : 'Grade',
        });
    }
               



    </script>


@endsection