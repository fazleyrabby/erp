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
            margin-bottom: 3cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: .65cm;
            left: 0cm;
            right: 0cm;
            text-align: center;
        }


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
            width: 33.33%;
            height: 30px;
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

        .underAlignment {
            text-align: right;
            font-size: 11px;
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

        h2 {
            margin: .2%;
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
        .leftAlign{font-size: 11px;}
    </style>
    <title>Purchase Invoice</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif;font-size: 13px;">
    <!-- Content Wrapper. Contains page content -->
    <header>
        <img src="{{ 'upload/images/' . Session::get('companySettings')[0]['logo'] }}" width='205' height='105'>
        <div class="supAddressFont">
            {!! Session::get('companySettings')[0]['report_header'] !!}
        </div>
    </header>


    <footer>


        <div class="signatures">

            <div class="row " style="font-size:10px;">
                <div class="column">
                    Ali Tech
                    <br>-----------------------<br>
                    Customer Signature
                </div>
                <div class="column">
                    {{ auth()->user()->name }}
                    <br>-----------------------<br>
                    Created By
                </div>
                <div class="column">
                    Manager
                    <br>-----------------------<br>
                    Authorized Signature
                </div>
            </div><br><br>
            <hr />
            <div style="font-size:10px;">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>


        </div>

    </footer>
    <main>
        @if (!$invoice->isEmpty())
            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <div class="citiestd13">Purchase Invoice </div>
                    <table cellspacing="0" cellpadding="3">
                        <tr>
                            <td width="60%" class="supAddress">
                                @foreach ($invoice as $user)
                                    <div><strong>Name : </strong>{{ $user->supplier_name . ' - ' . $user->code }}</div>
                                    <div><strong>Phone: </strong>{{ $user->contact }}</div>
                                    <div><strong>Address: </strong>{{ $user->address }}</div>
                                @break
                            @endforeach
                        </td>
                        <td width="40%" class="supAddress">
                            @foreach ($invoice as $info)
                                <div><strong id="invoiceNo">Invoice: #{{ $info->purchase_no }}</strong></div>
                                <div><strong>Purchase Date: </strong> {{ $info->date }}</div>
                                <div><strong>Entry By: </strong> {{ $info->entryBy }}</div>
                            @break
                        @endforeach
                    </td>
                </tr>
            </table>
            <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $totalAmount = 0;
                    @endphp
                    @foreach ($invoice as $products)
                        @php
                            $totalAmount = $products->total_amount;
                        @endphp
                        <tr class="leftAlign">
                            <td class="textCenter">{{ $i++ }}</td>
                            <td class="citiestd15">
                                {{ $products->name . ' - ' . $products->productCode }}
                                <br>
                                {{-- Serialize Product --}}
                                @if ($products->type == 'serialize')
                                    @php
                                        $serializeProducts = App\Models\inventory\SerializeProduct::SerializeProducts($products->product_id, $products->purchase_id);
                                    @endphp
                                    Details :
                                    @forelse ($serializeProducts as $serializeProduct)
                                        {{ $serializeProduct->serial_no }} {{ $loop->last ? '' : ', ' }}
                                    @empty
                                    @endforelse
                                @endif
                                {{-- Serialize Product --}}
                            </td>
                            <td class="text-center">{{ $products->quantity }}</td>
                            <td class="text-center">{{ $products->unit_price }}</td>
                            <td class="textRight"> {{ $products->subtotal }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center">Total </td>
                        <td class="textRight"> {!! Session::get('companySettings')[0]['currency'] . ' ' . $totalAmount !!}</td>
                    </tr>
                </tbody>
            </table>
            <!-- check table -->
            <table cellspacing="0" cellpadding="3">
                <tr>
                    <td width="70%" class="supAddress">
                        @foreach ($purchases as $purchase)
                            @php
                                $netPayable = $purchase->total_amount + $purchase->carrying_cost - $purchase->discount;
                                if ($purchase->previous_due != 0) {
                                    $purchase->previous_due = number_format(-$purchase->previous_due, 2);
                                }
                            @endphp
                            <table width="100%">
                                <tr>
                                    <td width="30%" class="underAlignment"><b>Amount In Words :</b></td>
                                    <td width="70%" class="underAlignmentLeft">
                                        {{ ucfirst(numberToWord($netPayable)) }} taka only</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="underAlignment"><b>Previous dues :</b></td>
                                    <td width="70%" class="underAlignmentLeft">
                                        {{ $purchase->previous_due }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="underAlignment"><b>Current Bill :</b></td>
                                    <td width="70%" class="underAlignmentLeft"> {{ $purchase->grand_total }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="underAlignment"><b>Paid Amount :</b></td>
                                    <td width="70%" class="underAlignmentLeft">
                                        {{ $purchase->current_payment }}</td>
                                </tr>
                                <tr>
                                    <td width="30%" class="underAlignment"><b>Current dues :</b></td>
                                    <td width="70%" class="underAlignmentLeft">
                                        {{ $purchase->current_balance }}</td>
                                </tr>

                            </table>
                        @break
                    @endforeach
                </td>
                <td width="20%" class="supAddress" style="text-align:right;">
                    @foreach ($purchases as $purchase)
                        @php
                            $totalCost = $purchase->total_amount + $purchase->carrying_cost;
                            $payment = $purchase->current_payment + $purchase->discount;
                            $dueForThisTransection = $totalCost - $payment;
                        @endphp
                        <div class="leftAlign"><strong>Total Amount: </strong></div>
                        <div class="leftAlign"><strong>Discount: </strong></div>
                        <div class="leftAlign"><strong>Transport: </strong></div>
                        <div class="leftAlign"><strong>Net Payable (Round): </strong></div>
                    @break
                @endforeach
                </td>
            <td width="10%" class="supAddress">
                @foreach ($purchases as $purchase)
                    @php
                        $totalCost = $purchase->discount + $purchase->carrying_cost;
                        $payment = $purchase->current_payment + $purchase->discount;
                    @endphp
                    <div class="leftAlign">{{ $purchase->total_amount }}</div>
                    <div class="leftAlign">{{ $purchase->discount }}</div>
                    <div class="leftAlign">{{ $purchase->carrying_cost }}</div>
                    <div class="leftAlign">{{ numberFormat($netPayable) }} </div>
                @break
            @endforeach
        </td>
    </tr>
</table>
<!-- end check table -->


</div>
</div>



</div>
</div>
</div>
</section>
</div>
@else
<div class="textCenter">Invoice Deleted Please check again !</div>
@endif

</main>

</body>

</html>
