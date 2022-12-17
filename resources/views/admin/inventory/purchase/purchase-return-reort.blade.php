<!DOCTYPE html>
<html>
<head>
    <title>{{Session::get('companySettings')[0]["name"]}} | Purchase invoice Pdf Report</title>
    <style>
        .citiestd13 {background-color: red;color: black;text-align: center;font-size: 13px;padding: 5px;}
        .citiestd14 {text-align: center;font-size: 11px;}
        .citiestd15 {text-align: left;font-size: 11px;}
        .supAddress {font-size: 12px;}
		.supAddressFont {font-size:11px;}
		table {
		width:100%;    
        border-collapse: collapse;
        margin: 0px 0;
        font-size: 0.8em;
        min-width: 400px;
        text-align:left;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        thead tr {
            background-color: #ffff;
            color: black;
            text-align: left;
        }
        th, td {
            padding: 12px 15px;
        }
        .footer-text{
            text-align:center;
           padding:25px;
        }
        
    </style>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="text-align: center;">
                <!-- <div><img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width="140" height="55"></div>
                <div><strong style="font-size: 1rem;"> {{Session::get('companySettings')[0]["name"]}} </strong></div> -->
                {!!Session::get('companySettings')[0]["report_header"]!!}
                <div class="citiestd14">Phone: {{Session::get('companySettings')[0]["phone"]}} Email:  {{Session::get('companySettings')[0]["email"]}}</div><br>
            <div class="citiestd13">Purchase Return Product Invoice </div>
            
            <table border="" cellspacing="0" cellpadding="3">
    			<tr>
    				<td width="60%" class="supAddress">
    				     @foreach($invoice as $user)
                                <div><strong>Name : </strong>{{$user->name. ' - '. $user->purchase_return_no}}</div>
                                <div><strong>Phone: </strong>{{$user->contact}}</div>
                                <div><strong>Address: </strong>{{$user->address}}</div>
                            @break
                        @endforeach
    				</td>
    				<td width="40%" class="supAddress">
    				    @foreach($invoice as $info)
                            <div><strong>Invoice: #{{$info->memo_no}}</strong></div>
                            <div><strong>Order Date: </strong> {{$info->purchase_return_date}}</div>
                        @break
                        @endforeach
    				</td>
    			</tr>
			
		    </table>   
            <table border="1" cellspacing="0" cellpadding="3">
                <thead><tr><th>SL</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
                    <tbody>
                        @php 
                            $i = 1;
                        @endphp
                        @foreach($invoice as $products)
                        <tr>
                            <td>{{$i++}}</td>
                            <td class="citiestd15">{{$products->name.' - '.$products->purchase_return_no}}</td>
                            <td>{{$products->return_qty}}</td>
                            <td>{!!Session::get('companySettings')[0]["currency"].' '.$products->unit_price!!}</td>
                            <td> {!! Session::get('companySettings')[0]["currency"].' '.$products->total_price!!}</td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
              
            <table border="" cellspacing="0" cellpadding="3"><br><br>
                @foreach ($invoice as $expense)
                    @php
                    $totalCost = floatval($expense->grand_total);
                    @endphp
                <tr><td width="55%"></td><td width="25%">Total:</td><td width="15%"> {!!Session::get('companySettings')[0]["currency"].' '.$totalCost!!}</td></tr>
                @break
                @endforeach
            </table>
                 
            </div>
            </div>
        </div>
    </section>

  </div>

  <div class="container footer-text ">
  <div class="row">
    <div class="col-md-12 text-center">
        {!!Session::get('companySettings')[0]['report_footer']!!}
    </div>
  </div>
</div>

</body>
</html>