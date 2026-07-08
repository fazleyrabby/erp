@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Purchase View
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div class="row g-3">
                    <!-- Left col -->
                    <section class="col-md-12">
                        <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Purchase Return List</h3>
                    <div class="card-actions">
                        <a class="btn btn-outline-secondary" onclick="location.reload()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar
                        route="{{ route('purchase.return.list') }}"
                        searchPlaceholder="Search purchase returns..."
                        :sortOptions="['purchase_returns.id' => 'ID', 'purchase_returns.purchase_return_no' => 'Return No', 'purchase_returns.purchase_return_date' => 'Return Date']"
                        :defaultSort="'purchase_returns.id'"
                        :defaultDirection="'DESC'"
                    />
                    <div class="table-responsive">
                        <table id="managePurchaseTable" class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL.</th>
                                    <th width="20%">Return Info</th>
                                    <th width="20%">Purchase Info</th>
                                    <th width="25%">Supplier Info</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Returned By</th>
                                    <th width="10%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseReturns as $pr)
                                    <tr>
                                        <td>{{ $loop->iteration + ($purchaseReturns->currentPage() - 1) * $purchaseReturns->perPage() }}</td>
                                        <td><b>Return Code: </b>{{ $pr->purchase_return_no }}<br><b>Return Date: </b>{{ $pr->purchase_return_date }}</td>
                                        <td><b>Purchase Code: </b>{{ $pr->purchase_no }}<br><b>Purchase Date: </b>{{ $pr->purchase_date }}</td>
                                        <td><b>Party: </b>{{ $pr->name }}<br><b>Contact: </b>{{ $pr->contact }}<br><b>Alt. Contact: </b>{{ $pr->alternate_contact }}</td>
                                        <td><b>Total: </b>{{ $pr->grand_total }}</td>
                                        <td>{{ $pr->userName }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#" onclick="printPurchase({{ $pr->id }})"><i class="fas fa-print me-2"></i> View Details</a>
                                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $pr->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No purchase returns found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $purchaseReturns->links() }}
                </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>
        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{ route('deletePurchaseReturn') }}",
                id       : id,
                itemName : 'Purchase Return',
            });
        }



        function printPurchase(id) {
            window.open("{{ url('purchase/return/invoice/') }}" + "/" + id);
        }

        function purchaseReturn(id) {
            window.location.href = "{{ url('purchase/purchase-return') }}" + "/" + id;

        }
    </script>
@endsection
