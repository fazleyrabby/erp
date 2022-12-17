@extends('admin.master')
@section('title')
    Admin Journal List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Journal List
                            <button type="button" class="btn  btn-primary float-right" onclick="create()"><i class="fa fa-plus-circle"></i>
                                Add Journal</button>
                    </h3>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer" id="manageJournalTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="30%" class="text-center">Transaction Date</td>
                                    <td width="30%" class="text-center">Reference</td>
                                    <td width="20%" class="text-center">Particulars</td>
                                    <td width="10%" class="text-center">Status</td>
                                    <td width="5%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div><!-- Card Content end -->

               

                 <!-- edit modal -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit COA</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        <i class="fas fa-window-close"></i></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editId">
                                    <div class="form-group">

                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" id="editName" name="name"
                                            placeholder="Name">
                                        <span class="text-danger" id="editNameError">{{ $errors->has('name') ? $errors->first('name') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Slug</label>
                                        <input type="text" class="form-control" id="editSlug" name="slug"
                                            placeholder="Slug">
                                        <span class="text-danger" id="editSlugError">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Code</label>
                                        <input type="text" class="form-control" id="editCode" name="code"
                                            placeholder="Code">
                                        <span
                                            class="text-danger" id="codeError">{{ $errors->has('code') ? $errors->first('code') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Parent</label>
                                        <select type="text" class="form-control" id="editParent_id" name="parent_id">
                                            <option value="0">Select Parent</option>
                                            @foreach($coas as $coa)
                                            <option value="{{$coa->id}}">{{$coa->name}}</option>
                                            @endforeach
                                        </select>
                                        <span
                                            class="text-danger" id="editParent_idError">{{ $errors->has('parent_id') ? $errors->first('parent_id') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Status</label>
                                        <select type="text" class="form-control" id="editStatus" name="Status">
                                            <option value=""selected disabled>Select Parent</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                        <span
                                            class="text-danger" id="editStatusError">{{ $errors->has('Status') ? $errors->first('parent_id') : '' }}</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn  btn-secondary mr-auto" data-dismiss="modal">x
                                        Close</button>
                                    <button  class="btn  btn-primary" onclick="updateCoa()"><i class="fa fa-save"></i>
                                        Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- create model End -->
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')
    <script>
         function create() {
            window.location.href = "{{ route('addJournal')}}";
        }

        $(document).ready(function() {
            table = $('#manageJournalTable').DataTable({
                'ajax': "{{route('getJournalData')}}",
                processing:true,
            });
        });







        function journalDetails(id){
            window.open("{{url('journal/details')}}"+"/"+id);
        }






        function editCOA(id){
        $.ajax({
            url:"{{route('editCOA')}}",
            method:"GET",
            data:{"id":id},
            datatype:"json",
            success:function(result){
                $("#editModal").modal('show');
                $("#editName").val(result.name);
                $("#editCode").val(result.code);
                $("#editSlug").val(result.slug);
                $("#editParent_id").val(result.parent_id);
                $("#editId").val(result.id);
                $("#editStatus").val(result.status);  
                if(result.status != ""){
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






    function updateCoa(){

        var id = $("#editId").val();
        var name = $("#editName").val();
        var code = $("#editCode").val();
        var slug = $("#editSlug").val();
        var parent_id = $("#editParent_id").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
            fd.append('name',name);
            fd.append('slug',slug);
            fd.append('code',code);
            fd.append('parent_id',parent_id);
            fd.append('status',status);
            fd.append('id',id);
            fd.append('_token',_token);
            
        $.ajax({
            url:"{{route('coaUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(JSON.stringify(result));
                $("#editModal").modal('hide');
                Swal.fire("Updated COA!",result.success,"success");
                table.ajax.reload(null, false);
            }, error: function(response) {
                //alert(JSON.stringify(response));
                $('#editNameError').text(response.responseJSON.errors.name);
                $('#editCodeError').text(response.responseJSON.errors.code);
                $('#editSlugError').text(response.responseJSON.errors.slug);
                $('#editParent_idError').text(response.responseJSON.errors.parent_id);
                $('#editStatusError').text(response.responseJSON.errors.status);
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    }







    function confirmDelete(id){
        Swal.fire({
            title: "Are you sure ?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete Group!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('coaDelete')}}",
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
          Swal.fire("Cancelled", "Your imaginary Group is safe :)", "error");
        }
      })
    }



    </script>
@endsection
