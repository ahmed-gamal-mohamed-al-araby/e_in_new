@php
    $currentLang = Config::get('app.locale');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-success">
    <div class="overlay"></div>
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link navbar-success">
        <h3>EEC <sub> Group</sub></h3>
    </a>

    <!-- Sidebar -->
    <div
        class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 590px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">

                    @if (!auth()->user()->hasRole('taxes_report_show'))
                        <div class="form-inline " style="width: 120%;">
                            <div class="input-group" data-widget="sidebar-search">
                                <input class="form-control form-control-sidebar" type="search"
                                       placeholder="@lang('site.search')" aria-label="@lang('site.search')">
                                <div class="input-group-append">
                                    <button class="btn btn-sidebar">
                                        <i class="fas fa-search fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                @endif

                <!-- Sidebar Menu -->

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent"
                            data-widget="treeview" role="menu" data-accordion="false">
                            @if (!auth()->user()->hasRole('taxes_report_show'))
                                <li class="nav-item">
                                    <a href="{{ route('home') }}"
                                       class="nav-link {{ request()->is('*ar') || request()->is('*en') ? 'active' : '' }}">
                                        <i class="fa fa-tachometer-alt nav-icon"></i>
                                        <p> @lang('site.dashboard')</p>
                                    </a>
                                </li>
                            @endif

                            {{-- Product --}}
                            @if (auth()->user()->hasPermission('product_read') ||
                                auth()->user()->hasPermission('product_create') ||
                                auth()->user()->hasPermission('product_update') ||
                                auth()->user()->hasPermission('product_delete'))
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                       class="nav-link {{ request()->is('*product') || request()->is('*/product/*/edit') ? 'active' : '' }}">
                                        <i class="fa fa-box-open  nav-icon"></i>
                                        <p>@lang('site.products')</p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('document_request'))
                                <li class="nav-item">
                                    <a href="{{ route('documents.newDocument2') }}"
                                       class="nav-link {{ request()->is('*/documents/newDocument2') || request()->is('*/documents/documentSubmission') || request()->is('*/document/*/edit') ? 'active' : '' }}">
                                        <i class="fa fa-plus nav-icon"></i>
                                        <p>@lang('site.add') @lang('site.document_request')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('documents.index_document_request') }}"
                                       class="nav-link {{ request()->is('*/documents/index_document_request') || request()->is('*/documents/documentSubmission') || request()->is('*/document/*/edit') ? 'active' : '' }}">
                                        <i class="fa fa-receipt  nav-icon"></i>
                                        <p>@lang('site.document_request')</p>
                                    </a>
                                </li>
                            @endif

                            {{-- warranty_checks --}}
                            @if (auth()->user()->hasPermission('warranty_checks_read') ||
                                auth()->user()->hasPermission('warranty_checks_create') ||
                                auth()->user()->hasPermission('warranty_checks_update') ||
                                auth()->user()->hasPermission('checks_issued_clients_report') ||
                                auth()->user()->hasPermission('warranty_checks_delete'))
                                <li
                                    class="nav-item {{ request()->is('*warranty_checks*') || request()->is('*checks_issued_clients*') ? ' menu-open' : '' }}">

                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            @lang('site.warranty_checks')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">


                                        {{-- warranty_checks --}}
                                        @if (auth()->user()->hasPermission('warranty_checks_read'))
                                            <li class="nav-item">
                                                <a href="{{ route('warranty_checks.index') }}"
                                                   class="nav-link {{ request()->is('*/warranty_checks') || request()->is('*/warranty_checks/create') || request()->is('*/warranty_checks/*/edit') ? 'active' : '' }}">
                                                    <i class="fa fa-plus nav-icon"></i>
                                                    <p>@lang('site.warranty_checks')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('checks_issued_clients_report'))
                                            <li class="nav-item">
                                                <a href="{{ route('reports.checks_issued_clients_view') }}"
                                                   class="nav-link  {{ request()->routeIs('reports.checks_issued_clients_view') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>

                                                    <p> @lang('site.checks_issued_clients_report')</p>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>

                                </li>
                            @endif
                            {{-- letter_guarantee --}}
                            @if (auth()->user()->hasPermission('valid_letters_guarantee_report') ||
                                auth()->user()->hasPermission('applicant_letters_guarantee_report') ||
                                auth()->user()->hasPermission('letter_guarantee_request_read') ||
                                auth()->user()->hasPermission('letter_guarantee_request_create') ||
                                auth()->user()->hasPermission('letter_guarantee_request_update') ||
                                auth()->user()->hasPermission('letter_guarantee_request_delete') ||
                                auth()->user()->hasPermission('letter_guarantee_request_print') ||
                                auth()->user()->hasPermission('letter_guarantee_request_letter_guarantee') ||
                                auth()->user()->hasPermission('letter_guarantee_read') ||
                                auth()->user()->hasPermission('letter_guarantee_create') ||
                                auth()->user()->hasPermission('letter_guarantee_update') ||
                                auth()->user()->hasPermission('letter_guarantee_delete') ||
                                auth()->user()->hasPermission('letter_guarantee_answered') ||
                                auth()->user()->hasPermission('letter_guarantee_extend_raise'))
                                <li
                                    class="nav-item {{ request()->is('*letter_guarantee_request*') || request()->is('*letter_guarantee*') || request()->is('*valid-letters-guarantee*') || request()->is('*applicant-letters-guarantee*') ? ' menu-open' : '' }}">

                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            @lang('site.letter_guarantee')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">


                                        {{-- letter_guarantee --}}
                                        @if (auth()->user()->hasPermission('letter_guarantee_request_read') ||
                                            auth()->user()->hasPermission('letter_guarantee_request_create') ||
                                            auth()->user()->hasPermission('letter_guarantee_request_update') ||
                                            auth()->user()->hasPermission('letter_guarantee_request_delete') ||
                                            auth()->user()->hasPermission('letter_guarantee_request_print') ||
                                            auth()->user()->hasPermission('letter_guarantee_request_letter_guarantee'))
                                            <li class="nav-item">
                                                <a href="{{ route('letter_guarantee_request.index') }}"
                                                   class="nav-link {{ request()->is('*/letter_guarantee_request') || request()->is('*/letter_guarantee_request/create') || request()->is('*/letter_guarantee_request/*/edit') ? 'active' : '' }}">
                                                    <i class="fa fa-plus nav-icon"></i>
                                                    <p>@lang('site.letter_guarantee_request')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('letter_guarantee_read') ||
                                            auth()->user()->hasPermission('letter_guarantee_create') ||
                                            auth()->user()->hasPermission('letter_guarantee_update') ||
                                            auth()->user()->hasPermission('letter_guarantee_delete') ||
                                            auth()->user()->hasPermission('letter_guarantee_answered') ||
                                            auth()->user()->hasPermission('letter_guarantee_extend_raise'))
                                            <li class="nav-item">
                                                <a href="{{ route('letter_guarantee.index') }}"
                                                   class="nav-link {{ request()->is('*/letter_guarantee') || request()->is('*/letter_guarantee/create') || request()->is('*/letter_guarantee/*/edit') ? 'active' : '' }}">
                                                    <i class="fa fa-plus nav-icon"></i>
                                                    <p>@lang('site.letter_guarantee')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('valid_letters_guarantee_report'))
                                            <li class="nav-item">
                                                <a href="{{ route('reports.valid_letters_guarantee_view') }}"
                                                   class="nav-link  {{ request()->routeIs('reports.valid_letters_guarantee_view') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>

                                                    <p> @lang('site.valid_letters_guarantee_report')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('applicant_letters_guarantee_report'))
                                            <li class="nav-item">
                                                <a href="{{ route('reports.applicant_letters_guarantee_view') }}"
                                                   class="nav-link  {{ request()->routeIs('reports.applicant_letters_guarantee_view') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>

                                                    <p> @lang('site.applicant_letters_guarantee_report')</p>
                                                </a>
                                            </li>
                                        @endif

                                    </ul>

                                </li>
                            @endif

                            {{-- Clients --}}
                            @if (auth()->user()->hasPermission('client_read') ||
                                auth()->user()->hasPermission('client_create') ||
                                auth()->user()->hasPermission('client_update') ||
                                auth()->user()->hasPermission('client_delete'))
                                <li
                                    class="nav-item {{ request()->is('*businessClients*') || request()->is('*personClient*') || request()->is('*foreignerClient*') || request()->is('*/clients/related-documents') ? ' menu-open' : '' }}">

                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            @lang('site.clients')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">


                                        {{-- business Clients --}}
                                        <li class="nav-item">
                                            <a href="{{ route('businessClients.index') }}"
                                               class="nav-link {{ request()->is('*businessClients') || request()->is('*businessClients/archive') || request()->is('*/businessClients/*/edit') || request()->is('*/businessClients/profile/*') || request()->is('*businessClients/create') ? 'active' : '' }}">
                                                <i class="fa fa-users  nav-icon"></i>
                                                <p>@lang('site.all_business_clients')</p>
                                            </a>
                                        </li>

                                        {{-- person clients --}}

                                        <li class="nav-item">
                                            <a href="{{ route('personClient.index') }}"
                                               class="nav-link {{ request()->is('*personClient') || request()->is('*/personClient/*/edit') ? 'active' : '' }}">
                                                <i class="fa fa-users  nav-icon"></i>
                                                <p>@lang('site.all_person_clients')</p>
                                            </a>
                                        </li>


                                        {{-- foreign clients --}}
                                        <li class="nav-item">
                                            <a href="{{ route('foreignerClient.index') }}"
                                               class="nav-link {{ request()->is('*foreignerClient') || request()->is('*/foreignerClient/*/edit') ? 'active' : '' }}">
                                                <i class="fa fa-users  nav-icon"></i>
                                                <p>@lang('site.all_foreign_clients')</p>
                                            </a>
                                        </li>

                                        {{-- Related documents --}}
                                        <li class="nav-item">
                                            <a href="{{ route('client.documents_view') }}"
                                               class="nav-link {{ request()->is('*/clients/related-documents') ? 'active' : '' }}">
                                                <i class="fas fa-file nav-icon"></i>
                                                <p>@lang('site._related_documents')</p>
                                            </a>
                                        </li>
                                    </ul>

                                </li>
                            @endif


                            {{-- Documents --}}
                            @if ((auth()->user()->hasPermission('document_read') && auth()->user()->id != 18) ||
                                auth()->user()->hasPermission('document_create') ||
                                auth()->user()->hasPermission('document_update') ||
                                auth()->user()->hasPermission('document_delete') ||
                                auth()->user()->hasPermission('document_send') ||
                                auth()->user()->hasPermission('document_send2') ||
                                auth()->user()->hasPermission('document_invoice') ||
                                auth()->user()->hasPermission('get_recent_documents_received'))
                                <li
                                    class="nav-item {{ request()->is('*documents/newDocument') || request()->is('*document/*/edit') || request()->is('*documents/documentSubmission') || request()->is('*/documents') || request()->is('*/documents/*') || request()->is('*documents/sent-document') ? ' menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fa fa-receipt"></i>
                                        <p>
                                            @lang('site.documents')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        @if (auth()->user()->hasPermission('document_create'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.newDocument') }}"
                                                   class="nav-link {{ request()->is('*/documents/newDocument') || request()->is('*/documents/documentSubmission') || request()->is('*/document/*/edit') ? 'active' : '' }}">
                                                    <i class="fa fa-plus nav-icon"></i>
                                                    <p>@lang('site.add_document')</p>
                                                </a>
                                            </li>
                                        @endif


                                        @if (auth()->user()->hasPermission('document_create'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.create_special') }}"
                                                   class="nav-link {{ request()->is('*/documents/create-special') ? 'active' : '' }}">
                                                    <i class="fa fa-plus nav-icon"></i>
                                                    <p>@lang('site.add_special_document')</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->hasPermission('document_read') ||
                                            auth()->user()->hasPermission('document_create') ||
                                            auth()->user()->hasPermission('document_update') ||
                                            auth()->user()->hasPermission('document_delete') ||
                                            auth()->user()->hasPermission('document_send2') ||
                                            auth()->user()->hasPermission('document_send'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.index') }}"
                                                   class="nav-link {{ request()->is('*/documents') ? 'active' : '' }}">
                                                    <i class="fa fa-receipt  nav-icon"></i>
                                                    <p>@lang('site.all_documents')</p>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('documents.indexOFSentDocument') }}"
                                                   class="nav-link {{ request()->is('*documents/sent-document') ? 'active' : '' }}">
                                                    <i class="fa fa-receipt  nav-icon"></i>
                                                    <p>@lang('site.all_sending_documents')</p>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->hasPermission('document_send'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.getRecentDocuments') }}"
                                                   class="nav-link {{ request()->is('*documents/getRecentDocuments') ? 'active' : '' }}">
                                                    <i class="fa fa-receipt  nav-icon"></i>
                                                    <p>@lang('site.get_recent_documents')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('document_send2') && !auth()->user()->hasPermission('document_send'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.getRecentDocuments') }}"
                                                   class="nav-link {{ request()->is('*documents/getRecentDocuments') ? 'active' : '' }}">
                                                    <i class="fa fa-receipt  nav-icon"></i>
                                                    <p>@lang('site.get_recent_documents')</p>
                                                </a>
                                            </li>
                                        @endif



                                        @if (auth()->user()->hasPermission('document_send') ||
                                            auth()->user()->hasPermission('get_recent_documents_received'))
                                            <li class="nav-item">
                                                <a href="{{ route('documents.getRecentDocumentsReceived') }}"
                                                   class="nav-link {{ request()->is('*documents/getRecentDocumentsReceived') ? 'active' : '' }}">
                                                    <i class="fa fa-receipt nav-icon"></i>
                                                    <p>@lang('site.received_documents')</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif



                            {{-- Purchase Order --}}
                            @if (auth()->user()->hasPermission('po_read') ||
                                auth()->user()->hasPermission('po_create') ||
                                auth()->user()->hasPermission('po_update') ||
                                auth()->user()->hasPermission('po_delete'))
                                <li
                                    class="nav-item {{ request()->is('*purchaseorders/create') || request()->is('*purchaseorders/*/edit') || request()->is('*purchaseorders') || request()->is('*purchaseorders/*') || request()->is('*/purchaseorders/related-documents') ? ' menu-open' : '' }}">

                                    <a href="#" class="nav-link">
                                        <i class="far fa-file nav-icon"></i>
                                        <p>
                                            @lang('site.purchaseorders')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        @if (auth()->user()->hasPermission('po_create'))
                                            <li class="nav-item">
                                                <a href="{{ route('purchaseorders.create') }}"
                                                   class="nav-link {{ request()->is('*/purchaseorders/create') || request()->is('*/purchaseorders/*/edit') ? 'active' : '' }}">
                                                    <i class="fas fa-file-medical nav-icon"></i>
                                                    <p>@lang('site.add_purchaseorder')</p>
                                                </a>
                                            </li>
                                        @endif

                                        <li class="nav-item">
                                            <a href="{{ route('purchaseorders.index') }}"
                                               class="nav-link {{ request()->is('*purchaseorders') || request()->is('*/purchaseorders/*/edit') ? 'active' : '' }}">
                                                <i class="fas fa-file nav-icon"></i>
                                                <p>@lang('site.all_purchaseorders')</p>
                                            </a>
                                        </li>

                                        {{-- Related documents --}}
                                        <li class="nav-item">
                                            <a href="{{ route('purchaseorders.related_document.show') }}"
                                               class="nav-link {{ request()->is('*/purchaseorders/related-documents') ? 'active' : '' }}">
                                                <i class="fas fa-file nav-icon"></i>
                                                <p>@lang('site._related_documents')</p>
                                            </a>
                                        </li>

                                    </ul>

                                </li>
                            @endif

                            {{-- Banks --}}
                            @if (auth()->user()->hasPermission('bank_read') ||
                                auth()->user()->hasPermission('bank_create') ||
                                auth()->user()->hasPermission('bank_update') ||
                                auth()->user()->hasPermission('bank_delete'))
                                <li
                                    class="nav-item {{ request()->is('*bank/create') || request()->is('*bank/*/edit') || request()->is('*bank') ? ' menu-open' : '' }}">


                                    <a href="#" class="nav-link">
                                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                                        <p>
                                            @lang('site.banks')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        @if (auth()->user()->hasPermission('bank_create'))
                                            <li class="nav-item">
                                                <a href="{{ route('bank.create') }}"
                                                   class="nav-link {{ request()->is('*/bank/create') || request()->is('*/bank/*/edit') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.add_bank')</p>
                                                </a>
                                            </li>
                                        @endif

                                        <li class="nav-item">
                                            <a href="{{ route('bank.index') }}"
                                               class="nav-link {{ request()->is('*bank') || request()->is('*/bank/*/edit') ? 'active' : '' }}">
                                                <i class="fas fa-money-check-alt nav-icon"></i>
                                                <p>@lang('site.all_banks')</p>
                                            </a>
                                        </li>

                                    </ul>

                                </li>
                            @endif

                            {{-- Deduction --}}
                            @if (auth()->user()->hasPermission('client_read') ||
                                auth()->user()->hasPermission('client_create') ||
                                auth()->user()->hasPermission('client_update') ||
                                auth()->user()->hasPermission('client_delete'))
                                <li class="nav-item ">
                                    <a href="{{ route('deduction.index') }}"
                                       class="nav-link {{ request()->is('ar/deduction') || request()->is('en/deduction') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-alt"></i>
                                        <p>@lang('site.deductions')</p>
                                    </a>
                                </li>
                            @endif

                            {{-- Payment --}}
                            @if (auth()->user()->hasPermission('document_read') ||
                                auth()->user()->hasPermission('bank_read') ||
                                auth()->user()->hasPermission('bank_create') ||
                                auth()->user()->hasPermission('bank_update') ||
                                auth()->user()->hasPermission('bank_delete'))
                                <li class="nav-item {{ request()->is('*payment*') ? ' menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                                        <p>
                                            @lang('site.payment')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @if (auth()->user()->hasPermission('bank_create'))
                                            {{-- Purchase order --}}
                                            <li class="nav-item">
                                                <a href="{{ route('payment.purchaseorder.cashe_cheque_bankTransfer') }}"
                                                   class="nav-link {{ request()->is('*/payment/purchaseorder/cashe_cheque-bankTransfer') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.purchaseOrder_C_BT')</p>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('payment.purchaseorder.deduction') }}"
                                                   class="nav-link {{ request()->is('*/payment/purchaseorder/deduction') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.purchaseOrder_d')</p>
                                                </a>
                                            </li>

                                            {{-- Document --}}
                                            <li class="nav-item">
                                                <a href="{{ route('payment.document.cashe_cheque_bankTransfer') }}"
                                                   class="nav-link {{ request()->is('*/payment/document/cashe_cheque-bankTransfer') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.document_C_BT')</p>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('payment.document.deduction') }}"
                                                   class="nav-link {{ request()->is('*/payment/document/deduction') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.document_d')</p>
                                                </a>
                                            </li>

                                            <li class="nav-item">
                                                <a href="{{ route('payment.document.store.multi_document') }}"
                                                   class="nav-link {{ request()->is('*/payment/document/multi-document') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.Multi_documents')</p>
                                                </a>
                                            </li>

                                            {{-- Related --}}
                                            <li class="nav-item">
                                                <a href="{{ route('payment.related.index') }}"
                                                   class="nav-link {{ request()->is('*/payment/related') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.Related_with')</p>
                                                </a>
                                            </li>
                                        @endif
                                        @if (auth()->user()->hasPermission('document_read'))
                                            {{-- Payment index --}}
                                            <li class="nav-item">
                                                <a href="{{ route('payment.index') }}"
                                                   class="nav-link {{ request()->is('*/payment') || request()->is('*/payment') ? 'active' : '' }}">
                                                    <i class="fas fa-money-bill-wave nav-icon"></i>
                                                    <p>@lang('site.payment')</p>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            {{-- Report Section --}}

                            @if (auth()->user()->id != 16 &&
                                !auth()->user()->hasPermission('get_recent_documents_received') &&
                                !auth()->user()->hasPermission('document_request') &&
                                !auth()->user()->hasRole('letter_guarantee_request_user'))

                                <li
                                    class="nav-item {{ request()->is('*reports/create') || request()->is('*reports/*/edit') || request()->is('*reports*') ? ' menu-open' : '' }}">

                                    <a href="#" class="nav-link ">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>
                                            @lang('site.reports')
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        {{-- @if (auth()->user()->hasPermission('report_create')) --}}

                                        @if(auth()->user()->id != 18)
                                            <li class="nav-item">
                                                <a href="{{ route('reports.vattaxreport') }}"
                                                   class="nav-link {{ request()->is('*reports/vattaxreport') || request()->is('*reports/vattaxreport') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>
                                                    <p>@lang('site.create_vat_tax_report')</p>
                                                </a>
                                            </li>
                                            {{-- @endif --}}
                                        @endif

                                        @if (!auth()->user()->hasRole('taxes_report_show'))
                                            {{-- create purchaseorder report --}}
                                            @if(auth()->user()->id != 18)
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.createpurchaseorderreport') }}"
                                                       class="nav-link {{ request()->is('*/reports/purchaserrderreport') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.create_purchase_order_report')</p>
                                                    </a>
                                                </li>

                                                {{-- General --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.general') }}"
                                                       class="nav-link {{ request()->is('*/reports/primary-delivery-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.purchase_orders_report_situation')</p>
                                                    </a>
                                                </li>

                                                {{-- tax_rate_letter_report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.tax_rate_letter_report') }}"
                                                       class="nav-link {{ request()->is('*/reports/primary-delivery-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.tax_rate_letter_report')</p>
                                                    </a>
                                                </li>

                                                {{-- primary delivery status --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.primary_delivery_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/primary-delivery-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.primary_delivery_status')</p>
                                                    </a>
                                                </li>

                                                {{-- final delivery status --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.final_delivery_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/final-delivery-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.final_delivery_status')</p>
                                                    </a>
                                                </li>

                                                {{-- social insurance status --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.social_insurance_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/social-insurance-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.social_insurance_status')</p>
                                                    </a>
                                                </li>

                                                {{-- labor isurance status --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.labor_insurance_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/labor-insurance-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.labor_insurance_status')</p>
                                                    </a>
                                                </li>

                                                {{-- tax exemption certificate status --}}
                                                <li
                                                    class="nav-item {{ $currentLang == 'ar' ? 'scroll_ellipsis_text_on_hover' : '' }}">
                                                    <a href="{{ route('reports.tax_exemption_certificate_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/tax-exemption-certificate-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.tax_exemption_certificate_status')</p>
                                                    </a>
                                                </li>

                                                {{-- received final performance bond status --}}
                                                <li class="nav-item scroll_ellipsis_text_on_hover">
                                                    <a href="{{ route('reports.received_final_performance_bond_status') }}"
                                                       class="nav-link {{ request()->is('*/reports/received_final-performance-bond-status') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.received_final_performance_bond_status')</p>
                                                    </a>
                                                </li>

                                            @endif

                                            {{-- Deduction report --}}
                                            <li class="nav-item">
                                                <a href="{{ route('reports.deduction_report_view') }}"
                                                   class="nav-link {{ request()->is('*/reports/deduction-report') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>
                                                    <p>@lang('site.deductions')</p>
                                                </a>
                                            </li>


                                            @if(auth()->user()->id != 18)
                                                {{-- Deduction report all --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.deduction_report_view_All') }}"
                                                       class="nav-link {{ request()->is('*/reports/deduction-report-all') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.deductions_all')</p>
                                                    </a>
                                                </li>

                                                {{-- Deductions report all --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.deductions_report_view_All') }}"
                                                       class="nav-link {{ request()->is('*/reports/deductions-report-all') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.deductions_all2')</p>
                                                    </a>
                                                </li>

                                                {{-- Client analysis report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.client_analysis_view') }}"
                                                       class="nav-link {{ request()->is('*/reports/client-analysis') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.client_analysis')</p>
                                                    </a>
                                                </li>

                                                {{-- Client balances report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.client_balances_view') }}"
                                                       class="nav-link {{ request()->is('*/reports/client-balances') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.client_balances')</p>
                                                    </a>
                                                </li>

                                                {{-- Daily Client Balances Report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.daily_client_balances_view') }}"
                                                       class="nav-link {{ request()->is('*/reports/daily-client-balances') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.daily_client_balances')</p>
                                                    </a>
                                                </li>

                                                {{-- Client Document Balances Report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.client_document_balances_view') }}"
                                                       class="nav-link {{ request()->is('*/reports/client-document-balances') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>
                                                        <p>@lang('site.client_document_balances')</p>
                                                    </a>
                                                </li>

                                            @endif

                                            <li class="nav-item">
                                                <a href="{{ route('reports.collections_view') }}"
                                                   class="nav-link {{ request()->is('*/reports/collections') ? 'active' : '' }}">
                                                    <i class="far fa-file-alt nav-icon"></i>
                                                    <p>@lang('site.collections')</p>
                                                </a>
                                            </li>

                                            @if(auth()->user()->id != 18)
                                                {{-- collections report --}}
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.report_account_number') }}"
                                                       class="nav-link  {{ request()->routeIs('reports.report_account_number') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>

                                                        <p> @lang('site.bank_report')</p>
                                                    </a>
                                                </li>

                                                <li class="nav-item">
                                                    <a href="{{ route('reports.payment_date_view') }}"
                                                       class="nav-link  {{ request()->routeIs('reports.payment_date_view') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>

                                                        <p> @lang('site.check_report')</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('reports.financialposition_of_supplyorder') }}"
                                                       class="nav-link  {{ request()->routeIs('reports.financialposition_of_supplyorder') ? 'active' : '' }}">
                                                        <i class="far fa-file-alt nav-icon"></i>

                                                        <p> @lang('site.financialposition_of_supplyorder')</p>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif

                                    </ul>
                                </li>
                            @endif

                            @if (auth()->user()->hasPermission('user_read') ||
                                auth()->user()->hasPermission('company_read') ||
                                auth()->user()->hasPermission('country_read') ||
                                auth()->user()->hasPermission('city_read'))
                                <li class="nav-header">@lang('site.settings')</li>
                            @endif

                            {{-- users --}}
                            @if (auth()->user()->hasPermission('user_read'))
                                <li class="nav-item " title="جميع المسنخدمين الموجودين فى الشركه">
                                    <a href="{{ route('users.index') }}"
                                       class="nav-link {{ request()->is('*users') || request()->is('*users/*/edit') || request()->is('*users/profile*') ? 'active' : '' }} ">
                                        <i class="nav-icon fa fa-user-cog"></i>
                                        <p>
                                            @lang('site.users')
                                            <span
                                                class="right badge {{ request()->is('*users') || request()->is('*users/*/edit') || request()->is('*users/profile*') ? 'badge-warning' : 'badge-success' }}">{{ $users_count ?? '' }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endif

                            {{-- companies --}}
                            @if (auth()->user()->hasPermission('company_read'))
                                <li class="nav-item ">
                                    <a href="{{ route('company.index') }}"
                                       class="nav-link {{ request()->is('*companies*') ? 'active' : '' }} ">
                                        <i class="nav-icon fa fa-building"></i>
                                        <p>
                                            @lang('site.companies')
                                        </p>
                                    </a>
                                </li>
                            @endif

                            {{-- Countries --}}
                            @if (auth()->user()->hasPermission('country_read'))
                                <li class="nav-item">
                                    <a href="{{ route('country.index') }}"
                                       class="nav-link {{ request()->is('*portal/countries') || request()->is('*portal/country/*/edit') ? 'active' : '' }}">
                                        <i class="fas fa-flag nav-icon"></i>
                                        <p> @lang('site.countries') </p>
                                    </a>
                                </li>
                            @endif

                            {{-- Cities --}}
                            @if (auth()->user()->hasPermission('city_read'))
                                <li class="nav-item">
                                    <a href="{{ route('city.index') }}"
                                       class="nav-link {{ request()->is('*portal/cities') || request()->is('*portal/city/*/edit') ? 'active' : '' }}">
                                        <i class="fas fa-city nav-icon"></i>
                                        <p> @lang('site.cities') </p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
</aside>
