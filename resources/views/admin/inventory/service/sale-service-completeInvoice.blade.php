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
            margin-bottom: 3cm;
           
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
            width: 13%;
            height: 33px;
        }

        .font13{
            font-size:10px;
        }
        .font15{
            font-size:13px;
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
            font-size: 10px;
            padding: 2px;
        }

        .supAddressFont {
            font-size: 11px;
        }

        .supAddressFont h2 {
            margin: 0% 0% 0% 0%;
        }

        .supAddressFontEmi {
            font-size: 10px;
        }

        .underAlignment {
            text-align: right;
            font-size: 10px;
        }

        .underAlignmentLeft {
            text-align: left;
            font-size: 10px;
        }

        .textLeft {
            text-align: left;
            font-size: 10px;
        }

        .textRight {
            text-align: right;
        }

        .textCenter {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse:collapse ;
            margin-top: 10px;
            font-size: 0.8em;
            min-width: 300px;
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
       
    </style>
    <title>Sale Order Invoice</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif;font-size: 13px;">
    <!-- Content Wrapper. Contains page content -->
    <header>
            <div class="row2">
                <div class="column2">
                    <img src="{{ 'upload/images/' . Session::get('companySettings')[0]['logo'] }}" width='205' height='105'>
                </div>
                <div class="column2">
                @if ($saleOrders->deleted == 'No')
                    <table cellspacing="0" cellpadding="3" class="customer-info">
                        <tr>
                            <td width="50%" class="supAddress">
                                <div><strong>Customer
                                        Name:</strong>{{ $saleOrders->name . ' - ' . $saleOrders->code . '' . $saleOrders->project_name }}</div>
                                <div><strong>Phone: </strong>{{ $saleOrders->contact }}</div>
                                <div><strong>Address: </strong>{{ $saleOrders->address }}</div>
                                <div><strong>Defect Reported: </strong>{{ $saleOrders->description }}</div>
                                <div><strong>Expected Del. Date: </strong>{{ $saleOrders->expected_delivery_date }}
                                </div>
                            </td>
                            
                        </tr>
                    </table>
                @endif
                </div>
            </div>
    </header>
    <footer>
        
    </footer>
    <main>
        @if ($saleOrders->deleted == 'No')
            <!-- Content Wrapper. Contains page content -->
            <div style="text-align: center;">
                    <div class="citiestd13">
                      <b>Service Order Invoice - {{ $saleOrders->sale_no }} </b>  
                    </div>
                    <table cellspacing="0" cellpadding="3">
                        <tr>
                            <td width="40%" class="supAddress">
                                <div><strong>Brand: </strong>{{ $saleOrders->brand }}</div>
                                <div><strong>Model: </strong>{{ $saleOrders->model }}</div>
                                @if($saleOrders->category == '1')
                                <div><strong>Category: </strong>Power Tools</div>
                                @elseif($saleOrders->category == '2')
                                <div><strong>Category: </strong>Welding Machine</div>
                                @endif
                                
                                @if($saleOrders->accessories_recieved == null)
                                <div style="display:none"><strong>Accessories Recieved : </strong><br>{{ $saleOrders->accessories_recieved}}</div>
                                @else
                                <div><strong>Accessories Recieved:</strong>{{ $saleOrders->accessories_recieved}},{{ $saleOrders->other_accessories }}</div>
                                @endif
                            </td>
                            <td width="35%" class="supAddress">
                                <div><strong>Item: </strong>{{ $saleOrders->item }}</div>
                                <div><strong>Quantity: </strong>{{ $saleOrders->quantity }}</div>
                                <div><strong>Status: </strong>{{ $saleOrders->order_status }}</div>
                            </td>2022-11-20
                            <td width="25%" class="supAddress">
                                <!-- <div><strong id="invoiceNo">Job No: #{{ $saleOrders->sale_no }}</strong></div> -->
                                <div><strong>Entry Of Date: </strong> {{ $saleOrders->date }}</div>
                                <div><strong>Entry By: </strong> {{ $saleOrders->entry_by }}</div>
                                <div><strong>{{ $saleOrders->order_status }} Date: </strong> {{ $saleOrders->order_status == 'Delivered' ? $saleOrders->delivered_date : $saleOrders->completed_date  }}</div>
                            </td>
                        </tr>
                    </table> 


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
                    
                    

                    <!-- Start Product Table -->
                    <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                        <thead>
                            <tr>
                                <th colspan="6"> <b>Sale Order List</b> </th>
                            </tr>
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
                                @if ($products->quantity > 0)
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
                                        <td class="textRight"> {{ $products->subtotal }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                                        @php
                                            $totalQty += $products->quantity;
                                            $totalAmount += $products->subtotal;
                                        @endphp
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-center"> Total =</td>
                                <td class="text-center">{{ $totalQty }} </td>
                                <td></td>
                                <td></td>
                                <td class="textRight"> {{ numberFormat($totalAmount) }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End Product Table -->
                    
                    
                    
                    <table cellspacing="0" cellpadding="3">
                        <thead>
                            <tr>
                                <th colspan="3" class="underAlignmentLeft"><b>Amount In Words: </b> {{ ucfirst(numberToWord($totalAmount)) }} taka only</th>
                            </tr>
                        </thead>
                       <tbody>
                        <tr>
                            
                            <td width="50%" >
                                @if($saleOrders->current_payment > '0')
                                <!-- Start payment Table -->
                                <table style="width:50%" >
                                    <thead>
                                        <tr>
                                            <th colspan="3"><u><b><span class="font13">Customer Payment History</span></b></u></th>
                                        </tr>
                                        <tr>
                                            <th width="10%" class="font13">#</th>
                                            <th width="25%" class="font13">Date</th>
                                            <th width="25%" class="font13" class="textRight">Payment</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $k=1;
                                        @endphp

                                        @foreach($payments as $payment)
                                        <tr>
                                            <td class="text-center font13" >{{$k++}}</td>
                                            <td class="text-center font13" >{{$payment->paymentDate}}</td>
                                            <td class="textRight font13" >{{$payment->amount}} {{ Session::get('companySettings')[0]['currency'] }}</td>

                                        </tr>
                                        @endforeach
                                    
                                    </tbody>
                                </table>
                                <!-- End Product Table --> 
                                @else
                                    <!-- Start payment Table -->
                                <table  style="display:none;">
                                    <thead>
                                        <tr>
                                            <th colspan="3"><u><b>Customer Payments History</b></u></th>
                                            
                                        </tr>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Date</th>
                                            <th width="25%" class="textRight">Payment</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $k=1;
                                        @endphp

                                        @foreach($payments as $payment)
                                        <tr>
                                            <td class="text-center">{{$k++}}</td>
                                            <td class="text-center">{{$payment->paymentDate}}</td>
                                            <td class="textRight">{{$payment->amount}}</td>
                                            
                                        </tr>
                                        @endforeach
                                    
                                    </tbody>
                                </table>
                                <!-- End Product Table -->   
                                @endif
                            </td>

                            <td width="30%" class="supAddress" style="text-align:left;">

                                <table width="100%">

                                 
                                    <tr>
                                        <td width="30%" class="underAlignment"><b>Previous due :</b></td>
                                        <td width="70%" class="underAlignmentLeft">
                                            {{ $saleOrders->previous_due }}  {{ Session::get('companySettings')[0]['currency'] }}</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b>Current Bill :</b></td>
                                        <td width="70%" class="underAlignmentLeft"> {{ $saleOrders->grand_total }} {{ Session::get('companySettings')[0]['currency'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b>Paid Amount :</b></td>
                                        <td width="70%" class="underAlignmentLeft">
                                            {{ $saleOrders->current_payment }} {{ Session::get('companySettings')[0]['currency'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b>Current due :</b></td>
                                        <td width="70%" class="underAlignmentLeft">
                                            {{ $saleOrders->current_dues }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                                    </tr>
                                    
                                </table>

                            </td>

                            <td width="30%" class="supAddress" style="text-align:right;">

                                @php
                                    $totalCost = $saleOrders->total_amount + $saleOrders->carrying_cost;
                                    $payment = $saleOrders->current_payment + $saleOrders->discount;
                                    $dueForThisTransection = $totalCost - $payment;
                                @endphp
                                <div><strong>Total Amount : </strong></div>
                                <div><strong>Discount : </strong></div>
                                <div><strong>Transport : </strong></div>
                                <div><strong>Vat : </strong></div>
                                <div><strong>Ait : </strong></div>
                                <div><strong>Net Payable (Round) : </strong></div>

                            </td>
                            <td width="15%" class="supAddress textRight">

                                @php
                                    $totalCost = $saleOrders->total_amount + $saleOrders->carrying_cost;
                                    $payment = $saleOrders->current_payment + $saleOrders->discount;
                                    $dueForThisTransection = $totalCost - $payment;
                                    $saledueForEmi = $saleOrders->current_dues;
                                @endphp
                                <div>{{ numberFormat($totalAmount) }} {{ Session::get('companySettings')[0]['currency'] }}</div>
                                <div>{{ $saleOrders->discount }} {{ Session::get('companySettings')[0]['currency'] }}</div>
                                <div>{{ $saleOrders->carrying_cost }} {{ Session::get('companySettings')[0]['currency'] }}</div>
                                <div>{{ $saleOrders->vat }} {{ Session::get('companySettings')[0]['currency'] }}</div>
                                <div>{{ $saleOrders->ait }} {{ Session::get('companySettings')[0]['currency'] }}</div>
                                <div>{{ numberFormat($saleOrders->grand_total) }} {{ Session::get('companySettings')[0]['currency'] }}</div>

                            </td>
                        </tr>
                        </tbody> 
                    </table>
                  
                        @if(count($saleOrderFeedbacks) > 0)
                            <table border="" class="invoice-info" cellspacing="0" cellpadding="3">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="textLeft"><b><span class="font15"><u>Customer Feedbacks</u></span></b></th>
                                    </tr>
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th width="15%">Date Of Contact</th>
                                        <th class="textLeft" width="80%">Customer Response</th>
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
                                            <td class="textLeft">{{ $saleOrderFeedback->customer_response }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                        <table border="" class="invoice-info" cellspacing="0" cellpadding="3" style="display:none;">
                                <thead>
                                    <tr>
                                        <th colspan="3">Customer Feedbacks</th>
                                    </tr>
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th width="15%">Date Of Contact</th>
                                        <th class="textLeft" width="80%">Customer Response</th>
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
                                            <td class="textLeft">{{ $saleOrderFeedback->customer_response }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
            
            </div>
        @else
            <div class="textCenter">Invoice Deleted Please check again !</div>
        @endif
        
        <br>
        <div class="signatures">

            <div class="row " style="font-size:10px;">
                <div class="column">

                    <br>--------------------------<br>
                    Customer Signature
                </div>
                <div class="column">
                    {{ Session::get('userName') }}
                    <br>----------------------------<br>
                    Created By
                </div>
                <div class="column22">

                    <br>-----------------------------<br>
                    Authorized Signature
                </div>
                <div style="font-size:8px;text-align: center;">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>
            </div>
            <hr/>
        </div>

    </main>


</body>

</html>
