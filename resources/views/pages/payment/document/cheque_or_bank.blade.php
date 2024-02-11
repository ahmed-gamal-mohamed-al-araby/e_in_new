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
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.add') @lang('site._payment') @lang('site.document') </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.add') @lang('site._payment') @lang('site.document')
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.add') @lang('site._payment') @lang('site.document') </h2>
            <form method="POST" id="PaymentForm" class="PoForm" action="{{ route('purchaseorders.store') }}"
                enctype="multipart/form-data">
                @csrf

                <h1 class="d-none text-center mt-2 mb-lg-n4 mb-n2 text-direction-arabic">
                    {{ __('site.available-payment-value') }}<span class="badge badge-success mx-2"
                        id="available-payment-value"></span>
                </h1>

                {{-- document details --}}

                <h3>
                    <span class="title_text">@lang('site.document')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content px-3">
                        <div class="card">

                            <h5 class="card-header bg-success">
                                @lang('site.document')
                            </h5>

                            <div class="card-body">

                                {{-- Client section --}}
                                <div class="row mb-3">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require">
                                                <option selected disabled>@lang('site.select') @lang('site.client_type')
                                                </option>
                                                <option value="b" data-label="@lang('site.tax_id_number_only')"
                                                    data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" data-label="@lang('site.national_id')"
                                                    data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f">@lang('site.foreigner_client')</option>
                                            </select>
                                        </div>
                                        @error('client_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Purchase Order Client id --}}
                                    <div class="col-md-8">
                                        <div class="select-foreigner-client d-none">
                                            <div class="row mb-3">
                                                <div class="col-md-8 input-group mb-3">
                                                    <label class="form-label d-block w-100">@lang('site.client')</label>
                                                    <select id='foreigner-client' class="form-control require" disabled>
                                                        <option selected disabled>@lang('site.select')
                                                            @lang('site.client_type')
                                                        </option>
                                                    </select>
                                                </div>

                                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                            </div>
                                        </div>
                                        @error('foreigner-client')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <div class="card-body p-0 client-details d-none">
                                            <div class="row mb-3">
                                                <div class="col-md-10 no-gutters">
                                                    {{-- tax_id_number for business client Or national ID person client --}}
                                                    <div class="row mb-1 no-gutters">
                                                        <div class="col-md-11">
                                                            <label for="tax_id_number_or_national_id"
                                                                class="form-label w-100"></label>
                                                          <!--
                                                            <input type="text" class="form control"
                                                                id="tax_id_number_or_national_id" />
                                                                -->
                                                                <select id='tax_id_number_or_national_id'
                                                                 name="" class="form-control require"
                                                                >
                                                            </select>
                                                        </div>

                                                        <div class="col-md-1 bank-spinner" style="padding:32px 0 0 10px">
                                                            <div class="search-bank spinner-border spinner-border-sm text-success"
                                                                role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--
                                                {{-- name --}}
                                                <div class="col-md-3">
                                                    <label for="name" class="form-label w-100"
                                                        id="min_payment_label">@lang('site.name') </label>
                                                    <input type="text" id="client_name" class="display" readonly>
                                                </div>

                                                {{-- address --}}
                                                <div class="col-md-4">
                                                    <label for="address" class="form-label w-100"
                                                        id="payment_label">@lang('site.address')</label>
                                                    <input type="text" id="client_address" class="display" readonly>
                                                </div>
                                                -->

                                                {{-- client_id --}}
                                                <div class="col-md-2" id="client-id-container">
                                                    <label for="address" class="form-label">@lang('site.id')</label>
                                                    <input type="text" name="client_id" id="client_id" readonly>
                                                </div>

                                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                                            </div> <!-- End Of First Row-->

                                        </div> <!-- End Of Card Body-->
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    {{-- Purchase Order type --}}
                                    <div class="col-md-4 d-none" id="purchaseorder_id_container">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.purchaseorder')</label>
                                            <select name="purchaseorder_id" id="select_purchase_order"
                                                class="form-control require">
                                                <option selected disabled>@lang('site.select')
                                                    @lang('site.purchaseorder')
                                                </option>
                                                {{-- Here is client Purchaseorders --}}
                                            </select>
                                        </div>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Document --}}
                                    <div class="col-md-4 d-none" id="document_id_container">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.document')</label>
                                            <select name="document_id" id="select_document" class="form-control require">
                                                <option selected disabled>@lang('site.select')
                                                    @lang('site.document')
                                                </option>
                                                {{-- Here is client Purchaseorders --}}
                                            </select>
                                        </div>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 no_documents d-none row align-content-center">
                                        <div class="text-danger">@lang('site.no_documents')</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_1_2')</span>
                    </div>
                </fieldset>

                {{-- Payment Details --}}
                <h3>
                    <span class="title_text"> @lang('site.payment_details')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content">
                        <div class="card ml-2">

                            <h5 class="card-header bg-success">
                                @lang('site.delivery_details')
                            </h5>

                            <div class="card-body">
                                <div class="row row-page supplier-accepted justify-content-between">
                                    {{-- Payment method --}}
                                    <h5 class="col-12 mb-2" style="color:#6c757d!important">
                                        @lang('site.check_available_payment_method')</h5>

                                    <div class="col-12 col-md-6 row">
                                        {{-- Check box options for payment method --}}
                                        <div class="form-check">
                                            {{-- Cashe option --}}
                                            <input class="form-check-input ml-2" type="radio" name="payment_method"
                                                id="cash_check_id" value="cashe">
                                            <label class="form-check-label" for="cash_check_id">
                                                @lang('site.cash')
                                            </label>
                                        </div>
                                        {{-- Cashe cheque option --}}
                                        <div class="form-check">
                                            <input class="form-check-input ml-2" type="radio" name="payment_method"
                                                id="cheque_check_id" value="cheque">
                                            <label class="form-check-label" for="cheque_check_id">
                                                @lang('site.cheque')
                                            </label>
                                        </div>
                                        {{-- Cashe bank_transfer option --}}
                                        <div class="form-check disabled">
                                            <input class="form-check-input ml-4" type="radio" name="payment_method"
                                                id="bank_transfer_check_id" value="bank_transfer">
                                            <label class="form-check-label" for="bank_transfer_check_id">
                                                @lang('site.bank_transfer')
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Cheques --}}
                                    <div class="col-12 col-md-6 input-group select d-none">
                                        {{-- <label>@lang('site.cheque')</label> --}}
                                        <select name="cheque_id" id="cheque_id" class="currenty-type-select">
                                            <option disabled selected>@lang('site.capital_select') @lang('site.cheque')
                                            </option>
                                            @foreach ($cheques as $cheque)
                                                <option value="{{ $cheque->id }}">
                                                    {{ $cheque->bank_name . '_' . $cheque->cheque_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- BankTransfer --}}
                                    <div class="col-12 col-md-6 input-group select d-none">
                                        {{-- <label>@lang('site.bank_transfer')</label> --}}
                                        <select name="bankTransfer_id" id="bankTransfer_id" class="currenty-type-select">
                                            <option disabled selected>@lang('site.capital_select')
                                                @lang('site.bank_transfer')
                                            </option>
                                            @foreach ($bankTransfers as $bankTransfer)
                                                <option value="{{ $bankTransfer->id }}">
                                                    {{ $bankTransfer->bank_name . '_' . $bankTransfer->received_date }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="text-danger d-none ml-2" id="payment_option_error">
                                        @lang('site.check_atleast_one_payment_method')</div>

                                    <div class="col-12"></div>

                                    {{-- file --}}
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.file')</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file">
                                            <label class="custom-file-label m-0" style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                            for="file">@lang('site.choose') @lang('site.file')</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row row-page supplier-accepted">

                                    {{-- total_money --}}
                                    <div class=" col-md-6 my-2">
                                        <label for="type" class="form-label">@lang('site.amount')</label>
                                        <input type="number" name="total_money" class="form-control" required
                                            placeholder="@lang('site.enter') @lang('site.amount')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.amount')')"
                                            oninput="setCustomValidity('')">

                                        <p class="text-danger text-bold text-center d-none"
                                            id="validate-payment_purchase_order-overflow">
                                            @lang('site.payment_purchase_order_overflow_error')</p>
                                        <p class="text-danger text-bold text-center d-none"
                                            id="validate-payment_document-overflow">
                                            @lang('site.payment_document_overflow_error')</p>

                                        @error('total_money')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Payment date --}}
                                    <div class="col-md-6 my-2">
                                        <label for="type" class="form-label">@lang('site.payment_date')</label>
                                        <input type="date" name="payment_date" required class="d-block"
                                            onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                    </div>

                                    <hr style="flex: 0 0 100%;">

                                    {{-- Bank_transfer --}}
                                    <div class="col-12 row d-none" id="bank_transfer_container">
                                        {{-- Bank name --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.bank_name')</label>z
                                            <input type="text" name="bank_transfer_bank_name" class="form-control" required
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
                                            <label for="type" class="form-label">@lang('site.received_date')</label>
                                            <input type="date" name="bank_transfer_received_date"
                                                id="bank_transfer_received_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- issue date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.issue_date')</label>
                                            <input type="date" name="bank_transfer_issue_date" id="bank_transfer_issue_date"
                                                required class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- deposit date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.deposit_date')</label>
                                            <input type="date" name="bank_transfer_deposit_date"
                                                id="bank_transfer_deposit_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- collect date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.collect_date')</label>
                                            <input type="date" name="bank_transfer_collect_date"
                                                id="bank_transfer_collect_date" required class="d-block"
                                                onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                        </div>
                                    </div>

                                    {{-- Cheque --}}
                                    <div class="col-12 row d-none" id="cheque_container">
                                        {{-- Bank name --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.bank_name')</label>
                                            <input type="text" name="cheque_bank_name" class="form-control" required
                                                placeholder="@lang('site.enter') @lang('site.bank_name')"
                                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_name')')"
                                                oninput="setCustomValidity('')">
                                            @error('cheque_bank_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- cheque number --}}
                                        <div class=" col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.cheque_number')</label>
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
                                            <label for="type" class="form-label">@lang('site.received_date')</label>
                                            <input type="date" name="cheque_received_date" id="cheque_received_date"
                                                required class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- issue date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.issue_date')</label>
                                            <input type="date" name="cheque_issue_date" id="cheque_issue_date" required
                                                class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- deposit date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.deposit_date')</label>
                                            <input type="date" name="cheque_deposit_date" id="cheque_deposit_date" required
                                                class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        {{-- collect date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">@lang('site.collect_date')</label>
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
                                                    <label for="bank_code" class="form-label w-100"
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
                                            <label for="bank_name" class="form-label w-100"
                                                id="min_payment_label">@lang('site.bank_name') </label>

                                            <input type="text" id="bank_name" class="display" readonly>


                                        </div>

                                        {{-- bank_account_number --}}
                                        <div class="col-md-3">
                                            <label for="bank_account_number" class="form-label w-100"
                                                id="payment_label">@lang('site.bank_account_number')</label>
                                            <input type="text" id="bank_account_number" class="display" readonly>
                                        </div>

                                        {{-- bank currency --}}
                                        <div class="col-md-2">
                                            <label for="bank_currency"
                                                class="min_payment_label">@lang('site.currency')</label>
                                            <input type="text" id="bank_currency" class="display" readonly>
                                        </div>

                                        {{-- Bank id --}}
                                        <div class="col-md-1">
                                            <label for="address" class="form-label w-100">@lang('site.id')</label>
                                            <input type="text" name="bank_id" value="" id="bank_id" readonly>
                                        </div>
                                    </div> <!-- End Of First Row-->

                                </div>
                            </div> <!-- End Of Card Body-->

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_2_2')</span>
                    </div>
                </fieldset>

            </form>
        </div>
    </section>

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
        language['select_client_type'] =
            `<option selected disabled>@lang('site.select') @lang('site.client_type')</option>`;
        language['select_document'] = `<option selected disabled>@lang('site.select') @lang('site.document')</option>`;
        language['client_PO_empty'] = "@lang('site.purchaseOrder_of_client_empty')";
        language['no_data'] = "@lang('site.no_data')";
        language['cheque_id'] =
            "@lang('site.capital_select') @lang('site.cheque')";
        language['bankTransfer_id'] =
            "@lang('site.capital_select') @lang('site.bank_transfer')";

        let validationMessages = [];
        validationMessages['client_type'] = "@lang('site.validate_client_type_message')";
        validationMessages['client_id'] = "@lang('site.validate_client_id_message')";
        validationMessages['PO_id'] = "@lang('site.please') @lang('site.select') @lang('site.purchaseorder')";
        validationMessages['document_id'] = "@lang('site.please') @lang('site.select') @lang('site.document')";
        validationMessages['bank_id'] = "@lang('site.validate_bank_id_message')";
        validationMessages['deduction_counter'] = "@lang('site.validate_deductions_counter_message')";
        validationMessages['payment_method'] = "@lang('site.check_one_payment_method')";
        validationMessages['payment_date'] = "@lang('site.check_one_payment_date')";

        validationMessages['deduction'] = "@lang('site.validate_deduction')";
        validationMessages['value'] = "@lang('site.validate_value')";

        let cheques = {!! json_encode($cheques) !!},
            bankTransfers = {!! json_encode($bankTransfers) !!};
    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('payment/21_11_v2_js/payment.js') }}"></script>

    <script>

        // change label for input file
        $('input[type=file]').on('change', function() {
            $(this).next().text($(this).val());
        })

        documentPage = true;

        $('[name="cheque_id"]').select2({
            placeholder: language['cheque_id'],
        });


        $('[name="bankTransfer_id"]').select2({
            placeholder: language['bankTransfer_id'],
        });

        $('#bank_transfer_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').removeClass('d-none');
                $('#bank_container').removeClass('d-none');
                $('#cheque_container').addClass('d-none');
                $('#bank_transfer_container input').attr('disabled', false);
                $('#bank_container input').attr('disabled', false);
                $('#cheque_id').parent().addClass('d-none');
                $('#bankTransfer_id').parent().removeClass('d-none');
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
                $('#cheque_id').parent().removeClass('d-none');
                $('#bankTransfer_id').parent().addClass('d-none');
            }
        });

        $('#cash_check_id').change(function() {
            resetData();
            $('#bank_transfer_container').addClass('d-none');
            $('#cheque_container').addClass('d-none');
            $('#bank_container').addClass('d-none');
        });

        $('#cheque_id').on('change', function() {
            const cheque = cheques.find(cheque => cheque.id == $(this).val());
            if (cheque) {
                $('input[name="cheque_bank_name"]').val(cheque.bank_name).attr('readonly', true);
                $('input[name="cheque_number"]').val(cheque.cheque_number).attr('readonly', true);
                $('input[name="cheque_received_date"]').val(cheque.received_date).attr('readonly', true);
                $('input[name="cheque_issue_date"]').val(cheque.issue_date).attr('readonly', true);
                $('input[name="cheque_deposit_date"]').val(cheque.deposit_date).attr('readonly', true);
                $('input[name="cheque_collect_date"]').val(cheque.collect_date).attr('readonly', true);
                $('#cheque_container .error').removeClass('error');
            }
        });

        $('#bankTransfer_id').on('change', function() {
            const bankTransfer = bankTransfers.find(bankTransfer => bankTransfer.id == $(this).val());
            if (bankTransfer) {
                $('input[name="bank_transfer_bank_name"]').val(bankTransfer.bank_name).attr('readonly', true);
                $('input[name="bank_transfer_received_date"]').val(bankTransfer.received_date).attr('readonly',
                    true);
                $('input[name="bank_transfer_issue_date"]').val(bankTransfer.issue_date).attr('readonly', true);
                $('input[name="bank_transfer_deposit_date"]').val(bankTransfer.deposit_date).attr('readonly', true);
                $('input[name="bank_transfer_collect_date"]').val(bankTransfer.collect_date).attr('readonly', true);
                $('#bank_transfer_container .error').removeClass('error');
            }
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
            $('#bankTransfer_id').val('').trigger('change');

            // Reset Cheque
            $('input[name="cheque_bank_name"]').val('').attr('readonly', false);
            $('input[name="cheque_number"]').val('').attr('readonly', false);
            $('input[name="cheque_received_date"]').val('').attr('readonly', false);
            $('input[name="cheque_issue_date"]').val('').attr('readonly', false);
            $('input[name="cheque_deposit_date"]').val('').attr('readonly', false);
            $('input[name="cheque_collect_date"]').val('').attr('readonly', false);
            $('#cheque_id').val('').trigger('change');
        }
        ajaxURL = "{{ route('payment.document.cashe_cheque_bankTransfer.store') }}";
        ajaxMethod = 'post';
        select2Function($('#tax_id_number_or_national_id'), "Choose");
        function select2Function(selector, placeholder) {
            const targetSelector = $(selector).parent().parent().next().find('select');
            $(selector).select2();
            $(selector).select2({
                allowClear: true,
                placeholder: placeholder,
            });
        }
    </script>
@endsection
