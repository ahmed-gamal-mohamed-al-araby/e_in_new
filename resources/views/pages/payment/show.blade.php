@extends('pages.layouts.master')

@section('title')
@lang('site._payment') {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}
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

        .items-from-excel-sheet-loader {
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
                    <h1>@lang('site.show') @lang('site._payment') {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.show') @lang('site._payment') {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.show') @lang('site._payment') {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}</h2>
            <form method="POST" id="PaymentForm" class="PoForm" action="{{ route('purchaseorders.store') }}"
                enctype="multipart/form-data">
                @csrf

                {{-- document Or purchaseOrder details --}}

                <h3>
                    <span class="title_text"> {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}</span>
                </h3>
                
                <fieldset>
                    <div class="fieldset-content px-3">
                        <div class="card">
                
                            <h5 class="card-header bg-success">
                                {{ $payment->table == 'D'? __('site.document'): __('site.purchaseorder') }}
                            </h5>
                
                            <div class="card-body">
                
                                {{-- Client section --}}
                                <div class="row mb-3">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' disabled name="client_type" class="form-control require">
                                                <option value="b" {{ $payment->client_type == 'b'?'selected':'' }}
                                                    data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" {{ $payment->client_type == 'p'?'selected':'' }}
                                                    data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f" {{ $payment->client_type == 'f'?'selected':'' }}>@lang('site.foreigner_client')</option>
                                            </select>
                                        </div>
                                        @error('client_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                
                                    {{-- Purchase Order Client id --}}
                                    <div class="col-md-8">
                                        {{-- foreigner client --}}
                                        @if($payment->client_type == 'f')
                                            <div class="select-foreigner-client">
                                                <div class="row mb-3">
                                                    <div class="col-md-8 input-group mb-3">
                                                        <label class="form-label d-block w-100">@lang('site.client')</label>
                                                        <select id='foreigner-client' disabled class="form-control require">
                                                            <option selected disabled>@lang('site.select')
                                                                @lang('site.client_type')
                                                            </option>
                                                            <option value="" selected>{{ $client->company_name }}</option>
                                                        </select>
                                                    </div>
                    
                                                    <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                                </div>
                                            </div>
                                            @error('foreigner-client')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        {{-- business or person client --}}
                                        @else
                                            <div class="card-body p-0 client-details">
                                                <div class="row mb-3">
                                                    <div class="col-md-3 no-gutters">
                                                        {{-- tax_id_number for business client Or national ID person client --}}
                                                        <div class="row mb-1 no-gutters">
                                                            <div class="col-md-11">
                                                                <label for="tax_id_number_or_national_id"class="form-label w-100">
                                                                @if ($payment->client_type == 'b')
                                                                    @lang('site.tax_id_number_only')    
                                                                @elseif ($payment->client_type == 'p')
                                                                    @lang('site.national_id')
                                                                @endif
                                                                </label>
                                                                <input type="text" readonly value="{{ $payment->client_type == 'b'? $client->tax_id_number: $client->national_id }}" class="form control"
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
                                                    <div class="col-md-9 pr-3">
                                                        <label for="name" class="form-label w-100"
                                                            id="min_payment_label">@lang('site.name') </label>
                                                        <input type="text" readonly value="{{ $payment->client_type == 'b'? $client->name: $client->name }}" id="client_name" class="display" readonly>
                                                    </div>
                    
                                                    <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                    
                                                </div> <!-- End Of First Row-->
                    
                                            </div> <!-- End Of Card Body-->
                                        @endif
                                    </div>
                                </div>
                
                
                                <div class="row mt-3">
                                    {{-- Purchase Order type --}}
                                    <div class="col-md-6" id="purchaseorder_id_container">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                id="order_label">@lang('site.purchaseorder')</label>
                                            <select name="purchaseorder_id" disabled id="select_purchase_order"
                                                class="form-control require">
                                                <option selected disabled>@lang('site.select')
                                                    @lang('site.purchaseorder')
                                                </option>
                                                <option value="" selected>{{ $purchaseOrderReference }}</option>
                                                {{-- Here is client Purchaseorders --}}
                                            </select>
                                        </div>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if ($payment->table == 'D')
                                        {{-- Document --}}
                                        <div class="col-md-6" id="document_id_container">
                                            <div class="input-group mb-3">
                                                <label class="form-label d-block w-100"
                                                    id="order_label">@lang('site.document')</label>
                                                <select name="document_id" disabled id="select_document"
                                                    class="form-control require">
                                                    <option selected disabled>@lang('site.select')
                                                        @lang('site.document')
                                                        <option value="" selected>{{ $documentNumber }}</option>
                                                    </option>
                                                    {{-- Here is client Purchaseorders --}}
                                                </select>
                                            </div>
                                            @error('type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

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
                    <span class="title_text"> {{ $payment->payment_method == 'deduction'? __('site.deduction'): __('site.payment_details') }}</span>
                </h3>
                {{-- Deduction --}}
                @if ($payment->payment_method == 'deduction')
                    <fieldset class="pl-1 pr-2">
                        <div class="row mb-2 ml-1 mr-2">
                            {{-- Deduction counter --}}
                            <div class="col-md-3">
                                <label for="name" class="form-label w-100"
                                    id="min_payment_label">@lang('site.deduction_counter')
                                </label>
                                <input type="text" value="{{ count($paymentMethod) }}" id="deduction_counter" name="deduction_counter" readonly class="mb-2">
                            </div>
                                {{-- Payment date --}}
                                <div class="offset-md-3 col-md-6">
                                <label for="type" class="form-label">@lang('site.payment_date')</label>
                                <input type="date" name="payment_date" readonly value="{{ $payment->payment_date }}" required class="d-block"
                                    placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                    data-date-format="DD/MM/YYYY" />
                            </div>
                        </div>
                        <div class="fieldset-content">
                            <div class="card ml-2">
                                <h5 class="card-header bg-success">
                                    @lang('site.delivery_details')
                                </h5>

                                <div class="card-body pt-2">
                                    <div class="row align-items-center">
                                        {{-- Show deductions --}}
                                        <div class="table-responsive">
                                            {{-- Table for view addded items --}}
                                            <table
                                                class="table table-bordered table-striped table-hover justify-content-center text-center m-0 deductions-table">
                                                <thead>
                                        
                                                    <tr>
                                                        <th scope="col" >
                                                            #
                                                        </th>
                                                        <th scope="col">
                                                            @lang('site.deduction')
                                                        </th>
                                                        <th scope="col">
                                                            @lang('site.value')
                                                        </th>
                                                    </tr>
                                        
                                                </thead>
                                                <tbody>
                                                    @foreach ($paymentMethod as $deduction)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $deduction->deduction->name }}</td>
                                                        <td>{{ number_format($deduction->value, 2) }}</td>
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
                                            <input type="text" value="{{ number_format($payment->value ,2) }}" readonly id="deduction-total">
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
                {{-- Cashe, Cheque Or Bank transfer --}}
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
                                            <input disabled class="form-check-input ml-2" type="radio" name="payment_method" id="cash_check_id" value="cashe" {{ $payment->payment_method == 'cashe'?'checked':'' }}>
                                            <label class="form-check-label" for="cash_check_id">
                                                @lang('site.cash')
                                            </label>
                                            </div>
                                            {{-- Cashe cheque option --}}
                                            <div class="form-check">
                                            <input disabled class="form-check-input ml-2" type="radio" name="payment_method" id="cheque_check_id" value="cheque" {{ $payment->payment_method == 'cheque'?'checked':'' }}>
                                            <label class="form-check-label" for="cheque_check_id">
                                                @lang('site.cheque')
                                            </label>
                                            </div>
                                            {{-- Cashe bank_transfer option --}}
                                            <div class="form-check disabled">
                                            <input disabled class="form-check-input ml-4" type="radio" name="payment_method" id="bank_transfer_check_id" value="bank_transfer" {{ $payment->payment_method == 'bank_transfer'?'checked':'' }}>
                                            <label class="form-check-label" for="bank_transfer_check_id">
                                                @lang('site.bank_transfer')
                                            </label>
                                            </div>

                                        <div class="text-danger d-none ml-2" id="payment_option_error">
                                            @lang('site.check_atleast_one_payment_method')</div>

                                            <div class="col-12"></div>


                                        {{-- total_money --}}
                                        <div class=" col-md-6 my-2">
                                            <label for="type" class="form-label">@lang('site.amount')</label>
                                            <input type="number" readonly name="total_money" value="{{ $payment->value }}"
                                                class="form-control" required placeholder="@lang('site.enter') @lang('site.amount')"
                                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.amount')')"
                                                oninput="setCustomValidity('')">
                                            @error('total_money')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Payment date --}}
                                        <div class="col-md-6 my-2">
                                            <label for="type" class="form-label">@lang('site.payment_date')</label>
                                            <input type="date" readonly name="payment_date" required class="d-block" value="{{ $payment->payment_date }}"
                                                placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                data-date-format="DD/MM/YYYY" />
                                        </div>

                                        <hr style="flex: 0 0 100%;">

                                        {{-- Bank_transfer --}}
                                        @if ($payment->payment_method == 'bank_transfer')
                                            <div class="col-12 row" id="bank_transfer_container">
                                                {{-- Bank name --}}
                                                <div class=" col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.bank_name')</label>
                                                    <input type="text" readonly value="{{ $paymentMethod->bank_name }}" name="bank_transfer_bank_name"
                                                        class="form-control" required placeholder="@lang('site.enter') @lang('site.bank_name')"
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
                                                    <input type="date" readonly value="{{ $paymentMethod->received_date }}" name="bank_transfer_received_date" id="bank_transfer_received_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- issue date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.issue_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->issue_date }}" name="bank_transfer_issue_date" id="bank_transfer_issue_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- deposit date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.deposit_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->deposit_date }}" name="bank_transfer_deposit_date" id="bank_transfer_deposit_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- collect date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.collect_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->collect_date }}" name="bank_transfer_collect_date" id="bank_transfer_collect_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>
                                            </div>

                                        {{-- Cheque --}}
                                        @elseif($payment->payment_method == 'cheque')
                                            <div class="col-12 row" id="cheque_container">
                                                {{-- Bank name --}}
                                                <div class=" col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.bank_name')</label>
                                                    <input type="text" readonly value="{{ $paymentMethod->bank_name }}" name="cheque_bank_name"
                                                        class="form-control" required placeholder="@lang('site.enter') @lang('site.bank_name')"
                                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_name')')"
                                                        oninput="setCustomValidity('')">
                                                    @error('cheque_bank_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- cheque number --}}
                                                <div class=" col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.cheque_number')</label>
                                                    <input type="text" readonly value="{{ $paymentMethod->cheque_number }}" name="cheque_number"
                                                        class="form-control" required placeholder="@lang('site.enter') @lang('site.cheque_number')"
                                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.cheque_number')')"
                                                        oninput="setCustomValidity('')">
                                                    @error('cheque_number')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                {{-- received date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.received_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->received_date }}" name="cheque_received_date" id="cheque_received_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- issue date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.issue_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->issue_date }}" name="cheque_issue_date" id="cheque_issue_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- deposit date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.deposit_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->deposit_date }}" name="cheque_deposit_date" id="cheque_deposit_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>

                                                {{-- collect date --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="type" class="form-label">@lang('site.collect_date')</label>
                                                    <input type="date" readonly value="{{ $paymentMethod->collect_date }}" name="cheque_collect_date" id="cheque_collect_date" required class="d-block"
                                                        placeholder="@lang('site.date')" onfocus="(this.type='date')"
                                                        data-date-format="DD/MM/YYYY" />
                                                </div>
                                            </div>
                                        @endif
                                        
                                        {{-- Bank --}}
                                        @if ($payment->payment_method != 'cashe')
                                            <div class="row mb-3 mx-2" id="bank_container">

                                                {{-- Bank code --}}
                                                <div class="col-md-3 no-gutters">
                                                    <div class="row mb-1 no-gutters">
                                                        <div class="col-md-11">
                                                            <label for="bank_code" class="form-label w-100"
                                                                id="min_payment_label">@lang('site.bank_code')</label>
                                                            <input type="number" readonly
                                                            value="{{ $paymentMethod->bank->bank_code }}"
                                                            class="form control" id="bank_code" />
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
                                                    <input type="text" readonly
                                                    value="{{ $paymentMethod->bank->bank_name }}"
                                                    id="bank_name" class="display" readonly>
                                                </div>
            
                                                {{-- bank_account_number --}}
                                                <div class="col-md-3">
                                                    <label for="bank_account_number" class="form-label w-100"
                                                        id="payment_label">@lang('site.bank_account_number')</label>
                                                    <input type="text" readonly
                                                    value="{{ $paymentMethod->bank->bank_account_number }}"

                                                    id="bank_account_number" class="display" readonly>
                                                </div>
            
                                                {{-- bank currency --}}
                                                <div class="col-md-2">
                                                    <label for="bank_currency" class="min_payment_label">@lang('site.currency')</label>
                                                    <input type="text" readonly
                                                    value="{{ $paymentMethod->bank->currency }}"
                                                    id="bank_currency" class="display" readonly>
                                                </div>
            
                                                {{-- Bank id --}}
                                                <div class="col-md-1">
                                                    <label for="address" class="form-label w-100">@lang('site.id')</label>
                                                    
                                                        <input type="text" readonly name="bank_id" 
                                                        value="{{ $paymentMethod->bank_id }}" id="bank_id" readonly>
                                                </div>
                                            </div> <!-- End Of First Row-->
                                        @endif
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
        language['select_client_type'] = `<option selected disabled>@lang('site.select') @lang('site.client_type')</option>`;
        language['select_document'] = `<option selected disabled>@lang('site.select') @lang('site.document')</option>`;
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
        $('.actions.clearfix ul li:last-child()').addClass('d-none');
    </script>
@endsection
