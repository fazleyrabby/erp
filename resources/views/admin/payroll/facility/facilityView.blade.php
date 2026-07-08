@extends('admin.master')
@section('title')
Admin Facility -View
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
                    <h3 class="card-title">Facility</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()"><i class="fa fa-plus circle"></i> Add Facility</button>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('facilityIndex') }}" searchPlaceholder="Search facilities..." :sortOptions="['id' => 'ID', 'facility_name' => 'Name']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <table id="manageFacilityTable" width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <td width="6%">SL</td>
                                <td>Facility Name</td>
                                <td>Group</td>
                                <td>Amount</td>
                                <td>Lower Limit</td>
                                <td>Upper Limit</td>
                                <td>Location</td>
                                <td width="8%">Status</td>
                                <td width="8%">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($facilities as $i => $facility)
                            <tr>
                                <td>{{ $facilities->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $facility->id }}" /></td>
                                <td>{{ $facility->facility_name }}</td>
                                <td>{{ $facility->groupName }}</td>
                                <td>{{ $facility->amount }}</td>
                                <td>{{ $facility->lower_limit }}</td>
                                <td>{{ $facility->upper_limit }}</td>
                                <td>{{ $facility->location }}</td>
                                <td class="text-center">
                                    @if ($facility->status == 'Active')
                                        <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $facility->status }}"></i>
                                    @else
                                        <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $facility->status }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-grade">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             <i class="fas fa-cog"></i>
                                         </button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <a class="dropdown-item" href="#" onclick="editFacility({{ $facility->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                             <a class="dropdown-item" href="#/" onclick="confirmDelete({{ $facility->id }})"><i class="fas fa-trash me-2"></i> Delete</a>
                                         </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No facilities found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $facilities->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>


<!-- add modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="FormFacilityStore" >
                <div class="modal-header">
                    <h4 class="modal-title float-left"> Add Facility</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>          
                </div> 
                <div class="modal-body">
                        @csrf
                    <div class="form-group row col-md-12">
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Group Name</label>
                            <select class="form-control" id="group_id" name="group_id">
                                <option value="" selected disabled>Choose Group</option>
                                @foreach($groups as $grd)
                                <option value="{{$grd->id}}">{{$grd->name}}</option>
                                @endforeach                                   
                            </select>
                            <span class="text-danger" id="group_idError"></span>
                        </div>   
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Facility Name</label>
                            <select class="form-control" id="facility_name" name="facility_name" required>
                                <option value="" selected disabled>Choose Facility</option>
                                <option value="House Rent">House Rent</option>                                  
                                <option value="Medical">Medical</option>                                  
                                <option value="Provident Fund">Provident Fund</option>                                  
                                <option value="Company Contribution">Company Contribution</option>                                  
                            </select>
                            <span class="text-danger" id="facility_nameError"></span>
                        </div> 
                    </div>
                    <div class="form-group row col-md-12">
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder=" Write Amount" >                                     
                            <span class="text-danger" id="amountError"></span>
                        </div>
                        <div class="col-sm-6">
                            <label for="carousalCaptionOffer">Lower Limit</label>
                            <input type="text" class="form-control" id="lower_limit" name="lower_limit" placeholder=" Write Lower Limit" >                                     
                            <span class="text-danger" id="lower_limitError"></span>
                        </div>
                    </div>
                    <div class="form-group row col-md-12">
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Upper Limit</label>
                            <input type="text" class="form-control" id="upper_limit" name="upper_limit" placeholder=" Write Upper Limit" >                                     
                            <span class="text-danger" id="upper_limitError"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="carousalCaptionOffer">Location</label>
                                <select class="form-control" id="location" name="location">
                                    <option value=""selected >Choose Location</option>
                                    <option value="Dhaka Metro">Dhaka Metro</option>
                                    <option value="Chittagong Metro">Chittagong Metro</option>
                                    <option value="Others">Others</option>
                                </select>
                                <span class="text-danger" id="locationError"></span>
                        </div>                       
                    </div>

                    </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">X Close</button>
                    <button type="submit" class="btn btn-primary float-right" ><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



