@extends('pages.layouts.master')
@php
$currentLang = Config::get('app.locale');
@endphp
@section('title')
@lang('site.valid_letters_guarantee_report')
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
<style>
    table.table-bordered.dataTable {
        direction: ltr;
    }

    input:focus,
    select:focus,
    textarea:focus {
        background: #bbb !important;
    }
</style>

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
    .date {
        direction: rtl !important;
    }

    .textDirection {
        text-align: right !important;
    }

    .flex_dir {
        flex-direction: row-reverse;
    }

    .select2-container {
        text-align: right !important;
    }

    div.dataTables_wrapper div.dataTables_filter {
        float: left;
    }
</style>
@endif
@endsection

@section('content')
<section class="content-header prequestHeader">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1>
                    @lang('site.valid_letters_guarantee_report')
                </h1>

            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        @lang('site.valid_letters_guarantee_report')
                    </li>

                    <li class="breadcrumb-item active">
                        @lang('site.reports')
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card">

    <div class="card-body">
        {{-- Date section --}}
        <div class="row date">
            <div class="col-md-3 mb-3 textDirection">
                <label for="type" class="form-label">@lang('site.side')</label>

                <select name="side" id="side">
                    <option value="" disabled selected>@lang('site.choose') @lang('site.side')</option>
                    @foreach($sides as $side)
                    <option value="{{$side}}">{{$side}}</option>
                    @endforeach
                </select>
                <div class="col-12 text-center text-danger d-none date-overflow">
                    @lang('site.date_overflow')</div>
                <div class="text-center text-danger d-none side_error">
                    @lang('site.data-required')</div>
            </div>
            <div class="col-md-3 mb-3 textDirection">
                <label for="type" class="form-label">@lang('site.bank_name')</label>

                <select name="bank_name" id="bank_name">
                    <option value="" disabled selected>@lang('site.choose') @lang('site.bank_name')</option>
                    @foreach($banksName as $bankName)
                    <option value="{{$bankName}}">{{$bankName}}</option>
                    @endforeach
                </select>
                <div class="col-12 text-center text-danger d-none date-overflow">
                    @lang('site.date_overflow')</div>
                <div class="text-center text-danger d-none bank_name_error">
                    @lang('site.data-required')</div>
            </div>

            <div class="col-md-2 mb-2 textDirection">
                <label class="form-label" for="">@lang('site.type')</label>
                <select required value="{{ old('type') }}" id="type" class="form-control pt-0 type @error('type') is-invalid @enderror" name="type">
                    <option selected hidden value="">@lang('site.choose')</option>
                    <option value="prepaid">@lang('site.prepaid')</option>
                    <option value="primary_insurance">@lang('site.primary_insurance')</option>
                    <option value="final_insurance">@lang('site.final_insurance')</option>
                    <option value="instant">@lang('site.instant')</option>
                    <option value="maritime">@lang('site.maritime')</option>
                    <option value="financial">@lang('site.financial')</option>
                </select>

            </div>

            <div class="col-md-2 mb-2 textDirection">
                <label for="type" class="form-label">@lang('site.status')</label>

                <select name="status" id="status" class="form-label">
                    <option value="">@lang('site.choose')</option>
                    <option value="answered">
                        <th>@lang("site.answered")</th>
                    </option>
                    <option value="not_answered">
                        <th>@lang("site.not_answered")</th>
                    </option>
                </select>
            </div>
            <div class="col-md-2 mb-2 textDirection">
                <label for="type" class="form-label">@lang('site.expiry_date')</label>

                <input type="date" name="expiry_date" id="expiry_date">
            </div>
            <div class="col-12 text-center text-danger d-none" id="from-date-greater-than-to-date">
                @lang('site.from_date_greater_than_to_date')</div>
        </div>



        <div class="row date justify-content-center">
            <div class="col-md-6 textDirection" style="margin-top: 29px">
                <button type="button" class="btn btn-success w-100 mb-2 eventBtu">@lang('site.create')
                    @lang('site.report')</button>
            </div>
        </div>




    </div>
</div>

{{-- supplier Analysis Report --}}
<div class="card date textDirection table-card ">
    <div class="card-body" id="Table_se">
        <!-- Image load here -->
        <div id='loader' style='display: none; text-align: center;'>
            <img src="{{ asset('images/loading.gif') }}" width='150px' height='150px'>
        </div>
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

{{-- Table --}}


<script>
    $('#side').select2();
    $('#bank_name').select2();

    $(".eventBtu").click(function(e) {
        var side = $("#side").val();
        var status = $("#status").val();
        var type = $("#type").val();
        var bank_name = $("#bank_name").val();
        var expiry_date = $("#expiry_date").val();
        $.ajax({
            url: "{{ route('reports.valid_letters_guarantee_get_data') }}",
            type: "post",
            data: {
                _token: '{{ csrf_token() }}',
                side: side,
                status: status,
                type: type,
                bank_name: bank_name,
                expiry_date: expiry_date,

            },
            beforeSend: function() {
                // Show image container
                $("#loader").show();
            },
            success: function(data) {
                $("#Table_se").html(data);


            }
        });
    });
</script>
@endsection