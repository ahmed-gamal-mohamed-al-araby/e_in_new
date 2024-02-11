@extends('pages.layouts.master')

@section('title')
    @lang('site._payment') @lang('site.document')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
    {{-- Loader --}}
    <style>
        @keyframes bouncing-loader {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0.1;
                transform: translateY(-1rem);
            }
        }

        .bouncing-loader {
            display: flex;
            justify-content: center;
        }

        .bouncing-loader>div {
            width: 1rem;
            height: 1rem;
            margin: 3rem 0.2rem;
            background: rgb(4, 182, 4);
            border-radius: 50%;
            animation: bouncing-loader 0.8s infinite alternate;
        }

        .bouncing-loader>div:nth-child(2) {
            animation-delay: 0.2s;
        }

        .bouncing-loader>div:nth-child(3) {
            animation-delay: 0.4s;
        }

        .bouncing-loader>div:nth-child(4) {
            animation-delay: 0.6s;
        }

        .bouncing-loader>div:nth-child(5) {
            animation-delay: 0.8s;
        }

        .loader-container {
            display: grid;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            border: solid;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

    </style>

    @if (Config::get('app.locale') == 'ar')
        <style>
            .dataDirection {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

            label {
                float: unset;
                margin-right: unset;
            }

            .payment-method label {
                margin-right: 20px;
            }

        </style>
    @else
        <style>
            .dataDirection {
                direction: ltr !important;
            }

            .textDirection {
                text-align: left;
            }

        </style>
    @endif

    <style>
        #payment_method-error,
        #validate_documents-error {
            display: none !important;
        }

        .client_type_container div.select2-container,
        .client_name_container div.select2-container,
        .purchase_orders_container div.select2-container,
        .documents_container div.select2-container {
            margin: 10px;
            display: block;
            max-width: 60%;
        }

        .my-error {
            width: 100%;
            display: block;
            position: relative;
            text-align: center;
            font-size: 11px;
            color: #ff0000;
        }

        .document-container:nth-child(even) {
            background-color: aliceblue
        }

        #available-numbers {
            position: sticky;
            top: 65px;
            z-index: 10000;
            background-color: #DCDCDC;
        }

    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-between">
                    <h1>@lang('site.add') @lang('site._payment') @lang('site.multi_documents') </h1>
                {{-- </div>
                <div class="col-md-6"> --}}
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                        <li class="breadcrumb-item active"> @lang('site.add') @lang('site._payment')
                            @lang('site.multi_documents')
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section id="available-numbers" class="row my-2 dataDirection mx-1 rounded">
        <h2 class="col-md-4 textDirection py-3 d-none">
            {{ __('site.available-payment-value') }}
            <span class="badge badge-success mx-2" id="available-payment-value"></span>
        </h2>
        <h2 class="col-md-4 textDirection py-3 d-none">{{ __('site.total_payemnts') }}
            <span class="badge badge-success mx-2" id="sum-of-payment-value"></span>
        </h2>
        <h2 class="col-md-4 textDirection py-3 d-none">{{ __('site.total_deductions') }}
            <span class="badge badge-success mx-2" id="sum-of-deduction-value"></span>
        </h2>
    </section>

    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.add') @lang('site._payment') @lang('site.multi_documents')
            </h2>
            <form method="POST" id="PaymentForm" class="PoForm" action=""
                enctype="multipart/form-data">
                @csrf

                {{-- client details --}}
                <h3 class="d-none0">
                    <span class="title_text">@lang('site.client')</span>
                </h3>

                <fieldset class="d-none0">
                    <div class="fieldset-content px-3">
                        <div class="card textDirection">

                            <h5 class="card-header bg-success">
                                @lang('site.client')
                            </h5>

                            <div class="card-body dataDirection">
                                {{-- Client section --}}
                                <div class="row mb-3">
                                    {{-- Client Type --}}
                                    <div class="col-md-3 col-12">
                                        <div class="input-group mb-3 client_type_container">
                                            <label class="form-label d-block w-100 textDirection mr-0"
                                                id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require"
                                                required>
                                                <option selected disabled>@lang('site.capital_select')
                                                    @lang('site.client_type')
                                                </option>
                                                <option value="b" data-label="@lang('site.tax_id_number_only')"
                                                    data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" data-label="@lang('site.national_id')"
                                                    data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f" data-label="@lang('site.vat_id')"
                                                    data-validate="@lang('site.validate_vat_id')">
                                                    @lang('site.foreigner_client')</option>
                                            </select>
                                        </div>
                                        @error('client_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="text-center text-danger d-none client_type_error">
                                            @lang('site.data-required')</div>
                                    </div>

                                    {{-- Client details --}}
                                    <div class="col-md-9 col-12 textDirection">

                                        <div class="card-body p-0 client-details d-none">
                                            <div class="row">
                                                <div class="col-md-9 col-12 no-gutters">
                                                    {{-- tax_id_number for business client Or national ID person client --}}
                                                    <div class="row no-gutters">
                                                        <div class="col-12 input-group client_name_container">
                                                            <label
                                                                class="form-label d-block w-100 textDirection mr-0">@lang('site.client_name')</label>
                                                            <select id='client_name' name="client_name" style="width: 100%"
                                                                class="form-control rounded require" disabled>
                                                                <option selected disabled>@lang('site.capital_select')
                                                                    @lang('site.client_name')
                                                                </option>
                                                            </select>
                                                            <div
                                                                class="text-center text-danger d-none w-100 client_name_error mt-3">
                                                                @lang('site.data-required')</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- name --}}
                                                <div class="col-md-3 col-12 mb-1">
                                                    <label for="tax_id_number_or_national_id_or_vat_id"
                                                        class="form-label w-100 textDirection"
                                                        id="min_payment_label">@lang('site.tax_id_number_only') </label>
                                                    <input type="text" id="tax_id_number_or_national_id_or_vat_id"
                                                        name="tax_id_number_or_national_id_or_vat_id" class="display w-100"
                                                        readonly>
                                                    <div
                                                        class="text-center text-danger d-none tax_id_number_or_national_id_or_vat_id_error mt-3">
                                                        @lang('site.data-required')</div>
                                                </div>

                                                {{-- <input type="hidden" name="client_id" id="client_id" required> --}}

                                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                                            </div> <!-- End Of First Row-->

                                        </div> <!-- End Of Card Body-->

                                    </div>
                                </div>
                                <div class="text-center text-danger d-none w-100 client-has-no-purchaseOrders mt-3">
                                    @lang('site.client-has-no-purchaseOrders')</div>
                                <div class="text-center text-danger d-none w-100 client-has-no-documents mt-3">
                                    @lang('site.client-has-no-documents')</div>
                            </div>
                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_1_3')</span>
                    </div>
                </fieldset>

                {{-- Payment Details --}}
                <h3 class="d-none0">
                    <span class="title_text"> @lang('site.payment')</span>
                </h3>

                <fieldset class="payment d-none0">
                    <div class="fieldset-content">
                        <div class="card ml-2 textDirection">

                            <h5 class="card-header bg-success">
                                @lang('site.payment')
                            </h5>

                            <div class="card-body dataDirection">
                                <div class="row row-page supplier-accepted payment-method justify-content-between">
                                    {{-- Payment method --}}
                                    <h5 class="col-12 mb-2" style="color:#6c757d!important">
                                        @lang('site.check_available_payment_method')</h5>

                                    <div class="col-12 col-md-6 row">
                                        {{-- Check box options for payment method --}}
                                        <div class="form-check">
                                            {{-- Cashe option --}}
                                            <input class="form-check-input mx-2"
                                                style="{{ Config::get('app.locale') == 'ar' ? 'margin-right: 1.5rem!important;' : '' }}"
                                                type="radio" name="payment_method" id="cash_check_id" value="cashe">
                                            <label class="form-check-label" for="cash_check_id">
                                                @lang('site.cash')
                                            </label>
                                        </div>
                                        {{-- cheque option --}}
                                        <div class="form-check">
                                            <input class="form-check-input mx-2"
                                                style="{{ Config::get('app.locale') == 'ar' ? 'margin-right: 1.5rem!important;' : '' }}"
                                                type="radio" name="payment_method" id="cheque_check_id" value="cheque">
                                            <label class="form-check-label" for="cheque_check_id">
                                                @lang('site.cheque')
                                            </label>
                                        </div>
                                        {{-- bank_transfer option --}}
                                        <div class="form-check">
                                            <input class="form-check-input mx-4"
                                                style="{{ Config::get('app.locale') == 'ar' ? 'margin: .3rem 42px !important;' : '' }}"
                                                type="radio" name="payment_method" id="bank_transfer_check_id"
                                                value="bank_transfer">
                                            <label class="form-check-label" for="bank_transfer_check_id">
                                                @lang('site.bank_transfer')
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-none ml-2 col-12 text-center" id="payment_option_error"
                                        style="font-weight: 700; color: #ff0000">
                                        @lang('site.check_one_payment_method')</div>

                                    <div class="col-12"></div>

                                    {{-- file --}}
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label d-block w-100 textDirection mx-0"
                                                id="order_label">@lang('site.file')</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file">
                                            <label class="custom-file-label m-0" style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                            for="file">@lang('site.choose') @lang('site.file')</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row row-page supplier-accepted">


                                    {{-- Payment date --}}
                                    <div class="col-md-6 my-2">
                                        <label for="type"
                                            class="form-label textDirection">@lang('site.payment_date')</label>
                                        <input type="date" name="payment_date" required class="d-block"
                                            onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                    </div>

                                    <hr style="flex: 0 0 100%;">

                                    {{-- Bank_transfer --}}
                                    <div class="col-12 row d-none" id="bank_transfer_container">
                                        {{-- Bank name --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection ">@lang('site.bank_name')</label>
                                            <input type="text" name="bank_transfer_bank_name" class="form-control" 
                                                placeholder="@lang('site.enter') @lang('site.bank_name')"
                                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_name')')"
                                                oninput="setCustomValidity('')">
                                            @error('bank_transfer_bank_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class=" offset-md-6 mb-3"></div>
                                        {{-- received date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection ">@lang('site.received_date')</label>
                                            <input type="date" name="bank_transfer_received_date"
                                                id="bank_transfer_received_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- issue date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection ">@lang('site.issue_date')</label>
                                            <input type="date" name="bank_transfer_issue_date" id="bank_transfer_issue_date"
                                                required class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- deposit date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection ">@lang('site.deposit_date')</label>
                                            <input type="date" name="bank_transfer_deposit_date"
                                                id="bank_transfer_deposit_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- collect date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection ">@lang('site.collect_date')</label>
                                            <input type="date" name="bank_transfer_collect_date"
                                                id="bank_transfer_collect_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>
                                    </div>

                                    {{-- Cheque --}}
                                    <div class="col-12 row d-none" id="cheque_container">
                                        {{-- Bank name --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.bank_name')</label>
                                            <input type="text" name="cheque_bank_name" class="form-control" required
                                                placeholder="@lang('site.enter') @lang('site.bank_name')"
                                                oninvalid="this.setCustomValidity(' @lang('site.please') @lang('site.enter') @lang('site.bank_name') ') "
                                                oninput="setCustomValidity('')">
                                            @error('cheque_bank_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- cheque number --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.cheque_number')</label>
                                            <input type="text" name="cheque_number" class="form-control" required
                                                placeholder="@lang('site.enter') @lang('site.cheque_number')"
                                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.cheque_number')')"
                                                oninput="setCustomValidity('')">
                                            @error('cheque_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- received date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.received_date')</label>
                                            <input type="date" name="cheque_received_date" id="cheque_received_date"
                                                required class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- issue date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.issue_date')</label>
                                            <input type="date" name="cheque_issue_date" id="cheque_issue_date" required
                                                class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- deposit date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.deposit_date')</label>
                                            <input type="date" name="cheque_deposit_date" id="cheque_deposit_date" required
                                                class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- collect date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type"
                                                class="form-label textDirection">@lang('site.collect_date')</label>
                                            <input type="date" name="cheque_collect_date" id="cheque_collect_date" required
                                                class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>
                                    </div>

                                    <div class="row mb-3 d-none mx-2" id="bank_container">

                                        {{-- Bank code --}}
                                        <div class="col-md-3 no-gutters">
                                            <div class="row mb-1 no-gutters">
                                                <div class="col-md-11">
                                                    <label for="bank_code" class="form-label w-100 textDirection"
                                                        id="min_payment_label">@lang('site.bank_code')</label>
                                                    <input type="number" class="form control" id="bank_code" />
                                                </div>
                                                <div class="col-md-1 bank-spinner" style="padding:32px 0 0 10px">
                                                    <div class="search-bank spinner-border spinner-border-sm text-success"
                                                        role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="vaild-client-register-tax ml-2"></p>
                                        </div>

                                        {{-- bank_name --}}
                                        <div class="col-md-3">
                                            <label for="bank_name" class="form-label w-100 textDirection"
                                                id="min_payment_label">@lang('site.bank_name') </label>
                                            <input type="text" id="bank_name" class="display" readonly>
                                        </div>

                                        {{-- bank_account_number --}}
                                        <div class="col-md-3">
                                            <label for="bank_account_number" class="form-label w-100 textDirection"
                                                id="payment_label">@lang('site.bank_account_number')</label>
                                            <input type="text" id="bank_account_number" class="display" readonly>
                                        </div>

                                        {{-- bank currency --}}
                                        <div class="col-md-2">
                                            <label for="bank_currency"
                                                class="min_payment_label textDirection">@lang('site.currency')</label>
                                            <input type="text" id="bank_currency" class="display" readonly>
                                        </div>

                                        {{-- Bank id --}}
                                        <div class="col-md-1">
                                            <label for="address"
                                                class="form-label w-100 textDirection">@lang('site.id')</label>
                                            <input type="text" name="bank_id" value="" id="bank_id" readonly>
                                        </div>
                                    </div> <!-- End Of First Row-->

                                </div>
                            </div> <!-- End Of Card Body-->

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_2_3')</span>
                    </div>
                </fieldset>

                {{-- documents --}}
                <h3>
                    <span class="title_text">@lang('site.document')
                    </span>
                </h3>

                <fieldset>

                    <div class="fieldset-content px-3">
                        <div class="card textDirection">

                            <h5 class="card-header bg-success">
                                @lang('site.document')
                            </h5>
                                
                            <div class="card-body dataDirection p-0" id="documents-container" >
                                <input type="text" name="validate_documents" class="d-none">
                                <div class="row align-content-center justify-content-center document-container p-3 m-0">
                                    <h2 class="text-muted col-12 pt-0 pb-1">@lang('site.document') <span
                                            class="document-counter">1</span></h2>
                                    <hr style="flex: 0 0 100%;">
                                    {{-- Purchase Orders --}}
                                    <div class="col-md-5 purchase_orders_container">
                                        <label class="form-label d-block w-100 textDirection">@lang('site.capital_select')
                                            @lang('site.purchaseorder')</label>
                                        <select name="purchaseorder_id[]" class="form-control  purchaseorder_id"
                                            data-document-index="1">
                                            <option selected disabled value="">@lang('site.capital_select')
                                                @lang('site.purchaseorder')
                                            </option>
                                        </select>
                                        
                                        <!-- <select name="purchaseorder_id[]" class="form-control require purchaseorder_id"
                                            data-document-index="1">
                                            <option selected disabled value="">@lang('site.capital_select')
                                                @lang('site.purchaseorder')
                                            </option>
                                        </select> -->
                                        <label class="my-error d-none validation-label">@lang('site.please')
                                            @lang('site.capital_select') @lang('site.purchaseorder')</label>
                                    </div>
                                    
                                    {{-- documents --}}
                                    <div class="col-md-6 documents_container">
                                        <label class="form-label d-block w-100 textDirection">@lang('site.capital_select')
                                            @lang('site.document')</label>
                                        <select name="document_id[]" class="form-control  document_id" 
                                            data-document-index="1">
                                            <option selected disabled class="placeholder-option" value="">
                                                @lang('site.capital_select')
                                                @lang('site.document')
                                            </option>
                                        </select>
                                        <label class="my-error d-none validation-label">@lang('site.please')
                                            @lang('site.capital_select') @lang('site.document')</label>
                                    </div>
                                    <div class="col-md-1 px-0 text-center remove-document">
                                        <span class="btn btn-danger delete-document d-none" data-document-index="1"
                                            style="font-size: 12px;padding: 3px 7px;margin-top: 29.5px;"><i
                                                class="fa fa-trash-alt m-0"></i></span>
                                        {{-- <span class="btn btn-warning edit-document"
                                            style="font-size: 12px;padding: 3px 7px;margin-top: 29.5px;"><i
                                                class="fa fa-edit m-0"></i></span> --}}
                                    </div>

                                    {{-- total_money --}}
                                    <div class=" col-md-6 my-2">
                                        <label for="type" class="form-label textDirection">@lang('site.amount')</label>
                                        <input type="number" name="total_money[]" id="" class="form-control amount_data total_money" 
                                            data-document-index="1" placeholder="@lang('site.enter') @lang('site.amount')"
                                            readonly
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.amount')')"
                                            oninput="setCustomValidity('')">
                                        <label class="my-error d-none validation-label">@lang('site.validate_value')</label>

                                        <p class="text-danger text-bold text-center d-none"
                                            id="validate-payment_purchase_order-overflow">
                                            @lang('site.payment_purchase_order_overflow_error')</p>
                                        <p
                                            class="text-danger text-bold text-center d-none validate-payment_document-overflow">
                                            @lang('site.payment_document_overflow_error')</p>

                                        @error('total_money')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="col-md-3">
                                        <a href="#" data-toggle="modal" data-target="#addline" class="addNewDeduction"
                                            data-document-index="1" style="margin-top: 44px;display: inline-block;"
                                            id="_addNewDeductionBtn"><i class="fa fa-plus"></i> @lang('site.add')
                                            @lang('site.deduction')</a>
                                    </div>

                                    <div class="col-md-3">
                                            <button type="button" style="margin-top: 34px;display: inline-block;" class="btn btn-warning set-available" id="">@lang('site.available-payment-value')
                                            </button>
                                    </div>




                                    <div class="col-12 mt-3">
                                        {{-- Show deductions --}}
                                        <div class="table-responsive">
                                            {{-- Table for view addded items --}}
                                            <table
                                                class="table table-bordered table-striped table-hover justify-content-center text-center m-0 deductions-table d-none0"
                                                data-document-index="1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            #
                                                        </th>
                                                        <th scope="col">
                                                            @lang('site.deduction')
                                                        </th>
                                                        <th scope="col">
                                                            @lang('site.value')
                                                        </th>
                                                        <th scope="col">
                                                            @lang('site.actions')
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <th>
                                                        @lang('site.total')
                                                    </th>
                                                    <th colspan="3">
                                                    </th>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2 mx-0">
                                    <span id="add-new-document" class="btn btn-success"><i class="fa fa-plus mx-2">
                                        </i>@lang('site.add') @lang('site._payment') @lang('site._document')</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_3_3')</span>
                    </div>
                </fieldset>

            </form>
        </div>
    </section>

    {{-- modal add Deduction --}}
    <div class="modal fade" id="addline" data-check-data="null" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header text-center">
                    <h6 class="modal-title card-header bg-success text-center" id="exampleModalLongTitle">@lang('site.add')
                        @lang('site.deduction')</h6>
                        <button type="button" class="btn btn-warning" id="set-available-payment-value">@lang('site.available-payment-value')
                        </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- end of model header --}}

                <div class="modal-body add-invoice-items">
                    <form id="adddeductionsForm">
                        <div class="row currenty-type mb-2">
                            {{-- Deduction --}}
                            <div class="col-6  input-group select">
                                <label>@lang('site.deduction')</label>
                                <select name="deduction" id="deduction" class="currenty-type-select">
                                    <option  selected>@lang('site.capital_select') @lang('site.deduction')</option>
                                    @foreach ($deductions as $deduction)
                                        <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- value --}}
                            <div class="col-6 mb-2">
                                <label>@lang('site.value')</label>
                                <div class="input-group select">
                                    <input type="number" name="value" id="deduction_value" placeholder="@lang('site.value')"
                                        data-document-index="0" class="input-group-item" />
                                </div>
                                <p class="text-danger text-bold text-center d-none"
                                    id="validate-payment_document-overflow-deduction">
                                    @lang('site.payment_document_overflow_error')</p>
                            </div>



                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success save-form-item">
                                    <i class="fa fa-save"></i>
                                    @lang('site.save')
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                {{-- end of model body --}}
            </div>
            {{-- end of model content --}}

        </div>
    </div>

    {{-- Loader for loading purchase order items from excel sheet --}}
    <div class="loader-container" style="display: none">
        <div class="bouncing-loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let language = [];
        language['save'] = "@lang('site.save')";
        language['send_data'] = "@lang('site.send_data')";
        language['data_sent'] = "@lang('site.data_sent')";
        language['send_data_error'] = "@lang('site.send_data_error')";
        language['error'] = "@lang('site.error')";
        language['next'] = "@lang('site.next')";
        language['prev'] = "@lang('site.prev')";
        language['save'] = "@lang('site.save')";
        language['select_client_name'] =
            `<option selected disabled>@lang('site.capital_select') @lang('site.client_name')</option>`;
        language['select_document'] =
            `<option selected disabled value='' class="placeholder-option">@lang('site.capital_select') @lang('site.document')</option>`;
        language['select_document_placeholder'] = `@lang('site.capital_select') @lang('site.document')`;
        language['select_purchaseOrder'] =
            `<option selected disabled value=''>@lang('site.capital_select') @lang('site.purchaseorder')</option>`;
        language['select_purchaseOrder__placeholder'] = `@lang('site.capital_select') @lang('site.purchaseorder')`;
        language['client_PO_empty'] = "@lang('site.purchaseOrder_of_client_empty')";
        language['no_data'] = "@lang('site.no_data')";
        language['deduction_id'] =
            "@lang('site.please') {{ ' ' }} @lang('site.capital_select') {{ ' ' }} @lang('site.deduction')";
        language['amount'] = "@lang('site.amount')";
        language['value_placeholder'] = "@lang('site.enter') @lang('site.amount')";
        language['add_deduction'] = "@lang('site.add') @lang('site.deduction')";
        language['deduction'] = "@lang('site.deduction')";
        language['value'] = "@lang('site.value')";
        language['actions'] = "@lang('site.actions')";
        language['total'] = "@lang('site.total')";
        language['document'] = "@lang('site.document')";
        language['payment_document_overflow_error'] = "@lang('site.payment_document_overflow_error')";
        language['available_payment'] = "@lang('site.available-payment-value')";

        let validationMessages = [];
        validationMessages['client_type'] = "@lang('site.validate_client_type_message')";
        validationMessages['client_name'] = "@lang('site.validate_client_name_message')";
        validationMessages['client_id'] = "@lang('site.validate_client_id_message')";
        validationMessages['PO_id'] = "@lang('site.please') @lang('site.capital_select') @lang('site.purchaseorder')";
        validationMessages['document_id'] = "@lang('site.please') @lang('site.capital_select') @lang('site.document')";
        validationMessages['bank_id'] = "@lang('site.validate_bank_id_message')";
        validationMessages['payment_method'] = "@lang('site.check_one_payment_method')";
        validationMessages['payment_date'] = "@lang('site.check_one_payment_date')";

        validationMessages['deduction'] = "@lang('site.validate_deduction')";
        validationMessages['value'] = "@lang('site.validate_value')";
    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('payment/15_12_V1_js/payment-multi_document.js') }}"></script>
    <!-- purchaseorder_id[] -->

    {{-- Client section --}}
    <script>
        // Client Type
        select2Function($('#client_type'), "@lang('site.client_type')");
        // Client Name
        select2Function($('#foreigner-client'), "@lang('site.client_name')");
        select2Function($('#client_name'), "@lang('site.client_name')");
        // Purchase Order
        select2Function($('.purchaseorder_id'), "@lang('site.capital_select') @lang('site.purchaseorder')");
        // Document
        select2Function($('.document_id'), "@lang('site.capital_select') @lang('site.document')");

        $('[name="deduction"]').select2({
            placeholder: language['deduction_id'],
        });

        function select2Function(selector, placeholder) {
            const targetSelector = $(selector).parent().parent().next().find('select');
            $(selector).select2();
            $(selector).select2({
                allowClear: true,
                placeholder: placeholder,
            });
        }
    </script>

    <script>
        documentPage = true;

        // change label for input file
        $('input[type=file]').on('change', function() {
            $(this).next().text($(this).val());
        })

        $('#bank_transfer_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').removeClass('d-none');
                $('#bank_container').removeClass('d-none');
                $('#cheque_container').addClass('d-none');
                $('#bank_transfer_container input').attr('disabled', false);
                $('#bank_container input').attr('disabled', false);
            }
        });

        $('#cheque_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').addClass('d-none');
                $('#cheque_container').removeClass('d-none');
                $('#cheque_container input').attr('disabled', false)
                $('#bank_container').removeClass('d-none');
                $('#bank_container input').attr('disabled', false);
            }
        });

        $('#cash_check_id').change(function() {
            resetData();
            $('#bank_transfer_container').addClass('d-none');
            $('#cheque_container').addClass('d-none');
            $('#bank_container').addClass('d-none');
        });


        function resetData() {
            if ($('#bank_id-error'))
                $('#bank_id-error').remove();

            // Bank reset
            $('#bank_code').val('');
            $('#bank_name').val('');
            $('#bank_account_number').val('');
            $('#bank_currency').val('');
            $('#bank_id').val('');
            $('#cheque_container input').attr('disabled', true).val('');
            $('#bank_transfer_container input').attr('disabled', true).val('');
            $('#bank_container input').attr('disabled', true).val('');

            // Reset errors
            $('#cheque_container .error').removeClass('error');
            // $('#bank_transfer_container .error').removeClass('error');
            $('#bank_container .error').removeClass('error');

            // Reset Bank Transfer
            $('input[name="bank_transfer_bank_name"]').val('').attr('readonly', false);
            $('input[name="bank_transfer_received_date"]').val('').attr('readonly', false);
            $('input[name="bank_transfer_issue_date"]').val('').attr('readonly', false);
            $('input[name="bank_transfer_deposit_date"]').val('').attr('readonly', false);
            $('input[name="bank_transfer_collect_date"]').val('').attr('readonly', false);
            // $('#bankTransfer_id').val('').trigger('change');

            // Reset Cheque
            $('input[name="cheque_bank_name"]').val('').attr('readonly', false);
            $('input[name="cheque_number"]').val('').attr('readonly', false);
            $('input[name="cheque_received_date"]').val('').attr('readonly', false);
            $('input[name="cheque_issue_date"]').val('').attr('readonly', false);
            $('input[name="cheque_deposit_date"]').val('').attr('readonly', false);
            $('input[name="cheque_collect_date"]').val('').attr('readonly', false);
            // $('#cheque_id').val('').trigger('change');
        }
        ajaxURL = "{{ route('payment.document.store') }}";
        ajaxMethod = 'post';

        $('[name="payment_method"]').on('change', function() {
            if ($('#cash_check_id').prop("checked") || $('#cheque_check_id').prop("checked") || $(
                    '#bank_transfer_check_id').prop("checked")) {
                $('#payment_option_error').addClass('d-none')
            }
        })

        $('[href="#next"]').on('click', function() {
            if ($('fieldset.current').hasClass('payment')) {
                if ($('#payment_method-error').length == 0) {
                    $('#payment_option_error').addClass('d-none');
                } else {
                    $('#payment_option_error').removeClass('d-none');
                }
            }
        })



    </script>
       <script>

        // $("#commentForm").validate();

        $(document).ready(function () {
            $('#offer_note').on("change", function () {
                var pro_id = $("#offer_note").val();

                if (pro_id == 'offer_note') {
                    $("#offer_note_attachment_div").removeAttr('hidden');
                    $(".offer_note_attachment").attr('required', true);
                    $('#offer_note_new').hide();
                } else {
                    $("#offer_note_attachment_div").attr('hidden', true);
                    $(".offer_note_attachment").attr('required', false);
                    $(".offer_note_attachment").val("");
                    $('#offer_note_new').show();
                }
            });

        });
    </script>
@endsection
