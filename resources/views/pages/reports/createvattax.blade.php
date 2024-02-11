@extends('pages.layouts.master')

@section('title')
    @lang('site.reports')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
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
            display:grid;
            position:fixed;
            top:0;
            left:0;
            bottom:0;
            right:0;
            border:solid;
            background: rgba(0, 0, 0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #createReport, .date{
            border-radius: 0;
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
<section class="content-header prequestHeader">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-between">
                <h1>
                    @lang('site.reports')
                </h1>
            {{-- </div>
            <div class="col-md-6"> --}}
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                    <li class="breadcrumb-item active">
                        @lang('site.reports')
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card">
    <div class="card-body">
        <div class="row dataDirection">
            <div class="col-md-2 mb-3 textDirection">
                <label for="type" class="form-label">@lang('site.from_date')</label>
                <input type="date" required name="from_date" id="from_date" class="date d-block w-100"
                    placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                    oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.from_date')')"
                    oninput="setCustomValidity('')">
                <p class="date-validation d-none text-danger"></p>
            </div>

            <div class="col-md-2 mb-3 textDirection">
                <label for="type" class="form-label">@lang('site.to_date')</label>
                <input type="date" required name="to_date" id="to_date" class="date d-block w-100 "
                    placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                    oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.to_date')')"
                    oninput="setCustomValidity('')">
                <p class="date-validation d-none text-danger"></p>
            </div>

            <div class="col-md-2 mb-3 textDirection">
                <label for="" class="form-label">  النوع </label>

                <select name="subtype" class="custom-select subtype" id="subtype">
                  <option value=""  selected="">اختر</option>
                    <option value="V001">V001</option>
                    <option value="V002">V002</option>
                    <option value="V003">V003</option>
                    <option value="V004">V004</option>
                    <option value="V005">V005</option>
                    <option value="V006">V006</option>
                    <option value="V007">V007</option>
                    <option value="V008">V008</option>
                    <option value="V009">V009</option>
                    <option value="V010">V010</option>

                    </option>

                </select>

                @error('subtype')
                <div class="text-danger">{{ $message }}
                </div>
                @enderror

              </div>
              <div class="col-md-2 mb-3 textDirection">
                <label for="" class="form-label">  الضريبة </label>

                <select name="tax_rate" class="custom-select subtype" id="tax_rate">
                  <option value=""  selected="">اختر</option>
                    <option value="0">0</option>
                    <option value="5">5%</option>
                    <option value="10">10%</option>
                    <option value="14">14%</option>



                    </option>

                </select>

                @error('tax_rate')
                <div class="text-danger">{{ $message }}
                </div>
                @enderror

              </div>


            <div class="col-md-3 mb-3" style="margin-top: 30px">
                <input id="createReport" type="submit"
                        class="btn btn-success" value="@lang('site.create') @lang('site.report')">
            </div>
        </div>
        {{-- End Of Dates Section --}}

        <div class="table-responsive">
            <div class="result_header">
                @if ($documents->count() > 0)
                    <div class="alert alert-success textDirection" role="alert">
                        <h2 class="pt-2 pb-2 textDirection">@lang('site.documents') </h2>
                        <span class="h5"><u>@lang('site.net_total')</u> :</span> <span class="h6"><span id="sumOfNetTotalSpan"></span></span>
                        <span class="h5">  -  <u>@lang('site.tax_value')</u> :</span> <span class="h6"> <span id="sumOftotalTaxesSpan"></span> </span><br>
                        @lang('site.from') <span id="dateStartSpan">{{ $dateStart }}</span> @lang('site.to')
                        <span id="dateEndSpan">{{ $dateEnd }}</span>
                        <span class="h5"><u>@lang('site.documents')</u></span>
                        <span id="documentNumberSpan">({{$documents->count()}})</span>
                    </div>

                @else
                    <div class="alert alert-danger textDirection" role="alert">
                        <h2 class="pt-2 pb-2 textDirection">@lang('site.no_data')</h2> @lang('site.from') <span id="dateStart">{{ $dateStart ?? '?' }}</span> @lang('site.to') <span id="dateEnd">{{ $dateEnd ?? '?' }}</span>
                    </div>
                @endif
            </div>
            <table id="vattaxreport" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th data-toggle="tooltip" data-placement="top"
                            title="1.@lang('site.invoice')&#013; 2.@lang('site.debit')&#013; 3.@lang('site.credit')">
                            @lang('site.document_type')
                        </th>
                        <th data-toggle="tooltip" data-placement="top"
                            title="1.@lang('site.public_items')&#013; 2.@lang('site.table_items')">
                            @lang('site.tax_type_report')
                        </th>
                        <th data-toggle="tooltip" data-placement="top"
                            title="1.@lang('site.no_item')&#013; 2.@lang('site.table_first')&#013; 3.@lang('site.table_second')">
                            @lang('site.table_items_type')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.document_number')
                        </th>
                        <th>
                            @lang('site.receiver_name')
                        </th>
                        <th>
                            @lang('site.client_tax_registration_number')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.client_tax_file_number')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.address')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.national_id')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.mobile')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.date')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.product_name')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.product_code')
                        </th>
                        <th class="hiddenCols" data-toggle="tooltip" data-placement="top"
                                title="1.@lang('site.statement_item')&#013; 2.@lang('site.service')&#013; 3.@lang('site.adjustments')">
                            @lang('site.statement_type')
                        </th>
                        <th class="hiddenCols" data-toggle="tooltip" data-placement="top"
                                title="1.@lang('site.local')&#013; 2.@lang('site.exports')&#013; 3.@lang('site.machines_equipment')
                                &#013;4.@lang('site.exemptions')">
                            @lang('site.item_type')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.unit')
                        </th>
                        <th class="hiddenCols">
                            @lang('site.unit_price')
                        </th>
                        <th>
                            @lang('site.tax_category')
                        </th>
                        <th>
                        @lang('site.type')
                        </th>

                        <th>
                            @lang('site.quantity')
                        </th>
                        <th>
                            @lang('site.total_amount')
                        </th>
                        <th>
                            @lang('site.discount_amount')
                        </th>
                        <th>
                            @lang('site.net_total')
                        </th>
                        <th>
                            @lang('site.tax_value')
                        </th>

                        <th>
                            @lang('site.total')
                        </th>
                        <th class="hiddenCols not-export-col">
                            @lang('site.actions')
                        </th>
                    </tr>
                </thead>
                <tbody class="document_results">
                    @php
                        $sumOfNetTotal = [];
                        $sumOftotalTaxes = [];
                        $test = [];
                    @endphp

                </tbody>
                <input type="hidden" id="sumOfNetTotal" name="sumOfNetTotal" value="{{ array_sum($sumOfNetTotal) }}">
                <input type="hidden" id="sumOftotalTaxes" name="sumOftotalTaxes" value="{{ array_sum($sumOftotalTaxes) }}">
            </table>
        </div>
        {{-- @endif --}}
    </div>
