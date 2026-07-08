<html>
<head>
    <style>
            #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 1cm;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;}
            
            img{}
            
            /** Define the footer rules **/
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;}
            .citiestd13 {border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            .supAddressFont h2{margin:0% 0% 0% 0%;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
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

        </style>
    <title>Sale Return Invoice</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <header>
            <h4>{{Session::get('companySettings')[0]['name']}}</h4>
            <div class="supAddressFont">
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
                        {{Session::get('userName')}}
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
        <!-- Content Wrapper. Contains page content -->
        <div>
            <div style="text-align: center;">
                <div class="citiestd13">Sale Return Invoice 
                    
                </div>
                <table cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="60%" class="supAddress">
                            @foreach($invoice as $user)
                            <div><strong>Name : </strong>{{$user->customer_name. ' - '. $user->code}}</div>
                            <div><strong>Phone: </strong>{{$user->contact}}</div>
                            <div><strong>Address: </strong>{{$user->address}}</div>
                            @break
                            @endforeach
                        </td>
                        <td width="40%" class="supAddress">
                            @foreach($invoice as $info)
                            <div><strong id="invoiceNo">Invoice: #{{$info->sale_no}}</strong></div>
                            <div><strong>Sale Date: </strong> {{$info->sale_date}}</div>
                            <div><strong>Return Date: </strong> {{$info->sale_return_date}}</div>
                            <div><strong>Entry By: </strong> {{$info->entryBy}}</div>
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
                        @endphp
                        @foreach($invoice as $products)
                        @php
                        $grand_total = $products->grand_total;
                        @endphp
                        <tr>
                            <td>{{$i++}}</td>
                            <td class="citiestd15">{{$products->name.' - '.$products->productCode}}</td>
                            <td class="text-center">{{$products->return_qty}}</td>
                            <td class="text-center">{{$products->unit_price}}</td>
                            <td class="textRight"> {{$products->total_price}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">Total </td>
                            <td class="textRight">{{ Session::get('companySettings')[0]["currency"].' '.numberFormat($grand_total) }}</td>
                        </tr>
                    </tbody>
                </table><br><br>
                <div>Net payable amount: {{ Session::get('companySettings')[0]["currency"].' '.$grand_total }}   [{{ numberToWord($grand_total) }} take only]</div>
            </div>
        </div>
        </div>
        </div>
        </div>
        </main>
</body>

</html>