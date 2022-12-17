@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Sale Return list
                       <!--  <a class="btn btn-outline-success" style="margin-left:20px;" onclick="reloadDt()"><i
                                class="fas fa-sync"></i> Refresh </a> -->
                    </h3>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="managePurchaseTable" width='100%' class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td width="5%">SL.</td>
                                        <td width="20%">Sale Return Info</td>
                                        <td width="20%">Sale Info</td>
                                        <td width="20%">Customer info</td>
                                        <td width="15%">Amount</td>
                                        <td width="10%">Returned By</td>
                                        <td width="5%">Status</td>
                                        <td width="5%">Actions</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#managePurchaseTable').DataTable({
                'ajax': "{{ url('sale/saleReturnView/' . $saleType) }}",
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
                        url: "{{ route('sale.deleteSaleReturn') }}",
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
                            $('#editNameError').text(response.responseJSON.errors.name);
                            $('#editImageError').text(response.responseJSON.errors.image);
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
            var url = '{{ route('sale.return.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }
    </script>
@endsection
