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
            margin: 0.4cm 0.2cm 0.2cm 0.2cm;
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
            top: .5cm;
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
            font-size: 10px;
            padding: 2px;
        }

        .supAddressFont {
            font-size: 11px;
        }

        .underAlignment {
            text-align: right;
            font-size: 13px;
        }

        .underAlignmentLeft {
            text-align: left;
            font-size: 13px;
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
        .text-right {
            text-align: right;
        }

    </style>
    <title>Product Ledger</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif;font-size: 13px;">
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

        <!-- Content Wrapper. Contains page content -->
        <div>
            <div style="text-align: center;">
                <div class="citiestd13">Product Ledger </div>
                <table cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="70%" class="supAddress">
                            <div><strong>Name : </strong>{{ $product->name . ' - ' . $product->code }}</div>
                            <div><strong>Category: </strong>{{ $product->category_name }}</div>
                            <div><strong>Brand : </strong>{{ $product->brand_name }}</div>
                            <div><strong>Unit : </strong>{{ $product->unit_name }}</div>
                        </td>
                        <td width="30%" class="supAddress">
                            <div><strong id="invoiceNo">Start Date:</strong> {{ $startAndEndDate[0] }}</div>
                            <div><strong>End Date: </strong> {{ $startAndEndDate[1] }}</div>
                            <div><strong>Print Date: </strong> {{ todayDate() }}</div>
                            <div><strong>Printed By: </strong> {{ Session::get('userName') }}</div>
                        </td>
                    </tr>
                </table>
                <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="10%">Date</th>
                            <th width="32%">Party</th>
                            <th width="23%">Sale/Purchase No</th>
                            <th width="10%">Stock In</th>
                            <th width="10%">Stock Out</th>
                            <th width="10%">Remaining</th>>
                            <th width="10%">Amount </th>>
                            <th width="10%">Amount 2</th>>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $balance = 0;
                            $totalIn = 0;
                            $totalOut = 0;
                            $stockIn = '';
                            $stockOut = '';
                            if ($finalOpeningStock > 0) {
                                $stockIn = $finalOpeningStock;
                                $balance += $finalOpeningStock;
                                $totalIn += $finalOpeningStock;
                            } else {
                                $stockOut = $finalOpeningStock;
                                $balance -= $finalOpeningStock;
                                $totalOut += $finalOpeningStock;
                            }
                        @endphp
                        <tr>
                            <td colspan="4" class="text-center">  Opening Stock - Before {{ $startAndEndDate[0] }} </td>
                            
                            <td class="text-center">{{ $stockIn }}</td>
                            <td class="text-center">{{ $stockOut }}</td>
                            <td class="text-center">{{ $balance }}</td>
                            <td class="text-center">{{ $balance }}</td>
                            <td class="text-center">{{ $balance }}</td>
                        </tr>
                        @foreach ($productLedgers as $productLedger)
                            @php
                                $stockIn = '';
                                $stockOut = '';
                                
                                if ($productLedger->report_type == 'Purchase' || $productLedger->report_type == 'Sale Return') {
                                    $stockIn = $productLedger->quantity;
                                    $balance += $productLedger->quantity;
                                    $totalIn += $productLedger->quantity;
                                } else {
                                    $balance -= $productLedger->quantity;
                                    $stockOut = $productLedger->quantity;
                                    $totalOut += $productLedger->quantity;
                                }
                                
                            
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td class="citiestd15">{{ date('Y-m-d', strtotime($productLedger->date)) }}</td>
                                <td>Name: {{$productLedger->name}} <br> Address: {{$productLedger->address}} <br> Contact : {{$productLedger->mobile}}</td>
                                <td class="text-center">{{ $productLedger->report_type }} - {{ $productLedger->invoice }} (Tk. {{ $productLedger->price }})</td>
                                <td class="text-center">{{ $stockIn }}</td>
                                <td class="text-center"> {{ $stockOut }}</td>
                                <td class="text-center"> {{ $balance }}</td>
                                <td class="text-center"> {{ $balance }}</td>
                                <td class="text-center"> {{ $balance }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-center">Total =</td>
                            <td class="text-center">{{ $totalIn }} </td>
                            <td class="text-center">{{ $totalOut }}</td>
                            <td class="text-center">{{ $balance }}</td>
                            <td class="text-center">{{ $balance }}</td>
                            <td class="text-center">{{ $balance }}</td>
                        </tr>
                    </tbody>
                </table>
        <br><br>        
        <div class="signatures">

            <div class="row " style="font-size:12px;">
                <div class="column">

                    <br>-----------------------<br>
                    Customer Signature
                </div>
                <div class="column">
                    {{ Session::get('userName') }}
                    <br>-----------------------<br>
                    Created By
                </div>
                <div class="column">

                    <br>-----------------------<br>
                    Authorized Signature
                </div>
            </div><br><div style="font-size:8px;">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>
            <hr/>
        </div>
            </div>
        </div>



        </div>
        </div>
        </div>
        </section>
        </div>



    </main>

</body>

</html>
