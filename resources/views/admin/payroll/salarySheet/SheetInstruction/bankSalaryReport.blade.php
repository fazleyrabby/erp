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
                margin-top: 6cm;
                margin-left: 1cm;
                margin-right: 0.5cm;
                margin-bottom: 4cm;
               
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;
                text-align:center;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 2.5cm; 
                left: 0cm; 
                right: 0cm;
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
                .signatures{
                    padding-bottom:-500px;
                }
                
    </style>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <!-- Content Header (Page header) -->

            <h4>{{Session::get('companySettings')[0]['name']}}</h4>
        <h3> {!!Session::get('companySettings')[0]["name"]!!}</h3>
        <p> {!!Session::get('companySettings')[0]["report_header"]!!} <br>
            {!!Session::get('companySettings')[0]["website"]!!}</p> 
        <hr/>
    </header>


    <footer>
   
   
           <div class="signatures">
           
                <div class="row ">
                    <div class="column">
                        ----------------------<br>                 
                        Signature of Manager 
                    </div>
                    <div class="column"> 
                    -----------------------<br>                         
                        Signature of CEO
                    </div>
                    <div class="column">
                    -----------------------<br>                       
                        Signature of Banker
                    </div>                 
                </div><br>
                <hr />
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
  
        
        </div>
        
    </footer>
<main>
        
        <h4>Bank Sheet</h4>
    <p>{!!$letterInstructions->letter_body!!}</p>
    <p>Mother Account: {{$letterInstructions->mother_account_no}}</p>

    <table border=1 cellspacing=0 style="width:100%;">
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee</th>   
                <th>Account No</th>
                <th>Salary</th>
                <th>Bank</th>
                
            </tr>
        </thead>
        

        @php 
        $sl=0;
        @endphp
        <tbody>
            @foreach($salaryinstruction as $salary)
                <tr>
                    <td style="text-align:center;">{{++$sl}}</td>
                    <td style="text-align:center;">{{$salary->member_name}}</td>
                    <td style="text-align:center;">{{$salary->account_no}}</td>
                    <td style="text-align:right;">{{$salary->net_total}} {!!Session::get('companySettings')[0]['currency']!!}</td>
                    <td style="text-align:center;">{{$salary->bank_name}}<br>({{$salary->branch_name}})</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align:center;">Net Payable: </td>
                <td style="text-align:right;">{{$netamounts}} {!!Session::get('companySettings')[0]['currency']!!}</td>
                <td></td>
                
            </tr>
        </tbody>    
    </table>
    <p>Net Payable in Word: {{numberToWord($netamounts)}} {!!Session::get('companySettings')[0]['currency']!!}</p>
    










            </div>
            </div>
        </div>
    </section>
  </div>
  
       
    
    </main>   
            
        
    


    








</body>
</html>