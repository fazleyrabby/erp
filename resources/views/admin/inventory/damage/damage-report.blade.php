<html>
    <head>
        <style>
            #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 3cm;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;}
            

            /** Define the footer rules **/
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;}
            .citiestd13 {background-color: rgb(242, 242, 242);border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            
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
        <title> Damage Invoice </title>
    </head>
    <body>
        <!-- Content Wrapper. Contains page content -->
        <header>
            <img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width='320' height='125'>
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
                <hr/>
                <div style="font-size:10px;">{!!Session::get('companySettings')[0]['report_footer']!!}</div>


            </div>
        </footer>
        <main>

            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <div class="citiestd13"> Damage Report </div>
                    <table cellpadding="3">
                        <tr>
                            @foreach($invoice as $products)
                                @php 
                                 $productName = $products->name;
                                 $damageDate = $products->damage_date;
                                 break;
                                 @endphp
                            @endforeach
                            <td style="width:70%;">Product Name : {{$productName}}</td>
                            <td style="width:30%;">Damage Date: {{$damageDate}}<br>Print Date : {{date('Y-m-d')}}<br>Print By : {{auth()->user()->name}}</td>
                        </tr>
                    </table>
                    
                    <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                        <thead>
                            <tr>
                                <th style="width:10%;">SL</th>
                                <th style="width:15%;">Date</th>
                                <th style="width:15%;">Order no</th>
                                <th style="width:10%;">Qty</th>
                                <th style="width:30%;">Remarks</th>
                                <th style="width:20%;">Created By</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $i = 1;
                            @endphp
                            @foreach($invoice as $products)
                            <tr>
                                <td>{{$i++}}</td>
                                <td class="text-center">{{$products->damage_date}}</td>
                                <td class="citiestd15">{{$products->damage_order_no}}</td>
                                <td class="text-center">{{$products->damage_quantity}}</td>
                                <td> {{$products->remarks}}</td>
                                <td> {{$products->createdBy}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
</main>   
</body>
</html>