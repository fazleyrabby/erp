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
                margin-top: 3cm;
                margin-left: -0.4cm;
                margin-right: 0cm;
                margin-bottom: 3cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0.2cm;
                left: 0cm;
                right: 0cm;
                
                height: 2cm;
                text-align:center;
                
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 2cm; 
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
                .footer-text{
                    padding:30px;
                    
                }
                
    </style>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <!-- Content Header (Page header) -->

            <h4>{{Session::get('companySettings')[0]['name']}}</h4>
        <p> {!!Session::get('companySettings')[0]["report_header"]!!}</p>  
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
                </div>
               <hr /> 
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
  
        
        </div>
        
    </footer>
<main>
        
        <h4 style="text-align:center;">Salary Sheet</h4>
    
   
        
    <table border=1 cellspacing=0 style="width:100%;">
        <thead>
            <tr>
                <th>SL</th>
                <th>Month Year</th>
                <th>Sheet</th>
                <th>Employee</th>
                <th>Account No</th>
                <th>Consulate</th>
                <th>Basic</th>
                <th>H.R</th>
                <th>Med</th>
                <th>C.C</th>
                <th>Laundry</th>
                <th>Phone</th>
                <th>Ta/Da</th>
                <th>PF</th>
                <th>C.PF</th>
                <th>Bonus</th>
                <th>Adj</th>
                <th>Step</th>
                <th>Total</th>
                <th>Due</th>
                <th>D.PF</th>
                <th>Loan<br>Tenure</th>
                <th>Net <br>Total {!!Session::get('companySettings')[0]['currency']!!}</th>
              
                
            </tr>
        </thead>
        

        @php 
        $sl=0;
        @endphp
        <tbody>
            @foreach($sheets as $sheet)
                <tr>
                    <td>{{++$sl}}</td>
                    <td>{{$sheet->month_year }}</td>
                    <td>{{$sheet->sheet_name}}</td>
                    <td>{{$sheet->member_name}}</td>
                    <td>{{$sheet->account_no}}</td>
                    <td>{{$sheet->consulate}}</td>
                    <td>{{$sheet->basic}}</td>
                    <td>{{$sheet->house_rent}}</td>
                    <td>{{$sheet->medical_allowence}}</td>
                    <td>{{$sheet->company_contribution}}</td>
                    <td>{{$sheet->laundry}}</td>
                    <td>{{$sheet->phone_bill}}</td>
                    <td>{{$sheet->ta_da}}</td>
                    <td>{{$sheet->provident_fund}}</td>
                    <td>{{$sheet->company_provident_fund}}</td>
                    <td>{{$sheet->monthly_bonus}}</td>
                    <td>{{$sheet->adjustment}}</td>
                    <td>{{$sheet->step_amount}}</td>
                    <td>{{$sheet->total}}</td>
                    <td>{{$sheet->due}}</td>
                    <td>{{$sheet->deduct_provident_fund}}</td>
                    <td>{{$sheet->loan_installment}}</td>
                    <td>{{$sheet->net_total}}</td>
                   
                </tr>
            @endforeach
           
        </tbody>    
    </table>
    

<div class="footer-text">
    <p>{!! $footertext->footer_instruction !!}</p>
</div>








            </div>
            </div>
        </div>
    </section>
  </div>
  
       
    
    </main>   
            
        
    


    








</body>
</html>