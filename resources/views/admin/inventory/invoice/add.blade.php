@extends('admin.master')
@section('title', 'Create Invoice')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Invoice</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Invoice Type <span class="text-danger">*</span></label>
                            <select name="invoice_type" id="invoice_type" class="form-select" required>
                                <option value="Sales" {{ request('type') === 'Purchase' ? '' : 'selected' }}>Sales Invoice</option>
                                <option value="Purchase" {{ request('type') === 'Purchase' ? 'selected' : '' }}>Purchase Bill</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" id="partyLabel">Customer <span class="text-danger">*</span></label>
                            <select name="party_id" id="party_id" class="form-select" required>
                                <option value="">Select Party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}">{{ $party->name }} ({{ $party->code ?? $party->contact }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reference</label>
                            <select name="reference_type" class="form-select">
                                <option value="">None</option>
                                <option value="Sale">Sale</option>
                                <option value="Purchase">Purchase</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reference ID</label>
                            <select name="reference_id" class="form-select">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
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
                                        <th style="width:30%">Product</th>
                                        <th style="width:20%">Description</th>
                                        <th style="width:10%">Quantity</th>
                                        <th style="width:15%">Unit Price</th>
                                        <th style="width:15%">Total</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr class="item-row">
                                        <td class="text-center item-sl">1</td>
                                        <td>
                                            <select name="items[0][product_id]" class="form-select product-select">
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">{{ $product->name }} - {{ $product->code }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-control" placeholder="Item description">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control item-qty" value="1" min="1" step="any">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][unit_price]" class="form-control item-price" value="0" min="0" step="any">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-total" value="0" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                                        <td><input type="text" id="totalAmount" class="form-control" value="0" readonly></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">Discount:</td>
                                        <td><input type="number" name="discount" id="discount" class="form-control" value="0" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">Carrying Cost:</td>
                                        <td><input type="number" name="carrying_cost" id="carrying_cost" class="form-control" value="0" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">VAT:</td>
                                        <td><input type="number" name="vat" id="vat" class="form-control" value="0" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">AIT:</td>
                                        <td><input type="number" name="ait" id="ait" class="form-control" value="0" min="0" step="any" oninput="calculateGrandTotal()"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                        <td><input type="text" id="grandTotal" class="form-control" value="0" readonly></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Terms & Conditions</label>
                            <textarea name="terms_conditions" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Create Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    let itemIndex = 1;

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
            const total = qty * price;
            row.find('.item-total').val(total.toFixed(2));
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

        const grandTotal = (total - discount) + carrying + vat + ait;
        $('#grandTotal').val(grandTotal.toFixed(2));
    }

    $(document).ready(function() {
        bindItemEvents();
    });
</script>
@endsection
