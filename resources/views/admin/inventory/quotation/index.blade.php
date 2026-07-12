@extends('admin.master')
@section('title', 'Manage Quotations')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Quotations</h3>
                <div class="card-options">
                    <a href="{{ route('quotations.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Add New Quotation</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table table-bordered table-vcenter">
                    <thead>
                        <tr>
                            <th>Quotation No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $quotation)
                        <tr>
                            <td>{{ $quotation->quotation_no }}</td>
                            <td>{{ $quotation->date }}</td>
                            <td>{{ $quotation->customer->name ?? 'N/A' }}</td>
                            <td>{{ number_format($quotation->grand_total, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $quotation->status == 'Pending' ? 'warning' : 'success' }}">{{ $quotation->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('quotations.pdf', $quotation->id) }}" class="btn btn-sm btn-info" target="_blank"><i class="fa fa-file-pdf-o me-1"></i> PDF</a>
                                @if($quotation->status !== 'Converted')
                                    <a href="{{ route('quotations.convert', $quotation->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to convert this quotation to a Sale Order?')"><i class="fa fa-exchange me-1"></i> Convert to Order</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No quotations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $quotations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
