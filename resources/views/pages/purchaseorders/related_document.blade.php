@extends('pages.layouts.master')

@section('title')
    @lang('site.related_documents_to_purchaseOrder')
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
                        @lang('site.related_documents_to_purchaseOrder')
                    </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.related_documents_to_purchaseOrder')
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('purchaseorders.index') }}">
                                @lang('site.purchaseorder')</a></li>
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
            <div id="feeds-box" role="tabpanel" class="collapse0 show m-3 mt-4" style="">
                <div class="row">
                    <div class="form-group col-12 col-md-6 col-xl-7">
                        <input name="reference" id="PO_reference" type="text" required class="form-control"
                            placeholder="@lang('site.please') @lang('site.enter') @lang('site.reference')"
                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.reference')')"
                            oninput="setCustomValidity('')">
                    </div>
                    <div class="col-12 col-md-2 col-xl-1 justify-content-center row align-content-center my-2 mx-md-0">
                        <div class="form-check pl-0">
                            <input class="form-check-input" type="checkbox" name="sent" id="sent-checkbox" style="margin-left: 2.5rem;">
                            <label class="form-check-label" for="sent-checkbox">
                                @lang('site.sent')
                            </label>
                        </div>
                    </div>
                    <div class="textDirection col-12 col-md-4">
                        <button type="submit" class="btn btn-success w-100 mb-2">@lang('site.search')</button>
                    </div>
                    <div class="col-12 col-md-8 text-center text-danger d-none reference_error">
                        @lang('site.data-required')</div>
                </div>
            </div>

            <div class="card-body">
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

        $('#PO_reference').on('change', function () {
            $(this).val()? $('.reference_error').addClass('d-none'): $('.reference_error').removeClass('d-none');
        })
        function validate() {
            $('#PO_reference').val()? $('.reference_error').addClass('d-none') : $('.reference_error').removeClass('d-none');

            return  $('#PO_reference').val();
        }

        function prepareDataToSubmit() {
            return {
                'PO_reference': $('#PO_reference').val() || null,
                'sent': $('#sent-checkbox').is( ":checked" )? 1 : 0,
            }
        }

        function submit() {
            $('[type="submit"]').text(language['send_data']);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var data = prepareDataToSubmit();

            let ajaxURL = `${subFolderURL}/${urlLang}/purchaseorders/related-documents`;
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