</div>

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

        // Handle Submit Button To Create Report
        $('#createReport').on('click', function(e) {

            $('.date-validation').addClass('d-none');
            let fromDate = $('#from_date').val();
            let toDate = $('#to_date').val();
            let subtype = $('#subtype').val();
            let tax_rate = $('#tax_rate').val();
            sendData = {
                fromDate: fromDate,
                toDate: toDate,
                subtype: subtype,
                tax_rate: tax_rate,

            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            if (fromDate != '' && toDate != '') {
                    $('.items-from-ajax-load').fadeIn();
                    $.ajax({
                    type: 'POST',
                    url: "{{ route('reports.getDocumentsToVatTaxReport') }}",
                    data: sendData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        getDocuments(response);
                        runDataTable();
                        $('.items-from-ajax-load').fadeOut(250);
                    },
                    error: function() {
                        $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                    },
                    complete: function() {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }else {

                $('.date').each(function () {
                    if ($(this).val() == ''){
                        $(this).next().removeClass('d-none').text('@lang("site.data-required")');
                    }else {
                        $('.date .date-validation').addClass('d-none');
                    }
                });
                // $('.date-validation').removeClass('d-none').text('@lang("site.data-required")');

            }
        });





        // Handle Change Inputs date
        $('.date').on('change', function () {
            $(this).next().addClass('d-none');
        });

        // Add Documents To Blade View
        function getDocuments(response) {
            var dt1 = $.fn.dataTable.tables()[0];
            $(dt1).DataTable().destroy();
            $('.document_results').remove();
            $('#dateStart').remove();
            $('#dateEnd').remove();

            $('#documentNumber').remove();
            $('#sumOfNetTotal').remove();
            $('#sumOftotalTaxes').remove();
            var table = $('#vattaxreport');
            // targetTableRow.empty();
            table.append(response);
            updateData();
        }

        // Run Data Tables Function
        function runDataTable(){
            var dateStart = $('#dateStartSpan').text();
            var dateEnd = $('#dateEndSpan').text();

            $("#vattaxreport").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "@lang('site.all')"]],
                retrieve: true,
                "buttons":
                [
                    "copy",
                    {
                        extend: 'excelHtml5',
                        title: '( '+ dateEnd + ' ) - ( '+ dateStart  + ') تقرير عن القيمة المضافة فى الفترة من',
                        exportOptions: {
                            columns: 'th:not(.not-export-col)',
                        },
                        customize: function (doc) {
                            var doc = doc;
                        }
                    },
                    {
                        extend: "print",
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win)
                        {
                            var last = null;
                            var current = null;
                            var bod = [];

                            var css = '@page { size: landscape; }',
                                head = win.document.head || win.document.getElementsByTagName('head')[0],
                                style = win.document.createElement('style');

                            style.type = 'text/css';
                            style.media = 'print';

                            if (style.styleSheet)
                            {
                               style.styleSheet.cssText = css;
                            }
                            else
                            {
                                style.appendChild(win.document.createTextNode(css));
                            }

                            head.appendChild(style);

                            win.document.body.getElementsByTagName('h1')[0].innerHTML = "<h3 class='mt-1 mb-3'>" + " ( " + dateEnd + ") - ( " + dateStart + " ) تقرير عن القيمة المضافة فى الفترة من " + "</h3>";
                            win.document.body.getElementsByTagName('h3')[0].style.textAlign = "center";
                        }
                    },
                    {
                        extend: "pdfHtml5",
                        customize: function (doc) {
                            doc.defaultStyle =
                            {
                                font: 'Cairo',
                            };
                            var doc = doc;
                            var tblBody = doc.content[1].table.body;
                            var tblHeader = doc.content[0];
                            // ***
                            //This section creates a grid border layout
                            // ***
                            doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return (i === 0 || i === node.table.body.length) ? 0 : 1;},
                            vLineWidth: function(i, node) {
                                return (i === 0 || i === node.table.widths.length) ? 0 : 1;},
                            hLineColor: function(i, node) {
                                return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';},
                            vLineColor: function(i, node) {
                                return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';}
                            };

                            tblHeader.alignment = "center" ;
                            doc.styles.tableHeader.fillColor = "#226130" ;
                            doc.styles.tableBodyOdd.fillColor = "#f2fcf5";
                            doc.styles.tableBodyEven.alignment = "center";
                            doc.styles.tableBodyOdd.alignment = "center";
                            // doc.content[1].table

                            // ***
                            //This section loops thru each row in table looking for where either
                            //the second or third cell is empty.
                            //If both cells empty changes rows background color to '#FFF9C4'
                            //if only the third cell is empty changes background color to '#FFFDE7'
                            // ***
                            $('#report').find('tr').each(function (ix, row) {
                                var index = ix;
                                var rowElt = row;
                                $(row).find('td').each(function (ind, elt) {
                                    if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {
                                        delete tblBody[index][ind].style;
                                        tblBody[index][ind].fillColor = '#226130';
                                    }
                                    else
                                    {
                                        if (tblBody[index][2].text == '') {
                                            delete tblBody[index][ind].style;
                                            tblBody[index][ind].fillColor = '#226130';
                                        }
                                    }
                                });
                            });
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                        orthogonal: "PDF",
                        columns: [5, 4, 3 ,2 ,1 ,0]
                        }
                    },
                    "colvis" ,

                ],
                columnDefs: [
                    {
                        targets: "hiddenCols", visible: false,
                        // targets: '_all',
                        render: function(data, type, row) {
                            if (type === 'PDF') {
                                return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        }
                    }
                ],
                "language": {
                    search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                    searchPlaceholder: '@lang("site.search") ',
                    "lengthMenu": "@lang('site.show')  _MENU_ @lang('site.records') ",
                    "paginate": {
                        "previous": "@lang('site.prev')",
                        "next" : "@lang('site.next')",
                    },
                    "emptyTable":     "@lang('site.no_data')",
                    "info":   "@lang('site.show')  _END_ @lang('site.from') _TOTAL_ @lang('site.record')",
                    "infoEmpty":      "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                    "infoFiltered":   "(@lang('site.search_in')  _MAX_  @lang('site.record'))",

                    buttons: {
                        colvis: '@lang("site.show_data") <i class="fa fa-eye-slash "> </i> ',
                        'print' : '@lang("site.print") <i class="fa fa-print "> </i> ',
                        'copy' : '@lang("site.copy") <i class="fa fa-copy"> </i>',
                        'excel' : '@lang("site.excel") <i class="fa fa-file-excel "> </i>',
                        'pdf' : '@lang("site.pdf") <i class="fa fa-file-pdf"> </i>',
                    },

                }
            }).buttons().container().appendTo('#vattaxreport_wrapper .col-md-6:eq(0)');

            $('[name="vattaxreport_length"] option[value="-1"]').attr('selected', true).parent().trigger('change');

        };

        // Update Data On Page
        function updateData() {
            var dateStart = $('#dateStart').val();
            var dateEnd = $('#dateEnd').val();
            var documentNumber = $('#documentNumber').val();
            var sumOfNetTotal = parseFloat($('#sumOfNetTotal').val());
            var sumOftotalTaxes = parseFloat($('#sumOftotalTaxes').val());
            $('#dateStartSpan').html(dateStart);
            $('#dateEndSpan').html(dateEnd);
            $('#documentNumberSpan').html(' ('+documentNumber+')');
            $('#sumOfNetTotalSpan').html(sumOfNetTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#sumOftotalTaxesSpan').html(sumOftotalTaxes.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }

        // Load Data Tables When Page Loading
        $(function () {
            var dateStart = $('#dateStartSpan').text();
            var dateEnd = $('#dateEndSpan').text();
            var sumOfNetTotal = parseFloat($('#sumOfNetTotal').val());
            var sumOftotalTaxes = parseFloat($('#sumOftotalTaxes').val());
            $('#sumOfNetTotalSpan').html(sumOfNetTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#sumOftotalTaxesSpan').html(sumOftotalTaxes.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            $("#vattaxreport").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "@lang('site.all')"]],
                "buttons":
                [
                    "copy",
                    {
                        extend: 'excelHtml5',
                        title: '( '+ dateEnd + ' ) - ( '+ dateStart  + ') تقرير عن القيمة المضافة فى الفترة من',
                        exportOptions: {
                            columns: 'th:not(.not-export-col)',
                        },
                        customize: function (doc) {
                            var doc = doc;
                        }
                    },
                    {
                        extend: "print",
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win)
                        {
                            var last = null;
                            var current = null;
                            var bod = [];

                            var css = '@page { size: landscape; }',
                                head = win.document.head || win.document.getElementsByTagName('head')[0],
                                style = win.document.createElement('style');

                            style.type = 'text/css';
                            style.media = 'print';

                            if (style.styleSheet)
                            {
                               style.styleSheet.cssText = css;
                            }
                            else
                            {
                                style.appendChild(win.document.createTextNode(css));
                            }

                            head.appendChild(style);

                            win.document.body.getElementsByTagName('h1')[0].innerHTML = "<h3 class='mt-1 mb-3'>" + " ( " + dateEnd + ") - ( " + dateStart + " ) تقرير عن القيمة المضافة فى الفترة من " + "</h3>";
                            win.document.body.getElementsByTagName('h3')[0].style.textAlign = "center";
                        }
                    },
                    {
                        extend: "pdfHtml5",
                        customize: function (doc) {
                            doc.defaultStyle =
                            {
                                font: 'Cairo',
                            };
                            var doc = doc;
                            var tblBody = doc.content[1].table.body;
                            var tblHeader = doc.content[0];
                            // ***
                            //This section creates a grid border layout
                            // ***
                            doc.content[1].layout = {
                            hLineWidth: function(i, node) {
                                return (i === 0 || i === node.table.body.length) ? 0 : 1;},
                            vLineWidth: function(i, node) {
                                return (i === 0 || i === node.table.widths.length) ? 0 : 1;},
                            hLineColor: function(i, node) {
                                return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';},
                            vLineColor: function(i, node) {
                                return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';}
                            };

                            tblHeader.alignment = "center" ;
                            doc.styles.tableHeader.fillColor = "#226130" ;
                            doc.styles.tableBodyOdd.fillColor = "#f2fcf5";
                            doc.styles.tableBodyEven.alignment = "center";
                            doc.styles.tableBodyOdd.alignment = "center";
                            // doc.content[1].table

                            // ***
                            //This section loops thru each row in table looking for where either
                            //the second or third cell is empty.
                            //If both cells empty changes rows background color to '#FFF9C4'
                            //if only the third cell is empty changes background color to '#FFFDE7'
                            // ***
                            $('#report').find('tr').each(function (ix, row) {
                                var index = ix;
                                var rowElt = row;
                                $(row).find('td').each(function (ind, elt) {
                                    if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {
                                        delete tblBody[index][ind].style;
                                        tblBody[index][ind].fillColor = '#226130';
                                    }
                                    else
                                    {
                                        if (tblBody[index][2].text == '') {
                                            delete tblBody[index][ind].style;
                                            tblBody[index][ind].fillColor = '#226130';
                                        }
                                    }
                                });
                            });
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                        orthogonal: "PDF",
                        columns: [5, 4, 3 ,2 ,1 ,0]
                        }
                    },
                    "colvis" ,

                ],
                columnDefs: [
                    {
                        targets: "hiddenCols", visible: false,
                        // targets: '_all',
                        render: function(data, type, row) {
                            if (type === 'PDF') {
                                return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        }
                    }
                ],
                "language": {
                    search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                    searchPlaceholder: '@lang("site.search") ',
                    "lengthMenu": "@lang('site.show')  _MENU_ @lang('site.records') ",
                    "paginate": {
                        "previous": "@lang('site.prev')",
                        "next" : "@lang('site.next')",
                    },
                    "emptyTable":     "@lang('site.no_data')",
                    "info":   "@lang('site.show')  _END_ @lang('site.from') _TOTAL_ @lang('site.record')",
                    "infoEmpty":      "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                    "infoFiltered":   "(@lang('site.search_in')  _MAX_  @lang('site.record'))",

                    buttons: {
                        colvis: '@lang("site.show_data") <i class="fa fa-eye-slash "> </i> ',
                        'print' : '@lang("site.print") <i class="fa fa-print "> </i> ',
                        'copy' : '@lang("site.copy") <i class="fa fa-copy"> </i>',
                        'excel' : '@lang("site.excel") <i class="fa fa-file-excel "> </i>',
                        'pdf' : '@lang("site.pdf") <i class="fa fa-file-pdf"> </i>',
                    },

                }
            }).buttons().container().appendTo('#vattaxreport_wrapper .col-md-6:eq(0)');
        })

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
