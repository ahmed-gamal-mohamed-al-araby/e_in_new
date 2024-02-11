@extends('pages.layouts.master')

@section('title')
    @lang('site._payment') {{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }}
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
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
                    <h1>@lang('site.edit') @lang('site._payment')
                        {{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }}</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.edit') @lang('site._payment')
                            {{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }} </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.edit') @lang('site._payment')
                {{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }}</h2>
            <form method="POST" id="PaymentForm" class="PoForm" action="{{ route('purchaseorders.store') }}"
                enctype="multipart/form-data">
                @csrf

                <h1 class="text-center mt-2 mb-lg-n4 mb-n2 text-direction-arabic">
                    {{ __('site.available-payment-value') }}<span class="badge badge-success mx-2"
                        id="available-payment-value">{{ number_format($totalAmount - $totalPayments, 8) }}</span>
                </h1>

                {{-- purchaseorder details --}}
                <h3>
                    <span
                        class="title_text">{{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }}</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content px-3">
                        <div class="card">

                            <h5 class="card-header bg-success">
                                {{ $payment->table == 'D' ? __('site.document') : __('site.purchaseorder') }}
                            </h5>

                            <div class="card-body">

                                {{-- Client section --}}
                                <div class="row mb-3">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require">
                                                <option disabled>@lang('site.select') @lang('site.client_type')
                                                </option>
                                                <option value="b" {{ $payment->client_type == 'b' ? 'selected' : '' }}
                                                    data-label="@lang('site.tax_id_number_only')"
                                                    data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" {{ $payment->client_type == 'p' ? 'selected' : '' }}
                                                    data-label="@lang('site.national_id')"
                                                    data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f" {{ $payment->client_type == 'f' ? 'selected' : '' }}>
                                                    @lang('site.foreigner_client')</option>
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
                                                <div class="col-md-3 no-gutters">
                                                    {{-- tax_id_number for business client Or national ID person client --}}
                                                    <div class="row mb-1 no-gutters">
                                                        <div class="col-md-11">
                                                            <label for="tax_id_number_or_national_id"
                                                                class="form-label w-100"></label>
                                                            <input type="text" class="form control"
                                                                id="tax_id_number_or_national_id" />
                                                        </div>

                                                        <div class="col-md-1 bank-spinner" style="padding:32px 0 0 10px">
                                                            <div class="search-bank spinner-border spinner-border-sm text-success"
                                                                role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- name --}}
                                                <div class="col-md-3">
                                                    <label for="client_name" class="form-label w-100">@lang('site.name')
                                                    </label>
                                                    <input type="text" id="client_name" class="display" readonly>
                                                </div>

                                                {{-- address --}}
                                                <div class="col-md-4">
                                                    <label for="client_address"
                                                        class="form-label w-100">@lang('site.address')</label>
                                                    <input type="text" id="client_address" class="display" readonly>
                                                </div>

                                                {{-- client_id --}}
                                                <div class="col-md-2" id="client-id-container">
                                                    <label for="client_id" class="form-label">@lang('site.id')</label>
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
                                            <label class="form-label d-block w-100">@lang('site.purchaseorder')</label>
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

                                    @if ($payment->table == 'D')
                                        {{-- Document --}}
                                        <div class="col-md-4 d-none" id="document_id_container">
                                            <div class="input-group mb-3">
                                                <label class="form-label d-block w-100">@lang('site.document')</label>
                                                <select name="document_id" id="select_document"
                                                    class="form-control require">
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
                                    @endif

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
                    <span class="title_text">
                        {{ $payment->payment_method == 'deduction' ? __('site.deduction') : __('site.payment_details') }}</span>
                </h3>
                @if ($payment->payment_method == 'deduction')
                    <fieldset class="pl-1 pr-2">
                        <div class="row mb-2 ml-1 mr-2">
                            {{-- Deduction counter --}}
                            <div class="col-md-3">
                                <label for="deduction_counter" class="form-label w-100">@lang('site.deduction_counter')
                                </label>
                                <input type="text" id="deduction_counter" value="{{ count($paymentMethod) }}"
                                    name="deduction_counter" readonly class="mb-2">
                            </div>

                            {{-- Payment date --}}
                            <div class="offset-md-3 col-md-6">
                                <label class="form-label">@lang('site.payment_date')</label>
                                <input type="date" name="payment_date" value="{{ $payment->payment_date }}" required
                                    class="d-block" onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                            </div>
                        </div>
                        <div class="fieldset-content">
                            <div class="card ml-2">
                                <h5 class="card-header bg-success">
                                    @lang('site.delivery_details')
                                </h5>

                                <div class="card-body pt-2">
                                    <div class="row align-items-center">
                                        {{-- add New Deduction --}}
                                        <div class="col-12 text-center items-links mb-2">
                                            <a href="#" data-toggle="modal" data-target="#addline" class="addNewDeduction"
                                                id="_addNewDeductionBtn"><i class="fa fa-plus"></i> @lang('site.add')
                                                @lang('site.deduction')</a>
                                        </div>
                                        {{-- Show deductions --}}
                                        <div class="table-responsive">
                                            {{-- Table for view addded items --}}
                                            <table
                                                class="table table-bordered table-striped table-hover justify-content-center text-center m-0 deductions-table">
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
                                                    @foreach ($paymentMethod as $deduction)
                                                        <tr>
                                                            <th>{{ $loop->iteration }}</th>
                                                            <td>{{ $deductionNames[$loop->index] }}</td>
                                                            <td>{{ number_format($deduction->value, 2) }}</td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger tableItemsBtn deleteDeduction"
                                                                    data-deduction-index="{{ $loop->index }}"><i
                                                                        class="fa fa-trash-alt"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div> <!-- End Of Card Body-->

                                {{-- Deduction total --}}
                                <div class="row justify-content-end">
                                    <div class="col-3 mb-2 p-0 mr-3">
                                        <div class="summery">
                                            <label class="mb-1">@lang('site.deduction_total')</label>
                                            <input type="text" value="{{ number_format($payment->value, 2) }}" readonly
                                                id="deduction-total">
                                            <input type="hidden" name="total_money">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fieldset-footer">
                            <span>@lang('site.step_2_2')</span>
                        </div>
                    </fieldset>
                @else
                    <fieldset>
                        <div class="fieldset-content">
                            <div class="card ml-2">

                                <h5 class="card-header bg-success">
                                    @lang('site.delivery_details')
                                </h5>

                                <div class="card-body">
                                    <div class="row row-page supplier-accepted">
                                        {{-- Payment method --}}
                                        <h5 class="col-12 mb-2" style="color:#6c757d!important">
                                            @lang('site.check_available_payment_method')</h5>

                                        {{-- Check box options for payment method --}}

                                        <div class="form-check">
                                            {{-- Cashe option --}}
                                            <input class="form-check-input ml-2" type="radio" name="payment_method"
                                                id="cash_check_id" value="cashe"
                                                {{ $payment->payment_method == 'cashe' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cash_check_id">
                                                @lang('site.cash')
                                            </label>
                                        </div>
                                        {{-- Cashe cheque option --}}
                                        <div class="form-check">
                                            <input class="form-check-input ml-2" type="radio" name="payment_method"
                                                id="cheque_check_id" value="cheque"
                                                {{ $payment->payment_method == 'cheque' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cheque_check_id">
                                                @lang('site.cheque')
                                            </label>
                                        </div>
                                        {{-- Cashe bank_transfer option --}}
                                        <div class="form-check disabled">
                                            <input class="form-check-input ml-4" type="radio" name="payment_method"
                                                id="bank_transfer_check_id" value="bank_transfer"
                                                {{ $payment->payment_method == 'bank_transfer' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="bank_transfer_check_id">
                                                @lang('site.bank_transfer')
                                            </label>
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

                                        <div class="col-md-6 mt-2" style="padding-top: 1.7rem">
                                            @if ($payment->file && file_exists(public_path('payment/files/' . $payment->file)))
                                                <a class="btn btn-success" style="font-size: 12px"
                                                href='{{ asset("payment/files/$payment->file") }}'
                                                target="_blank">@lang('site.show') @lang('site.file')</a>
                                            @else
                                                <span class="btn btn-danger" style="cursor: default; font-size: 12px">@lang('site.not_available')</span>
                                            @endif
                                        </div>



                                        {{-- total_money --}}
                                        <div class=" col-md-6 my-2">
                                            <label class="form-label">@lang('site.amount')</label>
                                            <input type="number" name="total_money" value="{{ $payment->value }}"
                                                class="form-control" required
                                                placeholder="@lang('site.enter') @lang('site.amount')"
                                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.amount')')"
                                                oninput="setCustomValidity('')">
                                            @error('total_money')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Payment date --}}
                                        <div class="col-md-6 my-2">
                                            <label class="form-label">@lang('site.payment_date')</label>
                                            <input type="date" name="payment_date" value="{{ $payment->payment_date }}"
                                                required class="d-block" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        <hr style="flex: 0 0 100%;">

                                        {{-- Bank_transfer --}}
                                        <div class="col-12 row d-none" id="bank_transfer_container">
                                            {{-- Bank name --}}
                                            <div class=" col-md-6 mb-3">
                                                <label class="form-label">@lang('site.bank_name')</label>
                                                <input type="text" name="bank_transfer_bank_name" class="form-control"
                                                    required placeholder="@lang('site.enter') @lang('site.bank_name')"
                                                    oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_name')')"
                                                    oninput="setCustomValidity('')">
                                                @error('bank_transfer_bank_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class=" offset-md-6 mb-3"></div>
                                            {{-- received date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.received_date')</label>
                                                <input type="date" name="bank_transfer_received_date"
                                                    id="bank_transfer_received_date" required class="d-block"
                                                    onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- issue date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.issue_date')</label>
                                                <input type="date" name="bank_transfer_issue_date"
                                                    id="bank_transfer_issue_date" required class="d-block"
                                                    onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- deposit date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.deposit_date')</label>
                                                <input type="date" name="bank_transfer_deposit_date"
                                                    id="bank_transfer_deposit_date" required class="d-block"
                                                    onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- collect date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.collect_date')</label>
                                                <input type="date" name="bank_transfer_collect_date"
                                                    id="bank_transfer_collect_date" required class="d-block"
                                                    onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                            </div>
                                        </div>

                                        {{-- Cheque --}}
                                        <div class="col-12 row d-none" id="cheque_container">
                                            {{-- Bank name --}}
                                            <div class=" col-md-6 mb-3">
                                                <label class="form-label">@lang('site.bank_name')</label>
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
                                                <label class="form-label">@lang('site.cheque_number')</label>
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
                                                <label class="form-label">@lang('site.received_date')</label>
                                                <input type="date" name="cheque_received_date" id="cheque_received_date"
                                                    required class="d-block" onfocus="(this.type='date')"
                                                    data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- issue date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.issue_date')</label>
                                                <input type="date" name="cheque_issue_date" id="cheque_issue_date" required
                                                    class="d-block" onfocus="(this.type='date')"
                                                    data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- deposit date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.deposit_date')</label>
                                                <input type="date" name="cheque_deposit_date" id="cheque_deposit_date"
                                                    required class="d-block" onfocus="(this.type='date')"
                                                    data-date-format="DD/MM/YYYY" />
                                            </div>

                                            {{-- collect date --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">@lang('site.collect_date')</label>
                                                <input type="date" name="cheque_collect_date" id="cheque_collect_date"
                                                    required class="d-block" onfocus="(this.type='date')"
                                                    data-date-format="DD/MM/YYYY" />
                                            </div>
                                        </div>

                                        <div class="row mb-3 d-none mx-2" id="bank_container">

                                            {{-- Bank code --}}
                                            <div class="col-md-3 no-gutters">
                                                <div class="row mb-1 no-gutters">
                                                    <div class="col-md-11">
                                                        <label for="bank_code"
                                                            class="form-label w-100">@lang('site.bank_code')</label>
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
                                                <label for="bank_name" class="form-label w-100">@lang('site.bank_name')
                                                </label>

                                                <input type="text" id="bank_name" class="display" readonly>


                                            </div>

                                            {{-- bank_account_number --}}
                                            <div class="col-md-3">
                                                <label for="bank_account_number"
                                                    class="form-label w-100">@lang('site.bank_account_number')</label>
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
                                                <label for="bank_id" class="form-label w-100">@lang('site.id')</label>
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
                @endif
            </form>
        </div>
    </section>


    @if ($payment->payment_method == 'deduction')
        {{-- modal add Deduction --}}
        <div class="modal fade" id="addline" data-check-data="null" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">@lang('site.add') @lang('site.deduction')</h6>
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
                                        <option disabled selected>@lang('site.capital_select') @lang('site.deduction')
                                        </option>
                                        @foreach ($basicDeductions as $deduction)
                                            <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- value --}}
                                <div class="col-6 mb-2">
                                    <label>@lang('site.value')</label>
                                    <div class="input-group select">
                                        <input type="number" name="value" id="deduction_value"
                                            placeholder="@lang('site.value')" class="input-group-item" />
                                    </div>
                                    <p class="text-danger text-bold text-center d-none" id="validate-payment_purchase_order-overflow">@lang('site.payment_purchase_order_overflow_error')</p>
                                <p class="text-danger text-bold text-center d-none" id="validate-payment_document-overflow">@lang('site.payment_document_overflow_error')</p>

                                    <p class="text-center text-bold d-none quantity_edit_error"
                                        style="font-size: 11px; color: red">
                                        @lang('site.quantity_edit_error')</p>
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
    @endif

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
        let totalAmount = {{ $totalAmount }},
            totalPayments = {{ $totalPayments }};
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
        language['client_PO_empty'] = "@lang('site.purchaseOrder_of_client_empty')";
        language['no_data'] = "@lang('site.no_data')";

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

    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('payment/25_07_V3_js/payment.js') }}"></script>

    <script>
        // change label for input file
        $('input[type=file]').on('change', function() {
            $(this).next().text($(this).val());
        })

        editMode = true;
        const clientId = "{{ $client->id }}";
        let URLLastPart = null,
            ajaxMethodPO = null;

        $('.search-bank.spinner-border').hide();
        $('.search-product.spinner-border').hide();

        $('#bank_transfer_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').removeClass('d-none');
                $('#bank_container').removeClass('d-none');
                $('#cheque_container').addClass('d-none');
                $('#bank_transfer_container input').attr('disabled', false);
                $('#bank_container input').attr('disabled', false)
            }
        });

        $('#cheque_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').addClass('d-none');
                $('#cheque_container').removeClass('d-none');
                $('#cheque_container input').attr('disabled', false)
                $('#bank_container').removeClass('d-none');
                $('#bank_container input').attr('disabled', false)
            }
        });

        $('#cash_check_id').change(function() {
            resetData();
            $('#bank_transfer_container').addClass('d-none');
            $('#cheque_container').addClass('d-none');
            $('#bank_container').addClass('d-none');
        });

        function resetData() {
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
        }
        @if ($payment->table == 'D')
            documentPage = true;
        @endif

        $('#client_type').on('change', function() {
            $('#document_id_container').addClass('d-none');
            $('#select_document :not(:first)').remove();
        })

        $('#tax_id_number_or_national_id').on('change', function() {
            $('#document_id_container').addClass('d-none');
            $('#select_document :not(:first)').remove();
        })

        $('#foreigner-client').on('change', function() {
            $('#document_id_container').addClass('d-none');
            $('#select_document :not(:first)').remove();
        })

        $('#client_type').trigger('change');
        @if ($payment->client_type != 'f')
            $('#tax_id_number_or_national_id').val("{{ $payment->client_type == 'b' ? $client->tax_id_number : $client->national_id }}")
        @endif

        // Get data from Client reference
        $('#client_id').val('');
        let clientType = $('#client_type').val(),
            searchContent = $('#tax_id_number_or_national_id').val().trim(),
            valid = false,
            sendData = {
                clientType: clientType,
                searchContent: searchContent,
            };

        const validateError = $('#client_type').find('option:selected').data('validate'),
            taxIdNumberRegex = /^[\d]{3}-[\d]{3}-[\d]{3}$/,
            //nationalIdRegex = /^(2|3)[0-9][1-9][0-1][1-9][0-3][1-9](01|02|03|04|11|12|13|14|15|16|17|18|19|21|22|23|24|25|26|27|28|29|31|32|33|34|35|88)\d\d\d\d\d$/;
            nationalIdRegex = /^[0-9]{14}$/;
        if (clientType == 'b') { // Validate Tax Id Number
            if (taxIdNumberRegex.test(searchContent)) { // valid
                $('.client-details .text-danger').addClass('d-none')
                valid = true;
                URLLastPart = 'getDocumentBusinessOrPersonClientData';
                ajaxMethodPO = 'post';
            } else {
                $('.client-details .text-danger').removeClass('d-none').text(validateError);

            }
        } else if (clientType == 'p') { // Validate National Id Number
            if (nationalIdRegex.test(searchContent)) { // valid
                $('.client-details .text-danger').addClass('d-none');
                valid = true;
                URLLastPart = 'getDocumentBusinessOrPersonClientData';
                ajaxMethodPO = 'post';
            } else {
                $('.client-details .text-danger').removeClass('d-none').text(validateError);
                $('#client_name').text('');
                $('#client_address').text('');
            }
        } else {
            valid = true;
            URLLastPart = `getDocumentForeignerPurchaseOrder/${clientId}`;
            ajaxMethodPO = 'get';
        }

        if (valid) { // If Valid for
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.search-bank.spinner-border').show();
            $.ajax({
                type: ajaxMethodPO,
                url: `${subFolderURL}/${urlLang}/clients/${URLLastPart}`,
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(client) {
                    $('.search-bank.spinner-border').hide();
                    // reset items
                    items = [];
                    $('#items').val(''); // number of addeditem
                    $('#invoice-discount').val('');
                    $('#invoice-total').val('');
                    $(".tableForItems tbody").html(''); // clear item in table
                    $('#select_purchase_order').prop('selectedIndex', 0);
                    if (client && ajaxMethodPO == 'post') {
                        $('#client_id').val(client.basic.id);
                        $('#client_id').trigger('keyup');
                        $('#client_name').val(client.basic.name);
                        $('#client_address').val(client.basic.address);
                        $('#select_purchase_order :not(:first)').remove();

                        if (client.purchaseOrders.length != 0) {
                            $('#purchaseorder_id_container').removeClass('d-none');
                            $('#client_id').val(client.basic.id);
                            $('#client_id').trigger('keyup');
                            // $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger('change');
                            for (let i = 0; i < client.purchaseOrders.length; i++) {
                                $('#select_purchase_order').append(
                                    `<option value="${client.purchaseOrders[i].id}">${client.purchaseOrders[i].purchase_order_reference}</option>`
                                );
                            }
                        } else {
                            $('#client_id').val('').parent().next().removeClass('d-none').text(language[
                                'client_PO_empty']);
                        }

                    } else if (client && ajaxMethodPO == 'get') {
                        if (client != 0) {
                            $('#purchaseorder_id_container').removeClass('d-none');
                            $('#client_id').val(clientId);
                            $('#client_id').trigger('keyup');
                            // $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger('change');
                            for (let i = 0; i < client.length; i++) {
                                $('#select_purchase_order').append(
                                    `<option value="${client[i].id}">${client[i].purchase_order_reference}</option>`
                                );
                            }
                        } else {
                            $('#client_id').val('').parent().next().removeClass('d-none').text(language[
                                'client_PO_empty']);
                        }

                    } else {
                        $('.client-details .text-danger').removeClass('d-none').text(language['no_data']);
                        $('#client_name').val('');
                        $('#client_address').val('');
                        $('.search-bank.spinner-border').hide();
                    }
                    // Set purchase Order
                    const POId = "{{ $purchaseOrderID }}";
                    $(`#select_purchase_order option[value="${POId}"]`).attr('selected', true);
                    if (documentPage) {
                        const documentId = "{{ $documentId }}";

                        const data = {
                            'id': $('#select_purchase_order').val(),
                        }

                        $('#document_id_container').addClass('d-none');

                        const targetSelector = $('#purchaseorder_id_container');
                        targetSelector.removeClass('d-none');
                        $.ajax({
                            url: `${subFolderURL}/${urlLang}/getDocumentFromPurchaseOrderByPOID`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: JSON.stringify(data),
                            contentType: 'application/json; charset=utf-8',
                            dataType: 'json',
                            success: function(PO_documents) {
                                if (PO_documents.length > 0) {
                                    $('#document_id_container').removeClass('d-none');
                                    $('#select_document :not(:first)').remove();
                                    for (let i = 0; i < PO_documents.length; i++) {
                                        $('#select_document').append(
                                            `<option value="${PO_documents[i].id}">${PO_documents[i].document_number}</option>`
                                        );
                                    }
                                    // Set Document
                                    $(`#select_document option[value="${documentId}"]`).attr(
                                        'selected', true);
                                }
                            }
                        });
                    }
                }
            });
        }

        $('#bank_transfer_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').removeClass('d-none');
                $('#bank_container').removeClass('d-none');
                $('#cheque_container').addClass('d-none');
                $('#bank_transfer_container input').attr('disabled', false);
                $('#bank_container input').attr('disabled', false)
            }
        });

        $('#cheque_check_id').change(function() {
            if (this.checked) {
                resetData();
                $('#bank_transfer_container').addClass('d-none');
                $('#cheque_container').removeClass('d-none');
                $('#cheque_container input').attr('disabled', false)
                $('#bank_container').removeClass('d-none');
                $('#bank_container input').attr('disabled', false)
            }
        });

        $('#cash_check_id').change(function() {
            resetData();
            $('#bank_transfer_container').addClass('d-none');
            $('#cheque_container').addClass('d-none');
            $('#bank_container').addClass('d-none');
        });

        function resetData() {
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
        }

        @if ($payment->payment_method == 'cheque' || $payment->payment_method == 'bank_transfer')
            function SetBankData() {
            @if (isset($paymentMethod->bank->bank_code))
            $('#bank_code').val('{{ $paymentMethod->bank->bank_code }}');
            $('#bank_name').val('{{ $paymentMethod->bank->bank_name }}');
            $('#bank_account_number').val('{{ $paymentMethod->bank->bank_account_number }}');
            $('#bank_currency').val('{{ $paymentMethod->bank->currency }}');
            $('#bank_id').val('{{ $paymentMethod->bank_id }}');
            @else
             $('#bank_code').val('');
            $('#bank_name').val('');
            $('#bank_account_number').val('');
            $('#bank_currency').val('');
            $('#bank_id').val('');
            @endif
            }
        @endif

        // Set payment data
        @if ($payment->payment_method == 'cashe')
            $('#cash_check_id').trigger('change');
        @elseif($payment->payment_method == 'cheque'){
            $('#cheque_check_id').trigger('change');
            $('input[name="cheque_bank_name"]').val('{{ $paymentMethod->bank_name }}');
            $('input[name="cheque_number"]').val('{{ $paymentMethod->cheque_number }}');
            $('input[name="cheque_received_date"]').val('{{ $paymentMethod->received_date }}');
            $('input[name="cheque_issue_date"]').val('{{ $paymentMethod->issue_date }}');
            $('input[name="cheque_deposit_date"]').val('{{ $paymentMethod->deposit_date }}');
            $('input[name="cheque_collect_date"]').val('{{ $paymentMethod->collect_date }}');
            SetBankData();
            }
        @elseif($payment->payment_method == 'bank_transfer' && isset($paymentMethod->bank_name)) {
            $('#bank_transfer_check_id').trigger('change');
            $('input[name="bank_transfer_bank_name"]').val('{{ $paymentMethod->bank_name }}');
            $('input[name="bank_transfer_received_date"]').val('{{ $paymentMethod->received_date }}');
            $('input[name="bank_transfer_issue_date"]').val('{{ $paymentMethod->issue_date }}');
            $('input[name="bank_transfer_deposit_date"]').val('{{ $paymentMethod->deposit_date }}');
            $('input[name="bank_transfer_collect_date"]').val('{{ $paymentMethod->collect_date }}');
            SetBankData();
        }
        @elseif($payment->payment_method == 'bank_transfer' && !isset($paymentMethod->bank_name)) {
            $('#bank_transfer_check_id').trigger('change');
            $('input[name="bank_transfer_bank_name"]').val('');
            $('input[name="bank_transfer_received_date"]').val('');
            $('input[name="bank_transfer_issue_date"]').val('');
            $('input[name="bank_transfer_deposit_date"]').val('');
            $('input[name="bank_transfer_collect_date"]').val('');
            SetBankData();
        }@elseif($payment->payment_method == 'deduction') {
            let _deductions = {!! json_encode($paymentMethod) !!};
            let _deductionNames = {!! json_encode($deductionNames) !!};
            setDeductionsForEdit(_deductions, _deductionNames);
            }
        @endif

        @if($payment->payment_method != 'deduction')
            oldValuesInEditMode = fixedTo20($('input[name="total_money"]').val());
            console.log({oldValuesInEditMode});
        @endif
        paymentId = {!! json_encode($payment->id) !!};
        ajaxURL = "{{ route('payment.update', $payment->id) }}";
        ajaxMethod = 'put';
        paymentTable ="{{ $payment->table }}";


    </script>
@endsection
