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
                <form id="partyLedger" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Party Ledger<a class="btn btn-outline-success float-right" href="{{ url('sale/') }}"> <i class="fa fa-plus-circle"></i> view Sale</a></h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        @csrf
                                        <div class="form-group col-md-2"></div>
                                        <div class="form-group col-md-4">
                                            <label>Select Party Type: </label>
                                            <div class="form-check">
                                                <input id="customer" class="form-check-input " type="radio"
                                                    name="exampleRadios" id="exampleRadios1" value="option1" checked>
                                                <label class="form-check-label" for="exampleRadios1">
                                                    Customers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input id="supplier" class="form-check-input" type="radio"
                                                    name="exampleRadios" id="exampleRadios2" value="option2">
                                                <label class="form-check-label" for="exampleRadios2">
                                                    Suppliers
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4" id="customersRow">
                                            <label>Customers: </label>
                                            <select id="customers" name="customers" class="form-control input-sm">
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4" id="suppliersRow">
                                            <label>Suppliers: </label>
                                            <select id="suppliers" name="suppliers" class="form-control input-sm">
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2"></div>
                                        <div class="form-group col-md-2"></div>
                                        <div class="form-group col-md-4">
                                            <label>Date From: </label>
                                            <input type="date" class="form-control" id="dateFrom"
                                                value="{{ todayDate() }}" aria-describedby="emailHelp">

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Date To: </label>
                                            <input type="date" class="form-control" id="dateTo"
                                                aria-describedby="emailHelp" value="{{ todayDate() }}">
                                        </div>
                                        <div class="form-group col-md-6"></div>
                                        <div class="form-group col-md-4">
                                            <label> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block "
                                                onclick="generateReport()">Generate Report </button>
                                        </div>
                                        <div class="form-group col-md-12"></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
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
    <!-- /.content-wrapper -->

@endsection
@section('javascript')
    <script>
        $(function() {
            $("select").select2();
        });

        $('#suppliersRow').hide();

        $("#customer").change(function() {
            $('#partyLedger').trigger("reset");
            $('#suppliersRow').hide();
            $('#customersRow').show();
        })
        $("#supplier").change(function() {
            $('#customersRow').hide();
            $('#suppliersRow').show();
        })

        const generateReport = () => {
            var partyId = 0;
            let customerId = $("#customers").val();
            let supplierId = $("#suppliers").val();
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();

            let partyType = "customer";
            if (supplierId.length > 0) {
                partyId = supplierId;
                partyType = "supplier";
            } else {
                partyId = customerId;
            }

            if (partyId == "" || dateFrom == "" || dateTo == "") {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Select Properly!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }
            const data = [partyId, dateFrom, dateTo]
            window.open("{{ url('report/party-report') }}" + "/" + data);
        }
    </script>
@endsection
