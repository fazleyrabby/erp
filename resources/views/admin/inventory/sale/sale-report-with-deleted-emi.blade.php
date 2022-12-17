<html>
<head>
    <style>
         #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(1, decimal); }
        @page {
                margin: 0.2cm 0.2cm 0.2cm 0.2cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 6.5cm;
                margin-left: 1cm;
                margin-right: 0.5cm;
                margin-bottom: 3cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 1cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;
                text-align:center;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 2cm; 
                left: 0cm; 
                right: 0cm;
                height: 1.8cm;
                text-align:center;
                
            }
         
            .column {
                float: left;
                width: 33.33%;
                /* padding: 10px; */
                height:30px; /* Should be removed. Only for demonstration */
                }

                /* Clear floats after the columns */
                .row:after {
                content: "";
                display: table;
                clear: both;
                }
                .signatures{
                    padding-bottom:-500px;
                }
                .citiestd13 {background-color: red;color: black;text-align: center;font-size: 13px;padding: 5px;}
                .citiestd14 {text-align: center;font-size: 11px;}
                .citiestd15 {text-align: left;font-size: 11px;}
                .supAddress {font-size: 12px;}
                .supAddressFont {font-size:11px;}
                table {
                width:100%;    
                border-collapse: collapse;
                margin: 15px 10px;
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
                    width:80%;
                    padding-left:10%;
                }
                .emi-table-title {
                    padding-left:10px;
                    margin-bottom:-5px;
                    padding-left:11%;
                }
                
                /* .footer-text{
                    text-align:center;
                padding:25px;
                } */
                .text-center{
                    text-align:center;
                }
                
    </style>
    <title>Sale Invoice</title>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <!-- Content Header (Page header) -->

            <div><img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width="110" height="55"></div>
        
       <p> {!!Session::get('companySettings')[0]["report_header"]!!}</p>
               <p class="citiestd14">Phone: {{Session::get('companySettings')[0]["phone"]}} Email:  {{Session::get('companySettings')[0]["email"]}}</p>

    </header>


    <footer>
   
   
           <div class="signatures">
           
                <div class="row ">
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
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
  
        
        </div>
        
    </footer>
<main>
        
<!-- Content Wrapper. Contains page content -->
<div>
    <div style="text-align: center;">
        <div class="citiestd13">Sales Invoice </div>
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="60%" class="supAddress">
                        @foreach($invoice as $user)
                            <div><strong>Name : </strong>{{$user->customerName. ' - '. $user->code}}</div>
                            <div><strong>Phone: </strong>{{$user->contact}}</div>
                            <div><strong>Address: </strong>{{$user->address}}</div>
                        @break
                    @endforeach
                </td>
                <td width="40%" class="supAddress">
                    @foreach($invoice as $info)
                        <div><strong id="invoiceNo">Invoice: #{{$info->sale_no}}</strong></div>
                        <div><strong>Sale Date: </strong> {{$info->date}}</div>
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
                <tr>
                    <td>{{$i++}}</td>
                    <td class="citiestd15">{{$products->name.' - '.$products->productCode}}</td>
                    <td class="text-center">{{$products->quantity}}</td>
                    <td class="text-center">{{$products->unit_price}}</td>
                    <td class="text-center"> {{$products->subtotal}}</td>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center"> Total =</td>
                    <td class="text-center"> {{$products->total_amount}}</td>
                </tr>
            </tbody>
        </table>
        <!-- start table -->
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="70%" class="supAddress">
                    @foreach($sales as $sale)
                            <div>Amount In Words :<strong> {{ numberToWord($sale->grand_total) }} taka only</strong></div>
                            <div>Previous dues : <strongz> {{ $sale->previous_due }}</strong></div>
                            <div>Current Bill :<strong>  {{ $sale->grand_total }}</strong></div>
                            <div>Paid Amount :<strong>   {{ $sale->current_payment }}</strong></div>
                            <div>Current dues : <strong> {!!Session::get('companySettings')[0]["currency"]!!}  {{ $sale->current_dues }}</strong></div>
                        @break
                    @endforeach
                </td>
                <td width="20%" class="supAddress" style="text-align:right;">
                    @foreach($sales as $sale)
                        @php
                            $totalCost = $sale->total_amount + $sale->carrying_cost ;
                            $payment = $sale->current_payment + $sale->discount ;
                            $dueForThisTransection =  $totalCost -  $payment ;
                        @endphp
                        <div><strong>Total Amount: </strong></div>
                        <div><strong >Discount: </strong></div>
                        <div><strong>Transport: </strong></div>
                        <div><strong>Net Payable (Round): {!!Session::get('companySettings')[0]["currency"]!!} </strong></div>                         
                    @break
                    @endforeach
                </td>
                <td width="10%" class="supAddress">
                    @foreach($sales as $sale)
                        @php
                            $totalCost = $sale->total_amount + $sale->carrying_cost ;
                            $payment = $sale->current_payment + $sale->discount ;
                            $dueForThisTransection =  $totalCost -  $payment ;
                            $saledueForEmi = $sale->current_dues;
                        @endphp
                        <div><strong> </strong> {{ $sale->total_amount }}</div>
                        <div><strong > </strong> {{$sale->discount}}</div>
                        <div><strong> </strong> {{ $sale->carrying_cost }}</div>
                        <div><strong> </strong> {{ $sale->grand_total }}</div>
                    @break
                    @endforeach
                </td>
            </tr>
        </table>   
        <!-- end table -->
    </div>
