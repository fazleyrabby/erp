@extends('admin.master')
@section('title')
Admin Create Loan
@endsection
@section('content')

<!--    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3> Create Loan </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                        
                    </ol>
                </div>
            </div>
        </div> /.container-fluid 
    </section>-->
    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header py-3">
                            <span class="text">Add Salary Loan
                                @if (session('status'))
                                <span class=" alert alert-success">
                                    {{ session('status') }}
                                </span>
                                @endif
                            </span>
                        </div>
                        <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        <form action="{{route('loanStore')}}" method='POST' enctype="multipart/form-data">
                            @csrf
                            
                                <div class="form-group mb-3 row col-md-12">
                                    <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Employee Name</label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <option value="" selected disabled>Choose Employee</option>
                                        @foreach($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->member_name}}</option>
                                        @endforeach                                   
                                    </select>        
                                    <span class="text-danger" id="user_idError"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer">Amount</label>
                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Write Amount" >
                                        <span class="text-danger" id="amountError"></span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row col-md-12">
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer">Tenure</label>
                                        <input type="text" class="form-control" id="tenure" name="tenure" placeholder="Write  Tenure" >
                                        <span class="text-danger" id="tenureError"></span>
                                    </div>    
                                   
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer"> Interest rate % </label>
                                        <input type="text" class="form-control" id="percent" name="percent" placeholder="Numeric Interest value. Don't use'%' " >                          
                                        <span class="text-danger" id="percentError"></span>
                                    </div>
                                    
                                </div>
                                <div class="form-group mb-3 row col-md-12">
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer"> Monthly Installment </label>
                                        <input type="text" class="form-control" id="installment" name="installment" placeholder="Monthly loan Amount + Interest" readonly>                          
                                        <span class="text-danger" id="installmentError"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer"> Issue Date </label>
                                        <input type="date" class="form-control" id="applicable_from" name="applicable_from"  >                          
                                        <span class="text-danger" id="applicable_fromError"></span>
                                    </div>
                                </div>

                                <div class="form-group mb-3 row col-md-12">
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer">Month Year</label>
                                            <select class="form-control" id="month_year" name="month_year">
                                            @php
                                                $inc = 36;
                                                for($i = 0; $i < 12; $i++)
                                                {
                                                    echo '<option>'.Date('F-Y', strtotime(Date("Y-m-d").' '.$i.' Month -1 Day')).'</option>';
                                                }                                               
                                            @endphp
                                            </select>
                                            <span class="text-danger" id="month_yearError"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="carousalCaptionOffer">Cause</label>
                                            <textarea type="text" class="form-control" id="cause" name="cause" placeholder="Write Short Cause" ></textarea>
                                            <span class="text-danger" id="causeError"></span>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 3.1%;">
                                        <a class="btn btn-primary" onclick="generateTenure()" style="color:#fff;">Generate Tenure</a>
                                    </div>
                                    
                                </div>
                                

                                <div class="form-group mb-3 row col-md-12">
                                    <div class="col-md-12">
                                    <section style="padding:20px;">

                                        <div id="tenureData" ></div>
                                        <div class= "netAmount" >
                                            <p style="display:none;" id="netAmountP" >Total Net Payable:<span id="netAmount" > </span></p>                                      
                                        </div>
                                        <div >
                                            <button class="btn btn-primary"   id="saveTenure"   style="display:none;"> Save </button>
                                        </div>

                                        <div class="form-group mb-3 col-md-12">
                                            <button type="submit" id="save_btn"  class="btn btn-primary btn-flat float-right"  onclick="tenureDataSave()" style="display:none;" ><i class="fa fa-save"></i> Save </button>
                                        </div>
                                    </div>




                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@section('contentJavaScripts')

<script>
            
       
       /*Tenure generate-*/
       function generateTenure() {
           
            var user_id = $("#user_id").val();
            var tenure = $("#tenure").val();
            var amount = $("#amount").val();
            var percent =$("#percent").val();
            var yearlyInterest= Math.ceil((percent/100)*amount);
            var monthlyInterest=Math.ceil(yearlyInterest/12);
            var installment = Math.ceil((amount/tenure)+monthlyInterest);
            var startingMonthYear = $("#month_year").val();
            var netPayable =Math.ceil(installment*tenure);

            $("#installment").val(installment);
            $("#netAmount").html(netPayable);
            $("#user_id").val(user_id);
            
            
            if(tenure > 0 && amount > 0){
                $.ajax({
                        url:"{{route('getTenureData')}}",
                        method:"get",

                        data:{"tenure":tenure,"user_id":user_id, "amount":amount, "percent":percent, "installment":installment, 
                                "startingMonthYear":startingMonthYear},

                        success:function(result){
                            $("#tenureData").html(result);
                            $("#save_btn").show();                         
                        }, 
                        error: function(response) {
                            alert(JSON.stringify(response));
                            
                        }, beforeSend: function () {
                            $('#loading').show();
                            //$('#saveTenure').show();
                            $('#netAmountP').show();
                            
                        },complete: function () {
                            $('#loading').hide();
                            
                        }
                    })
                }else{
                    $("#tenureData").html("Add tenure greater then 0");
                }



            /*if(tenure > 0 && amount > 0){
                var tenureData = "<table>";
                tenureData += "<tr><td>SL</td><td>Month Year</td><td>Installment</td><td>Action</td></tr>";
                for(var i = 0; i < tenure; i++){
                    
                    tenureData += "<tr><td>"+(i+1)+"</td><td>Month Year</td><td>"+installment+"</td><td>Action</td></tr>";
                }
                tenureData += "</table>";   
                $("#tenureData").html(tenureData);
            }else{
                $("#tenureData").html("Add tenure greater then 0");
            }*/
        }
            



        /*function tenureDataSave(){
        
            var tenure = $("#tenure").val();
            var _token = $('input[name="_token"]').val();

            var user_id =[];
            var month_year = [];
            var installment = [];

            for(var i = 0; i < tenure; i++){
                user_id[i] = $("#user_id_"+i).text();
                month_year[i] = $("#month_year_"+i).text();  
                installment[i] = $("#installment_"+i).text();                            
            }

            var fd = new FormData();
                fd.append('user_id',user_id);
                fd.append('month_year',month_year);
                fd.append('installment',installment);
                fd.append('_token',_token);

            $.ajax({
                url:"{{route('tenureDataSave')}}",
                method:"POST",
                data:fd,
                contentType: false,
                processData: false,
                datatype:"json",
                success:function(result){                    
                    Swal.fire("Saved!",result.success,"success");
                    table.ajax.reload(null, false);                                                                                       
                }, 
                    error: function(response) {
                        alert(JSON.stringify(response));
                        
                    }, beforeSend: function () {
                        $('#loading').show();
                        
                    },complete: function () {
                        $('#loading').hide();                       
                    }  
                })           
            }*/


/* $("span[id^='month_year_']").each(function() {
                alert(parseFloat($(this).text()));
            });
            $("span[id^='installment_']").each(function() {
                alert(parseFloat($(this).text()));
            }); */
            
            
       


            




</script>


@endsection