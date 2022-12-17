@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} daily ledger
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
                <form id="saleProducts" method="POST">
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Daily Report
                                        <a class="btn btn-success float-right" href="{{ url('sale/') }}"> <i
                                                class="fa fa-plus-circle"></i> view Sale</a>
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        {{-- <div class="form-group col-md-8"></div> --}}
                                        {{-- <div class="form-group col-md-4">
                                            <label class="p-2"> </label>
                                            <button type="button" class="btn btn-info btn-lg btn-block "> Something
                                            </button>
                                        </div> --}}
                                        <div class="form-group col-md-4">
                                            @foreach ($dateArray as $date)
                                                <input type="hidden" class="form-control" id="remainingDate"
                                                    name="remainingDate" value="{{ $date }}">
                                            @endforeach

                                            <label>Date: </label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}">



                                            {{-- <label>Date: </label>
                                            <input type="date" class="form-control" id="date" name="date"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}"> --}}
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="pt-3"> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block "
                                                onclick="viewCalculation()"> View Calculation </button>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label><strong>Daily Ledger Details:</strong></label>
                                            <table border="1" style="width:100%;">
                                                <thead class="text-center">
                                                    {{-- <tr>
                                                        <th>SL#</th>
                                                        <th>Dr(-)</th>
                                                        <th>Cr(+)</th>
                                                    </tr> --}}
                                                </thead>
                                                <tbody class="text-center" id="manageReportTable">
                                                    <th>Type</th>
                                                    <th>Amount</th>

                                                </tbody>
                                            </table>
                                            </table>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="hidden" id="totalDr" name="totalDr" value="">
                                            <input type="hidden" id="totalCr" name="totalCr" value="">
                                            <input type="hidden" id="totalExpense" name="totalExpense" value="">
                                        </div>
                                        <div class="form-group col-md-4"></div>
                                        <div class="form-group col-md-4">
                                            <label>Opening Balance : </label>
                                            <input type="text" class="form-control" id="openingBalance"
                                                name="openingBalance" aria-describedby="emailHelp" value="" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Total Amount (today) : </label>
                                            <input type="text" class="form-control" id="totalAmount" name="totalAmount"
                                                aria-describedby="emailHelp" value="" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Closing Amount : </label>
                                            <input type="text" class="form-control" id="closingAmount"
                                                name="closingAmount" aria-describedby="emailHelp" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label> </label>
                                            <a type="button"
                                                class="btn btn-success text-light my_button float-left mt-4 p-2"
                                                onclick="clearInput();"> Clear </a>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block "
                                                onclick="saveTodayReport()">Save Today Report</button>
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
            $("select").select2();
        });

        const viewCalculation = () => {

            let remainingDate = [];
            $("input[name=remainingDate]").each(function() {
                remainingDate.push($(this).val());
            });

            //--start check previous calculation---//
            let todayDate = $('#date').val();
            if (remainingDate.length > 0 && remainingDate < todayDate) {
                date = remainingDate[0];
                Swal.fire({
                    title: "Are you sure ?",
                    text: "Save Previous Report!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#008000",
                    confirmButtonText: "Yes, Confirm!",
                    closeOnConfirm: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#date').val(date);
                        getCalculation(date);

                    } else {
                        Swal.fire("Cancelled", "report cancelled!", "error");
                    }
                });

            } else {
                let date = $("#date").val();
                getCalculation(date);
            }
            //--end check previous calculation---//
        }

        const getCalculation = (preDate) => {
            let date = preDate;
            let _token = $('input[name="_token"]').val();
            let fd = new FormData();
            fd.append('date', date);
            fd.append('_token', _token);

            $.ajax({
                url: "{{ url('report/get-daily-report') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    
                    $("#manageReportTable").html(result[0]);
                    let totalAmount = result[2][3];
                    let openingBalance = result[1] == null ? 0 : result[1]['opening_balance'];
                    // let closingAmount = result[1]['opening_balance'] + totalAmount;
                    let closingAmount = openingBalance + totalAmount;
                    // $("#openingBalance").val(result[1]['opening_balance']);
                    $("#openingBalance").val(openingBalance);
                    $("#totalAmount").val(totalAmount);
                    $("#closingAmount").val(closingAmount);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $("#msg_error").html(JSON.stringify(response));
                }
            });
        }

        const saveTodayReport = () => {

            let date = $("#date").val();
            let openingBalance = $("#openingBalance").val();
            let totalAmount = $("#totalAmount").val();
            let closingAmount = $("#closingAmount").val();

            if (date == "" || openingBalance == "" || totalAmount == "" || closingAmount == "") {
                Swal.fire({
                    title: 'Error!',
                    text: 'Fill up form',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }
            let _token = $('input[name="_token"]').val();
            let fd = new FormData();
            fd.append('date', date);
            fd.append('openingBalance', openingBalance);
            fd.append('totalAmount', totalAmount);
            fd.append('closingAmount', closingAmount);
            fd.append('_token', _token);


            Swal.fire({
                title: "Are you sure ?",
                text: "Report Confirm!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#008000",
                confirmButtonText: "Yes, Confirm!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('report/save-today-report') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(result) {
                            Swal.fire("saved!", result.success, "success");
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                            //$('#nameError').text(response.responseJSON.errors.name);
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            clearData();
                            $('#loading').hide();
                            location.reload();
                        }

                    });

                } else {
                    Swal.fire("Cancelled", "report cancelled!", "error");
                }
            });

        }

        const clearInput = () => {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear data!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    clearData();

                } else {
                    Swal.fire("Cancelled", "Your data is safe :)", "error");
                }
            })
        }

        function clearData() {
            $("#manageReportTable").html('');
            $("#openingBalance").val('');
            $("#totalAmount").val('');
            $("#closingAmount").val('');
        }

        /*const generateReport = () => {
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();
            let partyType = $("#partyType").val();


            if (dateFrom.length <= 0 || dateTo.length <= 0 || partyType.length <= 0) {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Please Select Date Properly!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }
            window.open("{{ url('report/party-with-due-report') }}" + "/" + dateFrom + "/" + dateTo + "/" +
                partyType);
        }*/
    </script>
@endsection
