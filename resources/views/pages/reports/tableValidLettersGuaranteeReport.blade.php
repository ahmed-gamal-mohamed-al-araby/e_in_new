@php
$currentLang = app()->getLocale();
$x=1;
$cash_margin_sum=0;
$letter_guarantee_value_sum=0;
@endphp


<table id="clientAnalysis_report" class="table table-bordered mt-4 table-striped text-center date" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang("site.purpose")</th>
            <th>@lang("site.client_name")</th>
            <th>@lang("site.cash_margin")</th>
            <th>@lang("site.expiry_date")</th>
            <th>{{trans("site.release_date")}}</th>
            <th>@lang("site.letter_guarantee_value")</th>
            <th>@lang("site.bank")</th>
            <th>@lang("site.type")</th>
            <th>@lang("site.letter_guarantee_num")</th>

        </tr>
    </thead>
    <tbody>

        @foreach($lettersGuarantee as $signal_data)

        @php
        $cash_margin_sum=$cash_margin_sum+$signal_data->cash_margin;
        $letter_guarantee_value_sum=$letter_guarantee_value_sum+$signal_data->value;
        @endphp
        <tr>
            <th>{{$x++}}</th>
            <th>{{$signal_data->purpose}}</th>
            @if(isset($signal_data->client_name))
            <td>{{$signal_data->client_name}}</td>

            @else

            @if($signal_data->client_type=="b")
            <td>{{$signal_data->businessClient->name}}</td>
            @elseif($signal_data->client_type=="p")
            <td>{{$signal_data->personClient->name}}</td>
            @else
            <td>{{$signal_data->foreignerClient->company_name}}</td>
            @endif

            @endif
            <th>{{number_format($signal_data->cash_margin,2)}}</th>
            <th>{{$signal_data->expiry_date}}</th>
            <th>{{$signal_data->release_date}}</th>
            <th>{{number_format($signal_data->value,2)}}</th>
            <th>{{$signal_data->bank->bank_name}}</th>
            <th>@lang('site'.".".$signal_data->type)</th>
            <th>{{$signal_data->letter_guarantee_num}}</th>
        </tr>
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">
                @lang('site.total')
            </th>
            <td style="font-weight: bold;">{{number_format($cash_margin_sum,2)}}</td>
            <td colspan="2"></td>
            <td style="font-weight: bold;">{{number_format($letter_guarantee_value_sum,2)}}</td>
            <td colspan="3"></td>
            
        </tr>
    </tfoot>


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
    }).buttons().container().appendTo('#clientAnalysis_report_wrapper .col-md-6:eq(0)');
</script>