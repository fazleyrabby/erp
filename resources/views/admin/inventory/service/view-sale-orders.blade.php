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
                    Service Orders list
                    <a class="mr-2 btn btn-primary float-right" href="{{ route('sale.service.add') }}"> <i
                            class="fa fa-plus-circle"></i> Add Service Order</a>
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
                                <td width="5%" class="text-center">SL.</td>
                                <td width="7%" class="text-center">Job No</td>
                                <td width="8%" class="text-center">Job created Date</td>
                                <td width="20%" class="text-center">Product Info</td>
                                <td width="15%" class="text-center">Customer Info</td>
                                <td width="13%" class="text-center">Amount</td>
                                <td width="7%" class="text-center">Booked By</td>
                                <td width="10%" class="text-center">Status</td>
                                <td width="5%" class="text-center">Actions</td>
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
    table = $('#manageSaleTable').DataTable({
        'ajax': "{{ route('sale.service.getSaleOrders') }}",
        processing: true,
    });
});

function reloadDt() {
    table.ajax.reload(null, false);
}





function saleInvoice(id){
    var url = '{{ route('sale.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
}



function statusComplete(id) {

    var _token = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
        title: "Are you sure ?",
        text: "You can only change this action if the order is ready to deliverd!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Ready to deliverd!",
        closeOnConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('sale.service.statusComplete') }}",
                method: "POST",
                data: {
                    "id": id,
                    "_token": _token
                },
                success: function(result) {
                    Swal.fire("Updated!", result.success, "success");
                    reloadDt();
                },
                error: function(response) {
                    Swal.fire("Error", "Please try again", "error");
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire("Cancelled", "No Changes..", "warning");
        }
    });
}

function editSaleOrder(id) {
    var url = '{{ route('sale.service.edit.editSaleOrder', ':id') }}';
    url = url.replace(':id', id);
    window.location.href = url;
}

function createOrderToWalkinSale(id) {
    //alert(id);
    var url = '{{ route('sale.service.createOrderToWalkinSale', ':id') }}';
    url = url.replace(':id', id);
    window.location.href = url;
}

function orderInvoice(id) {
    var url = '{{ route('sale.service.orderInvoice', ':id') }}';
    url = url.replace(':id', id);
    window.open(url);
}

function completeInvoice(id) {
    var url = '{{ route('sale.service.completeInvoice', ':id') }}';
    url = url.replace(':id', id);
    window.open(url);
}


function seeFeedbacks(id){
    alert(id);
}





</script>
@endsection