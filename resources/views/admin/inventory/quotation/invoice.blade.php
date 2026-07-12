<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation #{{ $quotation->quotation_no }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
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
                                <h2>QUOTATION</h2>
                            </td>
                            <td>
                                Quotation #: {{ $quotation->quotation_no }}<br>
                                Created: {{ $quotation->date }}<br>
                                Status: {{ $quotation->status }}
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
                                <strong>To:</strong><br>
                                {{ $quotation->customer->name ?? 'N/A' }}<br>
                                {{ $quotation->customer->contact ?? 'N/A' }}<br>
                                {{ $quotation->customer->address ?? '' }}
                            </td>
                            <td>
                                <strong>Prepared By:</strong><br>
                                {{ $quotation->creator->name ?? 'Admin' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Item</td>
                <td class="text-center">Quantity</td>
                <td class="text-right">Unit Price</td>
                <td class="text-right">Total</td>
            </tr>
            @foreach($quotation->products as $item)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($quotation->total_amount, 2) }}</td>
            </tr>
            @if($quotation->discount > 0)
            <tr class="total">
                <td colspan="3" class="text-right">Discount:</td>
                <td class="text-right">- {{ number_format($quotation->discount, 2) }}</td>
            </tr>
            @endif
            @if($quotation->vat > 0)
            <tr class="total">
                <td colspan="3" class="text-right">VAT:</td>
                <td class="text-right">+ {{ number_format($quotation->vat, 2) }}</td>
            </tr>
            @endif
            <tr class="total">
                <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                <td class="text-right"><strong>{{ number_format($quotation->grand_total, 2) }}</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
