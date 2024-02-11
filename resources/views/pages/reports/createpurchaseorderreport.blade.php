@extends('pages.layouts.master')

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
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

        .bouncing-loader > div {
            width: 1rem;
            height: 1rem;
            margin: 3rem 0.2rem;
            background: rgb(4, 182, 4);
            border-radius: 50%;
            animation: bouncing-loader 0.8s infinite alternate;
        }

        .bouncing-loader > div:nth-child(2) {
            animation-delay: 0.2s;
        }

        .bouncing-loader > div:nth-child(3) {
            animation-delay: 0.4s;
        }

        .bouncing-loader > div:nth-child(4) {
            animation-delay: 0.6s;
        }

        .bouncing-loader > div:nth-child(5) {
            animation-delay: 0.8s;
        }

        .items-from-ajax-load {
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

        .display {
            border-radius: 0;
        }

        .container {
            margin: 0 auto;
        }

        #print {
            border-radius: 0;
        }

        @media print {
            #print, .main-footer, .action {
                display: none;
            }

            #client_type {
                width: 25%;
            }
        }
    </style>
    @if (Config::get('app.locale') == 'ar')
        <style>
            .dataDirection {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

        </style>
    @else
        <style>
            .dataDirection {
                direction: ltr !important;
            }

            .textDirection {
                text-align: left;
            }
        </style>
    @endif
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.create_purchase_orders_report')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.create_purchase_orders_report') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.create_purchase_order_report')</h2>
            <form method="POST" id="PoForm" class="PoForm" action="{{ route('purchaseorders.store') }}">
                @csrf

                {{-- purchaseorder details --}}

                <fieldset class="textDirection">
                    <div class="fieldset-content">
                        <div class="card ml-2 textDirection">

                            <h5 class="card-header bg-success">
                                @lang('site.create_purchase_order_report')
                            </h5>

                            <div class="card-body textDirection ">

                                {{-- Client section --}}
                                <div class="row mb-1 textDirection dataDirection">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-3 col-sm-3 textDirection">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100 textDirection"
                                                   id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require">
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
                                    <div class="col-md-9 col-sm-9">

                                        <div class="card-body p-0 client-details d-none">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-4  no-gutters">
                                                    {{-- tax_id_number for business client Or national ID person client --}}
                                                    <div class="row no-gutters">
                                                        <div class="col-md-11 col-sm-11 input-group">
                                                            <label
                                                                class="form-label d-block w-100 textDirection">@lang('site.client_name')</label>
                                                            <select id='client_name' style="width: 100%"
                                                                    class="form-control rounded require" disabled>
                                                                <option selected disabled>@lang('site.select')
                                                                    @lang('site.client_name')
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-1 bank-spinner"
                                                             style="padding:32px 0 0 10px">
                                                            <div
                                                                class="search-bank spinner-border spinner-border-sm text-success"
                                                                role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- name --}}
                                                <div class="col-md-3 col-sm-3 mb-1">
                                                    <label for="tax_id_number_or_national_id_or_vat_id"
                                                           class="form-label w-100 textDirection"
                                                           id="min_payment_label">@lang('site.tax_id_number_only') </label>
                                                    <input type="text" id="tax_id_number_or_national_id_or_vat_id"
                                                           class="display" readonly>
                                                </div>

                                                {{-- client_id --}}
                                                {{-- <div class="col-md-2 textDirection"> --}}
                                                {{-- <label for="address" class="form-label textDirection">@lang('site.id')</label> --}}
                                                <input type="hidden" name="client_id" id="client_id">
                                                {{-- </div> --}}

                                                {{-- Purchase Orderss --}}
                                                <div class="col-md-5 col-sm-5" id="client-id-container">
                                                    <label
                                                        class="form-label d-block w-100 textDirection">@lang('site.select') @lang('site.purchaseorder')</label>
                                                    <select id='purchase_order' style="width: 100%"
                                                            class="form-control rounded require" disabled>
                                                        <option selected disabled>@lang('site.select')
                                                            @lang('site.purchaseorder')
                                                        </option>
                                                    </select>
                                                </div>

                                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                                            </div> <!-- End Of First Row-->

                                        </div> <!-- End Of Card Body-->

                                    </div>

                                </div>

                                <div class="row ">
                                    <div class="col-md-12 col-sm-12 ">
                                        <div class="purchase_order_search_result d-none">
                                            <div class="container">
                                                <h4 style="background-color: #eee;"
                                                    class="textDirection p-1">  @lang("site.purchase_order_attention") </h4>

                                                <table id="purchase_order_result"
                                                       class="table table-bordered table-responsive table-striped dataDirection">
                                                    <thead class="textDirection">
                                                    <tr style="text-align:center;" class="textDirection">
                                                        <th> @lang('site.serial')</th>
                                                        <th>@lang('site.purchase_order_reference') </th>
                                                        <th>@lang('site.purchaseOrder_type') </th>
                                                        <th>@lang('site.client_name')</th>
                                                        <th>@lang('site.client_type')</th>
                                                        <th>@lang('site.tax_id_number_only')
                                                            || @lang('site.national_id') || @lang('site.vat_id')</th>
                                                        <th>@lang('site.po_project_name')</th>
                                                        <th>@lang('site.created_at')</th>
                                                        <th>@lang('site.total_amount')</th>
                                                        <th class="action">@lang('site.actions')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="purchase_order_result">

                                                    </tbody>

                                                </table>

                                                <h4 style="background-color: #eee;"
                                                    class="textDirection p-1">  @lang("site.documents_relatedTo_purchase_order_attention") </h4>
                                                <table id="documents_relatedto_purchase_order_table"
                                                       class="table table-bordered table-responsive table-striped dataDirection">
                                                    <thead>
                                                    <tr style="text-align:center;" class="textDirection">
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
                                                            @lang('site.issuer_from')
                                                        </th>
                                                        <th>
                                                            @lang('site.client_name')
                                                        </th>
                                                        <th>
                                                            @lang('site.client_type')
                                                        </th>
                                                        <th>
                                                            @lang('site.items_number')
                                                        </th>
                                                        <th>
                                                            @lang('site.total_amount')
                                                        </th>
                                                        <th class="action">
                                                            @lang('site.actions')
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="documents_relatedto_purchase_order_result">

                                                    </tbody>
                                                </table>

                                                <h4 style="background-color: #eee;"
                                                    class="textDirection p-1">  @lang("site.estimated_purchase_order") </h4>
                                                <table id="estimated_purchase_order_result"
                                                       class="table table-bordered table-responsive table-striped dataDirection">
                                                    <thead>
                                                    <tr style="text-align:center;" class="textDirection">
                                                        <th> @lang('site.serial')</th>
                                                        <th>@lang('site.purchase_order_reference') </th>
                                                        <th>@lang('site.purchaseOrder_type') </th>
                                                        <th>@lang('site.client_name')</th>
                                                        <th>@lang('site.client_type')</th>
                                                        <th>@lang('site.tax_id_number_only')
                                                            || @lang('site.national_id') || @lang('site.vat_id')</th>
                                                        <th>@lang('site.po_project_name')</th>
                                                        <th>@lang('site.created_at')</th>
                                                        <th>@lang('site.estimated_total')</th>
                                                        <th class="action">@lang('site.actions')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="estimated_purchase_order_result">

                                                    </tbody>
                                                </table>

                                                <div class="row justify-content-center">
                                                    <div class="col-md-12 justify-content-center text-center">
                                                        <button id="print"
                                                                class="btn btn-success justify-content-center pl-5 pr-5">@lang('site.print')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </fieldset>

            </form>
        </div>
    </section>

    <!-- Modal to set items via excel sheet -->

    {{-- Loader for loading purchase order items from excel sheet --}}
    <div class="items-from-ajax-load" style="display: none">
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
    <script>
        let language = [];
        language['save'] = "@lang('site.save')";

        let validationMessages = [];
        validationMessages['client_type'] = "@lang('site.validate_client_type_message')";
        validationMessages['client_id'] = "@lang('site.validate_client_id_message')";

    </script>

    <script>

        $('.search-bank.spinner-border').hide();
        $('.search-product.spinner-border').hide();

    </script>

    <script>

        // Handle Client Type Event (Select)
        $('#client_type').on('change', function () {
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
{{--                const url = "{{ route('getClientsFromclientType') }}";--}}
                if ($(this).val() == 'b' || $(this).val() == 'p' || $(this).val() == 'f')
                    sendAjax('GET', url, urlInputType, targetName, getBusinessOrPersonClientDataByName)
            }

        });

        // Handle Client Name Event (Select)
        $('#client_name').on('change', function () {
            $('#client_id').val($(this).val());
            $('#tax_id_number_or_national_id_or_vat_id').val($(this).val());
            $('#client_address').val($(this).val());
            // $('#purchase_order').val($(this).val());
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
                success: function (response) {
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
                success: function (response) {
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

        // Get Purchase Orders For Clients
        function getPurchaseOrdersForClient(response, targetName) {
            targetName.attr('disabled', false);
            // var response = JSON.parse(response);
            targetName.empty();
            targetName.append(`<option selected disabled>@lang('site.select') @lang('site.purchaseorder')</option>`);
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

        // Get PO Data To Blade
        function getPurchaseOrderData(response, targetTable) {
            targetTableRow = $('.purchase_order_result')
            targetTableRow.empty();
            targetTableRow.append(response);
        }

        // Get Documents Related To PO To View In Blade
        function getDocumentsRelatedTopurchaseOrder(response) {
            targetTableRow = $('.documents_relatedto_purchase_order_result')
            targetTableRow.empty();
            targetTableRow.append(response);
        }

        // Get Estimated PO To Blade
        function getEstimatedpurchaseOrder(response) {
            targetTableRow = $('.estimated_purchase_order_result')
            targetTableRow.empty();
            targetTableRow.append(response);
        }

        // Event To Handle Client Name
        $('#client_name').change(function (e) {
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
                    success: function (response) {
                        getBusinessOrPersonClientData(response);
                    },
                    error: function () {
                        $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                    },
                    complete: function () {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }

            let targetSelector = $('#purchase_order');
            if (targetSelector != '') {
                const urlInputId = $(this).val();
                const clientType = $('#client_type').val();
                const url = `${subFolderURL}/${urlLang}/reports/getPurchaseOrdersForClient`;
{{--                const url = "{{ route('getPurchaseOrdersForClient') }}";--}}
                if ($(this).val() != '') {
                    sendPostAjax('POST', url, clientType, urlInputId, targetSelector, getPurchaseOrdersForClient)
                }
            }
        });

        // Event To Handle Purchase Order
        $('#purchase_order').on('change', function (e) {

            $('.purchase_order_search_result').addClass('d-none');
            let clientType = $('#client_type').val();
            let purchaseOrder = $('#purchase_order').val();
            searchContent = $(this).val();

            sendData = {
                clientType: clientType,
                searchContent: searchContent,
            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if ($(this).val() != null) {
                $('.items-from-ajax-load').fadeIn();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('getPurchaseOrderData') }}",
                    data: sendData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // dataType: 'HTML',
                    success: function (response) {
                        $('.purchase_order_search_result').removeClass('d-none');
                        getPurchaseOrderData(response);
                        getDocumetsRelatedToPurchaseOrderFromModel(purchaseOrder);
                        getEstimatedPurchaseOrderData(purchaseOrder);
                        $('.items-from-ajax-load').fadeOut(250);
                    },
                    error: function () {
                        $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                        $('.purchase_order_search_result').addClass('d-none');
                    },
                    complete: function () {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }
        });

        // Get Documents Related To PO
        function getDocumetsRelatedToPurchaseOrderFromModel(purchaseOrderId) {
            var searchContent = purchaseOrderId;

            if (searchContent.length != null || searchContent.length != '') {

                $.ajax({
                    url: "{{ route('getDocumentsRelatedTopurchaseOrder') }}",
                    type: "POST",
                    data: {searchContent},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'html',
                    success: function (response) {
                        getDocumentsRelatedTopurchaseOrder(response);
                    },
                    error: function (data) {
                        $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                    }
                });
            } else {
                $('.purchase_order_search_result').addClass('d-none');
            }
        }

        // Get Estimated PO From Ajax
        function getEstimatedPurchaseOrderData(purchaseOrderId) {
            var searchContent = purchaseOrderId;

            if (searchContent.length != null || searchContent.length != '') {

                $.ajax({
                    url: "{{ route('getEstimatedPurchaseOrderData') }}",
                    type: "POST",
                    data: {searchContent},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'html',
                    success: function (response) {
                        getEstimatedpurchaseOrder(response);
                    },
                    error: function (data) {
                        $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                    }
                });
            } else {
                $('.purchase_order_search_result').addClass('d-none');
            }
        }

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

        // Event To handle Print Button
        $('#print').on('click', function (event) {

            window.print();
            window.onafterprint = function (e) {
                // closePrintView();
                $(this).removeClass('d-none');
            };

            return false;
        });

        function closePrintView() {
            window.location.href = "http://eecinvoice.online/ar/reports/purchaserrderreport";
        }

    </script>

@endsection
