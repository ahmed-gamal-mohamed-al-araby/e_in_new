@extends('pages.layouts.master')

@section('title')
    @lang('site.reports')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
    
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

    @if (Config::get('app.locale') == 'ar')
        <style>
            .date {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

            .flex_dir {
                flex-direction: row-reverse
            }
            .select_option {
                unicode-bidi: bidi-override;"
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
                        @lang('site.test_report')
                    </h1>

                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.test_report')
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
            <form action="{{ route('notification.archive') }}" method="Post">
                @csrf

                {{-- Date section --}}
                <div class="row flex_dir">
                    <div class="col-md-4 mb-3 textDirection">
                        {{-- <div class="form-group mb-3"> --}}
                        <label for="type" class="form-label">@lang('site.from_date')</label>
                        <input type="date" required name="from_date" id="from_date" class="d-block w-100 form-control"
                            placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.from_date')')"
                            oninput="setCustomValidity('')">
                        {{-- </div> --}}
                    </div>

                    <div class="col-md-4 mb-3 textDirection">
                        {{-- <div class="form-group mb-3"> --}}
                        <label for="type" class="form-label">@lang('site.to_date')</label>
                        <input type="date" required name="to_date" id="to_date" class="d-block w-100 form-control"
                            placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.to_date')')"
                            oninput="setCustomValidity('')">
                        {{-- </div> --}}
                    </div>
                    <div class="col-md-4 textDirection" style="margin-top: 29px">
                        <button type="submit" class="btn btn-success w-100 mb-2">@lang('site.create') @lang('site.report')</button>
                    </div>
                    <div class="col-12 text-center text-danger d-none" id="from-date-greater-than-to-date">
                        @lang('site.from_date_greater_than_to_date')</div>
                </div>
                <hr>
                {{-- Client section --}}
                <div class="row mb-3 flex_dir">
                    {{-- Purchase Order Client Type --}}
                    <div class="col-md-2 textDirection">
                        <div class="input-group mb-3">
                            <label class="form-label d-block w-100" id="order_label">@lang('site.client_type')</label>
                            <select id='client_type' name="client_type" class="form-control require date">
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
                    <div class="col-md-10 textDirection">
                        <div class="select-foreigner-client d-none">
                            <div class="row mb-3">
                                <div class="col-md-11 input-group mb-3">
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
                                            <label for="tax_id_number_or_national_id" class="form-label w-100"></label>
                                            <input type="text" class="form control" id="tax_id_number_or_national_id" />
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
                                    <label for="name" class="form-label w-100" id="min_payment_label">@lang('site.name')
                                    </label>
                                    <input type="text" id="client_name" readonly>
                                </div>

                                {{-- address --}}
                                <div class="col-md-5">
                                    <label for="address" class="form-label w-100"
                                        id="payment_label">@lang('site.address')</label>
                                    <input type="text" id="client_address" readonly>
                                </div>

                                {{-- client_id --}}
                                <div class="col-md-1" id="client-id-container">
                                    <label for="address" class="form-label">@lang('site.id')</label>
                                    <input type="text" name="client_id" id="client_id" readonly>
                                </div>

                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                            </div> <!-- End Of First Row-->

                        </div> <!-- End Of Card Body-->
                    </div>
                </div>

            </form>

        </div>
    </div>

    {{-- Loader for loading purchase order items from excel sheet --}}
    <div class="items-from-excel-sheet-loader" style="display: none">
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

    {{-- Date section --}}
    <script>
        // Validate the entered date not greater than today
        (function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }
            today = yyyy + '-' + mm + '-' + dd;
            $("#to_date").attr("max", today);
            $("#from_date").attr("max", today);
        }());

        $("#to_date").on('change', function() {
            const toDate = $(this).val();
            if ($(this).val() && ( $(this).val() > $(this).attr('max') || ($(this).attr('min') &&  $(this).val() < $(this).attr('min')) ) ){
                $(this).val('');
                $(this).addClass('is-invalid').next().removeClass('d-none');
                return;
            } else {
                $(this).removeClass('is-invalid').next().addClass('d-none');
            }

            // Validate another
            $("#from_date").attr("max", toDate);
            if ($("#from_date").val() && $("#from_date").val() > $(this).val()) {
                $("#from_date").val('');
                // $("#from_date").addClass('is-invalid');
                $('#from-date-greater-than-to-date').removeClass('d-none');
            } else {
                $('#from-date-greater-than-to-date').addClass('d-none');
            }
        })

        $('#from_date').on('change', function() {
            const fromDate = $(this).val();
            if ($(this).val() && ( $(this).val() > $(this).attr('max') || ($(this).attr('min') &&  $(this).val() < $(this).attr('min')) ) ){
                $(this).val('');
                $(this).addClass('is-invalid').next().removeClass('d-none');
                return;
            } else {
                $(this).removeClass('is-invalid').next().addClass('d-none');
            }

            // Validate another
            $("#to_date").attr("min", fromDate);
            if ($("#to_date").val() && $("#to_date").val() < $(this).val()) {
                $("#to_date").val('');
                // $("#to_date").addClass('is-invalid');
                $('#from-date-greater-than-to-date').removeClass('d-none');
            } else {
                $('#from-date-greater-than-to-date').addClass('d-none');
            }
        })
    </script>

    {{-- Client section --}}
    <script>
        $('.search-bank.spinner-border').hide();

        $('#foreigner-client').on('change', function() {
            $('#client_id').val($(this).val());
            $('#client_id').trigger('change');
        })

        $('#client_type').on('change', function() {
            $('#client_id').val('');

            $('.client-details .text-danger').addClass('d-none');
            $('#tax_id_number_or_national_id').val('');
            $('#client_name').val('');
            $('#client_address').val('');

            let selectValue = $(this).val();
            // check selector value
            if (selectValue == 'b' || selectValue == 'p') {
                $('.client-details').removeClass('d-none');
                $('.select-foreigner-client').addClass('d-none');
                $('#client-id-container').insertBefore(".client-details .row .text-danger");
                let labelOrInputParent = $('#tax_id_number_or_national_id').parent();
                // get data(label and name) value in option selected
                let dataLabel = $(this).find('option:selected').data('label');
                //change label text and input name
                labelOrInputParent.find('label').text(dataLabel);
            } else {
                $('.select-foreigner-client').removeClass('d-none');
                $('.client-details').addClass('d-none');
                $('#client-id-container').insertBefore(".select-foreigner-client .row .text-danger");
            }
            let targetSelector = $('#foreigner-client');
            if (targetSelector != '') {
                const urlInputId = $(this).val();
                const url = `${subFolderURL}/${urlLang}/clients/getClientsFromclientType`;
                if ($(this).val() == 'f')
                    sendAjax('GET', url, urlInputId, targetSelector, getForeignerClient)
            }

        });

        function sendAjax(method, url, urlInputId, targetSelector, successFunction) {
            targetSelector.attr('disabled', true);
            $.ajax({
                type: method,
                url: `${url}`,
                success: function(response) {
                    successFunction(response, targetSelector);
                }
            });
        }

        function getForeignerClient(response, targetSelector) {
            targetSelector.attr('disabled', false);
            var response = JSON.parse(response);
            targetSelector.empty();
            targetSelector.append(`<option selected disabled>@lang('site.select') @lang('site.client_type')</option>`);
            for (const key in response) {
                if (response.hasOwnProperty.call(response, key)) {
                    targetSelector.append(
                        `<option value="${key}">${response[key]}</option>`
                    );
                }
            }
        }

        function getBusinessOrPersonClientData(response) {

            if (response) {
                $('#client_id').val(response.id);
                $('#client_id').trigger('keyup');
                $('#client_name').val(response.name);
                $('#client_address').val(response.address);
            } else {
                $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                $('#client_name').val('');
                $('#client_address').val('');
            }
            $('.search-client.spinner-border').hide();
        }

        $('#tax_id_number_or_national_id').keydown(function(e) {
            let key = e.which;
            if (key == 13) {
                $('#client_id').val('');
                let clientType = $('#client_type').val(),
                    searchContent = $(this).val().trim(),
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
                    } else {
                        $('.client-details .text-danger').removeClass('d-none').text(validateError);

                    }
                } else if (clientType == 'p') { // Validate National Id Number

                    if (nationalIdRegex.test(searchContent)) { // valid
                        $('.client-details .text-danger').addClass('d-none');
                        valid = true;
                    } else {
                        $('.client-details .text-danger').removeClass('d-none').text(validateError);
                        $('#client_name').text('');
                        $('#client_address').text('');
                    }

                }
                if (valid) { // If Valid for
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $('.search-bank.spinner-border').show();
                    $.ajax({
                        type: 'POST',
                        url: `${subFolderURL}/${urlLang}/clients/getBusinessOrPersonClientData`,
                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            getBusinessOrPersonClientData(response);
                        },
                        error: function() {
                            $('.client-details .text-danger').removeClass('d-none').text(
                                '@lang("site.no_data")');
                        },
                        complete: function() {
                            $('.search-bank.spinner-border').hide();
                        }
                    });
                }
            }
        });

        // Prevent enter to submit
        $('form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    </script>

    {{-- Submit section --}}
    <script>
        
    </script>
@endsection