<!-- edit modal -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editFacilityForm" >
                @csrf
                <div class="modal-header">
                    <h4 class="float-left">Edit Facility</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">
                        <input type="hidden" name="editId" id="editId">
                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Group Name</label>
                                <select class="form-control" id="editGroup_id" name="editGroup_id">
                                    <option value="" selected disabled>Choose Group</option>
                                    @foreach($groups as $grd)
                                    <option value="{{$grd->id}}">{{$grd->name}}</option>
                                    @endforeach                                   
                                </select>
                                <span class="text-danger" id="editGroup_idError"></span>
                            </div>
                            <div class="col-sm-6">
                                <label for="carousalCaptionOffer">Facility Name</label>
                                <select class="form-control" id="editFacility_name" name="editFacility_name" required>
                                    <option value="" selected disabled>Choose Facility</option>
                                    <option value="House Rent">House Rent</option>                                  
                                    <option value="Medical">Medical</option>                                  
                                    <option value="Provident Fund">Provident Fund</option>                                  
                                    <option value="Company Contribution">Company Contribution</option>                                  
                                </select>
                                <span class="text-danger" id="editFacility_nameError"></span>
                            </div>
                        </div>
                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer" >Amount</label>
                                <input type="text" class="form-control" id="editAmount" name="editAmount" >                                     
                                <span class="text-danger" id="editAmountError"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Lower Limit</label>
                                <input type="text" class="form-control" id="editLower_limit" name="editLower_limit" >                                     
                                <span class="text-danger" id="editLower_limitError"></span>
                            </div>
                        </div>
                        <div class="form-group row col-md-12">
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Upper Limit</label>
                                <input type="text" class="form-control" id="editUpper_limit" name="editUpper_limit" >                                     
                                <span class="text-danger" id="editUpper_limitError"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="carousalCaptionOffer">Location</label>
                                <select class="form-control" id="editLocation" name="editLocation">
                                    <option value="" selected>Choose Location</option>
                                    <option value="Dhaka Metro">Dhaka Metro</option>
                                    <option value="Chittagong Metro">Chittagong Metro</option>
                                    <option value="Others">Others</option>
                                </select>
                                <span class="text-danger" id="locationError"></span>
                            </div>                       
                        </div>

                        <div class="form-group row col-md-12">
                            <label  for="carousalCaptionOffer"> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
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
                $('#facility_name').focus();
            })
        /* store data*/
        $('#FormFacilityStore').submit(function(e){
                    e.preventDefault();
                    clearMessages();       
                    var facility_name = $("#facility_name").val();
                    var amount = $("#amount").val();
                    var group_id = $("#group_id").val();
                    var lower_limit = $("#lower_limit").val();
                    var upper_limit = $("#upper_limit").val();
                    var location = $("#location").val();
                    var _token = $('input[name="_token"]').val();

                    var fd = new FormData();
                    fd.append('facility_name',facility_name);
                    fd.append('amount',amount);
                    fd.append('lower_limit',lower_limit);
                    fd.append('group_id',group_id);
                    fd.append('upper_limit',upper_limit);
                    fd.append('location',location);
                    fd.append('_token',_token);
                    $.ajax({
                    url:"{{route('facilityStore')}}",
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
                        alert(JSON.stringify(response));
                        $('#facility_nameError').text(response.responseJSON.errors.facility_name);
                        $('#amountError').text(response.responseJSON.errors.amount);
                        $('#group_idError').text(response.responseJSON.errors.group_id);
                        $('#lower_limitError').text(response.responseJSON.errors.lower_limit);
                        $('#upper_limitError').text(response.responseJSON.errors.upper_limit);
                        $('#locationError').text(response.responseJSON.errors.location);
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
        $("#group_id").val("");
        $("#lower_limit").val("");
        $("#upper_limit").val("");
        $("#location").val("");
        clearMessages();
        }


        function clearMessages(){
		$('#facility_nameError').text("");
		$('#amountError').text("");
        $('#group_idError').text("");
        $('#lower_limitError').text("");
        $('#upper_limitError').text("");
        $('#locationError').text("");
	}


    function editReset(){
		$("#editFacility_name").val("");
		$("#editAmount").val("");
		$("#editGroup_id").val("Active");
        $("#editLower_limit").val("Active");
        $("#editUpper_limit").val("Active");
        $("#editLocation").val("Active");
        editClearMessages();
	}
    function editClearMessages(){
		$('#editFacility_nameError').text("");
		$('#editAmountError').text("");
        $('#editGroup_idError').text("");
        $('#editLower_limitError').text("");
        $('#editUpper_limitError').text("");
        $('#editLocationError').text("");
	}


            /*Edit */
        function editFacility(id){
		
        $.ajax({
            url:"{{route('editFacility')}}",
            method:"GET",
            data:{"id":id},
            datatype:"json",
            success:function(result){
                $("#editModal").modal('show');               
                $("#editGroup_id").val(result.group_id);
                $("#editFacility_name").val(result.facility_name);
                $("#editAmount").val(result.amount);
                $("#editLower_limit").val(result.lower_limit);
                $("#editUpper_limit").val(result.upper_limit);
                $("#editLocation").val(result.location);
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


    /* Edit Facility */

    $("#editFacilityForm").submit(function (e){
        e.preventDefault();
      
        var group_id = $("#editGroup_id").val();
        var facility_name = $("#editFacility_name").val();
        var amount = $("#editAmount").val();
        var lower_limit = $("#editLower_limit").val();
        var upper_limit = $("#editUpper_limit").val();
        var location = $("#editLocation").val();
        var status  =$("#editStatus").val();
        var _token = $('input[name="_token"]').val();
        var id = $("#editId").val();

        var fd = new FormData();
        fd.append('facility_name',facility_name);
        fd.append('amount',amount);
        fd.append('group_id',group_id);
        fd.append('lower_limit',lower_limit);
        fd.append('upper_limit',upper_limit);
        fd.append('location',location);
        fd.append('status',status);
        fd.append('id',id);
        fd.append('_token',_token);

        $.ajax({
            url:"{{route('facilityUpdate')}}",
            method:"POST",
            data:fd,
            contentType: false,
            processData: false,
            success:function(result){
                //alert(result);
                $("#editModal").modal('hide');
                Swal.fire("Updated Facility!",result.success,"success");
                location.reload();
            }, error: function(response) {
                $('#editFacility_nameError').text(response.responseJSON.errors.facility_name);
                $('#editAmountError').text(response.responseJSON.errors.amount);
                $('#editGroup_idError').text(response.responseJSON.errors.group_id);
                $('#editLower_limitError').text(response.responseJSON.errors.lower_limit);
                $('#editUpper_limitError').text(response.responseJSON.errors.upper_limit);
                $('#editLocationError').text(response.responseJSON.errors.location);
            }, beforeSend: function () {
                $('#loading').show();
            },complete: function () {
                $('#loading').hide();
            }
        })
    });





    function confirmDelete(id) {
        confirmDeleteSwal({
            url      : "{{route('facilityDelete')}}",
            id       : id,
            itemName : 'Facility',
        });
    }
               


</script>

@endsection 