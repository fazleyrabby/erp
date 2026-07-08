@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Sale View
@endsection
@section('content')
    
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-capitalize">
                        @if ($saleType == 'walkin_sale')
                            Walkin Sale Return
                        @elseif ($saleType == 'service')
                            Service Sale Return
                        @elseif ($saleType == 'FS')
                            Final Sale Return
                        @else
                            {{ ucfirst(str_replace('_', ' ', $saleType)) }}
                        @endif
                        List
                    </h3>
                    <div class="card-actions">
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('sale.return.list', $saleType) }}"
                        searchPlaceholder="Search sale returns..."
                        :sortOptions="['sale_returns.id' => 'ID', 'sale_returns.sale_return_no' => 'Return No', 'sale_returns.sale_return_date' => 'Return Date']"
                        :defaultSort="'sale_returns.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="manageSaleReturnTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL.</th>
                                    <th width="20%">Sale Return Info</th>
                                    <th width="20%">Sale Info</th>
                                    <th width="20%">Customer Info</th>
                                    <th width="15%">Amount</th>
                                    <th width="10%">Returned By</th>
                                    <th width="5%">Status</th>
                                    <th width="5%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($saleReturns as $sr)
                                    <tr>
                                        <td>{{ $loop->iteration + ($saleReturns->currentPage() - 1) * $saleReturns->perPage() }}</td>
                                        <td><b>Return Code: </b>{{ $sr->sale_return_no }}<br><b>Return Date: </b>{{ $sr->sale_return_date }}</td>
                                        <td><b>Sale Code: </b>{{ $sr->sale_no }}<br><b>Sale Date: </b>{{ $sr->sale_date }}</td>
                                        <td><b>Name: </b>{{ $sr->name }}<br><b>Code: </b>{{ $sr->code }}<br><b>Contact: </b>{{ $sr->contact }}</td>
                                        <td><b>Total: </b>{{ $sr->grand_total }}</td>
                                        <td>{{ $sr->userName }}</td>
                                        <td>
                                            @if($sr->saleStatus == 'Active')
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
                                                    <a class="dropdown-item" href="#" onclick="printPurchase({{ $sr->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $sr->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No sale returns found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $saleReturns->links() }}
                </div>
            </div>
        @endsection
@section('javascript')
    <script>
        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{ route('sale.deleteSaleReturn') }}",
                id       : id,
                itemName : 'Sale Return',
            });
        }

        function printPurchase(id) {
            var url = '{{ route('sale.return.invoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }
    </script>
@endsection
