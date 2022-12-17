@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-capitalize">
                        @if ($saleType == 'walkin')
                            Walkin Sale
                        @elseif ($saleType == 'service')
                            Service Sale View List
                        @elseif ($saleType == 'ts')
                            Temporary Sale
                        @elseif ($saleType == 'FS')
                            Final Sale
                        @endif
                        list
                        @if ($saleType == 'walkin_sale')
                        <a class="mr-2 btn btn-primary float-right" href="{{ route('sale.add', ['type' => $saleType]) }}"> <i class="fa fa-plus-circle"></i> Add Sale</a>
                        @endif
                        
                        @if ($saleType == 'ts')
                            <a class="mr-2 btn btn-primary float-right"
                                href="{{ route('sale.temporarySaleAdjustment', ['type' => $saleType]) }}"> <i
                                    class="fa fa-plus-circle"></i> Sale Adjustment</a>
                        @endif
                        <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()"><i
                                class="fas fa-sync"></i> Refresh </a>
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <input type="hidden" id="salesType" name="salesType" value="{{ $saleType }}">
                    <div class="table-responsive">
                        <table id="manageSaleTable" width="100%" class="table table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <td width="5%">SL.</td>
                                    <td width="7%">Sale Code.</td>
                                    <td width="15%">Sale Date</td>
                                    <td width="13%">COA Name</td>
                                    <td width="25%">Customer Info</td>
                                    <td width="15%">Amount</td>
                                    <td width="10%">Sold By</td>
                                    <td width="5%">Status</td>
                                    <td width="5%">Actions</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        var table;

        $(document).ready(function() {
            var type = $("#salesType").val();
            table = $('#manageSaleTable').DataTable({
                'ajax': "{{ url('sale/view/' . $saleType) }}",
                processing: true,
            });
        });

        function reloadDt() {
            table.ajax.reload(null, false);
        }

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Sales!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('sale.delete') }}",
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
                    Swal.fire("Cancelled", "Your Imaginary Sales :)", "error");
                }
            })
        }

        function saleReturn(id) {

            var url = '{{ route('sale.sale-return', ':id') }}';

            url = url.replace(':id', id);

            window.location.href = url;
        }

        function printPurchase(id) {
            window.open("{{ url('sale/invoice/') }}" + "/" + id);
        }

        function printTsSales(id) {
            window.open("{{ url('sale/tsInvoice/') }}" + "/" + id);
        }
    </script>
@endsection
