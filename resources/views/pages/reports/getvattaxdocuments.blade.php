
<tbody class="document_results">
    @php
    $sumOfNetTotal = [];
    $sumOftotalTaxes = [];
    @endphp
    @foreach ($documents as $document)
    @foreach ($document->items as $doc_item)
    @php
    $subtype="";
    $subtype = $doc_item->basicItemData->purchaseOrderTaxes->first()->subtype ;

    $tax_rate="";
    $tax_rate = $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate ;
    @endphp
    @endforeach




    @if($tax == 1 && $type ==1)
    <tr class="justify-content-center" data-entry-id="{{ $document->id }}">
        <td>
            @if($document->type == 'I')
            {{ 1 }}
            @elseif($document->type == 'C')
            {{ 3 }}
            @else
            {{ 2 }}
            @endif
        </td>
        <td>
            @php
            $taxTypeRefrence = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $taxTypeRefrence = 1 ;
            }else {
            $taxTypeRefrence = 2 ;
            }
            }
            @endphp
            @endforeach
            {{ $taxTypeRefrence }}
        </td>
        <td>
            @php
            $tableItemsType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $tableItemsType = 0 ;
            }elseif ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax != 0) {
            $tableItemsType = 1;
            } else {
            $tableItemsType = 2;
            }
            }
            @endphp
            @endforeach
            {{ $tableItemsType }}
        </td>
        <td>
            {{ $document->document_number }}
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
            @if ($document->purchaseOrder->client_type == 'b')
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_id_number) }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'b')
            @if ($document->purchaseOrder->businessClient->tax_file_number != null)
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_file_number) }}
            @else
            {{ 0 }}
            @endif
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->address->street }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->address->street }}
            @else
            {{ $document->purchaseOrder->businessClient->address->street }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->national_id }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->person_mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'b')
            {{ $document->purchaseOrder->businessClient->mobile ?? 0 }}
            @endif
        </td>
        <td>
            {{ $document->date }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productName = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productName = $doc_item->basicItemData->product->product_name;
            @endphp
            @endforeach
            {{ $productName }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productCode = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productCode = $doc_item->basicItemData->product->product_code;
            @endphp
            @endforeach
            {{ $productCode }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if($document->type == 'I')
            @php
            $statementType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 14 ||
            $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 0 ) {
            $statementType = 3 ;
            }else {
            $statementType = 4 ;
            }
            }
            @endphp
            @endforeach
            {{ $statementType }}
            @elseif($document->type == 'C' || $document->type == 'D')
            {{ 5 }}
            @endif
        </td>
        <td>
            @php
            $itemType = null;
            @endphp
            @if($document->purchaseOrder->deliveryCountryOrigin->code == 'EG')
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax == 0 ) {
            $itemType = 7 ;
            }else {
            $itemType = 1 ;
            }
            }
            @endphp
            @endforeach
            {{ $itemType }}
            @else
            {{ 2 }}
            @endif
        </td>



        <td>
            @if ($document->items->count() == 1)
            @php
            $productUnit = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productUnit = $doc_item->basicItemData->unit;
            @endphp
            @endforeach
            @php
            if ($productUnit == "C62") {
            $productUnit = "عدد";
            }elseif ($productUnit == "TNE") {
            $productUnit = "طن";
            }
            @endphp
            {{ $productUnit }}
            @elseif ($document->items->count() > 1)
            @php
            $productUnit = "عدد";
            @endphp
            {{ $productUnit }}
            @else
            {{ $productUnit }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitPrice = null;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            }
            if ($document->type == 'C'){
            $unitPrice = $unitPrice * -1 ;
            }
            @endphp

            {{ number_format($unitPrice, 2) }}
            @elseif ($document->items->count() > 1)
            @php
            $totalnetAmount = 0.000000;
            foreach ($document->items as $doc_item){
            $totalnetAmount += ($doc_item->net_total);
            }
            if ($document->type == 'C'){
            $totalnetAmount = $totalnetAmount * -1 ;
            }
            @endphp
            {{ number_format($totalnetAmount, 2) }}
            @endif
        </td>
        <td>
            @php
            $taxCategory = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $taxCategory = $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate ;
            @endphp
            @endforeach
            {{ $taxCategory  }}%
        </td>

        <td>
            @php
            $subtype = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $subtype = $doc_item->basicItemData->purchaseOrderTaxes->first()->subtype ;
            @endphp
            @endforeach
            {{ $subtype  }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitQty = 1;
            foreach ($document->items as $doc_item){
            $unitQty = $doc_item->quantity;
            }
            @endphp
            {{ $unitQty }}
            @elseif ($document->items->count() > 1)
            {{ 1 }}
            @else
            {{ 1 }}
            @endif
        </td>
        <td class="totalSumatiom">
            @php
            $unitPrice = null;
            $totalSumatiom = 0;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            $totalSumatiom += $unitPrice * ($doc_item->quantity);
            }
            if ($document->type == 'C'){
            $totalSumatiom = $totalSumatiom * -1 ;
            }
            @endphp
            {{ number_format($totalSumatiom , 2) }}
        </td>
        <td>
            @php
            $unitPrice = null;
            $totalDiscountAmount = 0;
            foreach ($document->items as $doc_item){
            $totalDiscountAmount += $doc_item->discount_item_amount;
            }
            @endphp
            {{ number_format($totalDiscountAmount , 2) }}
        </td>
        <td class="netTotal">
            @php
            $netTotal = 0;
            $netTotal = $totalSumatiom - $totalDiscountAmount;
            // if ($document->type == 'C'){
            // $netTotal = $netTotal * -1 ;
            // }
            array_push($sumOfNetTotal, $netTotal);
            @endphp
            {{ number_format(($netTotal), 2)  }}
        </td>
        <td class="totalTaxes">
            @php
            $totalTaxes = 0;
            $data = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            foreach ($doc_item->DocumentTaxes as $key => $document_item_tax) {
            $totalTaxes += $document_item_tax->amount_tax;
            }
            }
            @endphp
                @foreach($doc_item->basicItemData->purchaseOrderTaxes as $taxTest)
                    @if($taxTest->tax_type  == 4)
                        @php
                            $data += 1;
                        @endphp
                    @endif
                @endforeach
            @endforeach
            @php
            if ($document->type == 'C'){
            $totalTaxes = $totalTaxes * -1 ;
            }

            @endphp
            @if($data > 0)
                @php
                    $totalTaxes  = $totalTaxes - ($netTotal * 0.01);
                @endphp
            @endif
                @php
                    array_push($sumOftotalTaxes, $totalTaxes)
                @endphp
            {{ number_format($totalTaxes, 2) }}
        </td>
        <td>
            @php
            $totalSumation = 0;
            $totalSumation = $netTotal + $totalTaxes;
            // if ($document->type == 'C'){
            // $totalSumation = $totalSumation * -1 ;
            // }
            @endphp
            {{ number_format($totalSumation, 2) }}
        </td>
        <td class="requests-btn">
            <div class="service-option-document text-center">
                <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i class="fa fa-eye"></i> </a>
            </div>
        </td>
    </tr>
    @elseif($tax==1 && $type !=1)
    @if($subtype==$type)
    <tr class="justify-content-center" data-entry-id="{{ $document->id }}">
        <td>
            @if($document->type == 'I')
            {{ 1 }}
            @elseif($document->type == 'C')
            {{ 3 }}
            @else
            {{ 2 }}
            @endif
        </td>
        <td>
            @php
            $taxTypeRefrence = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $taxTypeRefrence = 1 ;
            }else {
            $taxTypeRefrence = 2 ;
            }
            }
            @endphp
            @endforeach
            {{ $taxTypeRefrence }}
        </td>
        <td>
            @php
            $tableItemsType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $tableItemsType = 0 ;
            }elseif ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax != 0) {
            $tableItemsType = 1;
            } else {
            $tableItemsType = 2;
            }
            }
            @endphp
            @endforeach
            {{ $tableItemsType }}
        </td>
        <td>
            {{ $document->document_number }}
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
            @if ($document->purchaseOrder->client_type == 'b')
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_id_number) }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'b')
            @if ($document->purchaseOrder->businessClient->tax_file_number != null)
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_file_number) }}
            @else
            {{ 0 }}
            @endif
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->address->street }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->address->street }}
            @else
            {{ $document->purchaseOrder->businessClient->address->street }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->national_id }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->person_mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'b')
            {{ $document->purchaseOrder->businessClient->mobile ?? 0 }}
            @endif
        </td>
        <td>
            {{ $document->date }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productName = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productName = $doc_item->basicItemData->product->product_name;
            @endphp
            @endforeach
            {{ $productName }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productCode = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productCode = $doc_item->basicItemData->product->product_code;
            @endphp
            @endforeach
            {{ $productCode }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if($document->type == 'I')
            @php
            $statementType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 14 ||
            $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 0 ) {
            $statementType = 3 ;
            }else {
            $statementType = 4 ;
            }
            }
            @endphp
            @endforeach
            {{ $statementType }}
            @elseif($document->type == 'C' || $document->type == 'D')
            {{ 5 }}
            @endif
        </td>
        <td>
            @php
            $itemType = null;
            @endphp
            @if($document->purchaseOrder->deliveryCountryOrigin->code == 'EG')
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax == 0 ) {
            $itemType = 7 ;
            }else {
            $itemType = 1 ;
            }
            }
            @endphp
            @endforeach
            {{ $itemType }}
            @else
            {{ 2 }}
            @endif
        </td>



        <td>
            @if ($document->items->count() == 1)
            @php
            $productUnit = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productUnit = $doc_item->basicItemData->unit;
            @endphp
            @endforeach
            @php
            if ($productUnit == "C62") {
            $productUnit = "عدد";
            }elseif ($productUnit == "TNE") {
            $productUnit = "طن";
            }
            @endphp
            {{ $productUnit }}
            @elseif ($document->items->count() > 1)
            @php
            $productUnit = "عدد";
            @endphp
            {{ $productUnit }}
            @else
            {{ $productUnit }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitPrice = null;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            }
            if ($document->type == 'C'){
            $unitPrice = $unitPrice * -1 ;
            }
            @endphp

            {{ number_format($unitPrice, 2) }}
            @elseif ($document->items->count() > 1)
            @php
            $totalnetAmount = 0.000000;
            foreach ($document->items as $doc_item){
            $totalnetAmount += ($doc_item->net_total);
            }
            if ($document->type == 'C'){
            $totalnetAmount = $totalnetAmount * -1 ;
            }
            @endphp
            {{ number_format($totalnetAmount, 2) }}
            @endif
        </td>
        <td>
            @php
            $taxCategory = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $taxCategory = $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate ;
            @endphp
            @endforeach
            {{ $taxCategory  }}%
        </td>

        <td>
            @php
            $subtype = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $subtype = $doc_item->basicItemData->purchaseOrderTaxes->first()->subtype ;
            @endphp
            @endforeach
            {{ $subtype  }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitQty = 1;
            foreach ($document->items as $doc_item){
            $unitQty = $doc_item->quantity;
            }
            @endphp
            {{ $unitQty }}
            @elseif ($document->items->count() > 1)
            {{ 1 }}
            @else
            {{ 1 }}
            @endif
        </td>
        <td class="totalSumatiom">
            @php
            $unitPrice = null;
            $totalSumatiom = 0;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            $totalSumatiom += $unitPrice * ($doc_item->quantity);
            }
            if ($document->type == 'C'){
            $totalSumatiom = $totalSumatiom * -1 ;
            }
            @endphp
            {{ number_format($totalSumatiom , 2) }}
        </td>
        <td>
            @php
            $unitPrice = null;
            $totalDiscountAmount = 0;
            foreach ($document->items as $doc_item){
            $totalDiscountAmount += $doc_item->discount_item_amount;
            }
            @endphp
            {{ number_format($totalDiscountAmount , 2) }}
        </td>
        <td class="netTotal">
            @php
            $netTotal = 0;
            $netTotal = $totalSumatiom - $totalDiscountAmount;
            // if ($document->type == 'C'){
            // $netTotal = $netTotal * -1 ;
            // }
            array_push($sumOfNetTotal, $netTotal);
            @endphp
            {{ number_format(($netTotal), 2)  }}
        </td>
        <td class="totalTaxes">
            @php
            $totalTaxes = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            foreach ($doc_item->DocumentTaxes as $key => $document_item_tax) {
            $totalTaxes += $document_item_tax->amount_tax;
            }
            }
            @endphp
            @endforeach
            @php
            if ($document->type == 'C'){
            $totalTaxes = $totalTaxes * -1 ;
            }
            array_push($sumOftotalTaxes, $totalTaxes)
            @endphp
            {{ number_format($totalTaxes, 2) }}
        </td>
        <td>
            @php
            $totalSumation = 0;
            $totalSumation = $netTotal + $totalTaxes;
            // if ($document->type == 'C'){
            // $totalSumation = $totalSumation * -1 ;
            // }
            @endphp
            {{ number_format($totalSumation, 2) }}
        </td>
        <td class="requests-btn">
            <div class="service-option-document text-center">
                <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i class="fa fa-eye"></i> </a>
            </div>
        </td>
    </tr>
    @endif
    @elseif($tax!=1 && $type ==1)
    @if($tax_rate==$tax)
    <tr class="justify-content-center" data-entry-id="{{ $document->id }}">
        <td>
            @if($document->type == 'I')
            {{ 1 }}
            @elseif($document->type == 'C')
            {{ 3 }}
            @else
            {{ 2 }}
            @endif
        </td>
        <td>
            @php
            $taxTypeRefrence = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $taxTypeRefrence = 1 ;
            }else {
            $taxTypeRefrence = 2 ;
            }
            }
            @endphp
            @endforeach
            {{ $taxTypeRefrence }}
        </td>
        <td>
            @php
            $tableItemsType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $tableItemsType = 0 ;
            }elseif ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax != 0) {
            $tableItemsType = 1;
            } else {
            $tableItemsType = 2;
            }
            }
            @endphp
            @endforeach
            {{ $tableItemsType }}
        </td>
        <td>
            {{ $document->document_number }}
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
            @if ($document->purchaseOrder->client_type == 'b')
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_id_number) }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'b')
            @if ($document->purchaseOrder->businessClient->tax_file_number != null)
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_file_number) }}
            @else
            {{ 0 }}
            @endif
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->address->street }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->address->street }}
            @else
            {{ $document->purchaseOrder->businessClient->address->street }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->national_id }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->person_mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'b')
            {{ $document->purchaseOrder->businessClient->mobile ?? 0 }}
            @endif
        </td>
        <td>
            {{ $document->date }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productName = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productName = $doc_item->basicItemData->product->product_name;
            @endphp
            @endforeach
            {{ $productName }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productCode = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productCode = $doc_item->basicItemData->product->product_code;
            @endphp
            @endforeach
            {{ $productCode }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if($document->type == 'I')
            @php
            $statementType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 14 ||
            $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 0 ) {
            $statementType = 3 ;
            }else {
            $statementType = 4 ;
            }
            }
            @endphp
            @endforeach
            {{ $statementType }}
            @elseif($document->type == 'C' || $document->type == 'D')
            {{ 5 }}
            @endif
        </td>
        <td>
            @php
            $itemType = null;
            @endphp
            @if($document->purchaseOrder->deliveryCountryOrigin->code == 'EG')
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax == 0 ) {
            $itemType = 7 ;
            }else {
            $itemType = 1 ;
            }
            }
            @endphp
            @endforeach
            {{ $itemType }}
            @else
            {{ 2 }}
            @endif
        </td>



        <td>
            @if ($document->items->count() == 1)
            @php
            $productUnit = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productUnit = $doc_item->basicItemData->unit;
            @endphp
            @endforeach
            @php
            if ($productUnit == "C62") {
            $productUnit = "عدد";
            }elseif ($productUnit == "TNE") {
            $productUnit = "طن";
            }
            @endphp
            {{ $productUnit }}
            @elseif ($document->items->count() > 1)
            @php
            $productUnit = "عدد";
            @endphp
            {{ $productUnit }}
            @else
            {{ $productUnit }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitPrice = null;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            }
            if ($document->type == 'C'){
            $unitPrice = $unitPrice * -1 ;
            }
            @endphp

            {{ number_format($unitPrice, 2) }}
            @elseif ($document->items->count() > 1)
            @php
            $totalnetAmount = 0.000000;
            foreach ($document->items as $doc_item){
            $totalnetAmount += ($doc_item->net_total);
            }
            if ($document->type == 'C'){
            $totalnetAmount = $totalnetAmount * -1 ;
            }
            @endphp
            {{ number_format($totalnetAmount, 2) }}
            @endif
        </td>
        <td>
            @php
            $taxCategory = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $taxCategory = $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate ;
            @endphp
            @endforeach
            {{ $taxCategory  }}%
        </td>

        <td>
            @php
            $subtype = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $subtype = $doc_item->basicItemData->purchaseOrderTaxes->first()->subtype ;
            @endphp
            @endforeach
            {{ $subtype  }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitQty = 1;
            foreach ($document->items as $doc_item){
            $unitQty = $doc_item->quantity;
            }
            @endphp
            {{ $unitQty }}
            @elseif ($document->items->count() > 1)
            {{ 1 }}
            @else
            {{ 1 }}
            @endif
        </td>
        <td class="totalSumatiom">
            @php
            $unitPrice = null;
            $totalSumatiom = 0;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            $totalSumatiom += $unitPrice * ($doc_item->quantity);
            }
            if ($document->type == 'C'){
            $totalSumatiom = $totalSumatiom * -1 ;
            }
            @endphp
            {{ number_format($totalSumatiom , 2) }}
        </td>
        <td>
            @php
            $unitPrice = null;
            $totalDiscountAmount = 0;
            foreach ($document->items as $doc_item){
            $totalDiscountAmount += $doc_item->discount_item_amount;
            }
            @endphp
            {{ number_format($totalDiscountAmount , 2) }}
        </td>
        <td class="netTotal">
            @php
            $netTotal = 0;
            $netTotal = $totalSumatiom - $totalDiscountAmount;
            // if ($document->type == 'C'){
            // $netTotal = $netTotal * -1 ;
            // }
            array_push($sumOfNetTotal, $netTotal);
            @endphp
            {{ number_format(($netTotal), 2)  }}
        </td>
        <td class="totalTaxes">
            @php
            $totalTaxes = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            foreach ($doc_item->DocumentTaxes as $key => $document_item_tax) {
            $totalTaxes += $document_item_tax->amount_tax;
            }
            }
            @endphp
            @endforeach
            @php
            if ($document->type == 'C'){
            $totalTaxes = $totalTaxes * -1 ;
            }
            array_push($sumOftotalTaxes, $totalTaxes)
            @endphp
            {{ number_format($totalTaxes, 2) }}
        </td>
        <td>
            @php
            $totalSumation = 0;
            $totalSumation = $netTotal + $totalTaxes;
            // if ($document->type == 'C'){
            // $totalSumation = $totalSumation * -1 ;
            // }
            @endphp
            {{ number_format($totalSumation, 2) }}
        </td>
        <td class="requests-btn">
            <div class="service-option-document text-center">
                <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i class="fa fa-eye"></i> </a>
            </div>
        </td>
    </tr>
    @endif

    @elseif($tax!=1 && $type !=1)
    @if($subtype==$type && $tax_rate==$tax)
    <tr class="justify-content-center" data-entry-id="{{ $document->id }}">
        <td>
            @if($document->type == 'I')
            {{ 1 }}
            @elseif($document->type == 'C')
            {{ 3 }}
            @else
            {{ 2 }}
            @endif
        </td>
        <td>
            @php
            $taxTypeRefrence = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $taxTypeRefrence = 1 ;
            }else {
            $taxTypeRefrence = 2 ;
            }
            }
            @endphp
            @endforeach
            {{ $taxTypeRefrence }}
        </td>
        <td>
            @php
            $tableItemsType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->subtype == "V009") {
            $tableItemsType = 0 ;
            }elseif ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax != 0) {
            $tableItemsType = 1;
            } else {
            $tableItemsType = 2;
            }
            }
            @endphp
            @endforeach
            {{ $tableItemsType }}
        </td>
        <td>
            {{ $document->document_number }}
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
            @if ($document->purchaseOrder->client_type == 'b')
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_id_number) }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'b')
            @if ($document->purchaseOrder->businessClient->tax_file_number != null)
            {{ str_replace("-", "", $document->purchaseOrder->businessClient->tax_file_number) }}
            @else
            {{ 0 }}
            @endif
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->address->street }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->address->street }}
            @else
            {{ $document->purchaseOrder->businessClient->address->street }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->national_id }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->purchaseOrder->client_type == 'f')
            {{ $document->purchaseOrder->foreignerClient->person_mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'p')
            {{ $document->purchaseOrder->personClient->mobile ?? 0 }}
            @elseif($document->purchaseOrder->client_type == 'b')
            {{ $document->purchaseOrder->businessClient->mobile ?? 0 }}
            @endif
        </td>
        <td>
            {{ $document->date }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productName = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productName = $doc_item->basicItemData->product->product_name;
            @endphp
            @endforeach
            {{ $productName }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $productCode = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productCode = $doc_item->basicItemData->product->product_code;
            @endphp
            @endforeach
            {{ $productCode }}
            @elseif ($document->items->count() > 1)
            {{ 0 }}
            @else
            {{ 0 }}
            @endif
        </td>
        <td>
            @if($document->type == 'I')
            @php
            $statementType = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 14 ||
            $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate == 0 ) {
            $statementType = 3 ;
            }else {
            $statementType = 4 ;
            }
            }
            @endphp
            @endforeach
            {{ $statementType }}
            @elseif($document->type == 'C' || $document->type == 'D')
            {{ 5 }}
            @endif
        </td>
        <td>
            @php
            $itemType = null;
            @endphp
            @if($document->purchaseOrder->deliveryCountryOrigin->code == 'EG')
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->amount_tax == 0 ) {
            $itemType = 7 ;
            }else {
            $itemType = 1 ;
            }
            }
            @endphp
            @endforeach
            {{ $itemType }}
            @else
            {{ 2 }}
            @endif
        </td>



        <td>
            @if ($document->items->count() == 1)
            @php
            $productUnit = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $productUnit = $doc_item->basicItemData->unit;
            @endphp
            @endforeach
            @php
            if ($productUnit == "C62") {
            $productUnit = "عدد";
            }elseif ($productUnit == "TNE") {
            $productUnit = "طن";
            }
            @endphp
            {{ $productUnit }}
            @elseif ($document->items->count() > 1)
            @php
            $productUnit = "عدد";
            @endphp
            {{ $productUnit }}
            @else
            {{ $productUnit }}
            @endif
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitPrice = null;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            }
            if ($document->type == 'C'){
            $unitPrice = $unitPrice * -1 ;
            }
            @endphp

            {{ number_format($unitPrice, 2) }}
            @elseif ($document->items->count() > 1)
            @php
            $totalnetAmount = 0.000000;
            foreach ($document->items as $doc_item){
            $totalnetAmount += ($doc_item->net_total);
            }
            if ($document->type == 'C'){
            $totalnetAmount = $totalnetAmount * -1 ;
            }
            @endphp
            {{ number_format($totalnetAmount, 2) }}
            @endif
        </td>
        <td>
            @php
            $taxCategory = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $taxCategory = $doc_item->basicItemData->purchaseOrderTaxes->first()->tax_rate ;
            @endphp
            @endforeach
            {{ $taxCategory  }}%
        </td>

        <td>
            @php
            $subtype = null;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            $subtype = $doc_item->basicItemData->purchaseOrderTaxes->first()->subtype ;
            @endphp
            @endforeach
            {{ $subtype  }}
        </td>
        <td>
            @if ($document->items->count() == 1)
            @php
            $unitQty = 1;
            foreach ($document->items as $doc_item){
            $unitQty = $doc_item->quantity;
            }
            @endphp
            {{ $unitQty }}
            @elseif ($document->items->count() > 1)
            {{ 1 }}
            @else
            {{ 1 }}
            @endif
        </td>
        <td class="totalSumatiom">
            @php
            $unitPrice = null;
            $totalSumatiom = 0;
            foreach ($document->items as $doc_item){
            if ($doc_item->basicItemData->currency == "EGP") {
            $unitPrice = $doc_item->item_price;
            }elseif ($doc_item->basicItemData->currency != "EGP") {
            $amountSold = $doc_item->item_price;
            $currencyExchangeRate = $doc_item->rate;
            $unitPrice = $amountSold * $currencyExchangeRate;
            }
            $totalSumatiom += $unitPrice * ($doc_item->quantity);
            }
            if ($document->type == 'C'){
            $totalSumatiom = $totalSumatiom * -1 ;
            }
            @endphp
            {{ number_format($totalSumatiom , 2) }}
        </td>
        <td>
            @php
            $unitPrice = null;
            $totalDiscountAmount = 0;
            foreach ($document->items as $doc_item){
            $totalDiscountAmount += $doc_item->discount_item_amount;
            }
            @endphp
            {{ number_format($totalDiscountAmount , 2) }}
        </td>
        <td class="netTotal">
            @php
            $netTotal = 0;
            $netTotal = $totalSumatiom - $totalDiscountAmount;
            // if ($document->type == 'C'){
            // $netTotal = $netTotal * -1 ;
            // }
            array_push($sumOfNetTotal, $netTotal);
            @endphp
            {{ number_format(($netTotal), 2)  }}
        </td>
        <td class="totalTaxes">
            @php
            $totalTaxes = 0;
            @endphp
            @foreach ($document->items as $doc_item)
            @php
            if ($doc_item->basicItemData->purchaseOrderTaxes->first()->tax_type == 1) {
            foreach ($doc_item->DocumentTaxes as $key => $document_item_tax) {
            $totalTaxes += $document_item_tax->amount_tax;
            }
            }
            @endphp

            @endforeach

            @php
            if ($document->type == 'C'){
            $totalTaxes = $totalTaxes * -1 ;
            }
            array_push($sumOftotalTaxes, $totalTaxes)
            @endphp
            {{ number_format($totalTaxes, 2) }}
        </td>
        <td>
            @php
            $totalSumation = 0;
            $totalSumation = $netTotal + $totalTaxes;
            // if ($document->type == 'C'){
            // $totalSumation = $totalSumation * -1 ;
            // }
            @endphp
            {{ number_format($totalSumation, 2) }}
        </td>
        <td class="requests-btn">
            <div class="service-option-document text-center">
                <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i class="fa fa-eye"></i> </a>
            </div>
        </td>
    </tr>
    @endif
    @endif
    @endforeach
</tbody>
<input type="hidden" id="dateStart" name="dateStart" value="{{$dateStart}}">
<input type="hidden" id="dateEnd" name="dateEnd" value="{{$dateEnd}}">
<input type="hidden" id="documentNumber" name="documentNumber" value="{{$documents->count()}}">
<input type="hidden" id="sumOfNetTotal" name="sumOfNetTotal" value="{{ array_sum($sumOfNetTotal) }}">
<input type="hidden" id="sumOftotalTaxes" name="sumOftotalTaxes" value="{{ array_sum($sumOftotalTaxes) }}">