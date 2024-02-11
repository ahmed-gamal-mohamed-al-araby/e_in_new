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
        <h1 >@lang('site.add_bank')</h1>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"> @lang('site.add_bank') </li>
          <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>


<section class="main">

    <div class="form-container">

        <form method="POST" id="documentForm" class="documentForm"  action="{{ route('bank.store') }}">
            @csrf

            <fieldset>
                <div class="fieldset-content">

                    <h5 class="ml-2 mt-3  mb-4">
                        <span class="border-bottom border-success">@lang('site.add_bank')</span>
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
                                        <label for="bank_code" class="form-label" id="min_payment_label">@lang('site.bank_code')</label>
                                        <input type="number" class="form control" value="{{ old('bank_code') }}" name="bank_code" id="bank_code" required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('bank_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- bank_name --}}
                                    <div class="col-md-4">
                                        <label for="bank_name" class="form-label" id="min_payment_label">@lang('site.bank_name') </label>
                                        <input type="text" class="form control" value="{{ old('bank_name') }}" name="bank_name" id="bank_name"  required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_name')')"
                                            oninput="setCustomValidity('')">
                                        @error('bank_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- bank_currency --}}
                                    <div class="col-md-4">
                                        <label for="bank_currency" class="min_payment_label">@lang('site.currency')</label>
                                        <select  name="currency" class="bank_currency" id="bank_currency" required oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.currency')')"
                                        oninput="setCustomValidity('')">
                                            <option value="" disabled selected>@lang('site.select') @lang('site.currency')</option>
                                            <option value="EGP" {{ old('currency') =='EGP' ? 'selected' : '' }}>EGP</option>
                                            <option value="USD" {{ old('currency') =='USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency') =='EUR' ? 'selected' : '' }}>Euro</option>
                                            <option value="SAR" {{ old('currency') =='SAR' ? 'selected' : '' }}>SAR</option>
                                            <option value="RUB" {{ old('currency') =='RUB' ? 'selected' : '' }}>RUB</option>
                                            <option value="JPY" {{ old('currency') =='JPY' ? 'selected' : '' }}>JPY</option>
                                            <option value="GBP" {{ old('currency') =='GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="CHF" {{ old('currency') =='CHF' ? 'selected' : '' }}>CHF</option>
                                            <option value="CAD" {{ old('currency') =='CAD' ? 'selected' : '' }}>CAD</option>
                                            <option value="AUD/NZD" {{ old('currency') =='AUD/NZD' ? 'selected' : '' }}>AUD/NZD</option>
                                            <option value="ZAR" {{ old('currency') =='ZAR' ? 'selected' : '' }}>ZAR</option>
                                        </select>
                                        @error('currency')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div> <!-- End Of First Row-->

                                <div class="row mb-3">

                                    {{-- bank_account_number --}}
                                    <div class="col-md-4">
                                        <label for="bank_account_number" class="form-label" id="payment_label">@lang('site.bank_account_number')</label>
                                        <input type="text" class="form control" value="{{ old('bank_account_number') }}" name="bank_account_number" id="bank_account_number"  required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_account_number')')"
                                            oninput="setCustomValidity('')">
                                        @error('bank_account_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        
                                    </div>

                                    {{-- bank_account_iban --}}
                                    <div class="col-md-4">
                                        <label for="bank_account_iban" class="form-label" id="payment_label">@lang('site.bank_account_iban')</label>
                                        <input type="text" class="form control" value="{{ old('bank_account_iban') }}" name="bank_account_iban" id="bank_account_iban"  required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_account_iban')')"
                                            oninput="setCustomValidity('')">
                                        @error('bank_account_iban')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- swift_code --}}
                                    <div class="col-md-4">
                                        <label for="swift_code" class="form-label" id="min_payment_label">@lang('site.swift_code')</label>
                                        <input type="text" class="form control" value="{{ old('swift_code') }}" name="swift_code" id="swift_code"  required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.swift_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('swift_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div> <!-- End Of Second Row-->


                                <div class="row">

                                    {{-- bank_address --}}
                                    <div class="col-md-6">
                                        <label for="bank_address" class="form-label" id="textarea_payment_label">@lang('site.bank_address')</label>
                                        <textarea type="text" class="form control" name="bank_address" id="bank_address"  required 
                                        oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.bank_address')')"
                                            oninput="setCustomValidity('')">{{ old('bank_address') }}</textarea>
                                        @error('bank_address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div> <!-- End of Third Row-->

                            </div> <!-- End Of Card Body-->

                        </div> <!-- End Of Second Card -->

                    </div>


                </div>

                <div class="fieldset-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" id="submit" class="btn btn-success float-right mr-4">  Save <i class="far fa-save"></i></button>
                        </div>
                    </div>
                </div>

            </fieldset> <!-- End Of Tab 3-->

        </form> <!-- End Of Form -->

    </div> <!-- End of form container-->

</section> <!-- End of main section-->

@endsection


