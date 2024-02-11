@extends('pages.layouts.master')

@section('title')
@lang('site.show') @lang('site.warranty_checks')
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
                <h1> @lang('site.show') @lang('site.warranty_checks')</h1>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.show') @lang('site.warranty_checks')</li>
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
                    <span class="border-bottom border-success">@lang('site.show') @lang('site.warranty_checks')</span>
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
                                    @if($warranty_check->client_type=="b")
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->businessClient->name }}">

                                    @elseif($warranty_check->client_type=="p")
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->personClient->name }}">

                                    @else
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->foreignerClient->company_name }}">

                                    @endif

                                </div>
                                {{-- side --}}
                                <div class="col-md-6">
                                    <label for="side" class="form-label">@lang('site.side') </label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->side }}">
                                </div>



                            </div> <!-- End Of Row-->
                            <div class="row mb-3">
                                {{-- project_number --}}
                                <div class="col-md-6">
                                    <label for="project_number" class="form-label">@lang('site.project_number')</label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->project_number }}">

                                </div>
                                {{-- supply_order --}}
                                <div class="col-md-6">
                                    <label for="side" class="form-label">@lang('site.supply_order') </label>
                                    @if(isset($warranty_check->supply_order))
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->purchaseOrder->purchase_order_reference }}">
                                    @else
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->supply_order_name }}">
                                    @endif
                                </div>



                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- value --}}
                                <div class="col-md-6">
                                    <label for="value" class="form-label">@lang('site.value')</label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->value }}">

                                </div>
                                @if(isset($warranty_check->source_name))
                                {{-- source_name --}}
                                <div class="col-md-6">
                                    <label for="source_name" class="form-label">@lang('site.name_on_cheque')</label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->source_name }}">

                                </div>
                                @endif


                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- type --}}
                                <div class="col-md-6">
                                    <label for="type" class="form-label">@lang('site.type')</label>
                                    <input readonly type="text" class="form control" value="@lang('site'.'.'.$warranty_check->type)">


                                </div>
                                {{-- cheque_number --}}
                                <div class="col-md-6">
                                    @if($warranty_check->type =="bank_deposit")
                                    <label for="cheque_number" class="form-label">@lang('site.bank_account_number') </label>

                                    @else
                                    <label for="cheque_number" class="form-label">@lang('site.cheque_number') </label>
                                    @endif

                                    <input readonly type="text" class="form control" value="{{ $warranty_check->cheque_number }}">

                                </div>

                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- bank_name --}}
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">@lang('site.bank_name')</label>
                                    @if(isset($warranty_check->bank_id))
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->bank->bank_name }}">
                                    @else
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->bank_name }}">

                                    @endif

                                </div>
                                @if(isset($warranty_check->bank_id))

                                {{-- bank_account_number --}}
                                <div class="col-md-6">
                                    <label for="bank_account_number" class="form-label">@lang('site.bank_account_number') </label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->bank->bank_account_number }}">
                                </div>
                                @endif
                            </div> <!-- End Of Row-->


                            <div class="row mb-3">
                                {{-- check_date --}}
                                <div class="col-md-6">
                                    <label for="check_date" class="form-label">@lang('site.check_date')</label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->check_date }}">


                                </div>
                                {{-- document_nature --}}
                                <div class="col-md-6">
                                    <label for="document_nature" class="form-label">@lang('site.document_nature') </label>
                                    <input readonly type="text" class="form control" value="@lang('site'.'.'.$warranty_check->document_nature)">

                                </div>

                            </div> <!-- End Of Row-->

                            @if(isset($warranty_check->reply_date))
                            <div class="row mb-3">
                                {{-- giver_name --}}
                                <div class="col-md-4">
                                    <label for="giver_name" class="form-label">@lang('site.giver_name')</label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->user->name }}">


                                </div>
                                {{-- recipient_name --}}
                                <div class="col-md-4">
                                    <label for="recipient_name" class="form-label">@lang('site.recipient_name') </label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->recipient_name }}">

                                </div>
                                {{-- reply_date --}}
                                <div class="col-md-4">
                                    <label for="reply_date" class="form-label">@lang('site.reply_date') </label>
                                    <input readonly type="text" class="form control" value="{{ $warranty_check->reply_date }}">

                                </div>

                            </div> <!-- End Of Row-->

                            @endif
                            <div class="row">
                                {{-- purpose --}}
                                <div class="col-md-6">
                                    <label for="purpose" class="form-label" id="textarea_payment_label">@lang('site.purpose')</label>
                                    <textarea readonly type="text" class="form control">{{ $warranty_check->purpose }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="purpose" class="form-label" id="textarea_payment_label"></label>

                                    @if(isset($warranty_check->image))
                                    <a class="form-label" href="{{asset('image_letter/'.$warranty_check->image)}}" target="_blank"><button type="button" class="btn btn-info">@lang('site.attachment')</button> </a>
                                    @endif
                                </div>

                            </div> <!-- End of Row-->

                        </div> <!-- End Of Card Body-->

                    </div> <!-- End Of Second Card -->

                </div>
            </div>
        </fieldset> <!-- End Of Tab 3-->
    </div> <!-- End of form container-->

</section> <!-- End of main section-->

@endsection