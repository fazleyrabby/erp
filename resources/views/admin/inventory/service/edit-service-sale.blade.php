@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Edit Service
@endsection
@section('content')

    <style type="text/css">
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            width: 100%;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }
    </style>

    <div class="content-wrapper">
        <section class="content box-border">
            <form id="saleProducts" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left col -->
                    <section class="col-md-12">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <input type="hidden" name="saleType" id="saleType" value="{{ $type }}">
                            <input type="hidden" name="saleOrderId" id="saleOrderId" value="{{ $saleOrder->id ?: '' }}">
                            <div class="card-header">
                                <h3>
                                    Edit Service Centre
                                    <a class="mr-2 btn btn-primary float-right ml-1"
                                        href="{{ route('sale.service.add') }}"> <i class="fa fa-plus-circle"></i> add
                                        Service </a> 
                                    <a class="btn btn-primary float-right"
                                        href="{{ route('sale.service.SaleOrders') }} ">
                                        Back To Service Orders
                                        <i class="fa fa-reply"></i>
                                    </a>
                                </h3>
                                
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="row mx-auto">
                                    @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                        <div class="form-group col-md-12">
                                            <label>Barcode: </label>
                                            <input class="form-control input-sm" id="barcode" type="text"
                                                name="barcode" onkeyup="findProduct()">
                                            <span class="text-danger" id="barcodeError"></span>
                                        </div>
                                    @endif

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Service Recieve Section</legend>
                                        <div class="row">
                                            <div class="form-group col-md-4 d-none">
                                                <label>Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="saleDate" name="saleDate"
                                                    class="form-control input-sm" value="{{ todayDate() }}" />
                                            </div>

                                            @php 
                                                $display='';
                                                if($jafreeMatched == 'no'){
                                                    $display;
                                                }else{
                                                    $display='display:none';
                                                }
                                            @endphp
                                          
                                            @if($advance)
                                            <div class="form-group col-md-12"><span>Customer can not be changed,because this customer already paid some advances before.</span></div>
                                            @else
                                            <div class="form-group col-md-12 " style="{{$display}}">
                                                <div class="row">
                                                    <div class="form-group col-md-2">
                                                        <label class="text-white">.</label><br>
                                                        <span class="btn btn-secondary" onclick="openproject_name()">Change Customer</span>
                                                    </div>
                                                    <div class="col-md-10" id="changeCustomerInstruction"><label class="text-white">.</label><br><span>( You can switch the customer to {{Session::get('companySettings')[0]['name']}} if necessary)</span></div>
                                                </div>
                                            </div>
                                            @endif
                                           

                                            @php 
                                                $display2='';
                                                if($jafreeMatched == 'yes'){
                                                    $display2;
                                                }else{
                                                    $display2='display:none';
                                                }
                                            @endphp
                                            @if($advance)
                                            @else  
                                            <!-- <div class="col-md-10" id="changeCustomerSuccess" style="{{$display2}}"><label class="text-white">.</label><br><span class="text-success">Customer was successfully switched to {{Session::get('companySettings')[0]['name']}} </span></div> -->
                                            <div class=" form-group col-md-12"  style="{{$display2}}">
                                                <label >Project Name</label><br>
                                                <input class="form-control" type="text" id="project_name" name="project_name" placeholder="Project name..." value="{{$saleOrder->project_name}}">
                                                <span class="text-danger" id="project_nameError"></span>
                                            </div>
                                           @endif
                                           


                                            @if ($type != 'walkin_sale')
                                                <div class="form-group col-md-7">
                                                    <label>Party Name : <span class="text-danger">*</span></label>
                                                    <select id="customer" name="customer" class="abcd customer"
                                                        style="width:100%" required
                                                        onchange="getCustomerById(this.value, 'Customer');">
                                                        <option value='' selected='true'> Select Party </option>
                                                        @foreach ($customers as $customer)
                                                            <option value='{{ $customer->id }}'>
                                                                {{ $customer->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="customerNameError"></span>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="hidden" id="customer" name="customer" value="0" />
                                                    <label>Phone: </label>
                                                    <div class="d-flex">
                                                        <input type="text" id="partyPhoneNumber" name="partyPhoneNumber"
                                                            class="form-control input-sm" placeholder=" Phone Number"
                                                            readonly />
                                                    </div>
                                                    <span class="text-danger" id="partyPhoneNumberError"></span>
                                                </div>
                                            @endif
                                            @if ($type == 'walkin_sale')
                                                <div class="form-group col-md-4">
                                                    <input type="hidden" id="customer" name="customer"
                                                        value="{{ $customer->id }}" />
                                                    <input type="hidden" id="party_type" name="party_type"
                                                        value="{{ $customer->party_type }}" />
                                                    <label>Phone: <span class="text-danger">*</span></label>
                                                    <div class="d-flex">
                                                        <input type="text" id="partyPhoneNumber" name="partyPhoneNumber"
                                                            class="form-control input-sm" placeholder=" Phone Number"
                                                            value="{{ $customer->contact ?: '' }}" readonly />
                                                    </div>
                                                    <span class="text-danger" id="partyPhoneNumberError"></span>
                                                </div>
                                            @endif
                                            <div class="form-group col-md-4">
                                                <label>Name: <span class="text-danger">*</span></label>
                                                <input type="text" id="customerName" name="customerName"
                                                    class="form-control input-sm" placeholder=" Name"
                                                    value="{{ $customer->name ?: '' }}" readonly />
                                                <span class="text-danger" id="customerNameError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Address: </label>
                                                <input type="text" id="customerAddress" name="customerAddress"
                                                    class="form-control input-sm" placeholder=" Address"
                                                    value="{{ $customer->address ?: '' }}" readonly />
                                                <span class="text-danger" id="customerAddressError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Defect Reported: 
                                                <input type="text" id="defectReported" name="defectReported"
                                                    class="form-control input-sm" placeholder=" Defect Reported"
                                                    value="{{ $saleOrder->description ?: '' }}" />
                                                <span class="text-danger" id="defectReportedError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Work Approval Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="workApprovalDate" name="workApprovalDate"
                                                    class="form-control input-sm" placeholder=" Work Approval Date"
                                                    value="{{ $saleOrder->work_approval_date ?: '' }}" />
                                                <span class="text-danger" id="workApprovalDateError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Expected Delivery Date: <span class="text-danger">*</span></label>
                                                <input type="date" id="expectedDeliveryDate"
                                                    name="expectedDeliveryDate" class="form-control input-sm"
                                                    placeholder=" Expected Delivery Date"
                                                    value="{{ $saleOrder->expected_delivery_date ?: '' }}" />
                                                <span class="text-danger" id="expectedDeliveryDateError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Brand: <span class="text-danger">*</span></label>
                                                <input type="text" id="brand" name="brand"
                                                    class="form-control input-sm" placeholder=" Brand"
                                                    value="{{ $saleOrder->brand ?: '' }}" />
                                                <span class="text-danger" id="brandError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Model: <span class="text-danger">*</span></label>
                                                <input type="text" id="model" name="model"
                                                    class="form-control input-sm" placeholder=" Model"
                                                    value="{{ $saleOrder->model ?: '' }}" />
                                                <span class="text-danger" id="modelError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Item: <span class="text-danger">*</span></label>
                                                <input type="text" id="item" name="item"
                                                    class="form-control input-sm" placeholder=" Item"
                                                    value="{{ $saleOrder->item ?: '' }}" />
                                                <span class="text-danger" id="itemError"></span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Quantity: <span class="text-danger">*</span></label>
                                                <input type="text" id="quantity" name="quantity"
                                                    class="form-control input-sm" placeholder=" Quantity"
                                                    value="{{ $saleOrder->quantity ?: '' }}" readonly />
                                                <span class="text-danger" id="quantityError"></span>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>Credit Limit
                                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                        <span class="btn btn-secondary float-right viewPurchase"
                                                            style="height: 53%;" id="creditLimit">0</span>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Left credit
                                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                        <span class="btn btn-secondary float-right viewPurchase"
                                                            style="height: 53%;" id="leftCredit">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Manufacturing SI. No: </label>
                                                <input type="text" id="manufacturingSiNo" name="manufacturingSiNo"
                                                    class="form-control input-sm"
                                                    value="{{ $saleOrder->manufacturing_si_no ?: '' }}" />
                                                <span class="text-danger" id="manufacturingSiNoError"></span>
                                                <br>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>Due
                                                            ({{ Session::get('companySettings')[0]['currency'] }}):
                                                        </label><br>
                                                        <span class="btn btn-secondary float-right viewPurchase"
                                                            style="height: 53%;" id="currentDue">0</span>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Advance
                                                            ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                        <span  class="btn btn-secondary float-right viewPurchase"
                                                            style="height: 53%;" id="advance_payment">{{ $saleOrder->advance_payment }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                            <div class="form-group col-md-12">
                                                <input type="hidden" id="category" name="category" value="42">
                                              {{--   <label>COA Heads: <span class="text-danger">*</span></label>
                                                <select type="text" id="category" name="category"
                                                    class="form-control input-sm" >
                                                    <option value="{{$saleOrder->category}}" selected>Select COA</option>
                                                    @foreach($coas as $coa)
                                                    <option value="{{$coa->id}}">{{$coa->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="categoryError"></span> --}}
                                            </div>
                                                @if (!$saleOrder->accessories_recieved)
                                                    @php
                                                        $saleOrder->accessories_recieved = "";
                                                    @endphp
                                                @endif
                                                <label>Accessories Recieved (Please put tick mark below): <span
                                                        class="text-danger">*</span></label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Guard"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Guard') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox1"> Guard</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Chuck"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Chuck') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox2"> Chuck</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Handle"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Handle') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox2"> Handle</label>
                                                </div>
                                                <br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Flange"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Flange') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox2"> Flange</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Grinding Disc"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Grinding Disc') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox2"> Grinding
                                                        Disc</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="accessoriesRecieved" value="Box"
                                                        {{ str_contains($saleOrder->accessories_recieved, 'Box') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineCheckbox2">Box</label>
                                                </div>
                                                <br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        name="otherAccessoriesCheck" id="otherAccessoriesCheck"
                                                        value="Other Accessories"
                                                        {{ strlen($saleOrder->other_accessories) > 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="inlineRadio1">Other
                                                        Accessories</label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="otherAccessories" id="otherAccessories"
                                                        class="form-control input-sm"
                                                        {{ strlen($saleOrder->other_accessories) > 0 ? '' : 'readonly' }}
                                                        value="{{ $saleOrder->other_accessories }}"
                                                        placeholder=" Value 1, Value2..." />
                                                    <span class="text-danger" id="brandError"></span>
                                                </div>
                                                <span class="text-danger" id="accessoriesRecievedError"></span>
                                            </div>

                                            
                                            {{-- <div class="form-group col-md-2">
                                                <label>Credit Limit
                                                    ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                <span class="btn btn-success float-right viewPurchase"
                                                    style="height: 53%;" id="creditLimit">0</span>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Left credit
                                                    ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                <span class="btn btn-warning float-right viewPurchase"
                                                    style="height: 53%;" id="leftCredit">0</span>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Due ({{ Session::get('companySettings')[0]['currency'] }}):
                                                </label><br>
                                                <span class="btn btn-warning float-right viewPurchase"
                                                    style="height: 53%;" id="currentDue">0</span>
                                            </div> --}}
                                            <div class="form-group col-md-6 d-none">
                                                <label>Total With Due1
                                                    ({{ Session::get('companySettings')[0]['currency'] }}):</label><br>
                                                <span class="btn btn-danger float-right viewPurchase"
                                                    id="totalWithDue">0</span>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Customer Communication Section</legend>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Warehouse: <span class="text-danger">*</span></label>
                                                <select id="warehouse" name="warehouse" class="abcd"
                                                    style="width:100%" required>
                                                    
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value='{{ $warehouse->id }}'selected>
                                                            {{ $warehouse->wareHouseName }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <span class="text-danger" id="warehouseError"></span>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label>Product Search : <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select id="products" name="products" class="form-control input-sm"
                                                        style="width:96%">
                                                        <option value=""> Product Search </option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ $product->name . ' - ' . $product->code }} </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-primary input-group-addon"
                                                        onclick="showAdvanceSearch();"> <i
                                                            class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label class="text-white">.</label>
                                                <br>
                                                <button type="button" class="form-group btn btn-info btn-lg"
                                                    onclick="getOrderFeedbacks()"><i class="fa fa-info-circle"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Service Note</label><br>
                                                <textarea class="form-control" name="service_note" id="service_note" width="100%" rows="1" placeholder="Service note..">{{$saleOrder->service_note}}</textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Cart Details: </label>
                                                <table border="1" style="font-size: 13px; width:100%;"
                                                    class="table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th style="width: 36%;">Product Info</th>
                                                            <th>Available</th>
                                                            <th style="width: 12%;">Qty</th>
                                                            <th style="width: 14%;">Unit Price</th>
                                                            <th style="width: 12%;">Unit Dis.</th>
                                                            <th style="width: 14%;">Total</th>
                                                            <th style="width: 6%;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="manageCartTable"></tbody>
                                                  
                                                    
                                                    <tr>
                                                        <td colspan="6" class="text-right"> Discount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"> <input type="text"
                                                                id="discount" name="discount" onblur="calculateTotal()"
                                                                class="input-sm text-right" value="{{ $saleOrder->discount }}" /> </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Transport
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="transport" name="transport" onblur="calculateTotal()"
                                                                class="input-sm text-right" value="{{ $saleOrder->carrying_cost }}"/></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Vat
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="vat" name="vat" onblur="calculateTotal()"
                                                                class="input-sm text-right"value="{{ $saleOrder->vat }}" /></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right">Ait
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right font-weight-bold"><input type="text"
                                                                id="ait" name="ait" onblur="calculateTotal()"
                                                                class="input-sm text-right" value="{{ $saleOrder->ait }}"/></td>
                                                        <td></td>
                                                    </tr>


                                                    <tr>
                                                        <td colspan="6" class="text-right">Grand Total
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td class="text-right"><span
                                                                class="btn btn-light text-right viewPurchase"
                                                                id="grandSum">0</span>
                                                        </td>
                                                        <td></td>
                                                    </tr>


                                                    <tr>
                                                         <td colspan="6" class="text-right">Received Amount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td> 
                                                            <input type="text" id="advance_payment" name="advance_payment"
                                                                class="input-sm text-right advance_payment" value="{{ $saleOrder->advance_payment }}"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" readonly/>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                         <td colspan="6" class="text-right">Due Amount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td> 
                                                            <input type="text" id="due_amount" name="due_amount"
                                                                class="input-sm text-right due_amount" 
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" readonly/>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    
                                                   <!--  -->
                                                    <tr>
                                                        <td colspan="6" class="text-right">Paid Amount
                                                            {{ Session::get('companySettings')[0]['currency'] }} : </td>
                                                        <td> 
                                                            <input type="text" id="payment" name="payment"
                                                                class="input-sm text-right payment" value="0"
                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    
                                                    @if($saleOrder->order_status == 'Pending')
                                                    <tr>
                                                        <td td colspan="6" class="text-right">Status</td>
                                                        <td>
                                                            <select id="status" name="status" 
                                                                class="form-control text-center">
                                                                <option value="0" selected>Select a status</option>
                                                                <option value="Servicing" >Servicing</option>
                                                                <option value="Cancelled">Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    @elseif($saleOrder->order_status == 'ReadyToDeliverd')
                                                    <tr>
                                                        <td td colspan="6" class="text-right">Status</td>
                                                        <td>
                                                            <select id="status" name="status" 
                                                                class="form-control text-center">
                                                                <option value="0" selected>Select a status</option>
                                                                <option value="Servicing" >Servicing</option>
                                                                <option value="Delivered" >Delivered</option>
                                                                <option value="Cancelled">Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <td td colspan="6" class="text-right">Status</td>
                                                        <td>
                                                            <select id="status" name="status" 
                                                                class="form-control text-center">
                                                                <option value="0" selected>Select a status</option>
                                                                <option value="Cancelled">Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    @endif
                                                </table>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-2">
                                        <a class="check-out-btn float-left" href="#" onclick="clearCart()"> <i
                                                class="fa fa-trash"></i> <span class="check-out-text">  Clear Cart </span>  </a>
                                    </div>
                                    <div class="col-md-8"></div>
                                    <div class="col-md-2">
                                        <button type="button" id="checkOutCart"
                                            class="check-out-btn my_button float-right btn-block"><i
                                                class="fa fa-save"></i>  <span class="check-out-text">  Place Order </span></button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->

                            <!-- /.card -->
                        </div>
                    </section>
                </div><!-- /.container-fluid -->
        </section>
    </div>
    </form>

    <!-- /.content -->
    <!-- Product Advance Search modal -->
    <div class="modal fade" id="showAdvanceSearchModal">
        <div class="modal-dialog" style="max-width: 90%;" role="document">
            <!-- style, added by Md Hamid -->
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Product Advance Search</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"> x </button>
                </div>
                <div class="modal-body">
                    <form id="productForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
                            <!--data listing table-->
                            <div class="table-responsive">
                                <table id="advanceSearchProductTable" width="100%"
                                    class="table table-bordered table-hover ">
                                    <thead>
                                        <tr>
                                            <td width="5%">SL</td>
                                            <td width="18%">Product Info</td>
                                            <td width="18%">Product Info</td>
                                            <td width="21%">Speccification</td>
                                            <td width="10%">Price</td>
                                            <td width="20%">Stock</td>
                                            <td width="6%">Actions</td>
                                        </tr>
                                    </thead>
                                </table>
                                <!--data listing table-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"> x
                            </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- End Product Advance Search modal -->
    <!-- Start Serialize Product Modal -->
    <div class="modal fade" id="serialNumsModal">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Serialize Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body card-body">
                    <form id="serializeProductForm">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <table border="1" style="font-size: 13px; width:100%;" class="table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>SL. Number</th>
                                            <th>Remaining Qty</th>
                                            <th style="width: 25%">Sale Qty</th>
                                        </tr>
                                    </thead>
                                    <input type="hidden" id="serializProductId" name="serializProductId"
                                        value="">
                                    <input type="hidden" id="serializProductWarehouseId"
                                        name="serializProductWarehouseId" value="">
                                    <tbody id="serializeProductTable" class="text-center">
                                    </tbody>
                                </table>
                                <strong>Total Sale Quantity: <span name="totalStockQuantity"
                                        id="totalStockQuantity"></span></strong><br><span class="text-danger">** Sale
                                    Qty & Total Qty Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->
    <!-- Start Order Feedback Modal -->
    <div class="modal fade" id="orderFeedbackModal">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Followup Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body card-body">
                    <form id="orderFeedbackForm">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Date Of Contact: <span class="text-danger">*</span></label>
                                <input type="date" id="dateOfContact" name="dateOfContact"
                                    class="form-control input-sm" value="{{ todayDate() }}" />
                                <span class="text-danger" id="dateOfContactError"></span>
                            </div>
                            <div class="form-group col-md-7">
                                <label>Customer Response: <span class="text-danger">*</span></label>
                                <input type="text" id="customerResponse" name="customerResponse"
                                    class="form-control input-sm" />
                                <span class="text-danger" id="customerResponseError"></span>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-white">*</label>
                                <button type="button" id="addOrderFeedback"
                                    class="btn btn-success my_button float-right btn-block"><i class="fa fa-plus-circle"
                                        onclick="addOrderFeedback()"> Add </i> </button>
                            </div>
                            <div class="form-group col-md-12">
                                <table border="1" style="font-size: 13ppx; width:100%;" class="table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Date Of Contact</th>
                                            <th>Customer Response</th>
                                            <th width="9%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderFeedbackTable" class="text-center"></tbody>
                                </table>
                                <br>
                                <strong>Notes : </strong><span class="text-danger">*Followup With Customer Agreement.*</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-bs-dismiss="modal">x
                                Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Order Feedback Modal -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('javascript')
    <script>



    function openproject_name() {
        Swal.fire({
            title: "Are you sure you want to switch customer?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Change Customer !",
            closeOnConfirm: false
        }).then((result) => {
			if (result.isConfirmed) {
                var id=$('#saleOrderId').val();
               
                
            $.ajax({
                url: "{{ route('sale.service.changeCustomer') }}",
                method: "GET",
                data: {"id": id},
                datatype: "json",
                success: function(result) {
                   // alert(JSON.stringify(result));
                    $('#changeCustomerInstruction').hide();
                    Swal.fire("Customer Changed!",result.success,"success");
                    location.replace(location.href.split('#')[0]);
                    $('#project_nameDiv').show();
                    $('#changeCustomerSuccess').show();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                   
                }
            });
			}else{
			  Swal.fire("Cancelled", "Your imaginary customer   is safe :)", "error");
			}
        })
    }








        $("#otherAccessoriesCheck").click(function(e) {
            let checked = $(this).attr("checked");
            if (!checked) {
                $(this).attr("checked", true);
                $("#otherAccessories").attr("readonly", false);
            } else {
                $(this).removeAttr("checked");
                $(this).prop("checked", false);
                $("#otherAccessories").attr("readonly", true);
            }
        });
        //=========== Start Order Feedback ===========//
        function getOrderFeedbacks() {
            let saleOrderId = $('#saleOrderId').val();
            $("#orderFeedbackModal").modal("show");
            $.ajax({
                url: "{{ route('sale.service.getOrderFeedbacks') }}",
                method: "GET",
                data: {
                    "id": saleOrderId
                },
                datatype: "json",
                success: function(result) {
                    $("#orderFeedbackTable").html('');
                    $("#orderFeedbackTable").html(result.orderFeedbackTable);
                    $("#orderFeedbackModal").modal("show");
                    $('#dateOfContactError').text('');
                    $('#customerResponseError').text('');
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#orderFeedbackTable").text("Something Went Wrong.Please Try Again");
                }
            });
        }

        function addOrderFeedback() {
            var _token = $('input[name="_token"]').val();
            let saleOrderId = $('#saleOrderId').val();
            var dateOfContact = $("#dateOfContact").val();
            var customerResponse = $("#customerResponse").val();
            var fd = new FormData();
            fd.append('saleOrderId', saleOrderId);
            fd.append('dateOfContact', dateOfContact);
            fd.append('customerResponse', customerResponse);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('sale.service.addOrderFeedback') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    getOrderFeedbacks();
                    $("#customerResponse").val('');
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $('#dateOfContactError').text(response.responseJSON.errors.dateOfContact);
                    $('#customerResponseError').text(response.responseJSON.errors.customerResponse);
                }
            });
        }

        function removeOrderFeedback(id) {
            $.ajax({
                url: "{{ route('sale.service.removeOrderFeedback') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    getOrderFeedbacks();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#customerResponseError").text("Didn't Not Delete Data! Please Try Again.");
                }
            });
        }
        //=========== End Order Feedback ===========//

        // Start Advance Search Product
        var advanceSearchTable;
        var loadSearch = 1

        function showAdvanceSearch() {
            getManageProductTable();
            $("#showAdvanceSearchModal").modal('show');
        }

        function getManageProductTable() {
            if (loadSearch == 1) {
                advanceSearchTable = $('#advanceSearchProductTable').DataTable({
                    'ajax': "{{ route('viewAdvanceSearchProducts', ['page' => 'walkin_sale']) }}",
                    processing: true,
                    destroy: true,
                });
                loadSearch = 0;
            }
        }

        function warehouseWiseStock(id) {
            $.ajax({
                url: "{{ route('warehouseWiseStock') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    if (result) {
                        $("#" + id).html(result);
                    } else {
                        $("#" + id).html('Stock : 0');
                    }
                },
                error: function(response) {

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }
        $("#warehouse").change(function() {
            $("#products").val(null).trigger("change");
        })

        function selectProducts(productId, warehouseId) {
            var id = productId;
            var warehouseId = warehouseId;
            if (warehouseId != '') {
                var warehouseName = $("#wrhs_name" + warehouseId).text();
                var saleType = $("#saleType").val();
                var _token = $('input[name="_token"]').val();
                var fd = new FormData();
                fd.append('id', id);
                fd.append('warehouseId', warehouseId);
                fd.append('warehouseName', warehouseName);
                fd.append('quantity', 1);
                fd.append('saleType', saleType);
                fd.append('_token', _token);
                addToCart(fd);
            } else {
                Swal.fire("warning", "Warehouse must be selected", "warning");
            }
        }
        // End Advance Search Product

        $(document).ready(function() {
            fetchCart();
        });

        $("#warehouse").select2({
            
            allowClear: true,
            width: '100%'
        });
        $("#category").select2({
            placeholder: "Select Category",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        /*$("#brand").select2({
            placeholder: "Select Brand",
            allowClear: true,
            width: '100%'
        }); */
        $("#products").select2({
            placeholder: " Product Search ",
            /*dropdownParent: $("#modal"),*/
            allowClear: true,
            width: '100%'
        });
        $(document).ready(function() {
            $(".customer").select2({
                placeholder: "Select Party",
                /*dropdownParent: $("#modal"),*/
                allowClear: true,
                width: '100%'
            });
            $(function() {
                $("select").select2();
            });
        });

        function fetchCart() {
            $.ajax({
                url: "{{ route('sale.service.edit.fetchCart') }}",
                method: "GET",
                datatype: "json",
                success: function(result) {
                    $("#manageCartTable").html(result.data.cart);
                    $("#totalAmount").text(result.data.totalAmount);
                   
                    calculateTotal();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            })
        }

      
        $("#brand").change(function() {
           
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            var warehouseId = $("#warehouse").val();
            //loadProducts(categoryId, brandId, warehouseId);
        })

        function loadBrands(categoryId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', categoryId);
            fd.append('type', "sale");
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('brands/categoryWiseView') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var brandData = "<option value=''>Select Brand</option>";
                    for (var i = 0; i < result.length; i++) {
                        brandData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            "</option>";
                    }
                    if (brandData == "<option value=''>Select Brand</option>") {
                        brandData = "<option value=''>No Available Brand</option>";
                    }
                    $("#brand").html(brandData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            })
        }

        function loadProducts(categoryId, brandId, warehouseId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('categoryId', categoryId);
            fd.append('brandId', brandId);
            fd.append('warehouseId', warehouseId);
            fd.append('type', "sale");
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('products/brandAndCategoryWise') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var productData = "<option value=''>Select Product</option>";
                    let current_stock = '';
                    for (var i = 0; i < result.length; i++) {
                        if (warehouseId) {
                            current_stock = result[i]["currentStock"];
                        } else {
                            current_stock = result[i]["current_stock"];
                        }
                        productData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            " ( available-" + current_stock + " )</option>";
                    }
                    if (productData == "<option value=''>Select Product</option>") {
                        productData = "<option value=''>No Available Product</option>";
                    }
                    $("#products").html(productData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                }
            });
        }









        function calculateTotal() {
            var totalAmount = 0;
            var i = 0;
            var advance_payment=$('#advance_payment').text();

            $('span[id^="totalPrice_"]').each(function() {
                totalAmount += parseFloat($(this).text());
                i = i + 1;
            });
            
            $("#totalAmount").text(totalAmount);
            var discount = 0;
            var payChar = $("#discount").val().substr(-1);
            if (payChar == "%") {
                discount = (totalAmount / 100) * parseFloat($("#discount").val());
            } else {
                if (parseFloat($("#discount").val()) >= 0) {
                    discount = parseFloat($("#discount").val());
                } else {
                    $("#discount").val("0");
                    discount = 0;
                }
            }
            var transport = 0;
            if (parseFloat($("#transport").val()) >= 0) {
                transport = parseFloat($("#transport").val());
            } else {
                $("#transport").val("0");
                transport = 0;
            }
            var vat = 0;
            if (parseFloat($("#vat").val()) >= 0) {
                vat = parseFloat($("#vat").val());
            } else {
                $("#vat").val("0");
                vat = 0;
            }
            var ait = 0;
            if (parseFloat($("#ait").val()) >= 0) {
                ait = parseFloat($("#ait").val());
            } else {
                $("#ait").val("0");
                ait = 0;
            }
            var grandTotal = parseFloat(totalAmount) + parseFloat(transport) + parseFloat(vat) + parseFloat(ait) -
                parseFloat(discount);
            var due= grandTotal - parseFloat(advance_payment);

            $("#totalAmount").text(totalAmount);
            $("#due_amount").val(due)
            $("#grandSum").text(grandTotal);

            var currentDue = parseFloat($("#currentDue").text());
            var totalWithDue = parseFloat(grandTotal) + parseFloat(currentDue);
            $("#totalWithDue").text(totalWithDue);
        }










        function findProduct() {
            $("#barcodeError").text("");
            var barcode = $("#barcode").val();
            var warehouseId = $("#warehouse").val();
            var warehouseName = $("#warehouse option:selected").text();
            var _token = $('input[name="_token"]').val();
            if (warehouseId != '') {
                if (barcode.length >= 6) {
                    var result = confirm("Want to add?");
                    if (result) {
                        var fd = new FormData();
                        fd.append('barcode', barcode);
                        fd.append('warehouseId', warehouseId);
                        fd.append('warehouseName', warehouseName);
                        fd.append('quantity', 1);
                        fd.append('_token', _token);
                        addToCart(fd);
                    }
                }
            } else {
                alert('Warehouse must be selected');
            }
        }
        $("#products").change(function() {
            $("#barcodeError").text("");
            var id = $("#products").val();
            var warehouseId = $("#warehouse").val();
            if (id != '') {
                if (warehouseId != '') {
                    if (id != '') {
                        var warehouseName = $("#warehouse option:selected").text();
                        var _token = $('input[name="_token"]').val();
                        var fd = new FormData();
                        fd.append('id', id);
                        fd.append('warehouseId', warehouseId);
                        fd.append('warehouseName', warehouseName);
                        fd.append('quantity', 1);
                        fd.append('_token', _token);
                        addToCart(fd);
                    }
                } else {
                    Swal.fire("warning", "Warehouse must be selected", "warning");
                }
            }
        })


        $(document).ready(function() {
            let customerId = $("#customer").val();
            let customer_type = $("#party_type").val();
            getCustomerInfo(customerId, customer_type);
        });

        function getCustomerInfo(id, customer_type) {
            let partyPhoneNumber = $("#partyPhoneNumber").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('partyPhoneNumber', partyPhoneNumber);
            fd.append('customer_type', customer_type);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('sale.supplierDue') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    let isEpmty = Object.keys(result).length;
                    if (isEpmty > 0) {
                        $("#currentDue").text(result.current_due);
                        $("#customerName").val(result.name);
                        $("#customerAddress").val(result.address);
                        $("#customer").val(result.id);
                        $("#partyPhoneNumber").val(result.contact);
                        $("#creditLimit").text(result.credit_limit);
                        $("#leftCredit").text(result.credit_limit - result.current_due);
                        $("#customerName").prop('readonly', true);
                        $("#customerAddress").prop('readonly', true);
                    }
                    calculateTotal();
                    //$("#payment").focus();
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system");
                }
            })
        }

        function addToCart(fd) {
            $.ajax({
                url: "{{ route('sale.service.edit.addToCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    if (result.data == "Success") {
                        fetchCart();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.data,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response))
                    $("#barcodeError").text("No such product available in your system");
                }
            });
        }

        function updateCart(id, warehouse_id, product_type) {
            var product_quantity = $('#quantity_' + id + '_' + warehouse_id).val();
            var unitPrice = $('#unitPrice_' + id + '_' + warehouse_id).val();
            var discount = $('#discountPrice_' + id + '_' + warehouse_id).val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            // Serialize Product
            if (product_type == true) {
                fd.append('product_type', true);
                fd.append('serializeProductsId', serializeProductsId);
                fd.append('serializeSaleQuantity', serializeSaleQuantity);
            }
            fd.append('quantity', product_quantity);
            fd.append('unitPrice', unitPrice);
            fd.append('discount', discount);
            fd.append('_token', _token);
            fd.append('id', id);
            fd.append('warehouse_id', warehouse_id);
            $.ajax({
                url: "{{ route('sale.service.edit.updateCart') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    if(result.exceed == 'exceeded'){
                        Swal.fire("Sorry", "You can't sell any product lower then minimum price ", "error");
                    }
                    if (result.data == "Success") {
                        fetchCart();
                    } else {
                        //alert("Error To update cart");
                    }
                },
                beforeSend: function() {
                    //$('#loading').show();
                },
                complete: function() {
                    // $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                }
            })
        }

        function loadCartandUpdate(id, warehouse_id, product_type) {
            // Check Available Quantity
            let available_qty = parseFloat($('#available_qty_' + id + '_' + warehouse_id).text());
            let quantity = parseFloat($('#quantity_' + id + '_' + warehouse_id).val());
            let check_type = $('#product_type_' + id + '_' + warehouse_id).val();
            /* if (available_qty < quantity && check_type != "service") {
                Swal.fire({
                    title: 'Error!',
                    text: 'This Quantity Not available for sale',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                $('#quantity_' + id + '_' + warehouse_id).val(0);
                return 0;
            } */
            // End Available Quantity
            updateCart(id, warehouse_id, product_type);

           
        }

        function clearCart() {
            Swal.fire({
                title: "Are you sure ?",
                text: "Your product cart will be cleared!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear cart!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#barcode").val("");
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ route('sale.service.edit.clearCart') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            if (result == "Success") {
                                calculateTotal();
                                fetchCart();
                                clearSalesForm();
                                clearErrorMessage();
                            } else {
                                alert(JSON.stringify(response));
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            Swal.fire("Cancelled", "Error! Please try again:)", "error");
                        }
                    })
                } else {
                    Swal.fire("Cancelled", "Your product cart is safe :)", "error");
                }
            })
        }

        function removeCartProduct(id, warehouse_id, tbl_sale_order_productsId) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this product!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, remove cart data!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('id', id);
                    fd.append('warehouse_id', warehouse_id);
                    fd.append('tbl_sale_order_productsId', tbl_sale_order_productsId);
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ route('sale.service.edit.removeProduct') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            if (result.data == "Success") {
                                fetchCart();
                                calculateTotal();
                            } else {
                                Swal.fire("Cancelled", "Error Occured", "error");
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        }
                    })
                } else {
                    Swal.fire("Cancelled", "Your product is safe :)", "error");
                }
            })
        }

        //Update Order Sale Products
        $("#checkOutCart").click(function(e) {
            // Start Service Centre
            var accessoriesRecievedArray = [];
            $.each($("input[name='accessoriesRecieved']:checked"), function() {
                accessoriesRecievedArray.push($(this).val());
            });
            let isChecked = $('#otherAccessoriesCheck').is(':checked');
            if (isChecked == false) {
                $("#otherAccessories").val('');
            }
            var service_note=$('#service_note').val();
            var project_name=$('#project_name').val();
            var accessoriesRecieved = accessoriesRecievedArray.toString();
            var otherAccessories = $("#otherAccessories").val();
            var defectReported = $("#defectReported").val();
            var expectedDeliveryDate = $("#expectedDeliveryDate").val();
            var workApprovalDate = $("#workApprovalDate").val();
            var manufacturingSiNo = $("#manufacturingSiNo").val();
            var quantity = $("#quantity").val();
            var brand = $("#brand").val();
            var category = $("#category").val();
            var model = $("#model").val();
            var item = $("#item").val();
            var fd = new FormData();
            fd.append('defectReported', defectReported);
            fd.append('workApprovalDate', workApprovalDate);
            fd.append('expectedDeliveryDate', expectedDeliveryDate);
            fd.append('manufacturingSiNo', manufacturingSiNo);
            fd.append('brand', brand);
            fd.append('item', item);
            fd.append('model', model);
            fd.append('category', category);
            fd.append('service_note', service_note);
            fd.append('project_name', project_name);
            fd.append('accessoriesRecieved', accessoriesRecieved);
            fd.append('otherAccessories', otherAccessories);
            // End Service Centre

            // If Customer Not Exist
            var partyPhoneNumber = $("#partyPhoneNumber").val();
            var customerName = $("#customerName").val();
            var customerAddress = $("#customerAddress").val();

            var saleOrderId = $("#saleOrderId").val();
            var saleType = $("#saleType").val();
            var date = $("#saleDate").val();
            var customer_id = parseInt($("#customer").val());
            var totalAmount = $("#grandTotal").text();
            var discount = $("#discount").val();
            var carrying_cost = $("#transport").val();
            var vat = $("#vat").val();
            var ait = $("#ait").val();
            var grand_total = parseFloat($("#grandSum").text());
            //alert(grand_total);
            var previous_due = $("#currentDue").text();
            var total_with_due = parseFloat($("#totalWithDue").text());
            var current_payment = parseFloat($("#payment").val());
            var status = $("#status").val();
            var advance_payment = parseFloat($("#advance_payment").text());
            var payment_method = '';
            var current_balance = parseFloat(total_with_due) - parseFloat(current_payment) -  parseFloat($("#advance_payment").text());
            var totalDue = grand_total - (current_payment + advance_payment);
            var _token = $('input[name="_token"]').val();
            fd.append('saleOrderId', saleOrderId);
            fd.append('saleType', saleType);
            fd.append('date', date);
            fd.append('customer_id', customer_id);
            fd.append('total_amount', totalAmount);
            fd.append('discount', discount);
            fd.append('carrying_cost', carrying_cost);
            fd.append('vat', vat);
            fd.append('ait', ait);
            fd.append('grand_total', grand_total);
            fd.append('previous_due', previous_due);
            fd.append('payment_method', payment_method);
            fd.append('total_with_due', total_with_due);
            fd.append('current_payment', current_payment);
            fd.append('advance_payment', advance_payment);
            fd.append('current_balance', current_balance);
            fd.append('totalDue', totalDue);
            fd.append('customerName', customerName);
            fd.append('customerAddress', customerAddress);
            fd.append('partyPhoneNumber', partyPhoneNumber);
            fd.append('status', status);
            fd.append('_token', _token);
            clearErrorMessage();
            $.ajax({
                url: "{{ route('sale.service.edit.updatOrderSale') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    clearSalesForm();
                    fetchCart();
                    calculateTotal();
                    // Redirect After Click OK--//
                    let saleOrderId = result.saleOrderId;
                    Swal.fire({
                        title: "Saved !",
                        text: result.success,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        
                                printServiceUpdatedPdf(saleOrderId);
                                viewSaleOrders();
                            
                        }
                    }); 
                    //--End Redirect After Click OK--//
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    Swal.fire('Error!', 'Error: Please check again or contact with administrator',
                        'error');
                    $('#partyPhoneNumberError').text(response.responseJSON.errors.partyPhoneNumber);
                    $('#customerNameError').text(response.responseJSON.errors.customerName);
                    $('#customerAddressError').text(response.responseJSON.errors.customerAddress);
                    $('#defectReportedError').text(response.responseJSON.errors.defectReported);
                    $('#brandError').text(response.responseJSON.errors.brand);
                    $('#modelError').text(response.responseJSON.errors.model);
                    $('#itemError').text(response.responseJSON.errors.item);
                    $('#quantityError').text(response.responseJSON.errors.quantity);
                    $('#project_nameError').text(response.responseJSON.errors.project_name);
                    $('#accessoriesRecievedError').text(response.responseJSON.errors
                        .accessoriesRecieved);
                }
            })
        });





        
        function clearSalesForm() {
            $("#products").val(null).trigger("change");
            $("#warehouse").val(null).trigger("change");
            $("#total_amount").text("0");
            $("#discount").val("0");
            $("#transport").val("0");
            $("#vat").val(0);
            $("#ait").val(0);
            $("#grandTotal").text("0");
            $("#currentDue").text("0");
            $("#totalWithDue").text("0");
            $("#payment").val("0");
            $('#startDate').val('');
            $('#category').val('').trigger('change');
        }

        function clearErrorMessage() {
            $('#productError').text('');
            $('#partyPhoneNumberError').text('');
            $('#customerNameError').text('');
            $('#customerAddressError').text('');
        }

        function editSaleOrder(id) {
            var url = "{{ route('sale.service.edit.editSaleOrder', '') }}" + "/" + id;
            window.location.replace(url);
        }


        function viewSaleOrders() {
            var url = '{{ route('sale.service.SaleOrders') }}';
            window.location.href = url;
        }

        function printServiceUpdatedPdf(id) {
            var url = '{{ route('sale.service.completeInvoice', ':id') }}';
            url = url.replace(':id', id);
            window.open(url);
        }
        function reloadToEditPage(id){
            var url = '{{ route('sale.service.edit.editSaleOrder', ':id') }}';
            url = url.replace(':id', id);
            window.location.href = url;
        }
    </script>
@endsection
