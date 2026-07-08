@extends('admin.master')
@section('title')
{{ Session::get('companySettings')[0]['name'] }} Sale View
@endsection
@section('content')

    
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Service Orders</h3>
                <div class="card-actions">
                    <a class="btn btn-primary" href="{{ route('sale.service.add') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Service Order
                    </a>
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
            </div>
            <div class="card-body">
                <input type="hidden" id="salesType" name="salesType" value="{{ $saleType }}">
                <x-filter-bar
                    route="{{ route('sale.service.SaleOrders') }}"
                    searchPlaceholder="Search sale no, party, brand, model..."
                    :sortOptions="['id' => 'ID', 'sale_no' => 'Job No', 'date' => 'Date', 'created_date' => 'Created Date']"
                    :defaultSort="'id'"
                    :defaultDirection="'DESC'"
                />
                <div class="table-responsive">
                    <table id="manageSaleTable" class="table table-vcenter table-bordered">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">SL.</th>
                                <th width="7%" class="text-center">Job No</th>
                                <th width="8%" class="text-center">Job Date</th>
                                <th width="20%" class="text-center">Product Info</th>
                                <th width="15%" class="text-center">Customer Info</th>
                                <th width="13%" class="text-center">Amount</th>
                                <th width="7%" class="text-center">Booked By</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="5%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($saleOrders as $i => $so)
                            @php
                                $grandTotal = floatval($so->total_amount) - floatval($so->discount) + floatval($so->carrying_cost) + floatval($so->vat) + floatval($so->ait);
                                $now = \Carbon\Carbon::now();

                                if ($so->order_status == 'Pending') {
                                    $refDate = $so->created_date;
                                    $statusIcon = 'hourglass-start text-primary';
                                    $statusLabel = $so->order_status;
                                } elseif ($so->order_status == 'Servicing') {
                                    $refDate = $so->service_start_date;
                                    $statusIcon = 'fa-wrench text-info';
                                    $statusLabel = $so->order_status;
                                } elseif ($so->order_status == 'ReadyToDeliverd') {
                                    $refDate = $so->ready_to_deliver_date;
                                    $statusIcon = 'dolly text-primary';
                                    $statusLabel = 'Ready for delivery';
                                } elseif ($so->order_status == 'Delivered') {
                                    $refDate = $so->delivered_date;
                                    $statusIcon = 'check text-success';
                                    $statusLabel = $so->order_status;
                                } elseif ($so->order_status == 'Completed') {
                                    $refDate = null;
                                    $statusIcon = 'check-circle text-success';
                                    $statusLabel = $so->order_status;
                                } else {
                                    $refDate = null;
                                    $statusIcon = 'times text-danger';
                                    $statusLabel = $so->order_status;
                                }

                                if ($refDate) {
                                    $diff = $now->diffInDays(\Carbon\Carbon::parse($refDate));
                                } else {
                                    $diff = null;
                                }

                                $project = $so->project_name ? '<b>Project: </b>' . e($so->project_name) : '';
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($saleOrders->currentPage() - 1) * $saleOrders->perPage() }}</td>
                                <td class="text-center">{{ $so->sale_no }}</td>
                                <td>{{ date('d-m-Y h:i a', strtotime($so->date)) }}</td>
                                <td>
                                    <b>Brand: </b>{{ $so->brand }}<br>
                                    <b>Model: </b>{{ $so->model }}<br>
                                    <b>Item: </b>{{ $so->item }}<br>
                                    {!! $project !!}
                                </td>
                                <td>
                                    <b>Party: </b>{{ $so->name }}<br>
                                    <b>Contact: </b>{{ $so->contact }}<br>
                                    <b>Alt. Contact: </b>{{ $so->alternate_contact }}<br>
                                    <b>Address: </b>{{ $so->address }}
                                </td>
                                <td>
                                    <b>Total: </b>{{ $so->total_amount }}<br>
                                    <b>Discount: </b>{{ $so->discount }}<br>
                                    <b>Transport: </b>{{ $so->carrying_cost }}<br>
                                    <b>GrandTotal: </b>{{ $grandTotal }}<br>
                                    <b>Paid: </b>{{ $so->current_payment }}
                                </td>
                                <td>{{ $so->userName }}</td>
                                <td>
                                    <div class="text-center">
                                        @if ($so->order_status == 'Pending')
                                        <i class="fas fa-hourglass-start text-primary"></i><br>
                                        @elseif ($so->order_status == 'Servicing')
                                        <i class="fa fa-wrench text-info"></i><br>
                                        @elseif ($so->order_status == 'ReadyToDeliverd')
                                        <i class="fas fa-dolly text-primary"></i><br>
                                        @elseif ($so->order_status == 'Delivered')
                                        <i class="fas fa-check text-success"></i><br>
                                        @elseif ($so->order_status == 'Completed')
                                        <i class="fas fa-check-circle text-success"></i><br>
                                        @else
                                        <span class="text-danger">X</span><br>
                                        @endif
                                        {{ $so->order_status == 'ReadyToDeliverd' ? 'Ready for delivery' : $so->order_status }}
                                    </div>
                                    @if ($diff !== null)
                                        @if ($so->order_status == 'Delivered' && $diff == 0)
                                        <div class="text-center text-danger"><small>Delivered Today</small></div>
                                        @elseif ($so->order_status == 'ReadyToDeliverd' && $diff == 0)
                                        <div class="text-center text-danger"><small>Came from service Today</small></div>
                                        @elseif ($so->order_status == 'Servicing' && $diff == 0)
                                        <div class="text-center text-success"><small>Went to service Today</small></div>
                                        @elseif ($so->order_status == 'Pending' && $diff == 0)
                                        <div class="text-center text-success"><small>Today arrived</small></div>
                                        @elseif ($diff > 0)
                                        <div class="text-center"><small>{{ $diff }} days</small></div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if (in_array($so->order_status, ['Pending', 'Servicing']))
                                            <a class="dropdown-item" href="#" onclick="editSaleOrder({{ $so->id }})"><i class="fas fa-edit me-2"></i> Update Order</a>
                                            @if ($so->order_status == 'Pending')
                                            <a class="dropdown-item" href="#" onclick="orderInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Order Invoice</a>
                                            @endif
                                            @if ($so->order_status == 'Servicing')
                                            <a class="dropdown-item" href="#" onclick="statusComplete({{ $so->id }})"><i class="fas fa-check me-2"></i> Ready to deliverd</a>
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            @endif
                                            @elseif ($so->order_status == 'ReadyToDeliverd')
                                            <a class="dropdown-item" href="#" onclick="editSaleOrder({{ $so->id }})"><i class="fas fa-edit me-2"></i> Update Order</a>
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            @elseif ($so->order_status == 'Declined')
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            @elseif ($so->order_status == 'Delivered')
                                            <a class="dropdown-item" href="#" onclick="createOrderToWalkinSale({{ $so->id }})"><i class="fas fa-cart-plus me-2"></i> Final Service Sale</a>
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            @elseif ($so->order_status == 'Completed')
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            <a class="dropdown-item" href="#" onclick="saleInvoice({{ $so->sale_id }})"><i class="fas fa-file-pdf me-2"></i> Sale Invoice</a>
                                            @else
                                            <a class="dropdown-item" href="#" onclick="completeInvoice({{ $so->id }})"><i class="fas fa-file-pdf me-2"></i> Complete Invoice</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No service orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $saleOrders->links() }}
                </div>
            </div>
        </div>
    @endsection
