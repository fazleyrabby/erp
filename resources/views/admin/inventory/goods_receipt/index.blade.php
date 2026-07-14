@extends('admin.master')
@section('title', 'Goods Receipt Notes')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Goods Receipt Notes</h3>
                <div class="card-options">
                    <a href="{{ route('goods_receipts.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> New GRN</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>GRN #</th>
                                <th>Date</th>
                                <th>PO #</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $grn)
                            <tr>
                                <td>{{ $grn->grn_number }}</td>
                                <td>{{ $grn->date }}</td>
                                <td>{{ $grn->purchaseOrder->po_number ?? 'N/A' }}</td>
                                <td>{{ $grn->supplier->name ?? 'N/A' }}</td>
                                <td>
                                    @if($grn->status == 'Received')
                                        <span class="badge bg-success">Received</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $grn->creator->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('goods_receipts.show', $grn->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No goods receipt notes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $receipts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
