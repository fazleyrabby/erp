@extends('admin.master')
@section('title')
Admin Final Salary Sheet -View
@endsection
@section('content')


    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 style="float:left;">Final Salary Sheet</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('sheetDataStore')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 align-items-end mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Month Year</label>
                                    <select class="form-select form-select-sm" id="month_year" name="month_year">                                
                                            @php
                                                $inc = 36;
                                                for($i = 0; $i < 12; $i++)
                                                {
                                                    echo '<option>'.Date('F-Y', strtotime(Date("Y-m-d").' '.$i.' Month -1 Day')).'</option>';
                                                }                                               
                                            @endphp
                                    </select>                                 
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sheet</label>
                                    <select class="form-select form-select-sm" id="sheet_id" name="sheet_id" required>
                                        <option value="" selected disabled>Choose Sheet</option>
                                        @foreach($sheets as $sheet)
                                        <option value="{{$sheet->id}}">{{$sheet->sheet_name}}</option>
                                        @endforeach                                   
                                    </select>                                    
                                </div>
                                <div class="col-md-4">
                                    <button type="button" onclick="generateSalarySheet()" class="btn btn-primary w-100">Generate Salary Sheet</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="manageSalarySheetInstructionTable" class="table table-vcenter table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <td>SL</td>
                                        <td>Ac No</td>
                                        <td>Name</td>
                                        <td>joD</td>
                                        <td>Consulate</td>
                                        <td>Basic</td>
                                        <td>H.R</td>
                                        <td>Med</td>
                                        <td>C.C</td>
                                        <td>Laundry</td>
                                        <td>Phone</td>
                                        <td>Ta/Da</td>
                                        <td>PF</td>
                                        <td>C.PF</td>
                                        <td>Bonus</td>
                                        <td>Adj</td>
                                        <td>Step</td>
                                        <td>Total</td>
                                        <td>Due</td>
                                        <td>D.PF</td>
                                        <td>Loan<br>Tenure</td>
                                        <td>Net <br>Total</td>
                                       
                                    </tr>
                                </thead>
                                <tbody id="manageSalarySheetTable" style="width:100%;"></tbody>
                            </table>
                           
                            </div>
                           
                            <div class="row g-3 mt-3">
                                <div class="col-md-12 text-end">
                                    <button id="save_btn" type="submit" class="btn btn-primary" style="display:none;"><i class="fas fa-save me-1"></i> Save</button>  
                                </div>
                            </div>
                            </form>
                        </div>
                           
                        </div>
                    </div>
                </div>
            </div>
                           
        @endsection




@section('contentJavaScripts') 

<script>


function generateSalarySheet(){
            var month_year = $("#month_year").val();
            var sheet_id = $("#sheet_id").val();
            $("#save_btn").show();
           $.ajax({
               url: "{{route('generateFinalSalary')}}",
               method:"GET",
               data:{"month_year":month_year,"sheet_id":sheet_id},
               success:function(result){
                    //alert(JSON.stringify(result));		
                    $("#manageSalarySheetTable").html(result);
               }, error: function(response) {
                    //alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();  
                },complete: function () {
                    $('#loading').hide();                           
                }
           })
        }



</script>



@endsection