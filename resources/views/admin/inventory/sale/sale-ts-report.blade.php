<html><head>
        <style>
            #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 3cm;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;}
            img{border:1px solid gray;}

            /** Define the footer rules **/
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;}
            .citiestd13 {background-color: rgb(242, 242, 242);border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            .supAddressFontEmi {font-size:13px;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
            .textLeft{text-align: left;font-size:12px;}
            .textRight{text-align: right;}
            .textCenter{text-align: center;}
            table {width:100%; border-collapse: collapse;margin-top: 10px;font-size: 0.8em; min-width: 400px;box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);}
            /* .td-text{
                text-align:right;
                padding-right:35px ;
            } */
            thead tr {background-color: #ffff;color: black;text-align: left;}
            /* th, td {
                padding: 12px 15px;
            } */
            .overline {text-decoration: overline;}
            .emi-table {width:80%;padding-left:10%;}
            .emi-table-title {padding-left:10px;margin-bottom:-5px;padding-left:11%;}

            .text-center{text-align:center;}
            /*img {
              margin-top: 25px
            }*/
        </style>
        <title>Sale Invoice</title>
    </head>

    <body>
        <!-- Content Wrapper. Contains page content -->
        <header>
            <div class="supAddressFont">
            <h4>{{Session::get('companySettings')[0]['name']}}</h4>
            {!!Session::get("companySettings")[0]["report_header"]!!}
            </div>
        </header>


        <footer>
            

            <div class="signatures">

                <div class="row " style="font-size:14px;">
                    <div class="column">
                        
                        <br>-----------------------<br>                 
                        Customer Signature
                    </div>
                    <div class="column"> 
                        {{auth()->user()->name}}
                        <br>-----------------------<br>
                        Created By                        
                    </div>
                    <div class="column">
                        
                        <br>-----------------------<br>                       
                        Authorized Signature
                    </div>                 
                </div><br><br>
                <hr />
                <div style="font-size:10px;">{!!Session::get('companySettings')[0]['report_footer']!!}</div>


            </div>

        </footer>
        <main>
            @if ($sale->deleted == 'No')
            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <div class="citiestd13">Temporary Sales Invoice</div>
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
                    <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            $totalAmount=0;
                            $totalQty=0;
                            @endphp
                            @foreach ($invoice as $products)
                            <tr>

                                <td class="text-center">{{ $i++ }}</td>
                                <td class="citiestd15">{{ $products->name . ' - ' . $products->productCode }}</td>
                                <td class="text-center">{{ $products->quantity }}</td>
                                @php
                                $totalQty += $products->quantity;
                                @endphp
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-center"> Total =</td>
                                <td class="text-center">{{$totalQty}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- start table -->
                    <table cellspacing="0" cellpadding="3">
                        <tr>
                            <td width="70%" class="supAddress">
                                

                                <table width="100%">
                                    <tr>
                                        <td width="30%" class="underAlignment"><b></b></td>
                                        <td width="70%" class="underAlignmentLeft"></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b></b></td>
                                        <td width="70%" class="underAlignmentLeft"></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b></b></td>
                                        <td width="70%" class="underAlignmentLeft"></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b></b></td>
                                        <td width="70%" class="underAlignmentLeft"></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="underAlignment"><b></b></td>
                                        <td width="70%" class="underAlignmentLeft"></td>
                                    </tr>

                                </table>

                            </td>
                            <td width="20%" class="supAddress" style="text-align:right;">
                                
                                <div><strong></strong></div>
                                <div><strong></strong></div>
                                <div><strong></strong></div>
                                <div><strong></strong></div>
                                
                            </td>
                            <td width="10%" class="supAddress textRight">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                
                            </td>
                        </tr>
                    </table>
                    <!-- End Table -->

                    
                </div>
            </div>
            @else 
            <div class="textCenter">Invoice Deleted Please check again !</div>
            @endif

        </main>


    </body>

</html>
