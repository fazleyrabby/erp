<html>

<head>
    <style>
        #footer {
            position: fixed;
            right: 0px;
            bottom: 10px;
            text-align: center;
            border-top: 1px solid black;
        }

        #footer .page:after {
            content: counter(1, decimal);
        }

        @page {
            margin: 0.2cm 0.2cm 0.2cm 0.2cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3.1cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-bottom: 1cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: .5cm;
            left: 0cm;
            right: 0cm;
            margin-left:0.5cm;
            margin-top:-0.5cm;
        }
        .customer-info{
            margin-top:0.5cm;
        }

        img {}

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 2cm;
            left: 0cm;
            right: 0cm;
            height: 1.8cm;
            text-align: center;
        }

        .column {
            float: left;
            width: 45%;
            height: 33px;
        }
        .column11 {
            text-align: center;
            width: 30%;
            height: 33px;
        }
        .column22 {
            float: right;
            width: 15%;
            height: 33px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .signatures {
            padding-bottom: -500px;
        }

        .citiestd13 {
            border: 1px solid gray;
            color: black;
            text-align: center;
            font-size: 11px;
            padding: 3px;
        }

        .supAddressFont {
            font-size: 11px;
        }

        .supAddressFont h2 {
            margin: 0% 0% 0% 0%;
        }

        .supAddressFontEmi {
            font-size: 13px;
        }

        .underAlignment {
            text-align: right;
            font-size: 13px;
        }

        .underAlignmentLeft {
            text-align: left;
            font-size: 11px;
        }

        .textLeft {
            text-align: left;
            font-size: 11px;
        }

        .textRight {
            text-align: right;
        }

        .textCenter {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 0.8em;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        /* .td-text{
                text-align:right;
                padding-right:35px ;
            } */
        thead tr {
            background-color: #ffff;
            color: black;
            text-align: left;
        }

        /* th, td {
                padding: 12px 15px;
            } */
        .overline {
            text-decoration: overline;
        }

        .emi-table {
            width: 80%;
            padding-left: 10%;
        }

        .emi-table-title {
            padding-left: 10px;
            margin-bottom: -5px;
            padding-left: 11%;
        }

        .text-center {
            text-align: center;
        }

        .spaces {
            color: white;
        }
        .column2 {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px; /* Should be removed. Only for demonstration */
            }

            /* Clear floats after the columns */
            .row2:after {
            content: "";
            display: table;
            clear: both;
            }
        .pagenum:before {
                content: counter(page);
                margin-left:90%;
        }

        /*img {
              margin-top: 25px
            }*/
    </style>
    <title>Sale Order Invoice</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <header>
            <div class="row2">
                <div class="column2">
                    <h4>{{Session::get('companySettings')[0]['name']}}</h4>
                </div>
                <div class="column2">
                @if ($saleOrders->deleted == 'No')
                    <table cellspacing="0" cellpadding="3" class="customer-info">
                        <tr>
                            <td width="50%" class="supAddress">
                            @foreach ($invoice as $user)
                                    <div><strong>Customer Name :
                                        </strong>{{ $user->customerName}}</div>
                                    <div><strong>Phone: </strong>{{ $user->contact }}</div>
                                    <div><strong>Address: </strong>{{ $user->address }}</div>
                                    <div><strong>Defect Reported: </strong>{{ $user->description }}</div>
                                    <div><strong>Entry By: </strong> {{ $user->entryBy }}</div>
                                    @break
                            @endforeach
                            </td>
                            
                        </tr>
                    </table>
                @endif
                </div>
            </div>
       
        
       <!--  <div class="supAddressFont">
            {!! Session::get('companySettings')[0]['report_header'] !!}
        </div> -->
    </header>
    <footer>
        
    </footer>
    <main>
        @if ($saleOrders->deleted == 'No')
            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <div class="citiestd13"><b>Sale Orders Invoice - {{ $saleOrders->sale_no }}</b>
                    </div>
                    <table cellspacing="0" cellpadding="3">
                        <tr>
                           
                        <td width="45%" class="supAddress">
                            @foreach ($invoice as $user)
                                <div><strong>Brand: </strong>{{ $user->brand }}</div>
                                <div><strong>Model: </strong>{{ $user->model }}</div>
                                @if($user->category == '1')
                                <div><strong>Category: </strong>Power Tools</div>
                                @elseif($user->category == '2')
                                <div><strong>Category: </strong>Welding Machine</div>
                                @endif
                            @break
                        @endforeach
                        <div><strong>Accessories Recieved : </strong>{{ $saleOrders->accessories_recieved}}</div>
                    </td>
                    <td width="30%" class="supAddress">
                        @foreach ($invoice as $user)
                                
                                <div><strong>Item: </strong>{{ $user->item }}</div>
                                <div><strong>Quantity: </strong>{{ $user->saleOrderQty }}</div>
                            @break
                        @endforeach
                        <div><strong>Status: </strong>{{ $saleOrders->order_status}}</div>
                    </td>
                    <td width="25%" class="supAddress">
                        @foreach ($invoice as $info)
                            <!-- <div><strong id="invoiceNo">Job No: #{{ $info->sale_no }}</strong></div> -->
                            <div><strong>Entry Of Date: </strong> {{ $info->date }}</div>
                            <div><strong>Expected Del. Date: </strong>{{ $info->expected_delivery_date }}
                        @break
                    @endforeach
                </td>
            </tr>
        </table>
        <br>
        <table cellspacing="0" cellpadding="3"> 
                        <tr>
                            <td>I/We agree to the tearms and conditions mentioned below.</td>
                        </tr>
                        <tr>
                            <td>
                            <img src="{{ 'upload/images/conditions.png'}}" width='80%' height='80'>
                            </td>
                        </tr>
                    </table>
        {{-- <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Unit Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $totalAmount = 0;
                        $totalQty = 0;
                    @endphp
                    @foreach ($invoice as $products)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="citiestd15">{{ $products->name . ' - ' . $products->productCode }}
                                <br>
                                @php
                                    $serializeProducts = App\Models\inventory\SaleSerializeProduct::saleSerializeProducts($products->product_id, $products->id);
                                @endphp
                                @forelse ($serializeProducts as $serializeProduct)
                                    {{ $serializeProduct->sale_quantity }},
                                @empty
                                @endforelse
                            </td>
                            </td>
                            <td class="text-center">{{ $products->quantity }}</td>
                            <td class="text-center">{{ $products->unit_price }}</td>
                            <td class="text-center">{{ $products->unit_discount }}</td>
                            <td class="textRight"> {{ $products->subtotal }}</td>
                            @php
                                $totalQty += $products->quantity;
                                $totalAmount += $products->subtotal;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-center"> Total =</td>
                        <td class="text-center">{{ $totalQty }}</td>
                        <td></td>
                        <td></td>
                        <td class="textRight"> {{ numberFormat($totalAmount) }}</td>
                    </tr>
                </tbody>
            </table> --}}
        <!-- Start Table -->

        {{-- Start SaleOrderFeedbacks --}}
        {{-- <br> <br>
            <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                <thead>
                    <tr>
                        <th colspan="3">Sale Order Feedbacks</th>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>Date Of Contact</th>
                        <th>Customer Response</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($saleOrderFeedbacks as $saleOrderFeedback)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center">{{ $saleOrderFeedback->date_of_contact }}</td>
                            <td class="text-center">{{ $saleOrderFeedback->customer_response }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
        {{-- End SaleOrderFeedbacks --}}
        {{-- <table cellspacing="0" cellpadding="3">
                <tr>
                    <td width="70%" class="supAddress">


                        <table width="100%">
                            <tr>
                                <td width="30%" class="underAlignment"><b>Amount In Words :</b></td>
                                <td width="70%" class="underAlignmentLeft">
                                    {{ numberToWord($saleOrders->grand_total) }} taka only</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Previous due :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $saleOrders->previous_due }}</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Current Bill :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $saleOrders->grand_total }}</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Paid Amount :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $saleOrders->current_payment }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Current due :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $saleOrders->current_dues }}</td>
                            </tr>

                        </table>

                    </td>
                    <td width="20%" class="supAddress" style="text-align:right;">

                        @php
                            $totalCost = $saleOrders->total_amount + $saleOrders->carrying_cost;
                            $payment = $saleOrders->current_payment + $saleOrders->discount;
                            $dueForThisTransection = $totalCost - $payment;
                        @endphp
                        <div><strong>Total Amount : </strong></div>
                        <div><strong>Discount : </strong></div>
                        <div><strong>Transport : </strong></div>
                        <div><strong>Net Payable (Round) : </strong></div>

                    </td>
                    <td width="10%" class="supAddress textRight">

                        @php
                            $totalCost = $saleOrders->total_amount + $saleOrders->carrying_cost;
                            $payment = $saleOrders->current_payment + $saleOrders->discount;
                            $dueForThisTransection = $totalCost - $payment;
                            $saledueForEmi = $saleOrders->current_dues;
                        @endphp
                        <div>{{ numberFormat($totalAmount) }}</div>
                        <div>{{ $saleOrders->discount }}</div>
                        <div>{{ $saleOrders->carrying_cost }}</div>
                        <div>{{ numberFormat($saleOrders->grand_total) }}</div>

                    </td>
                </tr>
            </table> --}}
        <!-- End Table -->
    </div>
</div>
@else
<div class="textCenter">Invoice Deleted Please check again !</div>
@endif

        <div class="signatures">
            <div class="row " style="font-size:11px;">
                <div class="column">
                    <br>-----------------------<br>
                    Customer Signature
                </div>
                <div class="column">
                    {{ auth()->user()->name }}
                    <br>-----------------------<br>
                    Created By
                </div>
                <div class="column22">
                    <br>-----------------------<br>
                    Authorized Signature
                </div>
            </div>
            <div style="font-size:10px;text-align:center;">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>
            <hr />
            
            <span class="pagenum"></span>
        </div>

</main>


</body>

</html>
