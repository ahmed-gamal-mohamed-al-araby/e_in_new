@if ($documents->count() > 0)
    @foreach ($documents as $document)

        <tr class="justify-content-center">
            <td>
                {{ $document->document_number }}

            </td>
            <td>
                ({{ $document->type }})
                @if($document->type == 'I')
                Invoice
                @elseif($document->type == 'C')
                Credit
                @else
                Debit
                @endif
            </td>
            <td>
                {{ $document->date }}
            </td>
            <td>
                {{ 'V ' . $document->version }}
            </td>
            <td>
                {{ $document->company->company_name }}
            </td>
            <td>
                @if ($document->purchaseOrder->client_type == 'f')
                    {{ $document->purchaseOrder->foreignerClient->company_name }}
                @elseif($document->purchaseOrder->client_type == 'p')
                    {{ $document->purchaseOrder->personClient->name }}
                @else
                    {{ $document->purchaseOrder->businessClient->name }}
                @endif
            </td>
            <td>
                @if ($document->purchaseOrder->client_type == 'f')
                    {{ 'Foreigner' }}
                @elseif($document->purchaseOrder->client_type == 'p')
                    {{'Person' }}
                @else
                    {{ 'Business' }}
                @endif
            </td>
            <td>
                {{ $document->items->count() }}
            </td>
            <td>
                @php
                    $totalSumatiom=0;
                    $percentage = 20;
                    if ($document->items[0]->basicItemData->currency != 'EGP'){
                        foreach ($document->items as $item) {
                            $totalSumatiom += ($item->quantity / ($percentage / 100)) * ($item->item_price) * ($percentage / 100);
                        }
                    }else {
                        foreach ($document->items as $item) {
                            $totalSumatiom +=$item->total_amount;
                        }
                    }
                @endphp
                {{ number_format(($totalSumatiom - $document->extra_invoice_discount), 2) }}
            </td>

            <td class="requests-btn action">
                <div class="service-option-document text-center">
                    <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i
                            class="fa fa-eye"></i> </a>

                </div>
            </td>
        </tr>

    @endforeach
@else
    <tr>
        <th colspan="10" class="justify-content-center"><h6 class="text-center p-1">@lang("site.no_data")</h6></th>
    </tr>
@endif
