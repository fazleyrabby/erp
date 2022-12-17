<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">

                {{-- Start New Sidebar --}}
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('dashboard') }}" aria-expanded="false"><i class="fa fa-home"></i><span
                            class="hide-menu"> Dashboard</span></a></li>
                    
                    @if (Auth::guard('web')->user()->can('Inventory'))
                {{-- Start Inventory Module --}}
               <!--  <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                        href="javascript:void(0)" aria-expanded="false"><i class="fa fa-th-list"></i> Inventory Module
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level"> -->
                        <li class="sidebar-item"><a href="javascript:void(0)"
                                class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false"><i
                                    class="fa fa-cart-plus"></i><span class="hide-menu"> Inventory
                                </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                            @if (Auth::guard('web')->user()->can('Products'))
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="{{ route('products.view') }}" aria-expanded="false"><i
                                            class="fas fa-check-circle"></i><span class="hide-menu">Products</span></a>
                                </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('Damage'))
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="{{ route('damage.view') }}" aria-expanded="false"><i
                                            class="fas fa-check-circle"></i><span class="hide-menu">Damage
                                            Products</span></a></li>
                            @endif 
                            @if (Auth::guard('web')->user()->can('Warehouse'))
                                <li class="sidebar-item d-none"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="{{ url('warehouse/transfer/') }}" aria-expanded="false"><i
                                            class="fas fa-check-circle"></i><span class="hide-menu">Warehouse
                                            Transfer</span></a></li>
                            @endif
                            </ul>
                        </li>
                        @if (Auth::guard('web')->user()->can('Purchase'))
                        {{-- Purchase --}}
                        <li class="sidebar-item"><a href="javascript:void(0)"
                                class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                                <i class="fa fa-shopping-cart"></i><span class="hide-menu"> Purchase Management
                                </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @if (Auth::guard('web')->user()->can('purchase.view'))
                                <li class="sidebar-item"><a href="{{ route('purchase.index') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span class="hide-menu">
                                            Purchase
                                        </span></a>
                                </li>
                                @endif 
                                @if (Auth::guard('web')->user()->can('Purchase.return'))
                                <li class="sidebar-item">
                                    <a href="{{ route('purchase.return.list') }}"
                                        class="sidebar-link">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="hide-menu">Purchase Return </span>
                                    </a>
                                </li>
                                @endif 
                            </ul>
                        </li>
                        @endif
                        {{-- Sale --}}
                        @if (Auth::guard('web')->user()->can('Sale'))
                        <li class="sidebar-item"><a href="javascript:void(0)"
                                class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false"><i
                                    class="fa fa-shopping-bag"></i><span class="hide-menu"> Sale Management
                                </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @if (Auth::guard('web')->user()->can('sale.service.view')) 
                                <li class="sidebar-item"><a href="{{ route('sale.service.SaleOrders') }} "
                                        class="sidebar-link"><i class="fas fa-check-circle"></i></i><span
                                            class="hide-menu"> Service Orders </span></a>
                                </li>
                                @endif
                                @if (Auth::guard('web')->user()->can('walking.sale.view')) 
                                <li class="sidebar-item"><a
                                        href="{{ route('sale.sales', ['type' => 'walkin_sale']) }} "
                                        class="sidebar-link"><i class="fas fa-check-circle"></i></i><span
                                            class="hide-menu"> WI Sale </span></a></li>
                                <li class="sidebar-item"><a
                                        href="{{ route('sale.return.list', ['type' => 'walkin_sale']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">Sale Return </span></a></li>
                                <li class="sidebar-item"><a
                                        href="{{ route('sale.sales', ['type' => 'service']) }} "
                                        class="sidebar-link"><i class="fas fa-check-circle"></i></i><span
                                            class="hide-menu"> Order Sale View </span></a></li>
                                @endif
                                @if (Auth::guard('web')->user()->can('party.sale.view')) 
                                <li class="sidebar-item d-none"><a
                                        href="{{ route('sale.sales', ['type' => 'party_sale']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu"> Party Sale </span></a></li>
                                <li class="sidebar-item d-none"><a
                                        href="{{ route('sale.return.list', ['type' => 'party_sale']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">Party Sale Return </span></a></li>
                                @endif
                                
                                @if (Auth::guard('web')->user()->can('TS.sale.view')) 
                                <li class="sidebar-item d-none"><a href="{{ route('sale.sales', ['type' => 'ts']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu"> TS </span></a></li>
                                <li class="sidebar-item d-none"><a
                                        href="{{ route('sale.return.list', ['type' => 'ts']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">TS Return </span></a></li>
                                @endif
                                @if (Auth::guard('web')->user()->can('final.sale.view')) 
                                <li class="sidebar-item d-none"><a href="{{ route('sale.sales', ['type' => 'FS']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu"> Final Sale </span></a></li>
                                <li class="sidebar-item d-none"><a
                                        href="{{ route('sale.return.list', ['type' => 'FS']) }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">FS Return </span></a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    <!-- </ul>
                </li> -->
                {{-- End Inventory Module --}}
                @endif
                
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-credit-card"></i> Voucher</a>
                    <ul aria-expanded="false" class="collapse  first-level">
                    
                        <li class="sidebar-item"><a href="{{url('voucher/payment')}}" class="sidebar-link"><i class="fas fa-bars"></i><span class="hide-menu"> Payment Voucher</span></a></li>
                        <li class="sidebar-item"><a href="{{url('voucher/payment Received')}}" class="sidebar-link"><i class="fas fa-bars"></i><span class="hide-menu"> Received Voucher </span></a></li>
                        <li class="sidebar-item"><a href="{{url('voucher/Discount')}}" class="sidebar-link"><i class="fas fa-bars"></i><span class="hide-menu"> Discount Voucher</span></a></li> 
                    
                       
                    </ul>
                </li>
                    
                            
                @if (Auth::guard('web')->user()->can('user.view'))
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                        href="javascript:void(0)" aria-expanded="false"><i class="fa fa-user-plus"></i> User
                        management
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="javascript:void(0)"
                                class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false"><i
                                    class="fa fa-tasks"></i><span class="hide-menu"> Roles & Permissions
                                </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @if (Auth::guard('web')->user()->can('role.view'))
                                    <li class="sidebar-item"><a href="{{ route('rolesView') }}"
                                            class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                                class="hide-menu"> Roles</span></a>
                                    </li>
                                @endif
                                @if (Auth::guard('web')->user()->can('permission.view'))
                                    <li class="sidebar-item"><a href="{{ route('permissionView') }}"
                                            class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                                class="hide-menu"> Permissions</span></a></li>
                                @endif
                                @if (Auth::guard('web')->user()->can('permissionToRole.view'))
                                    <li class="sidebar-item"><a href="{{ route('permissionToRoleList') }}"
                                            class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                                class="hide-menu"> Give Permission to Role</span></a></li>
                                @endif
                            </ul>
                        </li>
                        @if (Auth::guard('web')->user()->can('user.view'))
                            <li class="sidebar-item"><a href="{{ route('users.') }}" class="sidebar-link"><i
                                        class="fas fa-tasks"></i><span class="hide-menu"> View Users </span></a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('user.changePassword'))
                            <li class="sidebar-item"><a onclick="ChangePasswordModal()" href="#"
                                    class="sidebar-link"><i class="fas fa-tasks"></i><span class="hide-menu">
                                        Change Password </span></a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif


                @if (Auth::guard('web')->user()->can('CRM'))
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                        href="javascript:void(0)" aria-expanded="false"> <i class="fas fa-users"
                            aria-hidden="true"></i> CRM</a>
                    <ul aria-expanded="false" class="collapse  first-level">

                         @if (Auth::guard('web')->user()->can('Supplier'))
                                <li class="sidebar-item"><a href="{{url('parties/view/Supplier')}}" class="sidebar-link"><i class="fas fa-check-circle"></i><span class="hide-menu"> Supplier </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('Customer'))
                                <li class="sidebar-item"><a href="{{url('parties/view/Customer')}}" class="sidebar-link"><i class="fas fa-check-circle"></i><span class="hide-menu"> Customer </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('Walkin Customer'))
                                <li class="sidebar-item"><a href="{{url('parties/view/Walkin_Customer')}}" class="sidebar-link"><i class="fas fa-check-circle"></i><span class="hide-menu"> Walkin Customer </span></a></li>
                            @endif
                        
                    </ul>
                </li>
                @endif

                
                
                

                <!-- Acccounts module start -->
                @if (Auth::guard('web')->user()->can('Accounts'))
                <li class="sidebar-item"> 
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                <i class="fas fa-calculator"></i>
                                Accounts
                            </a>
                        <ul aria-expanded="false" class="collapse  first-level">
                            @if (Auth::guard('web')->user()->can('coa.view'))
                            <li class="sidebar-item">
                                <a href="{{route('chartOfAccounts') }}" class="sidebar-link">
                                    <i class="fas fa-chart-pie"></i>
                                    <span class="hide-menu"> Chart of accounts </span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('journal.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('journalView') }}" class="sidebar-link">
                                    <i class="fab fa-gg"></i>
                                    <span class="hide-menu"> Journal </span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('expense.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('expenseView') }}" class="sidebar-link">
                                    <i class=" far fa-money-bill-alt"></i>
                                    <span class="hide-menu"> Expense </span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('bill.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('billView') }}" class="sidebar-link">
                                    <i class="far fa-file-alt"></i>
                                    <span class="hide-menu"> Bill </span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('bank.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('bankView') }}" class="sidebar-link">
                                    <i class="fas fa-university"></i>
                                    <span class="hide-menu">Banks </span>
                                </a>
                            </li>
                            @endif
                           
                        </ul>
                    </li>
                @endif



                <!-- reports -->
                @if (Auth::guard('web')->user()->can('Reports'))
                <li class="sidebar-item"> 
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="fas fa-paste"></i>
                            Reports
                        </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        @if (Auth::guard('web')->user()->can('party.ledger'))
                        <li class="sidebar-item">
                            <a href="{{ route('partyLedger') }}" class="sidebar-link">
                                <i class="fas fa-tasks"></i>
                                <span class="hide-menu"> Party Ledger </span>
                            </a>
                        </li>
                        @endif
                        @if (Auth::guard('web')->user()->can('monthlyAccounts.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('accountsLedgerDatewise') }}" class="sidebar-link">
                                <i class="fas fa-tasks"></i>
                                <span class="hide-menu"> Income & Expenditure</span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item">
                            <a href="{{ route('dailyAccountsLedger') }}" class="sidebar-link">
                                <i class="fas fa-tasks"></i>
                                <span class="hide-menu">Daily  Ledger </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('dailyServiceLedgerReport') }}" class="sidebar-link">
                                <i class="fas fa-tasks"></i>
                                <span class="hide-menu">Daily Service Report</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="{{ url('report/product-ledger') }}" class="sidebar-link">
                                <i class="fas fa-tasks"></i>
                                <span class="hide-menu">Product Ledger</span>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif


                     <!-- Acccounts module end -->
                   

                    <!-- payroll module starts -->
                @if (Auth::guard('web')->user()->can('Payroll'))
                <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                        <i class="nav-icon fas fa-cubes"></i>
                        <span class="hide-menu">Payroll</span>
                    </a>
                    <ul class="collapse  first-level" id="nav_tree">

                        <li class="sidebar-item" style="margin-left:10px;">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                            <i class="fas fa-user"></i>
                                <span class="hide-menu">Employee Informations</span>
                            </a>
                            <ul class="collapse  first-level" id="nav_tree">
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('ourTeam') }}" class="sidebar-link">
                                    <i class="fas fa-id-card"></i>
                                        <span class="hide-menu"> Employee's </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('gradeIndex') }}" class="sidebar-link">
                                    <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Grades </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('stepsIndex') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Steps </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('groupIndex') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Groups </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('facilityIndex') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Facilites </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="sidebar-item" style="margin-left:10px;">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                            <i class="fa fa-table"></i>
                                <span class="hide-menu">Salary Sheet</span>
                            </a>
                            <ul class="collapse  first-level" id="nav_tree">
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('SalarySheetView') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Salary Sheet </span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{route('finalSheetIndex')}}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Final Salary Sheet </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('SalaryInstructionView') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Salary Instruction </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('bonusListView') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span class="hide-menu"> Bonus List </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:10px;">
                                    <a href="{{route('monthlyAmountIndex')}}" class="sidebar-link">
                                        <i class="fas fa-exchange-alt"></i>
                                        <span class="hide-menu"> Adjust/Deduct </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:10px;">
                                    <a href="{{route('loanIndex')}}" class="sidebar-link">
                                        <i class='fas fa-hand-holding-usd'></i>
                                        <span class="hide-menu"> Loan Salary </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        

                        <li class="sidebar-item" style="margin-left:10px;">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                            <i class="fas fa-user"></i>
                                <span class="hide-menu">Attendence Management</span>
                            </a>
                            <ul class="collapse  first-level" id="nav_tree">
                                
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{route('attendenceIndex')}}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span> Attendence </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{route('monthlyAttendence')}}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span> Employee Attendence </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{route('groupAttendence')}}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span>Group Attendence </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('timeScheduleGroupIndex') }}" class="sidebar-link" >
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span>  Time Schedule Group </span>
                                    </a>
                                </li>
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('userTimeGroupIndex') }}" class="sidebar-link" >
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span>  User Time Group </span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item" style="margin-left:15px;">
                                    <a href="{{  route('leaveIndex') }}" class="sidebar-link">
                                        <i class="fas fa-th-list nav-icon"></i>
                                        <span>  Leave Management </span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif
                <!-- payroll module ends -->
                     


                <!-- setting -->
                @if (Auth::guard('web')->user()->can('Setting'))
                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                            href="javascript:void(0)" aria-expanded="false"> <i class="fa fa-cogs"
                                aria-hidden="true"></i>  Setting</a>
                        <ul aria-expanded="false" class="collapse  first-level">
                       
                            @if (Auth::guard('web')->user()->can('companySetting.view'))
                                    <li class="sidebar-item"><a href="{{ route('company.settings.view') }}"
                                            class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                                class="hide-menu"> Shop Settings </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('Categories'))
                                <li class="sidebar-item"><a href="{{ route('categories.view') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">
                                            Category </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('Brands'))
                                <li class="sidebar-item"><a href="{{ route('brands.view') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu"> Brand
                                        </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('units.view'))
                                <li class="sidebar-item"><a href="{{ route('units.view') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu"> Unit
                                        </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('warehouse.view'))
                                <li class="sidebar-item"><a href="{{ route('warehouse.view') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">
                                            Warehouse </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('transport.view'))
                                <li class="sidebar-item d-none"><a href="{{ route('transport.view') }}"
                                        class="sidebar-link"><i class="fas fa-check-circle"></i><span
                                            class="hide-menu">
                                            Transport </span></a></li>
                            @endif
                            @if (Auth::guard('web')->user()->can('accounts.setting'))
                                <li class="sidebar-item">
                                    <a href="{{ route('accountSettingView') }}" class="sidebar-link">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="hide-menu">
                                            Account Settings 
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('web')->user()->can('payroll.settings'))
                                <li class="sidebar-item">
                                    <a href="{{Route('settingIndex')}}" class="sidebar-link">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="hide-menu">Payroll Setting</span>
                                    </a>
                                </li>
                            @endif
                            </ul>
                           
                       
                    </li>
                @endif 



                {{-- End New Sidebar --}}

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- modal -->
<div class="modal fade" id="modalUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header float-left">

                <h4 class="modal-title float-left"> Change User Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fas fa-window-close"></i></button>
            </div>
            <div class="modal-body">
                <form id="userPasswordForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf

                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>User Name <span class="text-danger"> * </span></label><br>
                            <select id="selectUser" name="selectUser" class="form-control input-sm">
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"> {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label> Password : <span class="text-danger"> * </span></label>
                            <input class="form-control input-sm" id="userPassword" type="password"
                                name="userPassword">
                            <span class="text-danger" id="userPasswordError"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                            Close</button>
                        <button type="submit" class="btn btn-primary btnSave" id="saveUserPassword">Save</button>
                </form>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
    function ChangePasswordModal(id) {
        $("#modalUser").modal('show');
        $("#userPassword").val("");

        $(function() {
            $("#selectUser").select2({
                width: '100%'
            });

        });
    }

    $("#userPasswordForm").submit(function(e) {
        e.preventDefault();
        let userId = $("#selectUser").val();
        let userPassword = $("#userPassword").val();
        if (userPassword.length < 6) {
            $('#userPasswordError').text('password length must be greater than 6!');
            return 0;
        }

        let _token = $('input[name="_token"]').val();
        let fd = new FormData();
        fd.append('password', userPassword);
        fd.append('userId', userId);
        fd.append('_token', _token);
        $.ajax({
            url: "{{ route('changePassword') }}",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            success: function(result) {
                $("#modalUser").modal('hide');
                Swal.fire("Updated!", result.success, "success");
            },
            error: function(response) {
                //alert(JSON.stringify(response));
            },
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
            }
        })
    });
</script>
