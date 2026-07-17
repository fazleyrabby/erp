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
            margin-top: 3cm;
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
            text-align: center;
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
            font-size: 12px;
        }

        .underAlignmentLeft {
            text-align: left;
            font-size: 12px;
        }
        .underFontSize {
            font-size: 12px;
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

        /*img {
              margin-top: 25px
            }*/
    </style>
    <title>Sale Invoice</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <header>

        <h4>{{Session::get('companySettings')[0]['name']}}</h4>
        <div class="supAddressFont">
            {!! Session::get('companySettings')[0]['report_header'] !!}
        </div>
    </header>
    <footer>
        
    </footer>
    <main>
        @if ($sale && $sale->deleted == 'No')
            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <div class="citiestd13">Sales Invoice
                        @if ($sale->sales_type == 'party_sale')
                            Party Sales
                        @elseif ($sale->sales_type == 'walkin_sale')
                            @if($sale->tbl_sale_order_id == '')
                            WI Sale
                            @else 
                            Service Center Sale
                            @endif
                        @elseif ($sale->sales_type == 'FS')
                            Final Sale
                        @endif
                    </div>
                    <table cellspacing="0" cellpadding="3">
                        <tr>
                            <td width="70%" class="supAddress">
                                @foreach ($invoice as $user)
                                    <div><strong>Name : </strong>{{ $user->customerName . ' - ' . $user->code }}</div>
                                    <div><strong>Phone: </strong>{{ $user->contact }}</div>
                                    <div><strong>Address: </strong>{{ $user->address }}</div>
                                @break
                            @endforeach
                        </td>
                        <td width="30%" class="supAddress">
                            @foreach ($invoice as $info)
                                <div><strong id="invoiceNo">Invoice: #{{ $info->sale_no }}</strong></div>
                                <div><strong>Sale Date: </strong> {{ $info->date }}</div>
                                <div><strong>Entry By: </strong> {{ $info->entryBy }}</div>
                            @break
                        @endforeach
                    </td>
                </tr>
            </table>
            <table border="1" class="invoice-info underFontSize" cellspacing="0" cellpadding="3">
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
                                {{-- Serialize Product --}}
                                @if ($products->type == 'serialize')
                                    @php
                                        $serializeProducts = App\Models\inventory\SaleSerializeProduct::saleSerializeProducts($products->product_id, $products->id);
                                    @endphp
                                    Details :
                                    @forelse ($serializeProducts as $serializeProduct)
                                        {{ $serializeProduct->serial_no }} {{ $loop->last ? '' : ', ' }}
                                    @empty
                                    @endforelse
                                @endif
                                {{-- Serialize Product --}}
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
            </table>
            <!-- Start Table -->
            <table cellspacing="0" cellpadding="3">
                <tr>
                    <td width="70%" class="supAddress">


                        <table width="100%">
                            <tr>
                                <td width="30%" class="underAlignment"><b>Amount In Words :</b></td>
                                <td width="70%" class="underAlignmentLeft">
                                    {{ ucfirst(numberToWord($sale->grand_total)) }} taka only</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Previous due :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $sale->previous_due }}</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Current Bill :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $sale->grand_total }}</td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Paid Amount :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $sale->current_payment }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" class="underAlignment"><b>Current due :</b></td>
                                <td width="70%" class="underAlignmentLeft"> {{ $sale->current_dues }}</td>
                            </tr>

                        </table>

                    </td>
                    <td width="20%" class="supAddress" style="text-align:right;">

                        @php
                            $totalCost = $sale->total_amount + $sale->carrying_cost + $sale->vat + $sale->ait;
                            $payment = $sale->current_payment + $sale->discount;
                            $dueForThisTransection = $totalCost - $payment;
                        @endphp
                        <div ><strong>Total Amount : </strong></div>
                        <div class="underFontSize"><strong>Discount : </strong></div>
                        <div class="underFontSize"><strong>Transport : </strong></div>
                        <div class="underFontSize"><strong>Vat : </strong></div>
                        <div class="underFontSize"><strong>Ait : </strong></div>
                        <div class="underFontSize"><strong>Net Payable (Round) : </strong></div>

                    </td>
                    <td width="10%" class="supAddress textRight">

                        @php
                            $totalCost = $sale->total_amount + $sale->carrying_cost + $sale->vat + $sale->ait;
                            $payment = $sale->current_payment + $sale->discount;
                            $dueForThisTransection = $totalCost - $payment;
                            $saledueForEmi = $sale->current_dues;
                        @endphp
                        <div>{{ numberFormat($totalAmount) }}</div>
                        <div>{{ $sale->discount }}</div>
                        <div>{{ $sale->carrying_cost }}</div>
                        <div>{{ $sale->vat }}</div>
                        <div>{{ $sale->ait }}</div>
                        <div>{{ $sale->grand_total }}</div>

                    </td>
                </tr>
            </table>
            <!-- End Table -->
            @if($serviceCenter != '')
            <table border=''>
                <tr><td><strong>Job Summary </strong>- {{$serviceCenter->sale_no}} </td>
                    <td><strong>Total Amount: </strong>{{ numberFormat($serviceCenter->grand_total) }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                    <td><strong>Real Sale Amount: </strong>{{$serviceCenter->final_sale_amount }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                    <td><strong>Profit: </strong>{{numberFormat(($serviceCenter->grand_total)  - $serviceCenter->final_sale_amount) }} {{ Session::get('companySettings')[0]['currency'] }}</td>
                </tr>
            </table>
            @endif
        </div>
    </div>
@else
    <div class="textCenter">Invoice Deleted Please check again !</div>
@endif
        <br><div class="signatures">
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
            </div><div style="font-size:10px;text-align:center">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>
            <hr />
           
        </div>

</main>


</body>

</html>