</div>


@if( count($salesEmi) )
 
<div>					
    <div class="emi-table-title">
        <label style="font-weight:600;">EMI Details </label>
        <label>Total Amount : {{ $saledueForEmi }} </label>
    </div>
    <table class="emi-table text-center" cellspacing="0" border="1">
        <thead>
        <tr>
            <th>SL</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Is Paid</th>
            <!-- <th>Actions</th> -->
        </tr>
        </thead>
        <tbody>
            @php
                $serail = 1 ;
            @endphp
            @foreach($salesEmi as $emi)
                @if ($emi->is_paid == "Yes" && $emi->deleted == "No")
                    <tr>
                        <td>{{ $serail++ }}</td>
                        <td>{{$emi->tenure_payment_date}}</td>
                        <td  >{{$emi->per_tenur_amount}} </td>
                        <td><b>Yes</b></td>
                    </tr>
                @elseif($emi->deleted == 'No')
                    <tr>
                        <td>{{ $serail++ }}</td>
                        <td>{{$emi->tenure_payment_date}}</td>
                        <td>{{$emi->per_tenur_amount}} </td>
                        <td><b> {{ ($emi->is_paid=='No' && $emi->deleted=='No' ) ? 'No' : 'Yes' }} </b></td>
                    </tr>
                @endif
        @endforeach
        </tbody>
    </table>
</div>
<!--start deleted table -->
<div>					
    <div class="emi-table-title">
        <label>EMI Deleted Details Information : </label>	
    </div>					
    <table  class="emi-table text-center" cellspacing="0" border="1">
        <thead>
        <tr>
            <th>SL</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody style="color:red;margin-top:25px;">
            @php
                $serail = 1 ;
            @endphp
            @foreach($salesEmi as $emi)
                @if ($emi->is_paid != "Yes" && $emi->deleted == "Yes")
                    <tr>
                        <td>{{$serail++}}</td>
                        <td>{{$emi->tenure_payment_date}}</td>
                        <td >{{$emi->per_tenur_amount}} </td>
                        <td >deleted</td>
                    </tr>
                @endif
                
            @endforeach
        </tbody>
    </table>			
</div>
<!--end deleted table -->
@endif		 

 








            </div>
            </div>
        </div>
    </section>
  </div>
  
       
    
    </main>   
            
        
    


    








</body>
</html>