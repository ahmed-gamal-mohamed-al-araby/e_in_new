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

            <form method="POST" id="documentForm" class="documentForm"
                action="{{ route('bank_approved', $bank->id) }}?n_id={{ $notification->id }}">
                @csrf
                @method('put')
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
                                            <label class="form-label">@lang('site.bank_code')</label>
                                            <input readonly class="form control" value="{{ $bank->bank_code }}">
                                        </div>
                                        {{-- bank_name --}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('site.bank_name') </label>
                                            <input readonly class="form control" value="{{ $bank->bank_name }}">

                                        </div>
                                        {{-- bank_currency --}}
                                        <div class="col-md-4">
                                            <label class="min_payment_label">@lang('site.currency')</label>
                                            <select disabled name="currency" class="bank_currency" id="bank_currency">
                                                <option>{{ $bank->currency }}</option>
                                            </select>
                                        </div>


                                    </div> <!-- End Of First Row-->

                                    <div class="row mb-3">
                                        {{-- bank_account_number --}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('site.bank_account_number')</label>
                                            <input readonly class="form control" value="{{ $bank->bank_account_number }}">
                                        </div>
                                        {{-- bank_account_iban --}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('site.bank_account_iban')</label>
                                            <input readonly class="form control" value="{{ $bank->bank_account_iban }}">
                                        </div>
                                        {{-- swift_code --}}
                                        <div class="col-md-4">
                                            <label class="form-label">@lang('site.swift_code')</label>
                                            <input readonly class="form control" value="{{ $bank->swift_code }}">
                                        </div>

                                    </div> <!-- End Of Second Row-->

                                    <div class="row">
                                        {{-- bank_address --}}
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('site.bank_address')</label>
                                            <textarea disabled readonly
                                                class="form control">{{ $bank->bank_address }}</textarea>
                                        </div>

                                    </div> <!-- End of Third Row-->

                                </div> <!-- End Of Card Body-->

                            </div> <!-- End Of Second Card -->

                        </div>
                    </div>

                    <div class="fieldset-footer pb-1">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {{-- Show approve button if active user not user make this notification && this bank is not approved --}}
                                {{-- Change status to be approved --}}
                                @if ($notification->user_id != auth()->user()->id && $bank->approved == 0 && !$notification->comment && $notification->type == 'a')
                                    <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                    <button type="submit"
                                        class="btn btn-success float-right mr-4">@lang('site.approve') <i class="fas fa-check"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>

                </fieldset> <!-- End Of Tab 3-->

            </form> <!-- End Of Form -->

            {{-- Reply with comment --}}
            {{-- 
                * Show if active user is not that notification owner 
                * and this record is not approved 
                * notification type is a => for action not n => for normal
            --}}
            @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $bank->approved == 0)
                <form action="{{ route('notification.reply') }}" method="post" class="mx-5">
                    @csrf
                    <input type="hidden" name="n_id" value="{{ $notification->id }}">
                    <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                    <div class="row justify-content-end py-3">
                        <button type="submit" class="btn btn-danger mt-2 d-block">@lang('site.send') @lang('site.comment') <i class="fas fa-times"></i></button>
                    </div>
                </form>
            @endif

            {{-- Show edit for user that have this notification --}}
            {{-- 
                * Show if active user is notification owner 
                * and this record is not approved 
                * notification have comment or notification type is a => for action not n => for normal
            --}}
            @if ($notification->user_id == auth()->user()->id && $bank->approved == 0 && ($notification->type == 'a' || $notification->comment))
                <div class="row pb-3 pr-5 justify-content-end">
                    <a class="btn btn-sm btn-warning m-1"
                    href="{{ route('bank.edit', $bank->id) }}" data-toggle="tooltip"
                    data-placement="top" title="Edit">
                    @lang('site.go_for_edit')<i class="ml-2 fas fa-edit"></i>
                    </a>
                </div>
            @endif

        </div> <!-- End of form container-->

    </section> <!-- End of main section-->

@endsection
