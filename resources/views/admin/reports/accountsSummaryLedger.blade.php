@extends('admin.master')
@section('title')
    Admin account report
@endsection


@section('content')
    <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Income & Expenditure Statement</h3>
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" id="date_from"
                                value="{{ date('Y-m-01') }}">
                            <span class="text-danger"
                                id="date_fromError">{{ $errors->has('date_from') ? $errors->first('date_from') : '' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control form-control-sm" name="date_to" id="date_to"
                                value="{{ date('Y-m-d') }}">
                            <span class="text-danger"
                                id="date_toError">{{ $errors->has('date_to') ? $errors->first('date_to') : '' }}</span>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="generateReport()">Generate </button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="w-100" id="generatePdf"></div>
                        </div>
                    </div>

                    <div id="monthYearHeader"></div>
                    <br/>
                    <div class="table-responsive" id="tableData"></div>
                    <div class="container-fluid p-0 mt-2">
                        <div class="row no-gutters">
                            <div class="col-md-12" id="getVoucherButton">
                                <div id="closingBtn"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Content end -->
        <!-- pc-container end -->
@endsection


@section('javascript')
    <script>
        function generateReport() {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var _token = $('input[name="_token"]').val();

            var fd = new FormData();
            fd.append('date_from', date_from);
            fd.append('date_to', date_to);
            fd.append('_token', _token);

            $.ajax({
                url: "{{ route('generateSummaryReport') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    $('#monthYearHeader').html(result.monthYearHeader)
                    $('#tableData').html(result.table)
                    //$('#closingBtn').html(result.closingBtn)
                    $('#generatePdf').html(result.pdf)

                },
                error: function(error) {
                    alert(JSON.stringify(error));
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        }






        function closeBalance() {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var presentClosingBalance = $('#due').val();
            var previousMonthClosing = $('#previousMonthClosing').val();
            //alert(presentClosingBalance);
            Swal.fire({
                title: "Are you sure ?",
                text: "You want to close the balance!!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0c71ab",
                confirmButtonText: "Yes, Close balance!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('closingBalanceStore') }}",
                        method: "GET",
                        data: {
                            'date_to': date_to,
                            'date_from': date_from,
                            'presentClosingBalance': presentClosingBalance,
                            'previousMonthClosing': previousMonthClosing
                        },
                        success: function(result) {
                            //alert(JSON.stringify(result));
                            Swal.fire("Done!", "Balance closed succesfully!", "success");
                            //table.ajax.reload(null, false);
                            $('#closingBtn').hide();
                        },
                        error: function(response) {
                            //alert(JSON.stringify(response));
                            Swal.fire("Cancelled", result, "error");

                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Balance not closed :)", "error");
                }
            })
        }




        function generateAccountsSummaryPdf() {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            window.open("{{ url('account/summary/pdf/') }}" + "/" + date_from + "/" + date_to);
        }
    </script>
@endsection
