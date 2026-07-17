@extends('admin.master')
@section('title', 'Request Purchase Order')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('purchase_orders.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Purchase Order Request</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control select2" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->contact }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <h4>Items to Order</h4>
                    <table class="table table-bordered table-hover" id="product_table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Expected Unit Price</th>
                                <th>Total Estimate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="products[0][product_id]" class="form-control select2 product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->purchase_price ?? 0 }}">{{ $product->name }} - {{ $product->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="products[0][quantity]" class="form-control qty" value="1" min="1" required></td>
                                <td><input type="number" name="products[0][unit_price]" class="form-control price" value="0" step="0.01" required></td>
                                <td><input type="text" class="form-control row-total" readonly></td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"><button type="button" class="btn btn-sm btn-success" id="add_row">Add Another Item</button></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Estimated Total:</strong></td>
                                <td><input type="text" id="grand_total" class="form-control" readonly></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="form-group mt-3">
                        <label>Notes / Justification for Purchase</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Submit for Approval</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        // Initialize select2
        $("select").select2({ width: '100%' });

        let rowIndex = 1;
        
        function calculateTotal() {
            let total = 0;
            $('#product_table tbody tr').each(function() {
                let qty = parseFloat($(this).find('.qty').val()) || 0;
                let price = parseFloat($(this).find('.price').val()) || 0;
                let rowTotal = qty * price;
                $(this).find('.row-total').val(rowTotal.toFixed(2));
                total += rowTotal;
            });
            $('#grand_total').val(total.toFixed(2));
        }

        $(document).on('change', '.product-select', function() {
            let price = $(this).find(':selected').data('price');
            $(this).closest('tr').find('.price').val(price);
            calculateTotal();
        });

        $(document).on('input', '.qty, .price', function() {
            calculateTotal();
        });

        $('#add_row').click(function() {
            // Destroy select2 before cloning to prevent broken UI
            $('#product_table tbody tr:first select').select2('destroy');
            
            let newRow = $('#product_table tbody tr:first').clone();
            
            // Re-initialize select2 on the first row immediately
            $('#product_table tbody tr:first select').select2({ width: '100%' });

            newRow.find('select').attr('name', 'products[' + rowIndex + '][product_id]').val('');
            newRow.find('.qty').attr('name', 'products[' + rowIndex + '][quantity]').val(1);
            newRow.find('.price').attr('name', 'products[' + rowIndex + '][unit_price]').val(0);
            newRow.find('.row-total').val('');
            
            $('#product_table tbody').append(newRow);
            
            // Initialize select2 on the new row
            newRow.find('select').select2({ width: '100%' });
            
            rowIndex++;
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#product_table tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calculateTotal();
            }
        });
    });
</script>
@endsection
