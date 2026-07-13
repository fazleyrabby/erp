@extends('admin.master')
@section('title', 'Invoice ' . $invoice->invoice_no)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invoice: {{ $invoice->invoice_no }}</h3>
                <div class="card-options">
                    <span class="badge {{ $invoice->status_badge }} me-2" style="font-size:0.9rem">{{ $invoice->status }}</span>
                    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-secondary me-1" target="_blank"><i class="fa fa-file-pdf-o me-1"></i> PDF</a>
                    <a href="{{ route('invoices.index', ['type' => $invoice->invoice_type]) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i> Back</a>
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

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <h5>{{ $invoice->invoice_type === 'Sales' ? 'Customer' : 'Supplier' }} Information</h5>
                        <p class="mb-1"><strong>{{ $invoice->party_name }}</strong></p>
                        <p class="mb-1">{{ $invoice->party_contact }}</p>
                        <p class="mb-1">{{ $invoice->party_address }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5>Invoice Details</h5>
                        <p class="mb-1"><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $invoice->date }}</p>
                        <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge {{ $invoice->status_badge }}">{{ $invoice->status }}</span></p>
                    </div>
                </div>

                @if($invoice->description)
                <div class="mb-3">
                    <strong>Description:</strong> {{ $invoice->description }}
                </div>
                @endif

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->description ?? '' }}</td>
                                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No items found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                                <td class="text-end">{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                            @if($invoice->discount > 0)
                            <tr>
                                <td colspan="5" class="text-end">Discount:</td>
                                <td class="text-end">-{{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->carrying_cost > 0)
                            <tr>
                                <td colspan="5" class="text-end">Carrying Cost:</td>
                                <td class="text-end">{{ number_format($invoice->carrying_cost, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->vat > 0)
                            <tr>
                                <td colspan="5" class="text-end">VAT:</td>
                                <td class="text-end">{{ number_format($invoice->vat, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->ait > 0)
                            <tr>
                                <td colspan="5" class="text-end">AIT:</td>
                                <td class="text-end">{{ number_format($invoice->ait, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                <td class="text-end"><strong>{{ number_format($invoice->grand_total, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end">Paid Amount:</td>
                                <td class="text-end">{{ number_format($invoice->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Due Amount:</strong></td>
                                <td class="text-end"><strong class="text-{{ $invoice->due_amount > 0 ? 'danger' : 'success' }}">{{ number_format($invoice->due_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($invoice->notes)
                <div class="mb-2"><strong>Notes:</strong> {{ $invoice->notes }}</div>
                @endif
                @if($invoice->terms_conditions)
                <div class="mb-3"><strong>Terms & Conditions:</strong> {{ $invoice->terms_conditions }}</div>
                @endif

                @if($invoice->status !== 'Cancelled')
                <div class="d-flex gap-2 mb-3">
                    @if($invoice->status === 'Draft')
                    <form action="{{ route('invoices.status', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="Sent">
                        <button type="submit" class="btn btn-info"><i class="fa fa-paper-plane me-1"></i> Mark as Sent</button>
                    </form>
                    @endif
                    @if($invoice->status !== 'Paid' && $invoice->status !== 'Cancelled')
                    <button type="button" class="btn btn-success" onclick="$('#paymentModal').modal('show')"><i class="fa fa-money me-1"></i> Add Payment</button>
                    @endif
                    @if($invoice->status !== 'Paid' && $invoice->status !== 'Cancelled')
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning"><i class="fa fa-edit me-1"></i> Edit</a>
                    @endif
                    <form action="{{ route('invoices.status', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="Cancelled">
                        <button type="submit" class="btn btn-dark" onclick="return confirm('Cancel this invoice?')"><i class="fa fa-ban me-1"></i> Cancel</button>
                    </form>
                </div>
                @endif

                @if($invoice->payments->count() > 0)
                <div class="mt-4">
                    <h5>Payment History</h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->payments as $pmt)
                            <tr>
                                <td>{{ $pmt->payment_date }}</td>
                                <td>{{ number_format($pmt->amount, 2) }}</td>
                                <td>{{ $pmt->payment_method }}</td>
                                <td>{{ $pmt->reference ?? 'N/A' }}</td>
                                <td>{{ $pmt->notes ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($invoice->status !== 'Paid' && $invoice->status !== 'Cancelled')
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('invoices.payment', $invoice->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" value="{{ $invoice->due_amount }}" max="{{ $invoice->due_amount }}" min="0.01" step="any" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Check">Check</option>
                            <option value="Mobile Banking">Mobile Banking</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference</label>
                        <input type="text" name="reference" class="form-control" placeholder="Check/Transaction No">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-money me-1"></i> Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
