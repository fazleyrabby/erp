@extends('admin.master')
@section('title')
Final Salary Sheet -View
@endsection
@section('content')


    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 style="float:left;" >Salary Sheet </h3> 
                            <h3 style="float:right;">Total Salary - {{$sumsheets}}</h3>
                            <h3 class="text-center text-danger">{{Session::get('message')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                        <table id="finalSalarySheetTable" class="table table-bordered table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Month Year</th>
                                        <th>Sheet</th>
                                        <th>Employee</th>
                                        <th>Account No</th>
                                        <th>Consulate</th>
                                        <th>Basic</th>
                                        <th>H.R</th>
                                        <th>Med</th>
                                        <th>C.C</th>
                                        <th>Laundry</th>
                                        <th>Phone</th>
                                        <th>Ta/Da</th>
                                        <th>PF</th>
                                        <th>C.PF</th>
                                        <th>Bonus</th>
                                        <th>Adj</th>
                                        <th>Step</th>
                                        <th>Total</th>
                                        <th>Due</th>
                                        <th>D.PF</th>
                                        <th>Loan<br>Tenure</th>
                                        <th>Net <br>Total</th>
                                                                           
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php 
                                    $sl=1;
                                    @endphp
                                   @foreach($sheets as $sheet)
                                    <tr>
                                        <td>{{$sl++}}</td>
                                        <td>{{$sheet->month_year}}</td>
                                        <td>{{$sheet->sheet_name}}</td>
                                        <td>{{$sheet->member_name}}</td>
                                        <td>{{$sheet->account_no}}</td>
                                        <td>{{$sheet->consulate}}</td>
                                        <td>{{$sheet->basic}}</td>
                                        <td>{{$sheet->house_rent}}</td>
                                        <td>{{$sheet->medical_allowence}}</td>
                                        <td>{{$sheet->company_contribution}}</td>
                                        <td>{{$sheet->laundry}}</td>
                                        <td>{{$sheet->phone_bill}}</td>
                                        <td>{{$sheet->ta_da}}</td>
                                        <td>{{$sheet->provident_fund}}</td>
                                        <td>{{$sheet->company_provident_fund}}</td>
                                        <td>{{$sheet->monthly_bonus}}</td>
                                        <td>{{$sheet->adjustment}}</td>
                                        <td>{{$sheet->step_amount}}</td>
                                        <td>{{$sheet->total}}</td>
                                        <td>{{$sheet->due}}</td>
                                        <td>{{$sheet->deduct_provident_fund}}</td>
                                        <td>{{$sheet->loan_installment}}</td>
                                        <td>{{$sheet->net_total}}</td>
                                       

                                       
                                       
                                        <td>
                                        <div class="btn-grade">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                                <div class="dropdown-menu dropdown-menu-end">     
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#data{{$sheet->id}}"><i class="fas fa-calendar-alt me-2"></i> View</a> 
                                                </div> 
                                                 <!-- Modal -->
                                                 <div class="modal fade" id="data{{$sheet->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        
                                                        <h5 class="modal-title" id="exampleModalLongTitle"> Salary Data </h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!----------Table Start------> 
                                                        <table class="table">
                                                        <thead>
                                                          <tr>
                                                              <th>Name</th>
                                                              <th>{{$sheet->member_name}}</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                           
                                                                <td scope="col">Month Year</td>
                                                                <td scope="col">{{$sheet->month_year}}</td>
                                                           
                                                           </tr>
                                                            <tr>
                                                                <td>Account Number</td>
                                                                <td>{{$sheet->account_no}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sheet Name</td>
                                                                <td>{{$sheet->sheet_name}}</td>
                                                            </tr>
                                                            <tr>  
                                                                <td>Consulate</td>
                                                                <td>{{$sheet->consulate}} Taka</td>                                                          
                                                            </tr>                                                           
                                                            <tr>                                                           
                                                                <td>Basic</td>
                                                                <td>{{$sheet->basic}} Taka</td>                                                           
                                                            </tr>
                                                            <tr>                                                           
                                                                <td>House Rent</td>
                                                                <td>{{$sheet->house_rent}} Taka</td>                                                         
                                                            </tr>
                                                            <tr>                                                            
                                                               <td>Medical Allowence</td>
                                                                <td>{{$sheet->medical_allowence}} Taka</td>                                                           
                                                            </tr>
                                                            <tr>
                                                                 <td>Company Contribution</td>
                                                                <td>{{$sheet->company_contribution}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Laundry</td>
                                                                <td>{{$sheet->laundry}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                 <td>Phone Bill</td>
                                                                <td>{{$sheet->phone_bill}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Ta/Da</td>
                                                                <td>{{$sheet->ta_da}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                 <td>Provident Fund</td>
                                                                <td>{{$sheet->provident_fund}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Company Provident Fund</td>
                                                                <td>{{$sheet->company_provident_fund}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                 <td>Adjustments</td>
                                                                <td>{{$sheet->adjustment}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Steps Salary</td>
                                                                <td>{{$sheet->step_amount}}Taka</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Salary</td>
                                                                <td> <b>{{$sheet->total}} Taka</b> </td>
                                                            </tr>
                                                            <tr>
                                                                 <td>Dues</td>
                                                                <td>- {{$sheet->due}} Taka</td>
                                                            </tr>
                                                            <tr>
                                                                 <td>Deductable Provident Fund</td>
                                                                <td>- {{$sheet->deduct_provident_fund}} Taka</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                 <td>Monthly Loan</td>
                                                                <td>- {{$sheet->loan_installment}} Taka</td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                 <td>Net Total</td>
                                                                <td><b> {{$sheet->net_total}} Taka </b></td>
                                                            </tr>
                                                            
                                                            
                                      

                                                        </tbody>
                                                        </table>
                                                        <!----------Table End-------->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href=""><button type="button" class="btn btn-primary">Generate Sheet</button> </a>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div> 
                                                                             
                                        </div>
                                         
                                        </td>
                                    </tr>
                                   
                                    @endforeach
                                </tbody>
                                </table>

                               <input type="hidden" id="month_year" value="{{$sheetid->month_year}}" name="month_year">
                                <input type="hidden" id="sheet_id" value="{{$sheetid->sheet_id}}" name="sheet_id">

                                @php 
                                $color1='';
                                $enablity1='';
                                $text1='';
                                $color2='';
                                $enablity2='';
                                $text2='';
                                if($instructions == null){
                                    $color1='secondary';
                                    $enablity1='disabled';
                                    $text1='Please Create salary instruction to generate PDF';
                                    $color2='primary';
                                    $enablity2='';
                                }else{
                                    $color1='primary';
                                    $enablity1;
                                    $text1;
                                    $text2='Salary Instruction has been created before';
                                    $color2='secondary';
                                    $enablity2='disabled';
                                }
                                @endphp

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <button  onclick="generateSalarySheetPdf({{$sheetid->id}})" class="btn btn-{{$color1}}" {{$enablity1}} ><i class="fas fa-file-pdf"></i> Genereate PDF</button>
                                        <span class="text-danger">{{$text1}}</span>

                                        <a class="btn btn-{{$color2}}  {{$enablity2}} float-right"  href="{{route('sheetInstructionAdd')}}"> Create Salary Instruction</a>
                                        <span class="text-danger float-right">{{$text2}}</span>  
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection



@section('contentJavaScripts') 


<script>

    function generateSalarySheetPdf(id){
            var month_year = $("#month_year").val();
            var sheet_id = $("#sheet_id").val();
            window.open("{{URL('payroll/salary/Sheet/generate/pdf/')}}"+'/?month_year='+month_year+'&sheet_id='+sheet_id+'&id='+id );
    }




</script>




@endsection