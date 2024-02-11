@extends('pages.layouts.master')
@php
$currentLang = Config::get('app.locale');
@endphp
@section('title')
    @lang('site.client_document_balances_report')
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
                        @lang('site.client_document_balances_report')
                    </h1>

                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.client_document_balances_report')
                        </li>
                        <li class="breadcrumb-item active">
                            @lang('site.clients')
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
        <h5 class="card-header bg-success text-center">
            @lang('site.client_document_balances_report') <span id="total-number"></span>
        </h5>
        <div class="card-body">

            {{-- Client section --}}
            <div class="row mb-3 date">
                {{-- Purchase Order Client Type --}}
                <div class="col-md-3 col-12 textDirection">
                    <div class="input-group mb-3">
                        <label class="form-label d-block w-100 textDirection"
                            id="order_label">@lang('site.client_type')</label>
                        <select id='client_type' name="client_type" class="form-control">
                            <option selected disabled>@lang('site.select') @lang('site.client_type')
                            </option>
                            <option value="b" data-label="@lang('site.tax_id_number_only')"
                                data-validate="@lang('site.validate_Tax_id_number')">
                                @lang('site.the_businessClient')</option>
                            <option value="p" data-label="@lang('site.national_id')"
                                data-validate="@lang('site.validate_national_id')">
                                @lang('site.person_client')</option>
                            <option value="f" data-label="@lang('site.vat_id')"
                                data-validate="@lang('site.validate_vat_id')">
                                @lang('site.foreigner_client')</option>
                        </select>
                    </div>
                    @error('client_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foreiner Client --}}
                <div class="col-md-9 col-12 textDirection">

                    <div class="card-body p-0 client-details d-none">
                        <div class="row">
                            <div class="col-md-9 col-12 no-gutters">
                                {{-- tax_id_number for business client Or national ID person client --}}
                                <div class="row no-gutters">
                                    <div class="col-md-11 col-12 input-group">
                                        <label
                                            class="form-label d-block w-100 textDirection">@lang('site.client_name')</label>
                                        <select id='client_name' style="width: 100%" class="form-control rounded"
                                            disabled>
                                            <option selected disabled>@lang('site.select')
                                                @lang('site.client_name')
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-1 bank-spinner pl-0" style="padding:32px 0 0 10px">
                                        <div class="search-bank spinner-border spinner-border-sm text-success"
                                            role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- name --}}
                            <div class="col-md-3 col-12 mb-1">
                                <label for="tax_id_number_or_national_id_or_vat_id" class="form-label w-100 textDirection"
                                    id="min_payment_label">@lang('site.tax_id_number_only') </label>
                                <input type="text" id="tax_id_number_or_national_id_or_vat_id" class="display w-100"
                                    readonly>
                            </div>

                            <input type="hidden" name="client_id" id="client_id">

                            <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                        </div> <!-- End Of First Row-->

                    </div> <!-- End Of Card Body-->

                </div>
            </div>

            <div class="row mb-3 date">
                <div class="col-md-6 mb-3 textDirection">
                    <label for="type" class="form-label">@lang('site.to_date')</label>
                    <input type="date" name="to_date" id="to_date" class="d-block w-100 form-control"
                           placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                           oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.to_date')')"
                           oninput="setCustomValidity('')" required>
                    <div class="col-12 text-center text-danger d-none date-overflow">
                        @lang('site.date_overflow')</div>
                    <div class="text-center text-danger d-none to_date_error">
                        @lang('site.data-required')</div>
                </div>
            </div>

            <div class="row date justify-content-center">
                <div class="col-md-6 textDirection" style="margin-top: 29px">
                    <button type="submit" class="btn btn-success w-100 mb-2">@lang('site.create')
                        @lang('site.report')</button>
                </div>
            </div>

            {{-- Client Balances Report --}}
            <div class="card date textDirection table-card d-none">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clientDocumentBalances_report"
                            class="table table-bordered table-striped text-center d-none date" @if ($currentLang == 'ar') style="direction: rtl; text-align: right" @endif>
                            <thead>
                                <tr>
                                    <th>
                                        @lang('site.serial')
                                    </th>
                                    <th>
                                        @lang('site.client_name')
                                    </th>
                                    <th>
                                        @lang('site.date')
                                    </th>
                                    <th>
                                        @lang('site.document_number')
                                    </th>
                                    <th>
                                        @lang('site.total_amount_without_vat')
                                    </th>
                                    <th>
                                        @lang('site.tax_value')
                                    </th>
                                    <th>
                                        @lang('site.total_amount_with_vat')
                                    </th>
                                    <th>
                                        @lang('site.payment') C_Ch_BT
                                    </th>
                                    <th>
                                        @lang('site.deduction')
                                    </th>
                                    <th>
                                        ضرائب خصم أرباح تجارية و صناعية
                                    </th>
                                    <th>
                                        @lang('site.total')
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
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
        // reset table
        function resetTable() {
            $('#clientDocumentBalances_report').DataTable().destroy();

            $("#clientDocumentBalances_report tbody").html('');
            $('#clientDocumentBalances_report').removeClass('d-none');
        }

        // Run Data Tables Function
        function runDataTable() {
            $("#clientDocumentBalances_report").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "@lang('site.all')"]
                ],
                retrieve: true,
                "buttons": [
                    "copy",
                    {
                        extend: 'excelHtml5',
                        title: `@lang('site.clientDocumentBalances_report') @lang('site.value'): ${$('#total-number').text()}`,
                        exportOptions: {
                            columns: 'th:not(.not-export-col)',
                        },
                        customize: function(doc) {
                            var doc = doc;
                        }
                    },
                    {
                        extend: "print",
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            var last = null;
                            var current = null;
                            var bod = [];

                            var css = '@page { size: landscape; }',
                                head = win.document.head || win.document.getElementsByTagName(
                                    'head')[0],
                                style = win.document.createElement('style');

                            style.type = 'text/css';
                            style.media = 'print';

                            if (style.styleSheet) {
                                style.styleSheet.cssText = css;
                            } else {
                                style.appendChild(win.document.createTextNode(css));
                            }

                            head.appendChild(style);

                            win.document.body.getElementsByTagName('h1')[0].innerHTML =
                                "<h3 class='mt-1 mb-3'>" +
                                `@lang('site.clientDocumentBalances_report') @lang('site.value'): ${$('#total-number').text()}` +
                                "</h3>";
                            win.document.body.getElementsByTagName('h3')[0].style.textAlign =
                                "center";
                        }
                    },
                    "colvis",
                ],
                columnDefs: [{
                    targets: "hiddenCols",
                    visible: false,
                    // targets: '_all',
                    render: function(data, type, row) {
                        if (type === 'PDF') {
                            return data.split(' ').reverse().join(' ');
                        }
                        return data;
                    }
                }],
                "language": {
                    search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                    searchPlaceholder: '@lang("site.search") ',
                    "lengthMenu": "@lang('site.show')  _MENU_ @lang('site.records') ",
                    "paginate": {
                        "previous": "@lang('site.prev')",
                        "next": "@lang('site.next')",
                    },
                    "emptyTable": "@lang('site.no_data')",
                    "info": "@lang('site.show')  _END_ @lang('site.from') _TOTAL_ @lang('site.record')",
                    "infoEmpty": "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                    "infoFiltered": "(@lang('site.search_in')  _MAX_  @lang('site.record'))",

                    buttons: {
                        colvis: '@lang("site.show_data") <i class="fa fa-eye-slash "> </i> ',
                        'print': '@lang("site.print") <i class="fa fa-print "> </i> ',
                        'copy': '@lang("site.copy") <i class="fa fa-copy"> </i>',
                        'excel': '@lang("site.excel") <i class="fa fa-file-excel "> </i>',
                        'pdf': '@lang("site.pdf") <i class="fa fa-file-pdf"> </i>',
                    },
                }
            }).buttons().container().appendTo('#clientDocumentBalances_report_wrapper .col-md-6:eq(0)');

            $('[name="clientDocumentBalances_report_length"] option[value="-1"]').attr('selected', true).parent().trigger(
                'change');
        };
    </script>

    <script>
        let language = [];
        language['send_data'] = "@lang('site.send_data')";
        language['create_report'] = "@lang('site.create') @lang('site.report')";
        language['client_type'] =
            "@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.client_type')";
        language['foreigner_client'] =
            "@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.client')";


        $('[name="client_type"]').select2({
            placeholder: language['client_type'],
        });

        $('#foreigner-client').select2({
            placeholder: language['foreigner_client'],
        });
    </script>

    {{-- Client section --}}
    <script>
        $('.search-bank.spinner-border').hide();

        // Handle Client Type Event (Select)
        $('#client_type').on('change', function() {
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

        // Handle Client Name Event (Select)
        $('#client_name').on('change', function() {
            $('#client_id').val($(this).val());
            $('#tax_id_number_or_national_id_or_vat_id').val($(this).val());
            $('#client_address').val($(this).val());
            $('#client_id').trigger('change');
            $('#tax_id_number_or_national_id_or_vat_id').trigger('change');
            $('#client_address').trigger('change');
            // $('#purchase_order').trigger('change');
        })

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

        function sendPostAjax(method, url, clientType, urlInputId, target, successFunction) {
            target.attr('disabled', true);
            $.ajax({
                type: method,
                data: {
                    clientType: clientType,
                    urlInputId: urlInputId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
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
        // Event To Handle Client Name
        $('#client_name').change(function(e) {
            $('#client_id').val('');
            $('.purchase_order_search_result').addClass('d-none');
            $('.client-details .text-danger').addClass('d-none');
            let clientType = $('#client_type').val();
            searchContent = $(this).val();
            sendData = {
                clientType: clientType,
                searchContent: searchContent,
            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if ($(this).val() != null) {
                $('.search-bank.spinner-border').show();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('getBusinessOrPersonClientDataByName') }}",
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
        });

        // Client Type
        select2Function($('#client_type'), "@lang('site.client_type')");
        // Client Name
        select2Function($('#foreigner-client'), "@lang('site.client_name')");
        select2Function($('#client_name'), "@lang('site.client_name')");
        // Purchase Order
        select2Function($('#purchase_order'), "@lang('site.purchaseorder')");

        function select2Function(selector, placeholder) {
            const targetSelector = $(selector).parent().parent().next().find('select');
            $(selector).select2();
            $(selector).select2({
                allowClear: true,
                placeholder: placeholder,
            });
        }
    </script>

    {{-- Submit section --}}
    <script>
        $('[type="submit"]').on('click', function() {
            submit();
        })

        function validate() {
            $('#client_type').val() ? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass(
                'd-none');
            $('#tax_id_number_or_national_id_or_vat_id').val() ? $('.tax_id_number_or_national_id_or_vat_id_error')
                .addClass('d-none') : $('.tax_id_number_or_national_id_or_vat_id_error').removeClass('d-none');

            return $('#client_type').val() && $('#client_name').val() && $('#tax_id_number_or_national_id_or_vat_id').val();
        }

        function prepareDataToSubmit() {
            return {
                'clientType': $('#client_type').val() || null,
                'clientId': $('#client_id').val() || null,
                'to_date': $('#to_date').val() || null,
            }
        }

        function submit() {
            $('[type="submit"]').text(language['send_data']);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var data = prepareDataToSubmit();

            let ajaxURL = `${subFolderURL}/${urlLang}/reports/client-document-balances`;
            let ajaxMethod = 'post';

            $('[type="submit"]').css("pointer-events", "none");

            $('.loader-container').fadeIn();
            $.ajax({
                url: ajaxURL,
                type: ajaxMethod,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(data),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(documents) {
                    resetTable();
                    $('[type="submit"]').text(language['create_report']);
                    $('[type="submit"]').css("pointer-events", "auto");

                    let sum = 0;
                    let counter = 0;
                    documents.forEach((document, index) => {
                        // if(document.document_balance >= 0.1 || document.document_balance <= -0.1) {
                        if (document.document_balance != 0) {
                            counter++;
                            const newRow = $('<tr></tr>');
                            newRow.append($(`<td>${counter}</td>`));
                            newRow.append($(`<td>${document.supplier}</td>`));
                            newRow.append($(`<td>${document.date}</td>`));
                            newRow.append($(`<td>${document.reference}</td>`));
                            newRow.append($(
                                `<td>${document.totalwithoutTaxes.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(
                                `<td>${document.taxes.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(
                                `<td>${document.totalwithTaxes.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(
                                `<td>${document.C_Ch_BT.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(
                                `<td>${document.deductionwithout_D1.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(
                                `<td>${document.deduction_D1.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`
                            ));
                            newRow.append($(`<td>${document.document_balance.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })}</td>`));
                            // newRow.append($(`<td>${removeExponential(document.document_balance)}</td>`));
                            sum += document.document_balance;

                            $("#clientDocumentBalances_report tbody").append(newRow);
                        }
                    });
                    $('#total-number').text(
                        `( ${removeExponential(sum)} )`
                    );
                    // $('#total-number').text(
                    //     `( ${sum.toLocaleString('us', { minimumFractionDigits: 2, maximumFractionDigits: 20 })} )`
                    // );
                    $('.table-card').removeClass('d-none');
                    runDataTable();
                    $('.loader-container').fadeOut(250);
                },
            });
        }

        // Add Arabic Font To Data Tables
        pdfMake.fonts = {
            Cairo: {
                normal: "{{ asset('plugins/fonts/Cairo/Cairo-Regular.ttf') }}",
                bold: "{{ asset('plugins/fonts/Cairo/Cairo-SemiBold.ttf') }}",
                italics: "{{ asset('plugins/fonts/Cairo/Cairo-Light.ttf') }}",
                bolditalics: "{{ asset('plugins/fonts/Cairo/Cairo-ExtraLight.ttf') }}"
            },
            // download default Roboto font from cdnjs.com
            Roboto: {
                normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf',
                bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf',
                italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf',
                bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf'
            },
        }

        function removeExponential(x) {
            const sign = (x < 0) ? '-' : '';
            if (Math.abs(x) < 1.0) {
                var e = parseInt(x.toString().split('e-')[1]);
                if (e) {
                    x *= Math.pow(10, e - 1);
                    x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
                    const index = x.indexOf('.', (2));
                    x = x.substring(0, index) + "" + x.substring(index + 1);
                }
            } else {
                var e = parseInt(x.toString().split('+')[1]);
                if (e > 20) {
                    e -= 20;
                    x /= Math.pow(10, e);
                    x += (new Array(e + 1)).join('0');
                }
            }
            if (String(x)[0] != '-')
                return sign + x;
            else
                return x;
        }
    </script>
@endsection
