@extends('admin.master')
@section('title', 'Edit Invoice ' . $invoice->invoice_no)

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Invoice: {{ $invoice->invoice_no }}</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Party <span class="text-danger">*</span></label>
                            <select name="party_id" class="form-select" required>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" {{ $party->id == $invoice->party_id ? 'selected' : '' }}>{{ $party->name }} ({{ $party->code ?? $party->contact }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ $invoice->date }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $invoice->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Invoice Items <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-success btn-sm" onclick="addItemRow()"><i class="fa fa-plus me-1"></i> Add Item</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width:5%">SL</th>
                                        <th style="width:25%">Product</th>
                                        <th style="width:25%">Description</th>
                                        <th style="width:10%">Quantity</th>
                                        <th style="width:12%">Unit Price</th>
                                        <th style="width:12%">Total</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @foreach($invoice->items as $index => $item)
                                    <tr class="item-row">
                                        <td class="text-center item-sl">{{ $index + 1 }}</td>
                                        <td>
                                            <select name="items[{{ $index }}][product_id]" class="form-select product-select">
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][description]" class="form-control" value="{{ $item->description }}">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control item-qty" value="{{ $item->quantity }}" min="1" step="any">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]" class="form-control item-price" value="{{ $item->unit_price }}" min="0" step="any">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-total" value="{{ $item->total_price }}" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                                        <td><input type="text" id="totalAmount" class="form-control" value="{{ $invoice->total_amount }}" readonly></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">Discount:</td>
                                        <td><input type="number" name="discount" id="discount" class="form-control" value="{{ $invoice->discount }}" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">Carrying Cost:</td>
                                        <td><input type="number" name="carrying_cost" id="carrying_cost" class="form-control" value="{{ $invoice->carrying_cost }}" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">VAT:</td>
                                        <td><input type="number" name="vat" id="vat" class="form-control" value="{{ $invoice->vat }}" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">AIT:</td>
                                        <td><input type="number" name="ait" id="ait" class="form-control" value="{{ $invoice->ait }}" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                        <td><input type="text" id="grandTotal" class="form-control" value="{{ $invoice->grand_total }}" readonly></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $invoice->notes }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Terms & Conditions</label>
                            <textarea name="terms_conditions" class="form-control" rows="3">{{ $invoice->terms_conditions }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Update Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    let itemIndex = {{ $invoice->items->count() }};

    function addItemRow() {
        const html = `
            <tr class="item-row">
                <td class="text-center item-sl">${itemIndex + 1}</td>
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select product-select">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">{{ $product->name }} - {{ $product->code }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Item description">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control item-qty" value="1" min="1" step="any">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" class="form-control item-price" value="0" min="0" step="any">
                </td>
                <td>
                    <input type="text" class="form-control item-total" value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;
        $('#itemsBody').append(html);
        itemIndex++;
        bindItemEvents();
    }

    function removeItemRow(btn) {
        if ($('.item-row').length > 1) {
            $(btn).closest('tr').remove();
            renumberItems();
            calculateGrandTotal();
        }
    }

    function renumberItems() {
        $('.item-row').each(function(i) {
            $(this).find('.item-sl').text(i + 1);
        });
    }

    function bindItemEvents() {
        $('.item-qty, .item-price').off('input').on('input', function() {
            const row = $(this).closest('tr');
            const qty = parseFloat(row.find('.item-qty').val()) || 0;
            const price = parseFloat(row.find('.item-price').val()) || 0;
            row.find('.item-total').val((qty * price).toFixed(2));
            calculateGrandTotal();
        });
        $('.product-select').off('change').on('change', function() {
            const selected = $(this).find('option:selected');
            const price = selected.data('price');
            if (price && $(this).closest('tr').find('.item-price').val() == 0) {
                $(this).closest('tr').find('.item-price').val(price);
                $(this).closest('tr').find('.item-qty').trigger('input');
            }
        });
    }

    function calculateGrandTotal() {
        let total = 0;
        $('.item-total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalAmount').val(total.toFixed(2));
        const discount = parseFloat($('#discount').val()) || 0;
        const carrying = parseFloat($('#carrying_cost').val()) || 0;
        const vat = parseFloat($('#vat').val()) || 0;
        const ait = parseFloat($('#ait').val()) || 0;
        $('#grandTotal').val(((total - discount) + carrying + vat + ait).toFixed(2));
    }

    $(document).ready(function() {
        bindItemEvents();
    });
</script>
@endsection
