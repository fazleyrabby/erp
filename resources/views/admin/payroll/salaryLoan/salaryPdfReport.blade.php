<html>
<head>
    <style>
         #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(1, decimal); }
        @page {
                margin: 1cm 0.2cm 0.2cm 0.5cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 4cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
                margin-bottom: 3cm;
            }

            /** Define the header rules **/
            header {
                position:fixed;
                top: -0.5cm;
                left: 0cm;
                right: 0cm;
                
                height: 2.5cm;
                text-align:center;
                
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 2.5cm; 
                left: 0cm; 
                right: 1.0cm;
                height: .5cm;
                text-align:center;
                
            }
         
            .column {
                float: left;
                width: 33.33%;
                padding: 10px;
                height:30px; /* Should be removed. Only for demonstration */
                }

                /* Clear floats after the columns */
                .row:after {
                content: "";
                display: table;
                clear: both;
                }
              
                .footer-text{
                    padding:30px;
                    
                }
               .table1{
                width:50%;
                margin-left:auto;
                margin-right:auto;
               }
                
    </style>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <!-- Content Header (Page header) -->

            <div><img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width="110" height="55"></div>
        <h2>{!!Session::get('companySettings')[0]["name"]!!}</h2>
       <p> {!!Session::get('companySettings')[0]["report_header"]!!} <br>
       {!!Session::get('companySettings')[0]["website"]!!}</p> 
        <hr/>
        
    </header>
    <br>

    <footer>
   
   
           <div class="signatures">
           
                <div class="row ">
                    <div class="column">
                        ----------------------<br>                 
                        Manager Signature
                    </div>
                    <div class="column"> 
                    -----------------------<br>                         
                        CEO Signature
                    </div>
                    <div class="column">
                    -----------------------<br>                       
                        Bank Signature
                    </div>                 
                </div><br>
                <hr />
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
  
        
        </div>
        
    </footer>
<main>
        
        <h4 style="text-align:center;"><u>Loan Sheet</u></h4>
    
   
        <div class="loan-data">
        
            <table class="table1" border=1 cellspacing=0  >
                <tr>
                    <td >Name:</td>
                    <td style="text-align:center">{{$loan->member_name}}</td>
                </tr>
                <tr>
                    <td >Issue Date:</td>
                    <td style="text-align:center">{{$loan->applicable_from}}</td>
                </tr>
                <tr>
                    <td >Loan Amount:</td>
                    <td style="text-align:center">{{$loan->amount}}.00 {!!Session::get('companySettings')[0]['currency']!!} </td>
                </tr>
                <tr>
                    <td >Tenure:</td>
                    <td style="text-align:center">{{$loan->tenure}}</td>
                </tr>
                <tr>
                    <td >Interest:</td>
                    <td style="text-align:center">{{$loan->percent}}%</td>
                </tr>
                <tr>
                    <td >Monthly Installment:</td>
                    <td style="text-align:center">{{$loan->installment}} {!!Session::get('companySettings')[0]['currency']!!}</td>
                </tr>
                <tr>
                    <td >Total Payable:</td>
                    <td style="text-align:center">{{$nettotal}}.00 {!!Session::get('companySettings')[0]['currency']!!}</td>
                </tr>
                <tr>
                    <td >Loan Reason:</td>
                    <td style="text-align:center">{{$loan->cause}}</td>
                </tr>
            </table>
            
        </div>
        <br><br>
    <table border=1 cellspacing=0 style="width:100%;">
        <thead>
        <tr>
            <th>SL</th> 
            <th>Name</th>                                                                          
            <th>Month Year</th>                                      
            <th>Installment(Taka)</th>
            <th>Status</th>
        </tr>
        </thead>
        

        @php 
        $sl=0;
        @endphp
        <tbody>
            @foreach($loans as $loandata)
          <tr>
              <td style="text-align:center">{{++$sl}}</td>
              <td style="text-align:center">{{$loandata->member_name}}</td>
              <td style="text-align:center">{{$loandata->month_year}}</td>
              <td style="text-align:center">{{$loandata->installment}}.00 {!!Session::get('companySettings')[0]['currency']!!}</td>
              <td style="text-align:center">{{$loandata->loan_status}}</td>
          </tr>
          @endforeach
          <tr>
              <td></td>
              <td></td>
              <td style="text-align:center">Net Total Payable:</td>
              <td style="text-align:center">{{$nettotal}}.00 {!!Session::get('companySettings')[0]['currency']!!}</td>
              <td></td>
          </tr>
        </tbody>    
    </table>
    <p>Net total in word: {{numberToWord($nettotal)}} {!!Session::get('companySettings')[0]['currency']!!}</p>
    










           <!--  </div>
            </div>
        </div>
    </section>
  </div> -->
  
       
    
    </main>   
            
        
    


    








</body>
</html>