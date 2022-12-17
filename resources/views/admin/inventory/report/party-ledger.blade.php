@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Report
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div id="msg_error"></div>
                <form id="saleProducts" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Party Ledger
                                      
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        @csrf
                                      


                                        <div class="form-group col-md-3">
                                            <label>Party Type </label>
                                            <select id="party_type" name="party_type" class="form-control input-sm" onchange="getParty()">
                                                <option value=""selected >Select Party Type</option>
                                                <option value="Customer">Customer</option>
                                                <option value="Supplier">Supplier</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3" id="customersRow">
                                            <label>Party: </label>
                                            <select id="party_id" name="party_id" class="form-control input-sm">
                                                <option value="">Select Party</option>
                                               
                                            </select>
                                        </div>

                                        
                                      

                                        <!--<div class="form-group col-md-3">
                                            <label>Project </label>
                                            <select class="form-control" id="project_id" name="project_id" onchange="loadOrder()">
                                                    <option value='0' selected> Select Project </option>
                                                    
                                            </select>
                                            <span class="text-danger" id="project_idError"></span>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label >Work Order </label>
                                            <select class="form-control " id="work_order_id" name="work_order_id" >
                                                    <option value="">Select Work Order</option>
                                                    
                                            </select>
                                        </div>-->

                                       

                                        <div class="form-group col-md-3">
                                            <label>Date From: </label>
                                            <input type="date" class="form-control" id="dateFrom"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}">

                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Date To: </label>
                                            <input type="date" class="form-control" id="dateTo"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}">
                                        </div>
                                     <div class="form-group col-md-8"></div>
                                        <div class="form-group col-md-4">
                                            <label> </label>
                                            <span id="customerGenerateBtn"></span>
                                            <!-- <button type="button" class="btn btn-primary btn btn-block p-3"
                                                onclick="getPartyLegder()">View Party Ledger </button> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Party Ledger Details: </label>
                                            <table border="1" style="width:100%;text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:5%;">SL#</th>
                                                        <th style="width:10%;">Date</th>
                                                        <th style="width:10%;">Invoice No</th>
                                                        <th style="width:15%;">Voucher Type</th>
                                                        <th  style="width:20%;">Debit</th>
                                                        <th  style="width:20%;">Credit</th>
                                                        <th  style="width:20%;">Balance</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody id="managePartyTable">
                                                    
                                                </tbody>
                                            </table>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                           <span id="customer_btn"></span>
                                          
                                           
                                          <!--   <a type="button" id="checkOutCart" class="btn btn-success my_button float-right"
                                               style="color:#fff;" onclick="generateReport()" target="_blank"> Generate Report </a> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->

                                <!-- /.card -->
                            </div>
                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->

                        <!-- /.row (main row) -->

                    </div><!-- /.container-fluid -->
        </section>
        </form>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection




@section('javascript')
    <script>
        $(function() {
            $("select").select2({
                width:'100%'
            });
          
        });
       
        $('#suppliersRow').hide();

        $("#customer").change(function() {
            let color_select = $('select#suppliers');
            color_select.val("");
            $('#suppliersRow').hide();
            $('#customersRow').show();
        })
        $("#supplier").change(function() {
            let color_select = $('select#customers');
            color_select.val("");
            $('#customersRow').hide();
            $('#suppliersRow').show();
         
        })


        var customerId = 0;
        var supplierId = 0;
        var dateFrom = 0;
        var dateTo = 0;
        var _token = 0;




        /*const getPartyLegder = () => {
            partyId = $("#party_id").val();
            work_order_id = $("#work_order_id").val();
            _token = $('input[name="_token"]').val();
            dateFrom = $("#dateFrom").val();
            dateTo = $("#dateTo").val();

           alert(_token);

            var fd = new FormData();
            fd.append('partyId', partyId);
            fd.append('work_order_id',work_order_id);
            fd.append('dateFrom', dateFrom);
            fd.append('dateTo', dateTo);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('report/party-ledgerView') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {

                 //   alert(JSON.stringify(result));
                    //$("#managePartyTable").html('');
                    $("#managePartyTable").html(result.info);
                    
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                    //$("#msg_error").html(JSON.stringify(response));
                }
            });
        }*/

    




       





        function getPartyLegder(){
            
        var partyId=$('#party_id').val();
        var dateFrom = $("#dateFrom").val();
        var dateTo = $("#dateTo").val();
        var _token = $('input[name="_token"]').val();

        var fd = new FormData();
            fd.append('partyId', partyId);
            fd.append('dateFrom', dateFrom);
            fd.append('dateTo', dateTo);
            fd.append('_token',_token);
           
            $.ajax({
                url: "{{url('report/party-ledgerView') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                     $("#managePartyTable").html('');
                    $("#managePartyTable").html(result.info);
                   
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    alert(JSON.stringify(response));
                    //$("#msg_error").html(JSON.stringify(response));
                }
            }); 
    }







            function getParty(){
                var party_type=$('#party_type').val();
                $.ajax({
                      url:"{{route('getPartyReportList')}}",
                      method:"GET",
                      data:{"party_type":party_type},
                      datatype:"json",
                      success:function(result){
                        //alert(JSON.stringify(result));
                        $("#party_id").html(result.info);
                        $('#customerGenerateBtn').html(result.button);
                        $('#customer_btn').html(result.generateButton);
                      }, beforeSend: function () {
                      $('#loading').show();
                      },complete: function (){
                      $('#loading').hide();
                      }
                    });
            }

    
            
        const generateReport = () => {
            id =$("#party_id").val();
            dateFrom = $("#dateFrom").val();
            dateTo = $("#dateTo").val();
            const data = [id, dateFrom, dateTo]
            window.open("{{ url('report/party-report') }}" + "/" + data);
        }

        /*const generateSupplierReport = () => {
            id =$("#party_id").val();
            dateFrom = $("#dateFrom").val();
            dateTo = $("#dateTo").val();
            const data = [id, dateFrom, dateTo]
            window.open("{{ url('report/supplier-report') }}" + "/" + data);
        }*/


        function clearCart() {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear cart!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#managePartyTable").html('');
                    $("#dateFrom").val('');
                    $("#customers").val('');

                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            })
        }

        /*function clearSalesForm() {

            $("#suppliers").change();
            $("#customers").change();

            $("#supplier").val("");
            $("#total_amount").text("0");
            $("#discount").val("0");
            $("#transport").val("0");
            $("#grandTotal").text("0");
            $("#currentDue").text("0");
            $("#totalWithDue").text("0");
            $("#payment").val("0");
            //emi clear
            $('#downPayment').val(0);
            $("#noOfTenure").val(0);
            $('#perTenurAmount').text('');
            $('#startDate').val('');
            $(".tenurDate").remove();
            // customer info

        }*/
    </script>
@endsection
