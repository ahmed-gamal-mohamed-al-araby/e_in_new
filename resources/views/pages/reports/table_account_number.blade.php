@php
$currentLang = app()->getLocale();
@endphp


    <div class="table-responsive">
    <table id="example" class="table table-bordered mt-4 table-striped text-center date display" style="width:100%">


            <thead class="tableItem" id="headers">

                <td>
                    @lang("site.payment_date")
                </td>
                <td>
                    @lang("site.payment_type")
                </td>
                <td>
                    @lang("site.number_cheque_number_or_transfer")
                </td>
                <td>
                    @lang("site.supplier_name")
                </td>
                <td>
                    @lang("site.value_cheque_number_or_transfer")
                </td>

            </thead>

            <tbody>
                @foreach ($payments as $payment )
               <tr>
                <td>{{$payment->payment_date}}</td>
                <td> @if ($payment->payment_method == "bank_transfer")
                                @lang("site.bank_transfer")
                    @elseif ($payment->payment_method == "cheque")@lang("site.cheque")
                    @endif</td>
                <td>
                @if ($payment->payment_method == "cheque")
                 
                    {{$payment->cheque->cheque_number}}
                    @else
                    -
                @endif</td>

                <td>
                @if ($payment->client_type == "b")
                {{$payment->businessClient->name}}
                @elseif($payment->client_type == "p")
                {{$payment->personClient->name}}
                @else
                {{$payment->foreignerClient->name}}


                @endif</td>
             </td>
             <td>

             <?php
             echo(number_format($payment->value, 2, '.', ''));
      
             ?>
           
             </td>
               </tr>

                @endforeach
            </tbody>
        </table>

    </div>


    <script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        "bPaginate": false,
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
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
                    columns: [ 0, 1, 2, 5 ]
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
    } );
} );
</script>
