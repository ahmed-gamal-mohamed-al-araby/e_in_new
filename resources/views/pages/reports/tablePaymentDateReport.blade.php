@php
    $currentLang = app()->getLocale();
@endphp


<div class="table-responsive">
    <table id="example" class="table table-bordered mt-4 table-striped text-center date display" style="width:100%">


        <thead class="tableItem" id="headers">

       <td>
           @lang("site.release_date")
        </td>
        <td>
            @lang("site.cheque_number")
        </td>
        <td>
            @lang("site.supplier_name")
        </td>
        <td>
            @lang("site.value_cheque_number_or_transfer")
        </td>
        <td>
                        @lang("site.value_cheque_number_or_transfer")
            @lang("site.number_document_or_po")

        </td>

        </thead>

        <tbody>

        @foreach ($cheques as $cheque )
            <tr>
                <td>{{$cheque->issue_date}}</td>
                @php
                @endphp
                @foreach ($cheque->payments as $payment )
                    @php
                        $arr=[];
                        $arr[]= $payment->cheque->cheque_number;
                    @endphp


                @endforeach

                <td>
                    {{$arr[0]}}
{{--                    {{$cheque->payments->cheque_number}}--}}
{{--                    {{$cheque->payments->cheque}}--}}

                </td>
                <td>
                    @foreach ($cheque->payments as $payment )
                        @php
                            $arr=[];
                            $arr[]= $payment->cheque->cheque_number;
                        @endphp

                    @if ($payment->client_type == "b")
                        {{$payment->businessClient->name}}
                    @elseif($payment->client_type == "p")
                        {{$payment->personClient->name}}
                    @else
                        {{$payment->foreignerClient->name}}
                    @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($cheque->payments as $payment )
{{--                        {{$payment->value->count()}}--}}
<!--                    --><?php
//                    echo(number_format($payment->value, 2, '.', ''));
//                    ?>

                    @endforeach
                </td>
                <td>
                    @foreach ($cheque->payments as $payment )

                    @if ($payment->purchaseOrder)
                        {{$payment->purchaseOrder->purchase_order_reference}},
                    @else
                        {{$payment->document->document_number}} ,
                    @endif

                    @endforeach

                </td>
            </tr>

        @endforeach


        </tbody>
    </table>

</div>


<script>
    $(document).ready(function () {
        $('#example').DataTable({
            dom: 'Bfrtip',
            "bPaginate": false,
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, ':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 5]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });
    });
</script>
