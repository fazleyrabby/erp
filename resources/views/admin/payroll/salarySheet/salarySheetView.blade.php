@extends('admin.master')
@section('title')
Admin Salary Sheet -View
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
                    <h3 class="card-title">Salary Sheet</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()"><i class="fa fa-plus circle"></i> Add Salary Sheet</button>
                    </div>
                </div><!-- /.card-header -->
                <h3 class="text-center text-success">{{Session::get('message')}}</h3>

                <!-- /.card-header -->
                <div class="card-body">
                    <x-filter-bar route="{{ route('SalarySheetView') }}" searchPlaceholder="Search salary sheets..." :sortOptions="['id' => 'ID', 'sheet_name' => 'Name']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <table width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td width="6%">SL</td>
                                <td>Salay Sheet Name</td>
                                <td width="10%">Status</td>
                                <td width="8%">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $i => $sheet)
                            <tr>
                                <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $sheet->id }}" /></td>
                                <td>{{ $sheet->sheet_name }}</td>
                                <td>
                                    @if ($sheet->status == 'Active')
                                    <center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $sheet->status }}"></i></center>
                                    @else
                                    <center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $sheet->status }}"></i></center>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-grade">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#" onclick="editSalarySheet({{ $sheet->id }})"><i class="fas fa-edit me-2"></i> Edit </a>
                                            <a class="dropdown-item" href="#" onclick="confirmDelete({{ $sheet->id }})"><i class="fas fa-trash me-2"></i> Delete </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No salary sheets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="SalaySheetFormStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Salary Sheet</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                        @csrf
                        <div class="form-group mb-3 col-md-12">
                            <label for="carousalCaptionOffer" class="col-sm-5 col-form-label">Salary Sheet Name</label>
                            <input type="text" class="form-control" id="sheet_name" name="sheet_name" placeholder=" Write Salary Sheet Name" required>                                     
                            <span class="text-danger" id="sheet_nameError"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal"><i class="fa fa-close"></i>X Close</button>
                    <button type="submit" class="btn btn-primary " id="saveSheet"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSalarySheetForm" method="POST" enctype="multipart/form-data" action="#">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Salary Sheet</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                    <div class="row g-3">
                        @csrf
                        <input type="hidden" name="editId" id="editId">
                        <div class="form-group mb-3 col-md-6">
                            <label>Salary Sheet Name</label>
                            <input class="form-control input-sm" id="editSalary_sheet" type="text" name="editSalary_sheet" required="">
                            <span class="text-danger" id="editSalary_sheetError"></span>
                        </div>

                        <div class="form-group mb-3 col-md-6">
                            <label> Status</label>
                            <select id="editStatus" name="editStatus" class="form-control input-sm">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
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
            
            $("#modal").modal('show');
            }
            $('#modal').on('shown.bs.modal', function() {
                $('#sheet_name').focus();
            })


            




                /* store data*/
                $('#SalaySheetFormStore').submit(function(e){
                    e.preventDefault();
                    
                    var sheet_name = $("#sheet_name").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('sheet_name',sheet_name);
                    fd.append('_token',_token);
                    
                    $.ajax({
                    url:"{{route('salarySheetStore')}}",
                    method:"POST",
                    data:fd,
                    contentType: false,
                    processData: false,
                    datatype:"json",
                    success:function(result){
                    $("#modal").modal('hide');
                    Swal.fire("Saved!",result.success,"success");
                    location.reload();                    
                    }, 
                    error: function(response) {
                        //alert(JSON.stringify(response));
                        $('#sheet_nameError').text(response.responseJSON.errors.sheet_name);
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            });





        function editSalarySheet(id){
            
            $.ajax({
                url:"{{route('editSalarySheet')}}",
                method:"GET",
                data:{"id":id},
                datatype:"json",
                success:function(result){
                    $("#editModal").modal('show');
                    $("#editSalary_sheet").val(result.sheet_name);
                    $("#editId").val(result.id);

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






        $("#editSalarySheetForm").submit(function (e){
        e.preventDefault();
        
        var sheet_name = $("#editSalary_sheet").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('sheet_name',sheet_name);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);

        $.ajax({
            url:"{{route('sheetUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
                success:function(result){
                
                $("#editModal").modal('hide');
                Swal.fire("Updated Sheet!",result.success,"success");
                location.reload();
            }, error: function(response) {
                $('#editSalary_sheetError').text(response.responseJSON.errors.sheet_name);              
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });





    function confirmDelete(id) {
        confirmDeleteSwal({
            url      : "{{route('salarySheetDelete')}}",
            id       : id,
            itemName : 'Salary Sheet',
        });
    }











    </script>


@endsection