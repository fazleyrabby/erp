@extends('admin.master')
@section('title')
Admin Bonus Sheet -View
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
                <h3 class="card-title">Bonus</h3>
                <div class="card-actions">
                    <button type="button" class="btn btn-primary" onclick="create()"><i class="fa fa-plus circle"></i> Add Bonus</button>
                </div>
            </div><!-- /.card-header -->
            <h3 class="text-center text-success">{{Session::get('message')}}</h3>

            <div class="card-body">
                <x-filter-bar route="{{ route('bonusListView') }}" searchPlaceholder="Search bonuses..." :sortOptions="['id' => 'ID']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                <table width="100%" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td width="5%">SL</td>
                            <td width="20%">Bonus Name</td>
                            <td width="20%">Group</td>
                            <td width="10%">Month Year</td>
                            <td width="10%">Amount</td>
                            <td width="25%">Note</td>
                            <td width="5%">Status</td>
                            <td width="5%">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i => $bonus)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $bonus->id }}" /></td>
                            <td>{{ $bonus->bonus_name }}</td>
                            <td>{{ $bonus->groupName }}</td>
                            <td>{{ $bonus->month_year }}</td>
                            <td>{{ $bonus->amount }}</td>
                            <td>{{ $bonus->note }}</td>
                            <td>
                                @if ($bonus->status == 'Active')
                                <center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $bonus->status }}"></i></center>
                                @else
                                <center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $bonus->status }}"></i></center>
                                @endif
                            </td>
                            <td>
                                <div class="btn-grade">
                                     <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fas fa-cog"></i>
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-end">
                                         <a class="dropdown-item" href="#" onclick="editBonusList({{ $bonus->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                         <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $bonus->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                     </div>
                                 </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No bonuses found.</td>
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
            <form id="bonusSheetFormStore">
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Bonus Sheet</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>

                </div> 
                <div class="modal-body">
                    @csrf
                    <div class="form-group row col-md-12">
                        <label for="carousalCaptionOffer">Bonus Sheet Name</label>
                        <input type="text" class="form-control" id="bonus_name" name="bonus_name" placeholder=" Write Bonus Name">                                     
                        <span class="text-danger" id="bonus_nameError"></span>
                    </div>
                    <div class="form-group row col-md-12">
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Month Year</label>
                            <select class="form-control" id="month_year" name="month_year">   
                                <option value=""selected>Choose Month Year</option>                             
                                @foreach($salrysheets as $salrysheet)
                                <option value="{{$salrysheet->month_year}}">{{$salrysheet->month_year}}</option>
                                @endforeach
                            </select> 
                            <span class="text-danger" id="month_yearError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Issue Date</label>
                            <input type="date" class="form-control" id="applicable_from" name="applicable_from">                                     
                            <span class="text-danger" id="applicable_fromError"></span>
                        </div>
                    </div>
                    <div class="form-group row col-md-12">
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Group</label>
                            <select class="form-control" id="group_id" name="group_id">    
                                <option value="" selected>Choose group</option>                            
                                @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select> 
                            <span class="text-danger" id="group_idError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount in decimal or '%'">                                     
                            <span class="text-danger" id="amountError"></span>
                        </div>
                    </div>
                    <div class="form-group row col-md-12">
                        <label for="carousalCaptionOffer">Remarks</label>
                            <textarea type="text" class="form-control" id="note" name="note"> </textarea>                                    
                            <span class="text-danger" id="noteError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal"><i class="fa fa-close"></i>X Close</button>
                    <button type="submit" class="btn btn-primary float-right" ><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBonusListForm" method="POST" enctype="multipart/form-data" action="#">
                <div class="modal-header">
                 
                    <h4 class="modal-title">Edit Bonus List</h4>
                    <button type="button" class="close"
                            data-bs-dismiss="modal" aria-hidden="true"> X
                    </button>
                </div> 
                <div class="modal-body">

                    
                        @csrf
                        <input type="hidden" name="editId" id="editId">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Bonus Name</label>
                                <div>
                                    <input class="form-control input-sm" id="editBonus_name" type="text" name="editBonus_name" >
                                    <span class="text-danger" id="editBonus_nameError"></span>
                                </div>
                            </div>


                            <div class="form-group col-md-6">
                                <label>Month Year</label>
                                <div>
                                    <select class="form-control" id="editmonth_year" name="editmonth_year">   
                                       <option value=""selected disabled>Choose Month Year</option>                  
                                        @foreach($salrysheets as $salrysheet)
                                        <option value="{{$salrysheet->month_year}}">{{$salrysheet->month_year}}</option>
                                        @endforeach
                                    </select>  
                                   <!--  <input type="hidden" id="editmonth_year2" name="editmonth_year"> -->
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Issue Date</label>
                                <div>
                                    <input type="date" class="form-control" id="editApplicable_from" name="editApplicable_from">                                     
                                    <span class="text-danger" id="editApplicable_fromError"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Group</label>
                                <select class="form-control" id="editGroup_id" name="editGroup_id">    
                                    <option value="" selected>Choose group</option>                            
                                    @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select> 
                                <span class="text-danger" id="editGroup_idError"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Amount</label>
                                <input type="text" class="form-control" id="editAmount" name="editAmount" placeholder="Amount in decimal or '%'">                                     
                                <span class="text-danger" id="editAmountError"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Note</label>
                                <div>
                                    <textarea type="text" class="form-control" id="editNote" name="editNote"> </textarea>                                    
                                    <span class="text-danger" id="editNoteError"></span>
                                </div>
                            </div>
 
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary btnUpate" ><i class="fa fa-save"></i> Update</button>
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
                $('#bonus_name').focus();
            })










                /* store data*/
                $('#bonusSheetFormStore').submit(function(e){
                    e.preventDefault();
                    
                    var bonus_name = $("#bonus_name").val();
                    var month_year = $("#month_year").val();
                    var group_id = $("#group_id").val();
                    var amount = $("#amount").val();
                    var applicable_from = $("#applicable_from").val();
                    var note = $("#note").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('bonus_name',bonus_name);
                    fd.append('month_year',month_year);
                    fd.append('group_id',group_id);
                    fd.append('amount',amount);
                    fd.append('applicable_from',applicable_from);
                    fd.append('note',note);
                    fd.append('_token',_token);
                    
                    $.ajax({
                    url:"{{route('bonusDataStore')}}",
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
                        //alert(JSON.stringify(response));
                        $('#bonus_nameError').text(response.responseJSON.errors.bonus_name);
                        $('#month_yearError').text(response.responseJSON.errors.month_year);
                        $('#group_idError').text(response.responseJSON.errors.group_id);
                        $('#amountError').text(response.responseJSON.errors.amount);
                    }, beforeSend: function () {
                        $('#loading').show();
                    },complete: function () {
                        $('#loading').hide();
                    }
                })
            }); 


            function editBonusList(id){
                $.ajax({
                url:"{{route('editBonusSheet')}}",
                method:"GET",
                data:{"id":id},
                datatype:"json",
                success:function(result){
                    $("#editModal").modal('show');
                    $("#editBonus_name").val(result.bonus_name);
                    $("#editGroup_id").val(result.group_id);
                    $("#editAmount").val(result.amount);
                    $("#editmonth_year").val(result.month_year);
                    $("#editmonth_year2").val(result.month_year);
                    $("#editApplicable_from").val(result.applicable_from);
                    $("#editNote").val(result.note);
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




            
        $("#editBonusListForm").submit(function (e){
        e.preventDefault();
        
        var bonus_name = $("#editBonus_name").val();
        var month_year = $("#editmonth_year").val();
        var group_id = $("#editGroup_id").val();
        var amount = $("#editAmount").val();
        var applicable_from = $("#editApplicable_from").val();
        var note = $("#editNote").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('bonus_name',bonus_name);
        fd.append('month_year',month_year);
        fd.append('group_id',group_id);
        fd.append('amount',amount);
        fd.append('applicable_from',applicable_from);
        fd.append('note',note);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);

        $.ajax({
            url:"{{route('bonusListUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
                success:function(result){
                //alert(JSON.stringify(result));
                $("#editModal").modal('hide');
                Swal.fire("Updated Sheet!",result.success,"success");
                location.reload();
            }, error: function(response) {
                //alert(JSON.stringify(response));
                $('#editBonus_nameError').text(response.responseJSON.errors.bonus_name);              
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
            confirmButtonText: "Yes, delete bonus!",
            closeOnConfirm: false
        }).then((result) => {
        if (result.isConfirmed) {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"{{route('bonusListDelete')}}",
                method: "POST",
                data: {"id":id, "_token":_token},
                success: function (result) {
                    Swal.fire("Done!",result.success,"success");
                    location.reload();
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });
        }else{
          Swal.fire("Cancelled", "Your imaginary bonus is safe :)", "error");
        }
      })
        
    }



    
    </script>


@endsection