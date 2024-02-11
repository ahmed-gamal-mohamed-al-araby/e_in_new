@if ($purchaseorder->count() > 0)
    <tr class="justify-content-center">
            <td>
                {{ "1" }}
            </td>
            <td>
                {{ $purchaseorder->purchase_order_reference }}
            </td>
            <td>
                {{ $purchaseorder->type }}
            </td>
            <td>
                @if ($purchaseorder->client_type == 'b')
                    {{ $purchaseorder->businessClient->name }}
                @elseif ($purchaseorder->client_type == 'p')
                    {{ $purchaseorder->personClient->name }}
                @elseif ($purchaseorder->client_type == 'f')
                    {{ $purchaseorder->foreignerClient->person_name }}
                @endif
            </td>
            <td>
                @if ($purchaseorder->client_type == 'b')
                    @lang('site.the_businessClient')
                @elseif ($purchaseorder->client_type == 'p')
                    @lang('site.person_client')
                @elseif ($purchaseorder->client_type == 'f')
                    @lang('site.foreigner_client')
                @endif
            </td>
            <td>
                @if ($purchaseorder->client_type == 'b')
                    {{ $purchaseorder->businessClient->tax_id_number }}
                @elseif ($purchaseorder->client_type == 'p')
                    {{ $purchaseorder->personClient->national_id }}
                @elseif ($purchaseorder->client_type == 'f')
                    {{ $purchaseorder->foreignerClient->vat_id }}
                @endif
            </td>
            <td>
                {{ $purchaseorder->project_name }}
            </td>
            <td>
                {{ date('d-m-Y', strtotime($purchaseorder->created_at)) }}
            </td>
            <td>
                {{-- {{ number_format($purchaseOrderTotal,2) }} --}}
                @php
                    $totalSumatiom = 0;

                    foreach ($purchaseorder->items as $item){
                        $totalSumatiom += $item->total_amount;
                    }
                @endphp
                {{ number_format($totalSumatiom,2) }}
            </td>



            <td class="requests-btn action">
                <div class="service-option-invoice text-center">
                    <a href="{{ route('purchaseorders.show',$purchaseorder->id) }}" class=" btn btn-success"><i
                            class="fa fa-eye"></i> </a>
                </div>
            </td>

    </tr>
@else
    <tr>
        <th colspan="10" class="justify-content-center"><h6 class="text-center p-1">@lang("site.no_data")</h6></th>
    </tr>
@endif