@section('javascript')
<script>
function saleInvoice(id){
    var url = '{{ route('sale.invoice', ':id') }}';
    url = url.replace(':id', id);
    window.open(url);
}

function statusComplete(id) {
    var _token = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
        title: "Are you sure ?",
        text: "You can only change this action if the order is ready to delivered!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Ready to delivered!",
        closeOnConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('sale.service.statusComplete') }}",
                method: "POST",
                data: { "id": id, "_token": _token },
                success: function(result) {
                    Swal.fire("Updated!", result.success, "success");
                    location.reload();
                },
                error: function(response) {
                    Swal.fire("Error", "Please try again", "error");
                },
                beforeSend: function() { $('#loading').show(); },
                complete: function() { $('#loading').hide(); }
            });
        } else {
            Swal.fire("Cancelled", "No Changes..", "warning");
        }
    });
}

function editSaleOrder(id) {
    var url = '{{ route('sale.service.edit.editSaleOrder', ':id') }}';
    url = url.replace(':id', id);
    window.location.href = url;
}

function createOrderToWalkinSale(id) {
    var url = '{{ route('sale.service.createOrderToWalkinSale', ':id') }}';
    url = url.replace(':id', id);
    window.location.href = url;
}

function orderInvoice(id) {
    var url = '{{ route('sale.service.orderInvoice', ':id') }}';
    url = url.replace(':id', id);
    window.open(url);
}

function completeInvoice(id) {
    var url = '{{ route('sale.service.completeInvoice', ':id') }}';
    url = url.replace(':id', id);
    window.open(url);
}
</script>
@endsection