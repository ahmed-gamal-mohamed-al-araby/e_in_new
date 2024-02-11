@extends('pages.layouts.master')
@php
    $currentLang = Config::get('app.locale');
@endphp
@section('title')
    {{ $title }}
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
    <style>
        table.table-bordered.dataTable {
            direction: ltr;
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
                    <h1>{{ $title }} </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $title }} </li>
                        <li class="breadcrumb-item active"> <a
                                href="{{ route('purchaseorders.index') }}">@lang('site.purchaseorders') </a> </li>
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
            {{ $title }}
        </h5>
        <div class="card-body date textDirection">
            <div class="table-responsive" @if($currentLang == 'ar') style="direction: rtl; text-align: right" @endif>
                <table id="status_report" class="table table-bordered table-striped date text-center">
                    <thead>
                        <tr>
                            <th>
                                @lang('site.serial')
                            </th>
                            <th>
                                @lang('site.purchase_order_reference')
                            </th>

                            <th class="hiddenCols">
                                @lang('site.purchaseOrder_type')
                            </th>
                            <th class="">
                            @lang('site.client_name')
                            </th>
                            <th>
                                @lang('site.po_project_name')
                            </th>
                            <th class="hiddenCols">
                                @lang('site.created_at')
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($purchaseorders as $purchaseorder)

                            <tr class="justify-content-center" data-entry-id="11">
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $purchaseorder->purchase_order_reference }}
                                </td>
                                <td>
                                    {{ $purchaseorder->type }}
                                </td>
                                <td>
                                @if ($purchaseorder->client_type == 'f')
                                    {{ $purchaseorder->foreignerClient->company_name }}
                                @elseif($purchaseorder->client_type == 'p')
                                    {{ $purchaseorder->personClient->name }}
                                @else
                                    {{ $purchaseorder->businessClient->name }}
                                @endif
                            </td>
                                <td>
                                    {{ $purchaseorder->project_name }}
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime($purchaseorder->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {

            $("#status_report").DataTable({
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
                        title: '{{ $title }}  @lang('site.report')',
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
                                "<h3 class='mt-1 mb-3'>" + '{{ $title }}  @lang('site.report')' + "</h3>";
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
            }).buttons().container().appendTo('#status_report_wrapper .col-md-6:eq(0)');

            $('[name="status_report_length"] option[value="-1"]').attr('selected', true).parent().trigger('change');
        });
    </script>

@endsection
