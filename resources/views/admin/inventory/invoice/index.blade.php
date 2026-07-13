@extends('admin.master')
@section('title', 'Manage ' . ucfirst($type) . ' Invoices')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ ucfirst($type) }} Invoices</h3>
                <div class="card-options">
                    <a href="{{ route('invoices.add') }}?type={{ $type }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Add New Invoice</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <div class="d-flex mb-3">
                    <a href="{{ route('invoices.index', ['type' => 'Sales']) }}" class="btn btn-sm {{ $type === 'Sales' ? 'btn-primary' : 'btn-outline-primary' }} me-2">Sales Invoices</a>
                    <a href="{{ route('invoices.index', ['type' => 'Purchase']) }}" class="btn btn-sm {{ $type === 'Purchase' ? 'btn-primary' : 'btn-outline-primary' }}">Purchase Bills</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>{{ $type === 'Sales' ? 'Customer' : 'Supplier' }}</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                            <tr>
                                <td>{{ $inv->invoice_no }}</td>
                                <td>{{ $inv->date }}</td>
                                <td>{{ $inv->party_name }}</td>
                                <td>{{ number_format($inv->grand_total, 2) }}</td>
                                <td>{{ number_format($inv->paid_amount, 2) }}</td>
                                <td>{{ number_format($inv->due_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $inv->status_badge }}">{{ $inv->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('invoices.pdf', $inv->id) }}" class="btn btn-sm btn-secondary" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    @if($inv->status !== 'Paid' && $inv->status !== 'Cancelled')
                                        <a href="{{ route('invoices.edit', $inv->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No {{ $type }} invoices found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
