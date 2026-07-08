@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Dues
@endsection
@section('content')
    
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div id="msg_error"></div>
                <form id="saleProducts" method="POST">
                    <div class="row g-3">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Party With Due
                                        <a class="btn btn-success float-right" href="{{ url('sale/') }}"> <i
                                                class="fa fa-plus-circle"></i> view Sale</a>
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row g-3">
                                        @csrf
                                        <div class="form-group mb-3 col-md-4" id="suppliersRow">
                                            <label>Party Type : </label>
                                            <select id="partyType" name="partyType" class="form-control input-sm">
                                                <option value="">Select Party Type </option>
                                                @for ($i = 0; $i < count($parties); $i++)
                                                    <option value="{{ $parties[$i] }}">{{ $parties[$i] }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group mb-3 col-md-4">
                                            <label>Date From: </label>
                                            <input type="date" class="form-control" id="dateFrom"
                                                value="{{ todayDate() }}" aria-describedby="emailHelp">

                                        </div>
                                        <div class="form-group mb-3 col-md-4">
                                            <label>Date To: </label>
                                            <input type="date" class="form-control" id="dateTo"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}">
                                        </div>
                                        <div class="form-group mb-3 col-md-8"></div>
                                        <div class="form-group mb-3 col-md-4">
                                            <label class="p-2"> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block "
                                                onclick="generateReport()"> Generate Report </button>
                                        </div>
                                        <div class="form-group mb-3 col-md-12"></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row g-3">
                                        <div class="col-md-12"></div>
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
    

@endsection
@section('javascript')
    <script>
        $(function() {
            $("select").select2();
        });

        $("#partyType").change(function() {
            $('#loading').show();

            setTimeout(function() {
                $('#loading').hide();

            }, 100);
        });

        /*const getPartyWithDue = () => {
            let _token = $('input[name="_token"]').val();
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();
            let partyType = $("#partyType").val();

            if (dateFrom.length <= 0 || dateTo.length <= 0 || partyType.length <= 0) {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Please Select Properly!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }

            let fd = new FormData();
            fd.append('partyType', partyType);
            fd.append('dateFrom', dateFrom);
            fd.append('dateTo', dateTo);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('report/getParty-with-due') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    $("#managePartyTable").html('');
                    $("#managePartyTable").html(result.data);
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
        }*/

        const generateReport = () => {
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();
            let partyType = $("#partyType").val();


            if (dateFrom.length <= 0 || dateTo.length <= 0 || partyType.length <= 0) {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Please Select Properly!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }

            window.open("{{ url('report/party-with-due-report') }}" + "/" + dateFrom + "/" + dateTo + "/" +
                partyType);
        }
    </script>
@endsection
