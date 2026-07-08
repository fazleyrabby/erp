<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{route('dashboard')}}">
                <span style="font-size:1rem; font-weight:600;">{{Session::get('companySettings')[0]['name']}}</span>
            </a>
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{!empty(Auth::user()->image) ? url('upload/user_images/'.Auth::user()->image) : url('upload/no-image.jpg')}})"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fa fa-home"></i></span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>

                @if (Auth::guard('web')->user()->can('Inventory'))
                @php
                    $invRoutes = ['products.*', 'damage.*', 'warehouse.*', 'purchase.*', 'sale.*', 'sale.service.*'];
                    $invActive = request()->routeIs($invRoutes);
                @endphp
                <li class="nav-item dropdown {{ $invActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $invActive ? 'active' : '' }}" href="#navbar-inventory" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $invActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fa fa-cart-plus"></i></span>
                        <span class="nav-link-title">Inventory</span>
                    </a>
                    <div class="dropdown-menu {{ $invActive ? 'show' : '' }}">
                        @if (Auth::guard('web')->user()->can('Products'))
                        <a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Products
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Damage'))
                        <a class="dropdown-item {{ request()->routeIs('damage.*') ? 'active' : '' }}" href="{{ route('damage.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Damage Products
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Warehouse'))
                        <a class="dropdown-item d-none" href="{{ url('warehouse/transfer/') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Warehouse Transfer
                        </a>
                        @endif

                        @if (Auth::guard('web')->user()->can('Purchase'))
                        @php $purchActive = request()->routeIs('purchase.*'); @endphp
                        <div class="dropend {{ $purchActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $purchActive ? 'active' : '' }}" href="#navbar-purchase" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $purchActive ? 'true' : 'false' }}">
                                <i class="fa fa-shopping-cart icon-inline me-1"></i> Purchase Management
                            </a>
                            <div class="dropdown-menu {{ $purchActive ? 'show' : '' }}">
                                @if (Auth::guard('web')->user()->can('purchase.view'))
                                <a class="dropdown-item {{ request()->routeIs('purchase.index') ? 'active' : '' }}" href="{{ route('purchase.index') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Purchase
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('Purchase.return'))
                                <a class="dropdown-item {{ request()->routeIs('purchase.return.list') ? 'active' : '' }}" href="{{ route('purchase.return.list') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Purchase Return
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if (Auth::guard('web')->user()->can('Sale'))
                        @php $saleActive = request()->routeIs(['sale.*', 'sale.service.*']); @endphp
                        <div class="dropend {{ $saleActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $saleActive ? 'active' : '' }}" href="#navbar-sale" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $saleActive ? 'true' : 'false' }}">
                                <i class="fa fa-shopping-bag icon-inline me-1"></i> Sale Management
                            </a>
                            <div class="dropdown-menu {{ $saleActive ? 'show' : '' }}">
                                @if (Auth::guard('web')->user()->can('sale.service.view'))
                                <a class="dropdown-item {{ request()->routeIs('sale.service.*') ? 'active' : '' }}" href="{{ route('sale.service.SaleOrders') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Service Orders
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('walking.sale.view'))
                                <a class="dropdown-item {{ request()->routeIs('sale.sales') && request('type') === 'walkin_sale' ? 'active' : '' }}" href="{{ route('sale.sales', ['type' => 'walkin_sale']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> WI Sale
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('sale.return.list') && request('type') === 'walkin_sale' ? 'active' : '' }}" href="{{ route('sale.return.list', ['type' => 'walkin_sale']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Sale Return
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('sale.sales') && request('type') === 'service' ? 'active' : '' }}" href="{{ route('sale.sales', ['type' => 'service']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Order Sale View
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('party.sale.view'))
                                <a class="dropdown-item d-none" href="{{ route('sale.sales', ['type' => 'party_sale']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Party Sale
                                </a>
                                <a class="dropdown-item d-none" href="{{ route('sale.return.list', ['type' => 'party_sale']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Party Sale Return
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('TS.sale.view'))
                                <a class="dropdown-item d-none" href="{{ route('sale.sales', ['type' => 'ts']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> TS
                                </a>
                                <a class="dropdown-item d-none" href="{{ route('sale.return.list', ['type' => 'ts']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> TS Return
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('final.sale.view'))
                                <a class="dropdown-item d-none" href="{{ route('sale.sales', ['type' => 'FS']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Final Sale
                                </a>
                                <a class="dropdown-item d-none" href="{{ route('sale.return.list', ['type' => 'FS']) }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> FS Return
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </li>
                @endif

                @php $voucherActive = request()->is('voucher/*'); @endphp
                <li class="nav-item dropdown {{ $voucherActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $voucherActive ? 'active' : '' }}" href="#navbar-voucher" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $voucherActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fa fa-credit-card"></i></span>
                        <span class="nav-link-title">Voucher</span>
                    </a>
                    <div class="dropdown-menu {{ $voucherActive ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->is('voucher/payment') ? 'active' : '' }}" href="{{url('voucher/payment')}}">
                            <i class="fa fa-bars icon-inline me-1"></i> Payment Voucher
                        </a>
                        <a class="dropdown-item {{ request()->is('voucher/payment*') ? 'active' : '' }}" href="{{url('voucher/payment Received')}}">
                            <i class="fa fa-bars icon-inline me-1"></i> Received Voucher
                        </a>
                        <a class="dropdown-item {{ request()->is('voucher/Discount') ? 'active' : '' }}" href="{{url('voucher/Discount')}}">
                            <i class="fa fa-bars icon-inline me-1"></i> Discount Voucher
                        </a>
                    </div>
                </li>

                @if (Auth::guard('web')->user()->can('user.view'))
                @php
                    $userRoutes = ['rolesView', 'permissionView', 'permissionToRoleList', 'users.*', 'changePassword'];
                    $userActive = request()->routeIs($userRoutes);
                @endphp
                <li class="nav-item dropdown {{ $userActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $userActive ? 'active' : '' }}" href="#navbar-users" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $userActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fa fa-user-plus"></i></span>
                        <span class="nav-link-title">User Management</span>
                    </a>
                    <div class="dropdown-menu {{ $userActive ? 'show' : '' }}">
                        @php
                            $rolesRoutes = ['rolesView', 'permissionView', 'permissionToRoleList'];
                            $rolesActive = request()->routeIs($rolesRoutes);
                        @endphp
                        <div class="dropend {{ $rolesActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $rolesActive ? 'active' : '' }}" href="#navbar-roles" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $rolesActive ? 'true' : 'false' }}">
                                <i class="fa fa-tasks icon-inline me-1"></i> Roles & Permissions
                            </a>
                            <div class="dropdown-menu {{ $rolesActive ? 'show' : '' }}">
                                @if (Auth::guard('web')->user()->can('role.view'))
                                <a class="dropdown-item {{ request()->routeIs('rolesView') ? 'active' : '' }}" href="{{ route('rolesView') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Roles
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('permission.view'))
                                <a class="dropdown-item {{ request()->routeIs('permissionView') ? 'active' : '' }}" href="{{ route('permissionView') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Permissions
                                </a>
                                @endif
                                @if (Auth::guard('web')->user()->can('permissionToRole.view'))
                                <a class="dropdown-item {{ request()->routeIs('permissionToRoleList') ? 'active' : '' }}" href="{{ route('permissionToRoleList') }}">
                                    <i class="fa fa-check-circle icon-inline me-1"></i> Give Permission to Role
                                </a>
                                @endif
                            </div>
                        </div>
                        @if (Auth::guard('web')->user()->can('user.view'))
                        <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> View Users
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('user.changePassword'))
                        <a class="dropdown-item" onclick="ChangePasswordModal()" href="#">
                            <i class="fa fa-tasks icon-inline me-1"></i> Change Password
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                @if (Auth::guard('web')->user()->can('CRM'))
                @php $crmActive = request()->is('parties/view/*'); @endphp
                <li class="nav-item dropdown {{ $crmActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $crmActive ? 'active' : '' }}" href="#navbar-crm" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $crmActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fas fa-users"></i></span>
                        <span class="nav-link-title">CRM</span>
                    </a>
                    <div class="dropdown-menu {{ $crmActive ? 'show' : '' }}">
                        @if (Auth::guard('web')->user()->can('Supplier'))
                        <a class="dropdown-item {{ request()->is('parties/view/Supplier') ? 'active' : '' }}" href="{{url('parties/view/Supplier')}}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Supplier
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Customer'))
                        <a class="dropdown-item {{ request()->is('parties/view/Customer') ? 'active' : '' }}" href="{{url('parties/view/Customer')}}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Customer
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Walkin Customer'))
                        <a class="dropdown-item {{ request()->is('parties/view/Walkin_Customer') ? 'active' : '' }}" href="{{url('parties/view/Walkin_Customer')}}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Walkin Customer
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                @if (Auth::guard('web')->user()->can('Accounts'))
                @php
                    $acctRoutes = ['chartOfAccounts', 'journalView', 'expenseView', 'billView', 'bankView'];
                    $acctActive = request()->routeIs($acctRoutes);
                @endphp
                <li class="nav-item dropdown {{ $acctActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $acctActive ? 'active' : '' }}" href="#navbar-accounts" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $acctActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fas fa-calculator"></i></span>
                        <span class="nav-link-title">Accounts</span>
                    </a>
                    <div class="dropdown-menu {{ $acctActive ? 'show' : '' }}">
                        @if (Auth::guard('web')->user()->can('coa.view'))
                        <a class="dropdown-item {{ request()->routeIs('chartOfAccounts') ? 'active' : '' }}" href="{{route('chartOfAccounts')}}">
                            <i class="fa fa-chart-pie icon-inline me-1"></i> Chart of accounts
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('journal.view'))
                        <a class="dropdown-item {{ request()->routeIs('journalView') ? 'active' : '' }}" href="{{ route('journalView') }}">
                            <i class="fab fa-gg icon-inline me-1"></i> Journal
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('expense.view'))
                        <a class="dropdown-item {{ request()->routeIs('expenseView') ? 'active' : '' }}" href="{{ route('expenseView') }}">
                            <i class="far fa-money-bill-alt icon-inline me-1"></i> Expense
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('bill.view'))
                        <a class="dropdown-item {{ request()->routeIs('billView') ? 'active' : '' }}" href="{{ route('billView') }}">
                            <i class="far fa-file-alt icon-inline me-1"></i> Bill
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('bank.view'))
                        <a class="dropdown-item {{ request()->routeIs('bankView') ? 'active' : '' }}" href="{{ route('bankView') }}">
                            <i class="fas fa-university icon-inline me-1"></i> Banks
                        </a>
                        @endif
                    </div>
                </li>
                @endif

                @if (Auth::guard('web')->user()->can('Reports'))
                @php
                    $reportRoutes = ['partyLedger', 'accountsLedgerDatewise', 'dailyAccountsLedger', 'dailyServiceLedgerReport'];
                    $reportActive = request()->routeIs($reportRoutes) || request()->is('report/product-ledger');
                @endphp
                <li class="nav-item dropdown {{ $reportActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $reportActive ? 'active' : '' }}" href="#navbar-reports" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $reportActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fas fa-paste"></i></span>
                        <span class="nav-link-title">Reports</span>
                    </a>
                    <div class="dropdown-menu {{ $reportActive ? 'show' : '' }}">
                        @if (Auth::guard('web')->user()->can('party.ledger'))
                        <a class="dropdown-item {{ request()->routeIs('partyLedger') ? 'active' : '' }}" href="{{ route('partyLedger') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> Party Ledger
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('monthlyAccounts.view'))
                        <a class="dropdown-item {{ request()->routeIs('accountsLedgerDatewise') ? 'active' : '' }}" href="{{ route('accountsLedgerDatewise') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> Income & Expenditure
                        </a>
                        @endif
                        <a class="dropdown-item {{ request()->routeIs('dailyAccountsLedger') ? 'active' : '' }}" href="{{ route('dailyAccountsLedger') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> Daily Ledger
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('dailyServiceLedgerReport') ? 'active' : '' }}" href="{{ route('dailyServiceLedgerReport') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> Daily Service Report
                        </a>
                        <a class="dropdown-item {{ request()->is('report/product-ledger') ? 'active' : '' }}" href="{{ url('report/product-ledger') }}">
                            <i class="fa fa-tasks icon-inline me-1"></i> Product Ledger
                        </a>
                    </div>
                </li>
                @endif

                @if (Auth::guard('web')->user()->can('Payroll'))
                @php
                    $payrollRoutes = [
                        'ourTeam', 'gradeIndex', 'stepsIndex', 'groupIndex', 'facilityIndex',
                        'SalarySheetView', 'finalSheetIndex', 'SalaryInstructionView', 'bonusListView',
                        'monthlyAmountIndex', 'loanIndex',
                        'attendenceIndex', 'monthlyAttendence', 'groupAttendence',
                        'timeScheduleGroupIndex', 'userTimeGroupIndex', 'leaveIndex',
                    ];
                    $payrollActive = request()->routeIs($payrollRoutes);
                @endphp
                <li class="nav-item dropdown {{ $payrollActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $payrollActive ? 'active' : '' }}" href="#navbar-payroll" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $payrollActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fas fa-cubes"></i></span>
                        <span class="nav-link-title">Payroll</span>
                    </a>
                    <div class="dropdown-menu {{ $payrollActive ? 'show' : '' }}">
                        @php
                            $empRoutes = ['ourTeam', 'gradeIndex', 'stepsIndex', 'groupIndex', 'facilityIndex'];
                            $empActive = request()->routeIs($empRoutes);
                        @endphp
                        <div class="dropend {{ $empActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $empActive ? 'active' : '' }}" href="#navbar-employees" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $empActive ? 'true' : 'false' }}">
                                <i class="fas fa-user icon-inline me-1"></i> Employee Informations
                            </a>
                            <div class="dropdown-menu {{ $empActive ? 'show' : '' }}">
                                <a class="dropdown-item {{ request()->routeIs('ourTeam') ? 'active' : '' }}" href="{{ route('ourTeam') }}">
                                    <i class="fas fa-id-card icon-inline me-1"></i> Employee's
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('gradeIndex') ? 'active' : '' }}" href="{{ route('gradeIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Grades
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('stepsIndex') ? 'active' : '' }}" href="{{ route('stepsIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Steps
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('groupIndex') ? 'active' : '' }}" href="{{ route('groupIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Groups
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('facilityIndex') ? 'active' : '' }}" href="{{ route('facilityIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Facilites
                                </a>
                            </div>
                        </div>

                        @php
                            $salaryRoutes = ['SalarySheetView', 'finalSheetIndex', 'SalaryInstructionView', 'bonusListView', 'monthlyAmountIndex', 'loanIndex'];
                            $salaryActive = request()->routeIs($salaryRoutes);
                        @endphp
                        <div class="dropend {{ $salaryActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $salaryActive ? 'active' : '' }}" href="#navbar-salary" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $salaryActive ? 'true' : 'false' }}">
                                <i class="fa fa-table icon-inline me-1"></i> Salary Sheet
                            </a>
                            <div class="dropdown-menu {{ $salaryActive ? 'show' : '' }}">
                                <a class="dropdown-item {{ request()->routeIs('SalarySheetView') ? 'active' : '' }}" href="{{ route('SalarySheetView') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Salary Sheet
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('finalSheetIndex') ? 'active' : '' }}" href="{{route('finalSheetIndex')}}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Final Salary Sheet
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('SalaryInstructionView') ? 'active' : '' }}" href="{{ route('SalaryInstructionView') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Salary Instruction
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('bonusListView') ? 'active' : '' }}" href="{{ route('bonusListView') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Bonus List
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('monthlyAmountIndex') ? 'active' : '' }}" href="{{route('monthlyAmountIndex')}}">
                                    <i class="fas fa-exchange-alt icon-inline me-1"></i> Adjust/Deduct
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('loanIndex') ? 'active' : '' }}" href="{{route('loanIndex')}}">
                                    <i class='fas fa-hand-holding-usd icon-inline me-1'></i> Loan Salary
                                </a>
                            </div>
                        </div>

                        @php
                            $attRoutes = ['attendenceIndex', 'monthlyAttendence', 'groupAttendence', 'timeScheduleGroupIndex', 'userTimeGroupIndex', 'leaveIndex'];
                            $attActive = request()->routeIs($attRoutes);
                        @endphp
                        <div class="dropend {{ $attActive ? 'show' : '' }}">
                            <a class="dropdown-item dropdown-toggle {{ $attActive ? 'active' : '' }}" href="#navbar-attendance" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $attActive ? 'true' : 'false' }}">
                                <i class="fas fa-user icon-inline me-1"></i> Attendence Management
                            </a>
                            <div class="dropdown-menu {{ $attActive ? 'show' : '' }}">
                                <a class="dropdown-item {{ request()->routeIs('attendenceIndex') ? 'active' : '' }}" href="{{route('attendenceIndex')}}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Attendence
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('monthlyAttendence') ? 'active' : '' }}" href="{{route('monthlyAttendence')}}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Employee Attendence
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('groupAttendence') ? 'active' : '' }}" href="{{route('groupAttendence')}}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Group Attendence
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('timeScheduleGroupIndex') ? 'active' : '' }}" href="{{ route('timeScheduleGroupIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Time Schedule Group
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('userTimeGroupIndex') ? 'active' : '' }}" href="{{ route('userTimeGroupIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> User Time Group
                                </a>
                                <a class="dropdown-item {{ request()->routeIs('leaveIndex') ? 'active' : '' }}" href="{{ route('leaveIndex') }}">
                                    <i class="fas fa-th-list icon-inline me-1"></i> Leave Management
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endif

                @if (Auth::guard('web')->user()->can('Setting'))
                @php
                    $settingRoutes = ['company.settings.*', 'categories.*', 'brands.*', 'units.*', 'warehouse.*', 'transport.*', 'accountSettingView', 'settingIndex'];
                    $settingActive = request()->routeIs($settingRoutes);
                @endphp
                <li class="nav-item dropdown {{ $settingActive ? 'show' : '' }}">
                    <a class="nav-link dropdown-toggle {{ $settingActive ? 'active' : '' }}" href="#navbar-settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $settingActive ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="fa fa-cogs"></i></span>
                        <span class="nav-link-title">Setting</span>
                    </a>
                    <div class="dropdown-menu {{ $settingActive ? 'show' : '' }}">
                        @if (Auth::guard('web')->user()->can('companySetting.view'))
                        <a class="dropdown-item {{ request()->routeIs('company.settings.*') ? 'active' : '' }}" href="{{ route('company.settings.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Shop Settings
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Categories'))
                        <a class="dropdown-item {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Category
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('Brands'))
                        <a class="dropdown-item {{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Brand
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('units.view'))
                        <a class="dropdown-item {{ request()->routeIs('units.*') ? 'active' : '' }}" href="{{ route('units.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Unit
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('warehouse.view'))
                        <a class="dropdown-item {{ request()->routeIs('warehouse.*') ? 'active' : '' }}" href="{{ route('warehouse.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Warehouse
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('transport.view'))
                        <a class="dropdown-item d-none" href="{{ route('transport.view') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Transport
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('accounts.setting'))
                        <a class="dropdown-item {{ request()->routeIs('accountSettingView') ? 'active' : '' }}" href="{{ route('accountSettingView') }}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Account Settings
                        </a>
                        @endif
                        @if (Auth::guard('web')->user()->can('payroll.settings'))
                        <a class="dropdown-item {{ request()->routeIs('settingIndex') ? 'active' : '' }}" href="{{Route('settingIndex')}}">
                            <i class="fa fa-check-circle icon-inline me-1"></i> Payroll Setting
                        </a>
                        @endif
                    </div>
                </li>
                @endif

            </ul>
        </div>
    </div>
</aside>

<!-- Change Password Modal -->
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change User Password</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userPasswordForm" method="POST" enctype="multipart/form-data" action="#">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">User Name <span class="text-danger"> * </span></label>
                            <select id="selectUser" name="selectUser" class="form-select">
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger"> * </span></label>
                            <input class="form-control" id="userPassword" type="password" name="userPassword">
                            <span class="text-danger" id="userPasswordError"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveUserPassword">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function ChangePasswordModal() {
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
            error: function(response) {},
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
            }
        })
    });
</script>