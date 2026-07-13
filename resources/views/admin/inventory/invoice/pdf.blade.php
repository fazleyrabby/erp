<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; line-height: 22px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.15); }
        .invoice-box table { width: 100%; border-collapse: collapse; }
        .invoice-box table td { padding: 8px 5px; vertical-align: top; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 40px; line-height: 40px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #f5f5f5; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .fw-bold { font-weight: bold; }
        .text-danger { color: #dc3545; }
        .text-success { color: #28a745; }
        .mb-1 { margin-bottom: 4px; }
        .mt-3 { margin-top: 16px; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .bg-success { background: #28a745; color: #fff; }
        .bg-warning { background: #ffc107; color: #333; }
        .bg-danger { background: #dc3545; color: #fff; }
        .bg-info { background: #17a2b8; color: #fff; }
        .bg-secondary { background: #6c757d; color: #fff; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>{{ $invoice->invoice_type === 'Sales' ? 'INVOICE' : 'BILL' }}</h2>
                            </td>
                            <td class="text-right">
                                <strong>{{ $invoice->invoice_no }}</strong><br>
                                Date: {{ $invoice->date }}<br>
                                @if($invoice->due_date)
                                Due Date: {{ $invoice->due_date }}<br>
                                @endif
                                Status: <span class="status-badge bg-{{ $invoice->status === 'Paid' ? 'success' : ($invoice->status === 'Overdue' ? 'danger' : ($invoice->status === 'Partial' ? 'warning' : ($invoice->status === 'Sent' ? 'info' : 'secondary'))) }}">{{ $invoice->status }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>{{ $invoice->invoice_type === 'Sales' ? 'Bill To' : 'From' }}:</strong><br>
                                {{ $invoice->party_name }}<br>
                                {{ $invoice->party_contact }}<br>
                                {{ $invoice->party_address }}
                            </td>
                            <td class="text-right">
                                <strong>Prepared By:</strong><br>
                                {{ $invoice->creator->name ?? 'Admin' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if($invoice->description)
            <tr>
                <td colspan="4" style="padding-bottom:10px">
                    <strong>Description:</strong> {{ $invoice->description }}
                </td>
            </tr>
            @endif
            <tr class="heading">
                <td style="width:5%">#</td>
                <td style="width:35%">Item</td>
                <td style="width:15%" class="text-center">Quantity</td>
                <td style="width:20%" class="text-right">Unit Price</td>
                <td style="width:25%" class="text-right">Total</td>
            </tr>
            @forelse($invoice->items as $index => $item)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? ($item->description ?? 'Unknown Item') }}</td>
                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">No items.</td></tr>
            @endforelse
            <tr>
                <td colspan="4" class="text-right">Total Amount:</td>
                <td class="text-right">{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
            <tr>
                <td colspan="4" class="text-right">Discount:</td>
                <td class="text-right">-{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            @endif
            @if($invoice->carrying_cost > 0)
            <tr>
                <td colspan="4" class="text-right">Carrying Cost:</td>
                <td class="text-right">{{ number_format($invoice->carrying_cost, 2) }}</td>
            </tr>
            @endif
            @if($invoice->vat > 0)
            <tr>
                <td colspan="4" class="text-right">VAT:</td>
                <td class="text-right">{{ number_format($invoice->vat, 2) }}</td>
            </tr>
            @endif
            @if($invoice->ait > 0)
            <tr>
                <td colspan="4" class="text-right">AIT:</td>
                <td class="text-right">{{ number_format($invoice->ait, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="4" class="text-right fw-bold">Grand Total:</td>
                <td class="text-right fw-bold">{{ number_format($invoice->grand_total, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Paid Amount:</td>
                <td class="text-right">{{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right fw-bold">Due Amount:</td>
                <td class="text-right fw-bold text-{{ $invoice->due_amount > 0 ? 'danger' : 'success' }}">{{ number_format($invoice->due_amount, 2) }}</td>
            </tr>
        </table>

        @if($invoice->notes)
        <div class="mt-3">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
        @endif
        @if($invoice->terms_conditions)
        <div class="mt-3">
            <strong>Terms & Conditions:</strong><br>
            {{ $invoice->terms_conditions }}
        </div>
        @endif
    </div>
</body>
</html>
