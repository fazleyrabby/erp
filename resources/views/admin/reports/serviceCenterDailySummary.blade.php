@extends('admin.master')
@section('title')
Service Center Daily Report
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3>Service Center Daily Report</h3> 
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Select Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" id="date_from" value="{{ date('Y-m-d') }}">
                            <span class="text-danger" id="date_fromError">{{ $errors->has('date_from') ? $errors->first('date_from') : '' }}</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="generateServiceReport()">Generate Report</button>
                        </div>
                    </div>

                   
                    <div id="tableData"></div>
                    <div id="generatePdf"> </div>
                    
                </div><!-- Card Content end -->
               
               
        <!-- pc-container end -->
@endsection


@section('javascript')
    <script>
       

     
            
               
        function generateServiceReport(){
            var date_from=$('#date_from').val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
                fd.append('date_from',date_from);
                fd.append('_token',_token);
            $.ajax({
                url:"{{route('generateDailyServiceReport')}}",
                method:"POST",
                data:fd,
                contentType: false,
                processData: false,
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                   $('#tableData').html(result.table);
                   $('#generatePdf').html(result.pdf);
                  
                },error:function(response) {
                    alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }

            })
        }
           
        




        



        function generatePdf(){
            var date_from=$('#date_from').val();
            window.open("{{url('service/daily/report/pdf/')}}"+"/"+date_from);
        }


    </script>
@endsection
