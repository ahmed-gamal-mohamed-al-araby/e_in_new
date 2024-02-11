@php
    $currentLang = app()->getLocale();
    $x=1;
@endphp


<table id="clientAnalysis_report" class="table table-bordered mt-4 table-striped text-center date" style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang("site.reply_date")</th>
        <th>@lang("site.name_on_cheque")</th>
        <th>@lang("site.status")</th>
        <th>@lang("site.bank")</th>
        <th>@lang("site.value_cheque_number")</th>
        <th>@lang("site.cheque_number")</th>
        <th>@lang("site.check_type")</th>
        <th>@lang("site.side")</th>
        <th>@lang("site.purpose")</th>
        <th>@lang("site.project_number")</th>
        <th>@lang("site.check_date")</th>

    </tr>
    </thead>
    <tbody>

    @foreach($warrantyChecks as $signal_data)

    <tr>
        <th>{{$x++}}</th>
        <th>{{$signal_data->reply_date}}</th>
        <th>{{$signal_data->source_name}}</th>
    
        @if(isset($signal_data->reply_date))
        <th>@lang("site.answered")</th>
        @else
        <th>@lang("site.not_answered")</th>
        @endif

        @if(isset($signal_data->bank))
        <th>{{$signal_data->bank->bank_name}}</th>
        @else
        <th>{{$signal_data->bank_name}}</th>
        @endif

        <th>{{number_format($signal_data->value,2)}}</th>
        
        <th>{{$signal_data->cheque_number}}</th>
        <th>@lang('site'.".".$signal_data->type)</th>
        <th>{{$signal_data->side}}</th>
        <th>{{$signal_data->purpose}}</th>
        <th>{{$signal_data->project_number}}</th>
        <th>{{$signal_data->check_date}}</th>
    </tr>
    @endforeach

    </tbody>

</table>

<script>
            $("#clientAnalysis_report").DataTable({
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
                        customize: function (win) {
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

                         
                        }
                    },
                    
                    "colvis",
                ],
                columnDefs: [{
                    targets: "hiddenCols",
                    visible: false,
                    // targets: '_all',
                    render: function (data, type, row) {
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
            }).buttons().container().appendTo('#clientAnalysis_report_wrapper .col-md-6:eq(0)');
 
    </script>