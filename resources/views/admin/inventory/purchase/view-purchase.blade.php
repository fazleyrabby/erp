@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase List</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="{{ route('purchase.add') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Purchase
                        </a>
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('purchase.index') }}"
                        searchPlaceholder="Search purchases..."
                        :sortOptions="['purchases.id' => 'ID', 'purchases.purchase_no' => 'Purchase No', 'purchases.created_date' => 'Date']"
                        :defaultSort="'purchases.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="managePurchaseTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL.</th>
                                    <th width="10%">Purchase Code</th>
                                    <th width="12%">Purchase Date</th>
                                    <th width="20%">Supplier Info</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Purchased By</th>
                                    <th width="5%">Status</th>
                                    <th width="5%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $purchase)
                                    @php
                                        $grandTotal = floatval($purchase->total_amount) - floatval($purchase->discount) + floatval($purchase->carrying_cost);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration + ($purchases->currentPage() - 1) * $purchases->perPage() }}</td>
                                        <td>{{ $purchase->purchase_no }}</td>
                                        <td>{{ date('d-m-Y h:i a', strtotime($purchase->created_date)) }}</td>
                                        <td>
                                            <b>Name: </b>{{ $purchase->name }}<br>
                                            <b>Contact: </b>{{ $purchase->contact }}<br>
                                            <b>Alt. Contact: </b>{{ $purchase->alternate_contact }}<br>
                                            <b>Address: </b>{{ substr(str_pad($purchase->address, 4), 0, 25) }}
                                        </td>
                                        <td>
                                            <b>Total: </b>{{ $purchase->total_amount }}<br>
                                            <b>Discount: </b>{{ $purchase->discount }}<br>
                                            <b>Transport: </b>{{ $purchase->carrying_cost }}<br>
                                            <b>GrandTotal: </b>{{ $grandTotal }}<br>
                                            <b>Paid: </b>{{ $purchase->current_payment }}
                                        </td>
                                        <td>{{ $purchase->userName }}</td>
                                        <td>
                                            @if($purchase->purchaseStatus == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" onclick="printPurchase({{ $purchase->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                    <a class="dropdown-item" href="#" onclick="purchaseReturn({{ $purchase->id }})"><i class="fas fa-undo-alt me-2"></i> Return Purchase</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $purchase->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No purchases found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $purchases->links() }}
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {});

        function confirmDelete(id) {
            confirmDeleteSwal({
                url         : "{{ route('purchase.delete') }}",
                id          : id,
                itemName    : 'purchase',
                useFormData : true,
                onError     : function(response) {
                    alert(JSON.stringify(response));
                },
            });
        }

        function reloadDt() {
            location.reload();
        }

        function printPurchase(id) {
            var url = '{{ route('purchase.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }

        function purchaseReturn(id) {
            window.location.href = "{{ url('purchase/purchase-return') }}" + "/" + id;
        }
    </script>
@endsection
