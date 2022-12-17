<html>
<head>
     <style>

            #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.4cm 0.2cm 0.2cm 0.2cm;}


            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 14%;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 3cm;}


            /** Define the header rules **/
             header {position: fixed; top:.2cm;left: 0cm; right: 0cm; text-align:center;} 
            

            /** Define the footer rules **/
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;}
            .column {float: left;width: 33.33%;height:30px;}


            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;}
            .citiestd13 {border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 2px;}
            .supAddressFont {font-size:11px;margin-top:-30px;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
            table {width:100%; border-collapse: collapse;margin-top: 10px;font-size: 0.8em; min-width: 400px;box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);}
            
            /* .td-text{
                text-align:right;
                padding-right:35px ;
            } 
            */

            thead tr {background-color: #ffff;color: black;text-align: left;}
            
            /* th, td {
                padding: 12px 15px;
            } */

            .overline {text-decoration: overline;}
            .emi-table {width:80%;padding-left:10%;}
            .emi-table-title {padding-left:10px;margin-bottom:-5px;padding-left:11%;}

            .text-center{text-align:center;}

        </style>
    <title>Voucher</title>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
            <img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width='300' height='140'>
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
        <div class="citiestd13">Payment Receive Voucher </div>
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="60%" class="supAddress">
                     @foreach($paymentVouchers as $user)
                            <div><strong>Name : </strong>{{$user->name}}</div>
                            <div><strong>Phone: </strong>{{$user->contact}}</div>
                            <div><strong>Address: </strong>{{$user->address}}</div>
                        @break
                    @endforeach
                </td>
                <td width="40%" class="supAddress">
                @foreach($paymentVouchers as $info)
                        <div><strong id="invoiceNo">Voucher No : {{$info->voucherNo}}</strong></div>
                         <div><strong>Payment Date: </strong> {{$info->paymentDate}}</div> 
                        <div><strong>Entry By: </strong> {{$info->entryBy}}</div>
                    @break
                    @endforeach
                </td>
            </tr>
        </table>   
        <table border="1" class="invoice-info" cellspacing="0" cellpadding="3" style="font-size:13px;">
            <thead>
                <tr>
                    <th style="width:5%;">SL</th>
                    <th style="width:15%;">Date</th>
                    <th style="width:15%;">Payment Method</th>
                    <th style="width:35%;">Remarks</th>
                    <th style="width:20%;">Total</th>
                </tr>
            </thead>
            <tbody>
            @php 
                    $i = 1;
                @endphp
                @foreach($paymentVouchers as $paymentVoucher)
                <tr>
                    <td class="text-center">{{$i++}}</td>
                    <td class="text-center">{{ $paymentVoucher->paymentDate}}</td>
                    <td class="text-center"> {{ $paymentVoucher->payment_method}}</td>
                    <td class="text-center"> {{ $paymentVoucher->remarks}}</td>
                    <td class="text-center" style="text-align:right;">{{ $paymentVoucher->amount}}</td>
              </tr>
               @endforeach

               @foreach($paymentVouchers as $total)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center">Total  </td>
                    <td class="text-center" style="text-align:right;">  {!! Session::get('companySettings')[0]["currency"]!!} {{$total->amount}} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        

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