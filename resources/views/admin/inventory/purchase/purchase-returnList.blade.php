@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase View
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-md-12">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <div class="card-header">
                                <h3>Purchase Return list
                                   <!--  <a class="btn btn-primary float-right" href="{{ route('purchase.add') }}"> <i
                                            class="fa fa-plus-circle"></i> Add Purchase</a> -->
                                    <!-- <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i
                                            class="fas fa-sync"></i> Refresh </a> -->
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="managePurchaseTable" width="100%"
                                            class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <td width="5%">SL.</td>
                                                    <td width="20%">Return Info</td>
                                                    <td width="20%">Purchase Info</td>
                                                    <td width="25%">Supplier Info</td>
                                                    <td width="10%">Amount</td>
                                                    <td width="10%">Returnrd By</td>
                                                    <td width="10%">Actions</td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#managePurchaseTable').DataTable({
                'ajax': "{{ route('purchaseReturnView') }}",
                processing: true,
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete category!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('deletePurchaseReturn') }}",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            Swal.fire("Deleted!", result.success, "success");
                            table.ajax.reload(null, false);
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary Category is safe :)", "error");
                }
            })
        }



        function printPurchase(id) {
            window.open("{{ url('purchase/return/invoice/') }}" + "/" + id);
        }

        function purchaseReturn(id) {
            window.location.href = "{{ url('purchase/purchase-return') }}" + "/" + id;

        }
    </script>
@endsection
