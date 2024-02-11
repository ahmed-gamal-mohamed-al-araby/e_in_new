@extends('pages.layouts.master')

@section('title')
    @lang('site.banks')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.show_bank')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.show_bank') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">

        <div class="form-container">
            <fieldset>
                <div class="fieldset-content">

                    <h5 class="ml-2 mt-3  mb-4">
                        <span class="border-bottom border-success">@lang('site.show_bank')</span>
                    </h5>

                    <div class="fieldset-content">

                        <div class="card">

                            <h5 class="card-header bg-success">
                                @lang('site.bank_details')
                            </h5>

                            <div class="card-body">

                                <div class="row mb-3">
                                    {{-- bank_code --}}
                                    <div class="col-md-4">
                                        <label for="bank_code" class="form-label"
                                            id="min_payment_label">@lang('site.bank_code')</label>
                                        <input readonly type="number" class="form control" value="{{ $bank->bank_code }}">
                                    </div>
                                    {{-- bank_name --}}
                                    <div class="col-md-4">
                                        <label for="bank_name" class="form-label"
                                            id="min_payment_label">@lang('site.bank_name') </label>
                                        <input readonly type="text" class="form control" value="{{ $bank->bank_name }}">
                                    </div>
                                    {{-- bank_currency --}}
                                    <div class="col-md-4">
                                        <label for="bank_currency" class="min_payment_label">@lang('site.currency')</label>
                                        <select disabled name="currency" class="bank_currency">>
                                            <option selected>{{ $bank->currency }}</option>
                                        </select>
                                    </div>


                                </div> <!-- End Of First Row-->

                                <div class="row mb-3">
                                    {{-- bank_account_number --}}
                                    <div class="col-md-4">
                                        <label for="bank_account_number" class="form-label"
                                            id="payment_label">@lang('site.bank_account_number')</label>
                                        <input readonly type="text" class="form control"
                                            value="{{ $bank->bank_account_number }}">
                                    </div>
                                    {{-- bank_account_iban --}}
                                    <div class="col-md-4">
                                        <label for="bank_account_iban" class="form-label"
                                            id="payment_label">@lang('site.bank_account_iban')</label>
                                        <input readonly type="text" class="form control"
                                            value="{{ $bank->bank_account_iban }}">
                                    </div>
                                    {{-- swift_code --}}
                                    <div class="col-md-4">
                                        <label for="swift_code" class="form-label"
                                            id="min_payment_label">@lang('site.swift_code')</label>
                                        <input readonly type="text" class="form control" value="{{ $bank->swift_code }}">
                                    </div>

                                </div> <!-- End Of Second Row-->

                                <div class="row">
                                    {{-- bank_address --}}
                                    <div class="col-md-6">
                                        <label for="bank_address" class="form-label"
                                            id="textarea_payment_label">@lang('site.bank_address')</label>
                                        <textarea readonly type="text" class="form control">{{ $bank->bank_address }}</textarea>
                                    </div>

                                </div> <!-- End of Third Row-->

                            </div> <!-- End Of Card Body-->

                        </div> <!-- End Of Second Card -->

                    </div>
                </div>
            </fieldset> <!-- End Of Tab 3-->
        </div> <!-- End of form container-->

    </section> <!-- End of main section-->

@endsection
