@extends('admin.master')
@section('title', 'View Purchase Order')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Purchase Order #{{ $po->id }}</h3>
                <div class="card-options">
                    <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-reply me-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Supplier Details</h5>
                        <p>
                            <strong>Name:</strong> {{ $po->supplier->name ?? 'N/A' }}<br>
                            <strong>Contact:</strong> {{ $po->supplier->contact ?? 'N/A' }}<br>
                            <strong>Address:</strong> {{ $po->supplier->address ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <h5>Order Details</h5>
                        <p>
                            <strong>Date:</strong> {{ date('d M Y', strtotime($po->date)) }}<br>
                            <strong>Status:</strong> <span class="badge badge-info">{{ $po->status }}</span><br>
                            <strong>Created By:</strong> {{ $po->creator->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>SL</th>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->products as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->product->code ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                            <td class="text-right"><strong>{{ number_format($po->grand_total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                @if($po->notes)
                <div class="mt-4">
                    <h5>Notes</h5>
                    <p>{{ $po->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
