@extends('admin.master')
@section('title', 'Manage Purchase Orders')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Purchase Orders</h3>
                <div class="card-options">
                    <a href="{{ route('purchase_orders.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Request Purchase Order</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table class="table table-bordered table-vcenter table-hover">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Total Amount</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->date }}</td>
                            <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                            <td>{{ number_format($po->total_amount, 2) }}</td>
                            <td>{{ $po->creator->name ?? 'N/A' }}</td>
                            <td>
                                @if($po->status == 'Pending')
                                    <span class="badge badge-warning">Pending Approval</span>
                                @elseif($po->status == 'Approved')
                                    <span class="badge badge-success">Approved by {{ $po->approver->name ?? 'Manager' }}</span>
                                @else
                                    <span class="badge badge-info">{{ $po->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('purchase_orders.view', $po->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye me-1"></i> View</a>
                                
                                @if($po->status == 'Pending')
                                    <a href="{{ route('purchase_orders.approve', $po->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this PO?')"><i class="fa fa-check me-1"></i> Approve</a>
                                @elseif($po->status == 'Approved')
                                    <a href="{{ route('purchase_orders.convert', $po->id) }}" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to convert this into a Purchase? This will clear your current cart.')"><i class="fa fa-exchange me-1"></i> Convert to Purchase</a>
                                @endif
                                

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No purchase orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
