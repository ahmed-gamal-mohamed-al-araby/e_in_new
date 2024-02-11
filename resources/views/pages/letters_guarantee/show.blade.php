@extends('pages.layouts.master')

@section('title')
@lang('site.show') @lang('site.letter_guarantee')
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
                <h1> @lang('site.show') @lang('site.letter_guarantee')</h1>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.show') @lang('site.letter_guarantee')</li>
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
                    <span class="border-bottom border-success">@lang('site.show') @lang('site.letter_guarantee')</span>
                </h5>

                <div class="fieldset-content">

                    <div class="card">

                        <h5 class="card-header bg-success">
                            @lang('site.details')
                        </h5>

                        <div class="card-body">

                            <div class="row mb-3">
                                {{-- client_name --}}
                                <div class="col-md-6">
                                    <label for="client_name" class="form-label">@lang('site.client_name')</label>
                                    @if(isset($letter_guarantee->client_id))
                                    @if($letter_guarantee->client_type=="b")
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->businessClient->name }}">

                                    @elseif($letter_guarantee->client_type=="p")
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->personClient->name }}">

                                    @else
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->foreignerClient->company_name }}">

                                    @endif
                                    @else
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->client_name }}">

                                    @endif
                                </div>
                                {{-- side --}}
                                <div class="col-md-6">
                                    <label for="side" class="form-label">@lang('site.side') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->side }}">
                                </div>



                            </div> <!-- End Of Row-->
                            <div class="row mb-3">
                                {{-- project_number --}}
                                <div class="col-md-6">
                                    <label for="project_number" class="form-label">@lang('site.project_number')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->project_number }}">

                                </div>
                                {{-- supply_order --}}
                                <div class="col-md-6">
                                    <label for="side" class="form-label">@lang('site.supply_order') </label>
                                    @if(isset($letter_guarantee->supply_order))
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->purchaseOrder->purchase_order_reference }}">
                                    @else
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->supply_order_name }}">
                                    @endif
                                </div>



                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- value --}}
                                <div class="col-md-6">
                                    <label for="value" class="form-label">@lang('site.letter_guarantee_value')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->value }}">

                                </div>
                                {{-- cash_margin --}}
                                <div class="col-md-6">
                                    <label for="cash_margin" class="form-label">@lang('site.cash_margin') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->cash_margin }}">

                                </div>

                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- type --}}
                                <div class="col-md-6">
                                    <label for="type" class="form-label">@lang('site.type')</label>
                                    <input readonly type="text" class="form control" value="@lang('site'.'.'.$letter_guarantee->type)">


                                </div>
                                {{-- letter_guarantee_num --}}
                                <div class="col-md-6">
                                    <label for="letter_guarantee_num" class="form-label">@lang('site.letter_guarantee_num') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->letter_guarantee_num }}">

                                </div>

                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- bank_name --}}
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">@lang('site.bank_name')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->bank->bank_name }}">


                                </div>
                                {{-- bank_account_number --}}
                                <div class="col-md-6">
                                    <label for="bank_account_number" class="form-label">@lang('site.bank_account_number') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->bank->bank_account_number }}">

                                </div>

                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- release_date --}}
                                <div class="col-md-6">
                                    <label for="release_date" class="form-label">@lang('site.release_date')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->release_date }}">


                                </div>
                                {{-- expiry_date --}}
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label">@lang('site.expiry_date') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->expiry_date }}">

                                </div>

                            </div> <!-- End Of Row-->

                            @if(isset($letter_guarantee->reply_date))
                            <div class="row mb-3">
                                {{-- giver_name --}}
                                <div class="col-md-4">
                                    <label for="giver_name" class="form-label">@lang('site.giver_name')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->user->name }}">


                                </div>
                                {{-- recipient_name --}}
                                <div class="col-md-4">
                                    <label for="recipient_name" class="form-label">@lang('site.recipient_name') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->recipient_name }}">

                                </div>
                                {{-- reply_date --}}
                                <div class="col-md-4">
                                    <label for="reply_date" class="form-label">@lang('site.reply_date') </label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee->reply_date }}">

                                </div>

                            </div> <!-- End Of Row-->

                            @endif
                            <div class="row">
                                {{-- purpose --}}
                                <div class="col-md-6">
                                    <label for="purpose" class="form-label" id="textarea_payment_label">@lang('site.purpose')</label>
                                    <textarea readonly type="text" class="form control">{{ $letter_guarantee->purpose }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="purpose" class="form-label" id="textarea_payment_label"></label>

                                    @if(isset($letter_guarantee->image))
                                    <a class="form-label" href="{{asset('image_letter/'.$letter_guarantee->image)}}" target="_blank"><button type="button" class="btn btn-info">@lang('site.attachment')</button> </a>
                                    @endif
                                </div>

                            </div> <!-- End of Row-->

                            <hr>
                            <h2>@lang('site.extend_raise')</h2>
                            <br>
                            <br>
                            @foreach($letters_guarantee_changing as $letter_guarantee_changing)

                            <div class="row">
                                {{-- value --}}
                                <div class="col-md-3">
                                    <label for="value" class="form-label" >@lang('site.value')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_changing->value }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="cash_margin" class="form-label" >@lang('site.cash_margin')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_changing->cash_margin }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="expiry_date" class="form-label" >@lang('site.expiry_date')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_changing->expiry_date }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="user" class="form-label" >@lang('site.edit_user')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_changing->user->name }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="purpose" class="form-label" ></label>

                                    @if(isset($letter_guarantee_changing->image))
                                    <a class="form-label" href="{{asset('image_letter/'.$letter_guarantee_changing->image)}}" target="_blank"><button type="button" class="btn btn-info">@lang('site.attachment')</button> </a>
                                    @endif
                                </div>

                            </div> <!-- End of Row-->


                            @endforeach

                            <hr>
                            <h2>@lang('site.bank_commissions')</h2>
                            <br>
                            <br>
                            @foreach($letters_guarantee_bank_commissions as $letter_guarantee_bank_commissions)

                            <div class="row">
                                {{-- value_commission --}}
                                <div class="col-md-2">
                                    <label for="value_commission" class="form-label" id="textarea_payment_label">@lang('site.value_commission')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_bank_commissions->value_commission }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date" class="form-label" id="textarea_payment_label">@lang('site.date')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_bank_commissions->date }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="user" class="form-label" id="textarea_payment_label">@lang('site.edit_user')</label>
                                    <input readonly type="text" class="form control" value="{{ $letter_guarantee_bank_commissions->user->name }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="statement" class="form-label" id="textarea_payment_label">@lang('site.statement')</label>
                                    <!-- <input readonly type="text" class="form control" value="{{ $letter_guarantee_bank_commissions->statement }}"> -->
                                    <textarea readonly class="form control" cols="30" rows="1">{{ $letter_guarantee_bank_commissions->statement }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <label for="purpose" class="form-label" id="textarea_payment_label"></label>

                                    @if(isset($letter_guarantee_bank_commissions->image))
                                    <a class="form-label" href="{{asset('image_letter/'.$letter_guarantee_bank_commissions->image)}}" target="_blank"><button type="button" class="btn btn-info">@lang('site.attachment')</button> </a>
                                    @endif
                                </div>

                            </div> <!-- End of Row-->


                            @endforeach
                        </div> <!-- End Of Card Body-->

                    </div> <!-- End Of Second Card -->

                </div>
            </div>
        </fieldset> <!-- End Of Tab 3-->
    </div> <!-- End of form container-->

</section> <!-- End of main section-->

@endsection