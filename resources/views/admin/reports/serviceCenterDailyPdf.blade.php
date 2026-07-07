<html>
<head>
     <style>
            #footer { position: fixed; right: 0px; bottom: 20px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 4cm;font-family: "Arial Narrow";font-size: 12pt;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;font-family: "Arial Narrow";font-size: 12pt;}
            

            /** Define the footer rules **/
            footer {position: fixed; bottom: 1cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;font-family: "Arial Narrow";font-size: 10pt;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;font-family: "Arial Narrow";font-size: 10pt;}
            .citiestd13 {background-color: rgb(242, 242, 242);border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
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

            .textLeft {
                text-align: left;
                font-size: 14px;
            }
            .text-right {
                text-align: right;
                
            }
        </style>
    <title>Daily Service Summary  {{$date}}</title>
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
   
   
           <div class="signatures">
           
                <div class="row">
                    <div class="column">
                        Ali Tech
                        <br>-----------------------<br>                 
                        Customer Signature
                    </div>
                    <div class="column"> 
                        {{Session::get('userName')}}
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
                 <div style="font-size:10px;">{!!Session::get('companySettings')[0]['report_footer']!!}</div>
  
        
        </div>
        
    </footer>
<main>
        
<!-- Content Wrapper. Contains page content -->
<div>
    <div style="text-align: center;">
        <div class="citiestd13">Service Center Summary  (Date: {{$date}})</div>

        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="60%" class="supAddress">
                  
                </td>
                <td width="40%" class="supAddress">
                   
                </td>
            </tr>
        </table>   
     
<h3>Completed Jobs</h3>
    <table  cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <td class="text-center width="5%"><b>Sl.</b></td>
                <td width="8%"><b>Job No.</b></td>
                <td width="15%"><b>COA</b></td>
                <td width="20%"><b>Party Info</b></td>
                <td width="15%" class="text-right"><b>Job Amount</b></td>
                <td width="15%" class="text-right"><b>Sale Amount</b></td>
                <td width="15%" class="text-right"><b>Balance</b></td>
                <td class="text-center" width="12%"><b>Status</b></td>
        </tr>
        </thead>
        <tbody>
            @php 
            $i=1;
            $total_amount_sum=0;
            $total_sale_amount_sum=0;
            @endphp
            @foreach($orders as $order)
            @php 
            $coa=DB::table('tbl_acc_coas')->where('id','=',$order->category)->first();
            @endphp
            <tr>
                <td>{{$i++}}</td>
                <td>{{$order->sale_no}}</td>
                <td>{{$coa->name}}</td>
                <td>Name: {{$order->partyName}}<br>Contact: {{$order->contact}}</td>
                <td class="text-right">{{numberFormat($order->grand_total)}} {{ session::get('companySettings')[0]['currency']}}</td>
                <td class="text-right">{{numberFormat($order->final_sale_amount)}} {{ session::get('companySettings')[0]['currency']}}</td>
                <td class="text-right">{{numberFormat(($order->grand_total)-($order->final_sale_amount))}} {{ session::get('companySettings')[0]['currency']}}</td>
                <td>{{$order->order_status}}</td>
            </tr>    
            @php
            $total_amount_sum +=$order->grand_total;
            $total_sale_amount_sum +=$order->final_sale_amount;
            @endphp
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><b>Total: </b></td>
                <td class="text-right"><b>{{ numberFormat($total_amount_sum) }} {{ session::get('companySettings')[0]['currency']}}</b></td>
                <td class="text-right"><b>{{numberFormat($total_sale_amount_sum)}} {{ session::get('companySettings')[0]['currency']}}</b></td>
                <td class="text-right"><b>{{numberFormat(($total_amount_sum - $total_sale_amount_sum))}} {{ session::get('companySettings')[0]['currency']}}</b></td>
                <td></td>
            </tr>
        </tbody>
    </table>





   <h3  style="padding-top:30px;">Other Jobs Created Today</h3>
   <table  cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <td width="5%"><b>SL</b></td>
                <td width="8%"><b>Job No</b></td>
                <td width="15%"><b>COA</b></td>
                <td width="20%"><b>Party Info</b></td>
                <td width="15%" class="text-right"><b>Amount</b></td>
                <td width="15%" class="text-center"><b>Status</b></td>
                <td width="23%"><b>Remarks</b></td>
            </tr>
        <thead>
        <tbody>
            @php 
            $i-1 ;
            $totalSum=0;
            @endphp
        @foreach($other_orders as $order)
            @php 
            $coa=DB::table('tbl_acc_coas')->where('id','=',$order->category)->first();
            @endphp
            <tr>
                <td>{{$i++}}</td>
                <td>{{$order->sale_no}}</td>
                <td>{{$coa->name}}</td>
                <td>Name: {{$order->partyName}}<br>Contact: {{$order->contact}}</td>
                <td class="text-right">{{$order->grand_total}} {{ session::get('companySettings')[0]['currency']}}</td>
                <td class="text-center">{{$order->order_status}}</td>
                <td></td>
            </tr>
            @php
            $totalSum +=$order->grand_total;
            @endphp
        @endforeach
            <tr> 
                <td colspan="4" class="text-right"><b>Total : </b></td>
                <td class="text-right">{{numberFormat($totalSum)}} {{ session::get('companySettings')[0]['currency'] }} </td>
                <td colspan="2" ></td>
            </tr>
        <tbody>
    </table>

    

    <h3  style="padding-top:30px;">Total Jobs</h3>
   <table  cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <td>Service COA</td>
                <td class="text-center">Pending</td>
                <td class="text-center">Servicing</td>
                <td class="text-center">Ready For Delivery</td>
                <td class="text-center">Delivered</td>
                <td class="text-center">Completed</td>
            </tr>
        <thead>
        <tbody>
           @foreach($coas as $coa)
           @php
                $totalPending=DB::table('sale_orders')->where('order_status','=','Pending')->where('category','=',$coa->id)->where('created_date','=',$date)->count();
                $totalServicing=DB::table('sale_orders')->where('order_status','=','Servicing')->where('category','=',$coa->id)->where('service_start_date','=',$date)->count();
                $totalReady=DB::table('sale_orders')->where('order_status','=','ReadyToDeliverd')->where('category','=',$coa->id)->where('ready_to_deliver_date','=',$date)->count();
                $totalDelivered=DB::table('sale_orders')->where('order_status','=','Delivered')->where('category','=',$coa->id)->where('delivered_date','=',$date)->count();
                $totalCompleted=DB::table('sale_orders')->where('order_status','=','Completed')->where('category','=',$coa->id)->where('completed_date','=',$date)->count();
            @endphp
                <tr>
                <td>{{$coa->name}}</td>
                <td class="text-center">{{$totalPending}}</td>
                <td class="text-center">{{$totalServicing}}</td>
                <td class="text-center">{{$totalReady}}</td>
                <td class="text-center">{{$totalDelivered}}</td>
                <td class="text-center">{{$totalCompleted}}</td>
            </tr>
        @endforeach
        <tbody>
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