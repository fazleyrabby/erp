@extends('admin.master')
@section('title')
Admin Payroll Setting -View
@endsection
@section('content')

    
        <div class="card">
            <h3>Payroll Setting</h3>    
            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
        </div>
        <div class="container">
            <form action="{{route('deductDayUpdate')}}" method="POST">
                @csrf
                <div class="row g-3">
                    <h4 class="col-md-3">Absent Deduction:</h4>
                    <select class="form-control col-md-1" id="activation" name="activation" onchange="activationStatus()">
                        <option value="{{$datas->activation}}"selected>{{$datas->activation}}</option>
                        @php 
                        $status='';
                        $visibility='';
                        if($datas->activation == 'On'){
                            $status='Off';
                            $visibility='visible';
                        }else{
                            $status='On';
                            $visibility='none';
                        }
                        
                        @endphp
                        <option value="{{$status}}">{{$status}}</option>
                    </select>
                    <div class="col-md-8"></div>
                </div>
                <div class="row" id="deductionForm" style="display:{{$visibility}};">
                    <input type="hidden" name="id" value="1">
                    <div class="col-md-5">
                        <label >Absent:</label>
                        <input type="text" class="form-control" name="absent" id="absent" value="{{$datas->absent}}" >       
                        <span class="text-danger" id="ediabsentError"></span>
                    </div>
                    <div class="col-md-5">
                        <label >Punishment:</label>
                        <input type="text" class="form-control" name="deduct_amount_for_absent" id="deduct_amount_for_absent"  value="{{$datas->deduct_amount_for_absent}}">       
                        <span class="text-danger" id="editdeduct_amount_for_absentError"></span>
                    </div>
                    <div class="col-md-2">
                        <label >.</label><br>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
                
            </form>
        </div> <br><br>
    @endsection


    @section('contentJavaScripts')


    <script>
       
        $(document).ready(function(){
            tooglePayrollSettings();
            $('#CheckBox').on('change',function(){ 
                tooglePayrollSettings();
            });
        });


        function tooglePayrollSettings(){
            
            if($('#CheckBox:checked').val() == "on"){
                $("#deductionSection").show();

            }else{
                $("#deductionSection").hide();
            }
        }

           


        function activationStatus(){
            var activation=$('#activation').val();

            if(activation == 'Off'){
                $('#deductionForm').hide();
            }else{
                $('#deductionForm').show();
            }
            $.ajax({
                url:"{{route('activationStatus')}}",
                method:"GET",
                data:{"activation":activation},
                datatype:"json",
                success:function(result){
                    //alert(JSON.stringify(result));
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });   
        }



    </script>

    @endsection