@extends('pages.layouts.master')

@section('title')
    @lang('site.related_documents_to_client')
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

{{-- Page content --}}
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>
                        @lang('site.related_documents_to_client')
                    </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.related_documents_to_client')
                        </li>
                        <li class="breadcrumb-item active">
                            @lang('site.clients')
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="col-12">
        <!-- Partner Requests-->
        <div class="card">
            <div class="card-body">
                {{-- Client section --}}
                <div class="row mb-3 date">
                    {{-- Purchase Order Client Type --}}
                    <div class="col-md-3 col-12 textDirection">
                        <div class="input-group mb-3">
                            <label class="form-label d-block w-100 textDirection"
                                id="order_label">@lang('site.client_type')</label>
                            <select id='client_type' name="client_type" class="form-control require" required>
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
                        <div class="text-center text-danger d-none client_type_error">
                            @lang('site.data-required')</div>
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
                                            <select id='client_name' style="width: 100%" class="form-control rounded require"
                                                disabled>
                                                <option selected disabled>@lang('site.select')
                                                    @lang('site.client_name')
                                                </option>
                                            </select>
                                            <div class="text-center text-danger d-none w-100 client_name_error mt-3">
                                                @lang('site.data-required')</div>
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
                                    <input type="text" id="tax_id_number_or_national_id_or_vat_id" class="display w-100" readonly>
                                    <div class="text-center text-danger d-none tax_id_number_or_national_id_or_vat_id_error mt-3">
                                        @lang('site.data-required')</div>
                                </div>

                                <input type="hidden" name="client_id" id="client_id" required>

                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                            </div> <!-- End Of First Row-->

                        </div> <!-- End Of Card Body-->

                    </div>
                </div>

                <div class="row justify-content-center align-content-center my-2 mx-md-0">
                    <div class="form-check pl-0">
                        <input class="form-check-input" type="checkbox" name="sent" id="sent-checkbox" style="margin-left: 2.5rem;">
                        <label class="form-check-label" for="sent-checkbox">
                            @lang('site.sent')
                        </label>
                    </div>
                </div>

                <div class="row date justify-content-center">
                    <div class="col-md-6 textDirection" style="margin-top: 29px">
                        <button type="submit" class="btn btn-success w-100 mb-2">@lang('site.search')</button>
                    </div>
                </div>

                {{-- Documents table --}}
                <div class="card table-card d-none">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="relatedDocumentsTable" class="related-documents-table table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            @lang('site.serial')
                                        </th>
                                        <th>
                                            @lang('site.document_number')
                                        </th>
                                        <th>
                                            @lang('site.document_type')
                                        </th>
                                        <th>
                                            @lang('site.date')
                                        </th>
                                        <th>
                                            @lang('site.document_version')
                                        </th>
                                        <th>
                                            @lang('site.purchaseorder')
                                        </th>
                                        <th>
                                            @lang('site.sent')
                                        </th>
                                        <th>
                                            @lang('site.archive')
                                        </th>
                                        <th>
                                            @lang('site.actions')
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
        <!-- End Partner Requests-->


    </div>
    <!-- /.content -->

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
            $('#relatedDocumentsTable').DataTable().destroy();

            $("#relatedDocumentsTable tbody").html('');
            $('#relatedDocumentsTable').removeClass('d-none');
        }

        // Run Data Tables Function
        function runDataTable(){
            let dateEnd = $('#to_date').val();

            $("#relatedDocumentsTable").DataTable({
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
                        title:  `@lang('site.daily_client_balances_report') @lang('site.to') ( ${dateEnd} ) @lang('site.value'): ${$('#total-number').text()}`,
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
                                "<h3 class='mt-1 mb-3 text-direction'>" + `@lang('site.daily_client_balances_report') @lang('site.to') ( ${dateEnd} ) @lang('site.value'): ${$('#total-number').text()}` + "</h3>",
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
            }).buttons().container().appendTo('#relatedDocumentsTable_wrapper .col-md-6:eq(0)');
        };
    </script>

    <script>
        let language = [];
        language['send_data'] = "@lang('site.send_data')";
        language['search'] = "@lang('site.search')";
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
            $('#client_type').val()? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass('d-none');
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

            if(targetName != ''){
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
                data:{
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
                data:{
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
            $('#client_name').val()? $('.client_name_error').addClass('d-none') : $('.client_name_error').removeClass('d-none');

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
                        $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
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

        const documentTypes = {
            'I': 'Invoice',
            'C': 'Credit',
            'D': 'Debit',
        };

        $('[type="submit"]').on('click', function() {
            if (validate()) {
                submit();
            }
        })

        function validate() {
            $('#client_type').val()? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass('d-none');
            $('#client_name').val()? $('.client_name_error').addClass('d-none') : $('.client_name_error').removeClass('d-none');
            $('#tax_id_number_or_national_id_or_vat_id').val()? $('.tax_id_number_or_national_id_or_vat_id_error').addClass('d-none') : $('.tax_id_number_or_national_id_or_vat_id_error').removeClass('d-none');

            return  $('#client_type').val() &&  $('#client_name').val() && $('#tax_id_number_or_national_id_or_vat_id').val();
        }

        function prepareDataToSubmit() {
            return {
                'clientType': $('#client_type').val() || null,
                'clientId': $('#client_id').val() || null,
                'sent': $('#sent-checkbox').is( ":checked" )? 1 : 0,
            }
        }

        function submit() {
            $('[type="submit"]').text(language['send_data']);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var data = prepareDataToSubmit();

            let ajaxURL = `${subFolderURL}/${urlLang}/clients/related-documents`;
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
                    $('[type="submit"]').text(language['search']);
                    $('[type="submit"]').css("pointer-events", "auto");

                    documents.forEach((document, index) => {
                        
                        const newRow = $('<tr></tr>');
                        newRow.append($(`<td>${index+1}</td>`));
                        newRow.append($(`<td>${document.document_number}</td>`));
                        newRow.append($(`<td>(${document.type}) ${documentTypes[document.type]}</td>`));
                        newRow.append($(`<td>${document.date}</td>`));
                        newRow.append($(`<td>${document.version}</td>`));
                        newRow.append($(`<td><a href="${subFolderURL}/${urlLang}/purchaseorders/${document.PO_id}" target="_blank">${document.PO_reference}</a></td>`));
                        newRow.append($(`<td>${document.submit_status == 1? '<i class="fas fa-check text-success">': '<i class="fas fa-times text-danger"></i>'}</td>`));
                        newRow.append($(`<td>${document.archive == 1? '<i class="fas fa-check text-success">': '<i class="fas fa-times text-danger"></i>'}</td>`));
                        newRow.append($(`<td class="requests-btn"><div class="service-option-document text-center"><a href="${subFolderURL}/${urlLang}/documents/${document.id}" class=" btn btn-success"><i class="fa fa-eye"></i> </a></div></td>`));

                        $("#relatedDocumentsTable tbody").append(newRow);
                    });

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

    </script>
@endsection
