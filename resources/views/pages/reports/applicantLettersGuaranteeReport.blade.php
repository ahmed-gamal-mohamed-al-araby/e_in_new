@extends('pages.layouts.master')
@php
$currentLang = Config::get('app.locale');
@endphp
@section('title')
@lang('site.applicant_letters_guarantee_report')
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
                    @lang('site.applicant_letters_guarantee_report')
                </h1>

            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        @lang('site.applicant_letters_guarantee_report')
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
            {{-- Purchase Order Client Type --}}
            <div class="col-md-2 mb-3 textDirection">
                <div class="input-group ">
                    <label class="form-label d-block w-100 textDirection" id="order_label">@lang('site.client_type')</label>
                    <select id='client_type' name="client_type" class="form-control require">
                        <option selected disabled>@lang('site.select') @lang('site.client_type')
                        </option>
                        <option value="b" data-label="@lang('site.tax_id_number_only')" data-validate="@lang('site.validate_Tax_id_number')">
                            @lang('site.the_businessClient')</option>
                        <option value="p" data-label="@lang('site.national_id')" data-validate="@lang('site.validate_national_id')">
                            @lang('site.person_client')</option>
                        <option value="f" data-label="@lang('site.vat_id')" data-validate="@lang('site.validate_vat_id')">
                            @lang('site.foreigner_client')</option>
                    </select>
                </div>
                @error('client_type')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="col-md-4 mb-12 textDirection">
                <div class="card-body p-0 client-details d-none">
                    <div class="row">
                        <div class="col-md-9 col-12 no-gutters">
                            {{-- tax_id_number for business client Or national ID person client --}}
                            <div class="row no-gutters">
                                <div class="col-md-11 col-12 input-group">
                                    <label class="form-label d-block w-100 textDirection">@lang('site.client_name')</label>
                                    <select id='client_name' style="width: 100%" class="form-control rounded require" disabled>
                                        <option selected disabled>@lang('site.select')
                                            @lang('site.client_name')
                                        </option>

                                    </select>
                                    @error('client_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="client_id" id="client_id">
                        <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                    </div> <!-- End Of First Row-->

                </div> <!-- End Of Card Body-->
            </div>
            <!-- end here client -->


            <div class="col-md-6 mb-3 textDirection">
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

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>


<script>
    $('#client_name').select2();

    $('#client_type').on('change', function() {
        $('#client_type').val() ? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass('d-none');
        $('.items-from-ajax-load').fadeIn();
        $('#client_id').val('');
        $('.purchase_order_search_result').addClass('d-none');
        $('#tax_id_number_or_national_id_or_vat_id').val('');
        $('#purchase_order').empty();
        $('.items-from-ajax-load').fadeOut(250);

        let selectValue = $(this).val();
        // check selector value
        if (selectValue == 'b' || selectValue == 'p' || selectValue == 'f') {
            $('.client-details').removeClass('d-none');
            $('.select-foreigner-client').addClass('d-none');
            $('#client-id-container').insertBefore(".client-details .row .text-danger");
            let labelOrInputParent = $('#tax_id_number_or_national_id_or_vat_id').parent();
            // get data(label and name) value in option selected
            let dataLabel = $(this).find('option:selected').data('label');
            // change label text and input name
            labelOrInputParent.find('label').text(dataLabel);
        }
        let targetName = $('#client_name');

        if (targetName != '') {
            const urlInputType = $(this).val();
            const url = `${subFolderURL}/${urlLang}/reports/getClientsFromclientType`;
            if ($(this).val() == 'b' || $(this).val() == 'p' || $(this).val() == 'f')
                sendAjax('GET', url, urlInputType, targetName, getBusinessOrPersonClientDataByName)
        }
    });

    function sendAjax(method, url, urlInputType, target, successFunction) {
        target.attr('disabled', true);
        $.ajax({
            type: method,
            data: {
                clientType: urlInputType,
            },
            url: `${url}`,
            success: function(response) {
                successFunction(response, target);
            }
        });
    }


    // Get Clients Data To Select
    function getBusinessOrPersonClientDataByName(response, targetName) {
        targetName.attr('disabled', false);
        var response = JSON.parse(response);
        targetName.empty();
        targetName.append(`<option selected disabled>@lang('site.select') @lang('site.client_name')</option>`);
        for (const key in response) {
            if (response.hasOwnProperty.call(response, key)) {
                targetName.append(
                    `<option value="${key}">${response[key]}</option>`
                );
            }
        }
    }


    // Add Client Data To Blade
    function getBusinessOrPersonClientData(response) {
        if (response) {
            $('#client_id').val(response.id);
            $('#client_id').trigger('keyup');
            $('#tax_id_number_or_national_id_or_vat_id').val(response.tax_id_number_or_national_id_or_vat_id);
            $('#tax_id_number_or_national_id_or_vat_id').trigger('keyup');
        } else {
            $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
            $('#tax_id_number_or_national_id_or_vat_id').val('');
        }
        $('.search-bank.spinner-border').hide();
    }

    $(".eventBtu").click(function(e) {

        let client_type = $('#client_type').val();
        let client_id = $('#client_name').val();
        let status = $('#status').val();
        $.ajax({
            url: "{{ route('reports.applicant_letters_guarantee_get_data') }}",
            type: "post",
            data: {
                _token: '{{ csrf_token() }}',
                client_type: client_type,
                client_id: client_id,
                status: status,

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