@extends('pages.layouts.master')

@section('title')
    @lang('site._payment') @lang('site.purchaseorder')
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
                    <h1>@lang('site.add') @lang('site._payment') @lang('site.purchaseorder')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.add') @lang('site._payment')
                            @lang('site.purchaseorder') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.add') @lang('site._payment') @lang('site.purchaseorder')</h2>
            <form method="POST" id="PaymentForm" class="PoForm" action="{{ route('purchaseorders.store') }}"
                enctype="multipart/form-data">
                @csrf

                <h1 class="d-none text-center mt-2 mb-lg-n4 mb-n2 text-direction-arabic">
                    {{ __('site.available-payment-value') }}<span class="badge badge-success mx-2"
                        id="available-payment-value"></span>
                </h1>

                {{-- purchaseorder details --}}

                <h3>
                    <span class="title_text">@lang('site.purchaseorder')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content px-3">
                        <div class="card">

                            <h5 class="card-header bg-success">
                                @lang('site.purchaseorder')
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
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_1_2')</span>
                    </div>
                </fieldset>

                {{-- add Deductions --}}
                <h3>
                    <span class="title_text">@lang('site.add') @lang('site.deduction')</span>
                </h3>

                <fieldset class="pl-1 pr-2">
                    <div class="row mb-2 ml-1 mr-2">
                        {{-- Deduction counter --}}
                        <div class="col-md-3">
                            <label for="name" class="form-label w-100"
                                id="min_payment_label">@lang('site.deduction_counter')
                            </label>
                            <input type="text" id="deduction_counter" name="deduction_counter" readonly class="mb-2">
                        </div>
                        {{-- Payment date --}}
                        <div class="offset-md-3 col-md-6">
                            <label for="type" class="form-label">@lang('site.payment_date')</label>
                            <input type="date" name="payment_date"  class="d-block" onfocus="(this.type='date')"
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
                                        <input type="text" readonly id="deduction-total">
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

            </form>
        </div>
    </section>

    {{-- modal add Deduction --}}
    <div class="modal fade" id="addline" data-check-data="null" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLongTitle">@lang('site.add') @lang('site.deduction')</h6>
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
                                    <option disabled selected>@lang('site.capital_select') @lang('site.deduction')</option>
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
                                        class="input-group-item" />
                                </div>
                                <p class="text-danger text-bold text-center d-none"
                                    id="validate-payment_purchase_order-overflow">
                                    @lang('site.payment_purchase_order_overflow_error')</p>
                                <p class="text-danger text-bold text-center d-none" id="validate-payment_document-overflow">
                                    @lang('site.payment_document_overflow_error')</p>

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

        language['deduction_id'] =
            "@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.deduction')";

        $('[name="deduction"]').select2({
            placeholder: language['deduction_id'],
        });
    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('payment/21_11_v2_js/payment.js') }}"></script>
    <script>
        ajaxURL = "{{ route('payment.purchaseorder.deduction.store') }}";
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
