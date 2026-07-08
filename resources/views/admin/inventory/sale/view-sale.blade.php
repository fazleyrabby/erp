@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale View
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-capitalize">
                        @if ($saleType == 'walkin_sale')
                            Walkin Sale
                        @elseif ($saleType == 'service')
                            Service Sale
                        @elseif ($saleType == 'ts')
                            Temporary Sale
                        @elseif ($saleType == 'FS')
                            Final Sale
                        @else
                            {{ ucfirst(str_replace('_', ' ', $saleType)) }}
                        @endif
                        List
                    </h3>
                    <div class="card-actions">
                        @if ($saleType == 'walkin_sale')
                            <a class="btn btn-primary" href="{{ route('sale.add', ['type' => $saleType]) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Add Sale
                            </a>
                        @endif
                        @if ($saleType == 'ts')
                            <a class="btn btn-primary" href="{{ route('sale.temporarySaleAdjustment', ['type' => $saleType]) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Sale Adjustment
                            </a>
                        @endif
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <input type="hidden" id="salesType" name="salesType" value="{{ $saleType }}">
                    <x-filter-bar
                        route="{{ route('sale.sales', $saleType) }}"
                        searchPlaceholder="Search sale..."
                        :sortOptions="['id' => 'ID', 'sale_no' => 'Sale No', 'created_date' => 'Date']"
                        :defaultSort="'id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="manageSaleTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL.</th>
                                    <th width="7%">Sale Code.</th>
                                    <th width="15%">Sale Date</th>
                                    <th width="25%">Customer Info</th>
                                    <th width="15%">Amount</th>
                                    <th width="10%">Sold By</th>
                                    <th width="5%">Status</th>
                                    <th width="5%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                                        @if ($saleType == 'ts')
                                            <td>{{ $sale->tsNo }}</td>
                                            <td>{{ $sale->date }}</td>
                                            <td>-</td>
                                            <td><b>Party: </b>{{ $sale->name }}<br><b>Code: </b>{{ $sale->code }}<br><b>Contact: </b>{{ $sale->contact }}<br><b>Alt. Contact: </b>{{ $sale->alternate_contact }}</td>
                                            <td>-</td>
                                            <td>{{ $sale->user_name }}</td>
                                            <td>-</td>
                                        @else
                                            @php
                                                $grandTotal = floatval($sale->total_amount) - floatval($sale->discount) + floatval($sale->carrying_cost) + floatval($sale->vat) + floatval($sale->ait);
                                            @endphp
                                            <td>{{ $sale->sale_no }}<br>{{ $sale->type }}</td>
                                            <td>{{ date('d-m-Y h:i a', strtotime($sale->created_date)) }}</td>
                                            <td><b>Name: </b>{{ $sale->name }}<br><b>Contact: </b>{{ $sale->contact }}<br><b>Alt. Contact: </b>{{ $sale->alternate_contact }}<br><b>Address: </b>{{ $sale->address }}</td>
                                            <td><b>Total: </b>{{ $sale->total_amount }}<br><b>Discount: </b>{{ $sale->discount }}<br><b>Transport: </b>{{ $sale->carrying_cost }}<br><b>GrandTotal: </b>{{ numberFormat($grandTotal) }}<br><b>Paid: </b>{{ $sale->current_payment }}</td>
                                            <td>{{ $sale->userName }}</td>
                                            <td>
                                                @if($sale->saleStatus == 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @if ($saleType == 'ts')
                                                        <a class="dropdown-item" href="#" onclick="printTsSales({{ $sale->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                    @else
                                                        <a class="dropdown-item" href="#" onclick="printPurchase({{ $sale->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                        @if ($saleType == 'walkin_sale')
                                                            <a class="dropdown-item" href="#" onclick="saleReturn({{ $sale->id }})"><i class="fas fa-undo-alt me-2"></i> Return Sale</a>
                                                        @endif
                                                    @endif
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $sale->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $sales->links() }}
                </div>
                <!-- /.card -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{ route('sale.delete') }}",
                id       : id,
                itemName : 'Sales',
            });
        }

        function saleReturn(id) {
            var url = '{{ route('sale.sale-return', ':id') }}';
            url = url.replace(':id', id);
            window.location.href = url;
        }

        function printPurchase(id) {
            window.open("{{ url('sale/invoice/') }}" + "/" + id);
        }

        function printTsSales(id) {
            window.open("{{ url('sale/tsInvoice/') }}" + "/" + id);
        }
    </script>
@endsection
