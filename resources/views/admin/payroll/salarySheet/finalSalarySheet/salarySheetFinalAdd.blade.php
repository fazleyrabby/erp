@extends('admin.master')
@section('title')
Admin Final Salary Sheet -View
@endsection
@section('content')
<div class="content-wrapper">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 style="float:left;">Final Salary Sheet</h3>
                        </div>
                        <form action="{{route('sheetDataStore')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                        
                            <div class="form-group row ">
                                <div class="col-md-4">
                                    <label>Month Year</label>
                                    <select class="form-control" id="month_year" name="month_year">                                
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
                                    <label>Sheet</label>
                                    <select class="form-control" id="sheet_id" name="sheet_id" required>
                                        <option value="" selected disabled>Choose Sheet</option>
                                        @foreach($sheets as $sheet)
                                        <option value="{{$sheet->id}}">{{$sheet->sheet_name}}</option>
                                        @endforeach                                   
                                    </select>                                    
                                </div>
                            
                                <div class="col-md-4" style="margin-top: 3%;">
                                    <span   onclick="generateSalarySheet()" class=" btn btn-primary" style="color:#fff;">Generate Salary Sheet </span>
                                                           
                                </div>
                            </div>


                        <!-- /.card-header -->
                        <div class="card-body " >
                            <div class="table-responsive">
                            <table id="manageSalarySheetInstructionTable"  class="table table-bordered table-striped " >
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
                           
                            <div class="row">
                                <div class="col-md-12">
                             <button id="save_btn" type="submit" class="btn btn-primary float-right" style="display:none;"><i class="fas fa-save"></i> Save</button>  
                                </div>
                            </div>
                            </form>
                            
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
                           
        </section>
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