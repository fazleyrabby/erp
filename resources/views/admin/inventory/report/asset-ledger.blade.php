@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Asset
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
                    <div class="row g-3">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Company Asset View
                                        <a class="btn btn-success float-right" href="{{ url('sale/') }}"> <i
                                                class="fa fa-plus-circle"></i> view Sale</a>
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="form-group mb-3 col-md-10 offset-md-2">
                                            <h1>Your Asset : </h1>
                                        </div>
                                        <div class="form-group mb-3 col-md-2">
                                        </div>
                                        <div class="form-group mb-3 col-md-10">
                                            <label><strong>Company Current Asset : </strong></label>
                                            <table border="1" style="width:60%;">
                                                <thead>
                                                    <tr>
                                                        <th>Total Product Value</th>
                                                        <th class="pl-4"> {{ $companyAsset[0] }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Party (Customers+Suplliers) Payable</th>
                                                        <th class="pl-4"> {{ $companyAsset[1] }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Company Payable</th>
                                                        <th class="pl-4"> {{ $companyAsset[2] }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Your Net Worth =</th>
                                                        <th class="pl-2 p-2">{!! Session::get('companySettings')[0]['currency'] !!}
                                                            {{ $companyAsset[3] }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <a type="button" id="checkOutCart" class=" my_button float-right"
                                                target="_blank"></a>
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
        const generateReport = () => {
            alert("Asset Report")
            let report = 'asset';
            window.open("{{ url('report/asset-report') }}" + "/" + report);
        }
    </script>
@endsection
