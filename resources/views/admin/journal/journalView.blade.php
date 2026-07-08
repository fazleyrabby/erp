@extends('admin.master')
@section('title')
    Admin Journal List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Journal List</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Journal
                        </button>
                    </div>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('journalView') }}" searchPlaceholder="Search journals..." :sortOptions="['id' => 'ID', 'transaction_date' => 'Date', 'reference' => 'Reference']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <div class="table-responsive">
                        <table class="table table-vcenter table-bordered" id="manageJournalTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%" class="text-center">Sl</th>
                                    <th width="30%" class="text-center">Transaction Date</th>
                                    <th width="30%" class="text-center">Reference</th>
                                    <th width="20%" class="text-center">Particulars</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="5%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($journals as $i => $journal)
                                <tr>
                                    <td class="text-center">{{ $journals->firstItem() + $i }}<input type="hidden" name="id" value="{{ $journal->id }}" /></td>
                                    <td class="text-center">{{ $journal->transaction_date }}</td>
                                    <td class="text-center">{{ $journal->reference }}</td>
                                    <td>{{ $journal->internal_information }}</td>
                                    <td class="text-center">
                                        @if ($journal->status == 'Active')
                                            <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $journal->status }}"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $journal->status }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 <i class="fas fa-cog"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                 <a class="dropdown-item" href="#/" onclick="journalDetails({{ $journal->id }})"><i class="fa fa-file-pdf me-2"></i> See Details</a>
                                             </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No journals found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $journals->links() }}
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <button type="button" class="btn  btn-secondary mr-auto" data-bs-dismiss="modal">x
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
                location.reload();
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
        confirmDeleteSwal({
            url      : "{{route('coaDelete')}}",
            id       : id,
            itemName : 'Group',
            onSuccess: function(result) {
                Swal.fire("Done!", result.success, "success");
                location.reload();
            },
        });
    }



    </script>
@endsection
