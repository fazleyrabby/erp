@extends('admin.master')
@section('title')
Admin Salary Sheet Instruction -View
@endsection
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Salary Sheet Instructions</h3>
                            <div class="card-actions">
                                <a href="{{route('sheetInstructionAdd')}}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Salary Instruction</a>
                            </div>
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>

                        <div class="card-body">
                            <x-filter-bar route="{{ route('SalaryInstructionView') }}" searchPlaceholder="Search instructions..." :sortOptions="['id' => 'ID']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                            <table width="100%" class="table table-vcenter table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="6%">SL</th>
                                        <th>Month Year</th>
                                        <th>Sheet Name</th>
                                        <th>Bank Name</th>
                                        <th>Branch</th>
                                        <th width="8%">Status</th>
                                        <th width="8%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $i => $instruction)
                                    <tr>
                                        <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $instruction->id }}" /></td>
                                        <td>{{ $instruction->month_year }}<input type="hidden" name="month_year" id="month_year" value="{{ $instruction->month_year }}" /><input type="hidden" name="sheet_id" id="sheet_id" value="{{ $instruction->sheet_id }}" /></td>
                                        <td>{{ $instruction->sheet_name }}</td>
                                        <td>{{ $instruction->bank_name }}</td>
                                        <td>{{ $instruction->branch_name }}</td>
                                        <td>
                                            @if ($instruction->status == 'Active')
                                            <center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $instruction->status }}"></i></center>
                                            @else
                                            <center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $instruction->status }}"></i></center>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-grade">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" onclick="viewInstruction({{ $instruction->id }})"><i class="fas fa-calendar-alt me-2"></i> View </a>
                                                    <a class="dropdown-item" href="#" onclick="editSalarySheetInstruction({{ $instruction->id }})"><i class="fas fa-edit me-2"></i> Edit </a>
                                                    <a class="dropdown-item" href="#" onclick="confirmDelete({{ $instruction->id }})"><i class="fas fa-trash me-2"></i> Delete </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No instructions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- edit modal -->
<div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
			<form id="editSalarySheetInstructionForm" >
                <div class="modal-header">
                    <h4 class="modal-title1" >Edit Sheet Instruction</h4>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-window-close"></i></button>
                </div> 
                <div class="modal-body">
                      <input type="hidden" name="editId" id="editId">
                     
                <div class="col-md-12">
                    <div class="row" >
                        <div class="form-group mb-3 col-md-6">
                            <label for="carousalCaptionOffer">Month Year</label>
                                <select class="form-control col-md-12" id="editMonth_year" name="editMonth_year">
                                @php
                                    $inc = 36;
                                    for($i = 0; $i < 36; $i++)
                                    {
                                        echo '<option>'.Date(Session::get('companySettings')[0]['month_year'], strtotime(Date("Y-m-d").' '.$i.' Month -1 Day')).'</option>';
                                    }   
                                @endphp
                                </select>
                                <span class="text-danger" id="editMonth_yearError"></span>
                        </div>
    

                        <div class="form-group mb-3 col-md-6">
                            <label for="carousalCaptionOffer" >Sheet Name</label>
                                <select class="form-select form-select-sm" id="editSheet_id" name="editSheet_id" required>
                                <option value="" selected disabled>Choose Sheet</option>
                                @foreach($sheets as $sheet)
                                <option value="{{$sheet->id}}">{{$sheet->sheet_name}}</option>
                                @endforeach                                   
                                </select>        
                                <span class="text-danger" id="editSheet_idError"></span>                      
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label for="carousalCaptionOffer" >Bank Name</label>
                            <input type="text" class="form-control form-control-sm" id="editBank_name" name="editBank_name" placeholder=" Write Bank Name" >                                     
                            <span class="text-danger" id="editBank_nameError"></span>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label for="carousalCaptionOffer" >Branch Name</label>
                            <input type="text" class="form-control form-control-sm" id="editBranch_name" name="editBranch_name" placeholder=" Write Branch Name" >                                     
                            <span class="text-danger" id="editBranch_nameError"></span>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label for="carousalCaptionOffer" >Account No</label>
                            <input type="text" class="form-control form-control-sm" id="editMother_account_no" name="editMother_account_no" placeholder=" Write Account Number">                                     
                            <span class="text-danger" id="editMother_account_noError"></span>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label > Status</label>
                            <select id="editStatus" class="form-control form-control-sm" name="editStatus" >
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group mb-3 ">
                            <label >Footer Description</label>
                            <textarea  class="form-control ckeditor" id="editFooter_instruction" name="editFooter_instruction"></textarea>
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label >Letter Body</label>
                            <textarea  class="form-control ckeditor" id="editLetter_body" name="editLetter_body"></textarea>
                        </div>
                        
                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                        <button type="submit" class="btn btn-primary btnUpate" id="editGroup"><i class="fa fa-save"></i> Update</button>
                    </div>

                </div>
                

			</form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



@endsection





@section('contentJavaScripts')

<script>
            
            




                function viewInstruction(id){ 
                    var month_year = $("#month_year").val();
                    var sheet_id = $("#sheet_id").val();
                    window.open("{{URL('payroll/salary/instruction/view/details/data/')}}"+'/?month_year='+month_year+'&sheet_id='+sheet_id+'&id='+id );
                }
                








                /* Edit Salary Sheet Instruction */
          function editSalarySheetInstruction(id){
            $.ajax({
                url:"{{route('editSalarySheetInstruction')}}",
                method:"GET",
                data:{"id":id},
                datatype:"json",
                success:function(result){
                    $("#editModal").modal('show');
                    $("#editMonth_year").val(result.month_year);
                    $("#editSheet_id").val(result.sheet_id);
                   
                    $("#editBank_name").val(result.bank_name);
                    $("#editBranch_name").val(result.branch_name);
                    $("#editMother_account_no").val(result.mother_account_no);
                    CKEDITOR.instances['editFooter_instruction'].setData(result.footer_instruction);
                    CKEDITOR.instances['editLetter_body'].setData(result.letter_body);
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








        $("#editSalarySheetInstructionForm").submit(function (e){
        e.preventDefault();

        var month_year = $("#editMonth_year").val();
        var sheet_id = $("#editSheet_id").val();
        
        var bank_name = $("#editBank_name").val();
        var branch_name = $("#editBranch_name").val();
        var mother_account_no = $("#editMother_account_no").val();
        var footer_instruction = $("#editFooter_instruction").val();
        var letter_body = $("#editLetter_body").val();
        var status  =$("#editStatus").val();
        var _token  =$('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('month_year',month_year);
        fd.append('sheet_id',sheet_id);
       
        fd.append('bank_name',bank_name);
        fd.append('branch_name',branch_name);
        fd.append('footer_instruction',footer_instruction);
        fd.append('letter_body',letter_body);
        fd.append('mother_account_no',mother_account_no);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);
        $.ajax({
            url:"{{route('sheetInstructionUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
                success:function(result){
                
                $("#editModal").modal('hide');
                Swal.fire("Updated Sheet!",result.success,"success");
                location.reload();
            }, error: function(response) {
                $('#editTotal_amountError').text(response.responseJSON.errors.total_amount); 
                $('#editMother_account_noError').text(response.responseJSON.errors.mother_account_no);             
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });  







    function confirmDelete(id) {
        confirmDeleteSwal({
            url      : "{{route('sheetInstructionDelete')}}",
            id       : id,
            itemName : 'Sheet',
        });
    }










</script>
@endsection