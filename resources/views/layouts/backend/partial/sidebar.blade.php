<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ asset('assets/backend')}}/html/ltr/vertical-menu-boxicons-template/index.html">

                    @php
                        $settings= \App\Setting::where('config_name', 'company_name')->first()
                    @endphp

                    <p style="font-size: 14px; color: #fff;">{{ $settings->config_value}}</p>
                </a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">

            <li class="nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="File">File</span></a>
                <ul class="menu-content">
                    @if (Auth::user()->hasPermission('app.users.index'))                        
                        <li class="">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="User Management">User Management</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->hasPermission('app.mapping.index'))
                        <li class="{{ Request::is('mapping*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('mapping.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Maping">Maping</span>
                            </a>
                        </li> 
                    @endif
                    <li class="{{ Request::is('project-details*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('projectDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Maping">Project Details</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('bank-details*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('bankDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Bank details">Bank details</span>
                        </a>
                    </li>



                </ul>
            </li>

            <li class="navigation-header text-truncate"><span data-i18n="UI Elements">Accounting</span> </li>

            <li class="nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Chart of Accounts">Chart of Accounts</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('master-accounts-details*') || Request::is('master-details/*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('masteAccDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Master Account">Master Account</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('accounts-head-details') || Request::is('accounts-head-details/*') || Request::is('accounts-head-details/*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('accHeadDetails') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Account Head">Account Head</span>
                        </a>
                    </li>

                </ul>
            </li>


            <li class="nav-item {{ Request::is('cost-center-details') || Request::is('cost-center/edit/*') || Request::is('cost-center-details/*') ? 'active' : ''}}">
                <a href="{{ route('costCenterDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Cost Center">Cost Center</span>
                </a>
            </li>

            <li class="nav-item {{ Request::is('profit-details') || Request::is('student/*') || Request::is('profit-center/edit/*') ? 'active' : ''}}">
                <a href="{{ route('profitCenterDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Profit Center">Profit Center</span>
                </a>
            </li>

            <li class="nav-item {{ Request::is('party-info') || Request::is('party-info/*')  ? 'active' : ''}}">
                <a href="{{ route('partyInfoDetails') }}">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Party Info">Party Info</span>
                </a>
            </li>

            <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Journal">Journal</span></a>
                <ul class="menu-content">
                    @if(auth()->user()->hasPermission('app.journal_entry'))
                    <li  class="{{ Request::is('journal-entry*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalEntry') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Entry">Entry</span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasPermission('app.journal_authorize'))
                    <li class="{{ Request::is('journal-authorize*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalAuthorize') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Authorization</span>
                        </a>
                    </li>
                    @endif
                    @if (auth()->user()->hasPermission('app.journal_approval'))
                    <li class="{{ Request::is('journal-approval*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="{{ route('journalApproval') }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Authorization">Approval</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Accounts Report">Accounts Report</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('attendance/*') || Request::is('attendance') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="General Ledger">General Ledger</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('employee-attendance/*') || Request::is('employee-attendance') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Trial Balance">Trial Balance</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Income Statement / Profit Loss">Income Statement / Profit Loss</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Balance Sheet">Balance Sheet</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('student-leave*') ? 'active' : ''}}">
                        <a class="d-flex align-items-center" href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item text-truncate" data-i18n="Cash Flow Statement">Cash Flow Statement</span>
                        </a>
                    </li>
                </ul>
            </li>


            <li class=" navigation-header text-truncate"><span data-i18n="Inventory">Inventory</span> </li>
                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Product Specification">Product Specification</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('style*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('style.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Group">Style</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('brand*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('brand.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Brand">Color</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('group*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('group.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Group">Size</span>
                            </a>
                        </li>
                        <li  class="{{ Request::is('item-list*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('item-list.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Item List / Setup">Item List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Stock">Stock</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ Request::is('stock-position') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('stockPosition') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Stock Position">Monthly Stock Report</span>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Reconciliation">Reconciliation</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </li>

            <li class=" navigation-header text-truncate"><span data-i18n="Report">Report</span> </li>
<!--                 <li class="nav-item {{ Request::is('class-routine') ? 'active' : ''}}">
                    <a href="#">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Party Ledger">Party Ledger</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('teacher-schedule') ? 'active' : ''}}">
                    <a href="#">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Product performance">Product performance</span>
                    </a>
                </li>

                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Sales Report">Sales Report</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('books/*') || Request::is('books') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Sales Return">Sales Return</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-purchese/*') || Request::is('books-purchese') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Sales Summary">Sales Summary</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-lending/*') || Request::is('books-lending') || Request::is('received/*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Sales in Qty, Volume, Value">Sales in Qty, Volume, Value</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-lending/*') || Request::is('books-lending') || Request::is('received/*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Sales by Product, Customer ">Sales by Product, Customer </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Purchase Report">Purchase Report</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('books/*') || Request::is('books') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase in Qty, Volume, Value">Purchase in Qty, Volume, Value</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-purchese/*') || Request::is('books-purchese') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase by Product, Customer ">Purchase by Product, Customer </span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-lending/*') || Request::is('books-lending') || Request::is('received/*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase Summary">Purchase Summary</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-lending/*') || Request::is('books-lending') || Request::is('received/*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase Return">Purchase Return</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="VAT">VAT</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('books/*') || Request::is('books') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Weekly VAT Report">Weekly VAT Report</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('books-purchese/*') || Request::is('books-purchese') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Monthly VAT Report">Monthly VAT Report</span>
                            </a>
                        </li>

                    </ul>
                </li> -->


                <li class=" navigation-header text-truncate"><span data-i18n="Daily Operation">Daily Operation</span> </li>
                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Purchase">Purchase</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('purchase-requisition*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('purchase-requisition.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase Requisition">Purchase Requisition</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('item-purchase*') || Request::is("po-generation-approval-list") || Request::is("po-generation-approval-details") ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('item-purchase.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="PO Generation">PO Generation</span>
                            </a>
                        </li>
                        <li class="{{Request::is('goods-received*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route("goods-received.index")}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Goods Receive">Goods Receive</span>
                            </a>
                        </li>
                        <li class="{{Request::is('invoice-posting*') || Request::is('invoice_post_form') || Request::is('invoice-posting-submit') || Request::is('ip_details') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route("invoice_posting")}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Invoice Posting">Invoice Posting</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('purchase-return*') || Request::is('gr-details') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route('purchase-return.index')}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Purchase Return">Purchase Return</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('payment-voucher*') || Request::is('pv-process') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route("payment-voucher.index")}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Payment Voucher">Payment Voucher</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('purchase-summary*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route("purchase-summary.index")}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Payment Voucher">Purchase Summary</span>
                            </a>
                        </li>
                    </ul>
                </li>




                <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Sales & Delivery">Sales & Delivery</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('stationery-item/*') || Request::is('stationery-item') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Quotation">Quotation</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('sale-order*') || Request::is('sale-order') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('saleOrderReceive') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="SO Receive">SO Receive</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('delivery-note*') || Request::is('delivery-note') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('deliveryNote') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Delivery Note">Delivery Note</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('stationery-sale/*') || Request::is('stationery-sale') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="#">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Delivery Summary">Delivery Summary</span>
                            </a>
                        </li>
                        <li class="">
                            <a class="d-flex align-items-center" href="{{ route('salesTaxtInvoiceIssue') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Tax Invoice Issue (Tax Invoice)">Tax Invoice Issue (Tax Invoice)</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('receipt-voucher*') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{route("receipt-voucher.index")}}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Receipt Voucher (Cash Reciept)">Receipt Voucher</span>
                            </a>
                        </li>
                        <li class="{{ Request::is('sales-return/*') || Request::is('sales-return') ? 'active' : ''}}">
                            <a class="d-flex align-items-center" href="{{ route('salesReturn') }}">
                                <i class="bx bx-right-arrow-alt"></i>
                                <span class="menu-item text-truncate" data-i18n="Sales Return">Sales Return</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class=" navigation-header text-truncate"><span data-i18n="Support">Access Control </span> </li>
                <li class=" nav-item {{ Request::is('role*') ? 'active' : ''}}"><a href="{{route('role.index')}} "><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Roles">Roles</span></a></li>
                <li class=" nav-item {{ Request::is('settings*') ? 'active' : ''}}"><a href="{{route('settings.index')}} "><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Roles">Settings</span></a></li>

                {{-- <li class=" nav-item"><a href="#"><i class="bx bx-user-plus"></i><span class="menu-title text-truncate" data-i18n="Users">Users</span></a></li> --}}

                <li class=" navigation-header text-truncate"><span data-i18n="Support">Logout</span> </li>

            <li class=" nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="bx bx-log-out-circle"></i><span class="menu-title text-truncate" data-i18n="Logout">Logout</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
