@extends('admin.master')
@section('title')
Final Salary Sheet -View
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 style="float:left;">Add Sheet</h3>
                            <a href="{{route('finalSalarySheetAdd')}}"   class="btn btn-primary btn-icon-split float-right"><i class="fas fa-plus"></i> Add Sheet</a>     
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body ">
                        <table id="finalSalarySheetTable" width="100%" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <td width="6%">SL</td>
                                    <td>Month Year</td>
                                    <td>Sheet</td>
                                    <td>Net Total</td>                                                                           
                                    <td width="8%">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            @php 
                                $sl=1;
                                $display='';
                                @endphp
                               
                                @foreach($sheets as $sheet)
                                <tr>
                                    <td>{{$sl++}}</td>
                                    <td>{{$sheet->month_year}}<input type="hidden"  id="month_year" value="{{$sheet->month_year}}"></td>
                                    <td>{{$sheet->sheet_name}}<input type="hidden"  id="sheet_id" value="{{$sheet->sheet_id}}"></td>
                                    <td>{{$sheet->company_payable_net_salary}} {{Session::get('companySettings')[0]['currency']}}</td>                                      
                                    <td>
                                    
                                      
                                    <div class="btn-grade">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                            <div class="dropdown-menu dropdown-menu-end">               
                                                <a class="dropdown-item" href="{{route('finalSalarySheetView',$sheet->id)}}"><i class="fas fa-calendar-alt me-2"></i> View</a>
                                                <a class="dropdown-item" href="{{route('finalSalarySheetDelete',$sheet->id)}}" onclick="return swalConfirmLink(event, this)" data-item="Sheet" data-action="delete"><i class="fas fa-trash me-2"></i> Delete </a>
                                            </div>                                               
                                    </div>

                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


@section('contentJavaScripts') 


<script>

    

</script>




@endsection
