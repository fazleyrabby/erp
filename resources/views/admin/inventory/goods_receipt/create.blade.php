@extends('admin.master')
@section('title', 'Create Goods Receipt Note')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Goods Receipt Note</h3>
                <div class="card-options">
                    <a href="{{ route('goods_receipts.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('goods_receipts.store') }}" id="grnForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Purchase Order <span class="text-danger">*</span></label>
                            <select name="purchase_order_id" id="purchase_order_id" class="form-control" required>
                                <option value="">-- Select PO --</option>
                                @foreach($pendingPOs as $po)
                                    <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name ?? 'N/A' }} ({{ $po->status }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="1"></textarea>
                        </div>
                    </div>

                    <div id="poProductsSection" class="mt-4 d-none">
                        <h5>PO Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="poProductsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Ordered Qty</th>
                                        <th>Previously Received</th>
                                        <th>Unit Price</th>
                                        <th>Received Qty</th>
                                    </tr>
                                </thead>
                                <tbody id="poProductsBody">
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check me-1"></i> Receive Goods</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    $('#purchase_order_id').on('change', function() {
        var poId = $(this).val();
        if (!poId) {
            $('#poProductsSection').addClass('d-none');
            $('#poProductsBody').empty();
            return;
        }

        $.get('{{ route("goods_receipts.load-po-products", "") }}/' + poId, function(response) {
            var tbody = $('#poProductsBody');
            tbody.empty();

            if (response.po && response.po.products) {
                response.po.products.forEach(function(item) {
                    var prevReceived = 0;
                    if (response.received && response.received[item.id]) {
                        response.received[item.id].forEach(function(r) {
                            prevReceived += parseFloat(r.received_quantity);
                        });
                    }
                    var remaining = parseFloat(item.quantity) - prevReceived;
                    if (remaining < 0) remaining = 0;

                    var row = '<tr>' +
                        '<td>' + (item.product ? item.product.name + ' - ' + item.product.code : 'Product #' + item.product_id) + '</td>' +
                        '<td>' + parseFloat(item.quantity) + '</td>' +
                        '<td>' + prevReceived + '</td>' +
                        '<td>' + parseFloat(item.unit_price).toFixed(2) + '</td>' +
                        '<td>' +
                            '<input type="hidden" name="products[' + item.id + '][purchase_order_product_id]" value="' + item.id + '">' +
                            '<input type="hidden" name="products[' + item.id + '][product_id]" value="' + item.product_id + '">' +
                            '<input type="hidden" name="products[' + item.id + '][ordered_quantity]" value="' + item.quantity + '">' +
                            '<input type="hidden" name="products[' + item.id + '][unit_price]" value="' + item.unit_price + '">' +
                            '<input type="number" name="products[' + item.id + '][received_quantity]" class="form-control received-qty" value="' + remaining + '" min="0" max="' + remaining + '" step="0.01">' +
                        '</td>' +
                    '</tr>';
                    tbody.append(row);
                });
            }

            $('#poProductsSection').removeClass('d-none');
        });
    });
</script>
@endsection
