@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 style="float:left;"> Purchase list </h3>
                    <a class="btn btn-primary float-right" href="{{ route('purchase.add') }}"> <i
                            class="fa fa-plus-circle"></i> Add Purchase</a>
                    <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i
                            class="fas fa-sync"></i> Refresh </a>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="managePurchaseTable" width="100%" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td width="5%">SL.</td>
                                    <td width="10%">Purchase Code</td>
                                    <td width="12%">Purchase Date</td>
                                    <td width="15%">COA Name</td>
                                    <td width="20%">Supplier Info</td>
                                    <td width="10%">Amount</td>
                                    <td width="10%">Purchased By</td>
                                    <td width="5%">Status</td>
                                    <td width="5%">Actions</td>
                                </tr>
                            </thead>
                        </table>
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
                'ajax': "{{ route('purchase.viewPurchase') }}",
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
                confirmButtonText: "Yes, delete purchase!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');

                    var fd = new FormData();
                    fd.append('_token', _token);
                    fd.append('id', id);
                    $.ajax({
                        url: "{{ route('purchase.delete') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            Swal.fire("Deleted!", result.Success, "success");
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
                    Swal.fire("Cancelled", "Your imaginary purchase is safe :)", "error");
                }
            })
        }

        function reloadDt() {
            table.ajax.reload(null, false);
        }

        function printPurchase(id) {
            var url = '{{ route('purchase.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }

        function purchaseReturn(id) {
            window.location.href = "{{ url('purchase/purchase-return') }}" + "/" + id;
        }
    </script>
@endsection
