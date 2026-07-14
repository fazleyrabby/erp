@extends('admin.master')
@section('title', 'GRN - ' . $grn->grn_number)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Goods Receipt Note: {{ $grn->grn_number }}</h3>
                <div class="card-options">
                    <a href="{{ route('goods_receipts.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>GRN Number:</strong> {{ $grn->grn_number }}
                    </div>
                    <div class="col-md-3">
                        <strong>Date:</strong> {{ $grn->date }}
                    </div>
                    <div class="col-md-3">
                        <strong>PO Reference:</strong> {{ $grn->purchaseOrder->po_number ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Supplier:</strong> {{ $grn->supplier->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-3 mt-2">
                        <strong>Status:</strong>
                        @if($grn->status == 'Received')
                            <span class="badge bg-success">Received</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </div>
                    <div class="col-md-3 mt-2">
                        <strong>Created By:</strong> {{ $grn->creator->name ?? 'N/A' }}
                    </div>
                    @if($grn->notes)
                    <div class="col-12 mt-2">
                        <strong>Notes:</strong> {{ $grn->notes }}
                    </div>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product</th>
                                <th>Ordered Qty</th>
                                <th>Received Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grn->products as $i => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? 'N/A' }} - {{ $item->product->code ?? '' }}</td>
                                <td>{{ $item->ordered_quantity }}</td>
                                <td>{{ $item->received_quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->received_quantity * $item->unit_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No products in this GRN.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
