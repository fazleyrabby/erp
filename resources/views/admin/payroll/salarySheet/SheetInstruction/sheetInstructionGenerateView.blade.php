@extends('admin.master')
@section('title')
Admin Generate Salary Sheet  -View
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header" style="padding: 0px 1.0rem;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Generate Sheet Instruction</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


<section>
    <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
            
            
                        <div class="table">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                        @foreach($letterInstructions as $Instruction)
                                            <h4 style="text-align:center;">Bank Sheet</h4>
                                            <p>{!! $Instruction->letter_body !!}</p>
                                            <p>Mother Account: {{$Instruction->mother_account_no}}</p>
                                        <table  style="width:100%;text-align:center;margin-top:1px;">
                                            <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Employee</th>   
                                                        <th>Account No</th>
                                                        <th>Salary</th>
                                                        <th>Bank</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead> 
                                        @endforeach 
                                        @php 
                                        $i=1;
                                        @endphp
                                        @foreach ($salaryinstruction as $instruction)
                                            <tbody >
                                                <tr> 
                                                    <td>{{$i++}}</td>
                                                    <td>{{$instruction->member_name}}</td>
                                                    <td>{{$instruction->account_no}}</td>
                                                    <td>{{$instruction->net_total}} Taka</td>
                                                    <td>{{$instruction->bank_name}}<br>({{$instruction->branch_name}})</td>
                                                    <td><a class="btn btn-primary" style="color:#fff;" onclick=finalSheet({{$instruction->id}}) ><i class="fas fa-calendar-day"></i> Sheet</a></td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Net Payable: </th>
                                                    <th>{{$netamounts}} Taka Only</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
                                            @foreach($letterInstructions as $pdfInstruction)
                                            <button class="btn btn-success" onclick="generatePdf({{$pdfInstruction->id}})" ><i class="fas fa-file-pdf"></i> Generate PDF</button>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
            
</section>

</div>


  



<!-- edit modal -->
<div class="modal fade" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
			
                <div class="modal-header">
                    <h4 class="modal-title mr-auto">Salary Instructions</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div> 
            <div class="modal-body">
                <div class="row" style="padding:20px;">
                    <div class="col-md-10">
                        <ul class="list-group list-group-flush" id="instructionData"></ul>
                    </div>
                </div>
            
            </div>
			  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
                 </div>
				 </form>
            </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection










@section('contentJavaScripts')



<script>

        function generateInstruction(){
            var month_year = $("#month_year").val();
            var sheet_id = $("#sheet_id").val(); 
           $.ajax({
               url: "{{route('getSalaryInstructionData')}}",
               method:"GET",
               data:{"month_year":month_year,"sheet_id":sheet_id},
               success:function(result){
               // alert(JSON.stringify(result));
                $("#manageSalaryInstructionTable").html(result);
               
               }, error: function(response) {
                    alert(JSON.stringify(response));
                    
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
           })
        }








        function finalSheet(member_id){
		//alert(member_id);
         $.ajax({
            url:"{{route('generateInstructionBody')}}",
            method:"GET",
            data:{"member_id":member_id},
            datatype:"json",
            success:function(result){
                //alert(JSON.stringify(result));
                $("#viewModal").modal('show');               
                $("#instructionData").html(result);
                //$("#account_no").html(result.account_no);
            }, error: function(response) {
                    alert(JSON.stringify(response));
            }, beforeSend: function () {
                  $('#loading').show();
            },complete: function () {
                  $('#loading').hide();
            }
        }); 
    }


    function generatePdf(id){
        var month_year = $("#month_year").val();
        var sheet_id = $("#sheet_id").val();
        window.open("{{URL('payroll/salary/instruction/generate/pdf/')}}"+'/?month_year='+month_year+'&sheet_id='+sheet_id+'&id='+id);
    }
</script>

@endsection
