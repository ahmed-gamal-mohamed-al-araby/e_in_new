@extends('pages.documents.print.master')
@php
    $currentLang = Config::get('app.locale');
    App::setLocale('en');
@endphp
@section('content')
    {{-- content --}}
    <table>
        {{-- Table header repeated in top of all pages --}}
        <thead>
            <div class="print_header">
                <img src="{{ asset('Images/spicail_header.png') }}" class="w-100 print_invoice_header" alt="">
                <div class="card show-one-request print" style="box-shadow: none !important">
                    <div class="card-body show-request-id">
                        <div class="mb-2">
                            <div class="row my-0">

                                    @if ($document->type == 'I')
                            <div class="col-12">
                                <p class="d-inline-block">Invoice Number : <span class="header_number" style="unicode-bidi: embed;">{{ $document->document_number }}</span> </p>
                                <p class="d-inline-block ml-5">Invoice Date : <span class="header_number" style="unicode-bidi: plaintext;">{{ $document->date }}</span> </p>
                            </div>
                            @endif

                            @if ($document->type == 'C')
                            <div class="col-12">
                                <p class="d-inline-block">Credit Number : <span class="header_number" style="unicode-bidi: embed;">{{ $document->document_number }}</span> </p>
                                <p class="d-inline-block ml-5">Credit Date : <span class="header_number" style="unicode-bidi: plaintext;">{{ $document->date }}</span> </p>
                            </div>
                            @endif

                            @if ($document->type == 'D')
                            <div class="col-12">
                                <p class="d-inline-block">Debit Number : <span class="header_number" style="unicode-bidi: embed;">{{ $document->document_number }}</span> </p>
                                <p class="d-inline-block ml-5">Debit Date : <span class="header_number" style="unicode-bidi: plaintext;">{{ $document->date }}</span> </p>
                            </div>
                            @endif

                                <div class="col-6">
                                    <div class="card card_border m-0">
                                        <div class="card-header">
                                            Client information
                                        </div>
                                        <div class="card-body"
                                            style="max-height: 110px; line-height: 1rem; padding: .75rem 1.25rem; overflow: hidden;">
                                            @if ($document->purchaseOrder->client_type == 'f')
                                                <p class="text-bold">
                                                    {{ $document->purchaseOrder->foreignerClient->company_name }} </p>
                                                <p class="mb-1" style="line-height: 1rem;">
                                                @if($document->purchaseOrder->foreignerClient->address->city_id)
                                                    {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->city->name . ', ' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                                    @else
                                                    {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->city->name . ', ' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                                    @endif                                                </p>
                                            @elseif($document->purchaseOrder->client_type == 'p')
                                                <p class="text-bold"> {{ $document->purchaseOrder->personClient->name }}
                                                </p>
                                                <p class="mb-1" style="line-height: 1rem;">
                                                    {{ $document->purchaseOrder->personClient->address->country->name . ' ,' . $document->purchaseOrder->personClient->address->city->name . ', ' . $document->purchaseOrder->personClient->address->region_city . ', ' . $document->purchaseOrder->personClient->address->street . ', ' . $document->purchaseOrder->personClient->address->building_no }}
                                                </p>
                                            @else
                                                <p> <span
                                                        class="text-bold mr-2">{{ $document->purchaseOrder->businessClient->name }}</span>
                                                    Tax ID number
                                                    {{ $document->purchaseOrder->businessClient->tax_id_number }} </p>
                                                <p class="mb-1" style="line-height: 1rem;">
                                                    {{ $document->purchaseOrder->businessClient->address->country->name . ' ,' . $document->purchaseOrder->businessClient->address->city->name . ', ' . $document->purchaseOrder->businessClient->address->region_city . ', ' . $document->purchaseOrder->businessClient->address->street . ', ' . $document->purchaseOrder->businessClient->address->building_no }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="card card_border" style="max-height: 100px; overflow: hidden;">
                                        <div class="card-header">
                                            Purchase order number : <span
                                                class="header_number">{{ $document->purchaseOrder->purchase_order_reference }}</span>
                                        </div>
                                        <div class="card-header border-0">
                                            Project : <span>{{ $document->purchaseOrder->project_name }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>

            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>

            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>

            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>

            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
            <tr>
                <th>
                    <div class="t-head">&nbsp;
                </th>
            </tr>
        </thead>

        <tbody>
            {{-- Page content --}}
            <tr>
                <td>
                    <div class="card show-one-request print my-0" style="box-shadow: none !important">
                        <div class="card-body show-request-id">
                            <div class="mb-2">
                                <div class="mb-3">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                            {{-- Table header repeated in top of all pages --}}
                                            <thead>
                                                <tr>
                                                    <th scope="col" width0="30px">
                                                        ID
                                                    </th>
                                                    @if ($columnsName)
                                                        @for ($i = 0; $i < count($columnsName); $i++)
                                                            <th scope="col" width0="350px">
                                                                {{ $columnsName[$i] }}
                                                            </th>
                                                        @endfor
                                                    @else
                                                        <th scope="col" width0="350px">
                                                            Description
                                                        </th>
                                                    @endif
                                                    <th scope="col" width0="50px">
                                                        Unit
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        Price
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        Quantity
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        Sales Amount
                                                    </th>
                                                </tr>
                                            </thead>
                                            {{-- Table body (contant) --}}
                                            <tbody>
                                                @php $total_amount=0 @endphp
                                                @foreach ($document->items as $item)
                                                    <tr>
                                                        <th>
                                                            {{ $loop->iteration }}
                                                        </th>
                                                        @if ($columnsName)
                                                            @for ($i = 0; $i < count($columnsName); $i++)
                                                                @php
                                                                    $columns = explode('|', $item->description);
                                                                @endphp
                                                                <td scope="col">
                                                                    {{ explode(':', $columns[$i])[1] }}
                                                                </td>
                                                            @endfor
                                                        @else
                                                            <td>
                                                                {{ $item->description }}
                                                            </td>
                                                        @endif
                                                        <td>
                                                            {{ $item->basicItemData->productUnit->name_en }}
                                                        </td>
                                                        <td>
                                                            {{ $currency }} {{ number_format(($item->item_price), 2) }}
                                                        </td>
                                                        <td>
                                                            {{ $item->quantity }}
                                                        </td>
                                                        <td>
                                                            @php $total_amount += $item->quantity * $item->item_price @endphp
                                                            {{ $currency }} {{ number_format(($item->quantity * $item->item_price), 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-7"></div> --}}
                                    <div class="col-5 mt-3">
                                        <table
                                            class="table table-bordered table-striped table-hover float-right table-striped"
                                            id="tableTotal">
                                            <tbody>
                                                {{-- sumation taxes amount in the same type --}}
                                                @php
                                                    for ($key = 0; $key < 20; $key++) {
                                                        $taxes[$key] = 0;
                                                    }
                                                    $totalTaxes = 0;
                                                @endphp

                                                @foreach ($document->items as $doc_item)
                                                    @foreach ($doc_item->DocumentTaxes as $key => $document_item_tax)
                                                        @php
                                                            $taxes[$doc_item->basicItemData->purchaseOrderTaxes[$key]->tax_type] += $document_item_tax->amount_tax;
                                                            $totalTaxes += $document_item_tax->amount_tax;
                                                        @endphp
                                                    @endforeach
                                                @endforeach

                                                {{-- sumation all disount amount on items --}}
                                                @php $discountSumation=0 @endphp
                                                @foreach ($document->items as $item)
                                                    @php $discountSumation +=$item->discount_item_amount @endphp
                                                @endforeach

                                                <tr>
                                                    <th>
                                                        Total Amount
                                                    </th>
                                                    <th>
                                                        {{ $currency }} {{ number_format(($total_amount), 2) }}
                                                    </th>
                                                </tr>

                                                @foreach ($taxes as $index => $tax)
                                                    @if ($tax != 0)
                                                        <tr>
                                                            <th>
                                                                @if ($index == 1)
                                                                    @lang('site.value_add_tax')
                                                                @elseif($index == 2)
                                                                    @lang('site.table_tax_percentage')
                                                                @elseif($index == 3)
                                                                    @lang('site.table_tax_fixed')
                                                                @elseif($index == 4)
                                                                    @lang('site.withholding_tax')
                                                                @elseif($index == 5 || $index == 13)
                                                                    @lang('site.stamping_tax_percentage')
                                                                @elseif($index == 6 || $index == 14)
                                                                    @lang('site.stamping_tax_amount')
                                                                @elseif($index == 7 || $index == 15)
                                                                    @lang('site.entertainment_tax')
                                                                @elseif($index == 8 || $index == 16)
                                                                    @lang('site.resource_development_fee')
                                                                @elseif($index == 9 || $index == 17)
                                                                    @lang('site.service_charges')
                                                                @elseif($index == 10 || $index == 18)
                                                                    @lang('site.municipality_fees')
                                                                @elseif($index == 11 || $index == 19)
                                                                    @lang('site.medical_insurance_fee')
                                                                @elseif($index == 12 || $index == 20)
                                                                    @lang('site.other_fees')
                                                                @endif
                                                            </th>
                                                            <th>
                                                                {{ $currency }} {{ number_format(($tax) / $item->rate, 2) }}
                                                            </th>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                <tr>
                                                    <th>
                                                        Total discount
                                                    </th>
                                                    <th>
                                                        {{ $currency }} {{ number_format(($discountSumation), 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Invoice discount
                                                    </th>
                                                    <th>
                                                        {{ $currency }} {{ number_format(($document->extra_invoice_discount), 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Total Due
                                                    </th>

                                                    <th>
                                                        @php
                                                            $totalSumatiom = 0;
                                                            $itemRate = $document->items[0]->rate;
                                                        @endphp
                                                        @foreach ($document->items as $item)
                                                            @php $totalSumatiom +=$item->total_amount @endphp
                                                        @endforeach
                                                        {{ $currency }}
                                                        {{ number_format($totalSumatiom / $itemRate - $document->extra_invoice_discount, 2) }}
                                                    </th>


                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="tafqeet" class="col-12 text-bold d-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>

        {{-- Table footer repeated in bottom of all pages --}}
        <tfoot class="tf">
            <div class="print_footer">
                {{-- Start signature --}}

                {{-- Two signatures --}}

                {{-- Without signature name --}}
                {{-- <div class="row justify-content-center text-center my-5 py-5">
                    <div class="col-6 text-center">
                        Finance and administration director
                    </div>
                    <div class="col-6 text-center">
                        @if(!isset($document->document_number))
                        Requested By
                        @else
                        Accounting and auditing director 
                        @endif
                    </div>
                </div> --}}

                {{-- With signature name --}}
                {{-- <div class="row justify-content-center text-center my-5 pt-5 pb-2">
                    <div class="col-6 text-center">
                        Finance and administration director
                        <p class="text-bold m-0 p-0 pt-3">{{ $finance_and_administration_director }}</p>
                    </div>
                    <div class="col-6 text-center">
                        @if(!isset($document->document_number))
                        Requested By
                        @else
                        Accounting and auditing director 
                        @endif
                        <p class="text-bold m-0 p-0 pt-3">{{ $accounting_and_auditing_director }}</p>
                    </div>
                </div> --}}

                {{-- One signature --}}

                {{-- Without signature name --}}
                {{-- <div class="row my-5 py-5">
                    <div class="col-6 text-center">
                        @if(!isset($document->document_number))
                        Requested By
                        @else
                        Accounting and auditing director 
                        @endif
                    </div>
                </div> --}}

                {{-- With signature name --}}
                <div class="row mt-5 pt-5 pb-1">
                    <div class="col-6 text-center">
                        @if(!isset($document->document_number))
                        Requested By
                        @else
                        Accounting and auditing director 
                        @endif
                        <p class="text-bold m-0 p-0 pt-3">{{ $accounting_and_auditing_director }}</p>
                    </div>
                </div>

                {{-- End signature --}}

                <div class="row justify-content-center text-center my-5">
                    <div class="col-4 text-center">
                        {{ $company->tax_file_number }}
                        <span class="d-inline-block ml-3"> ملف ضريبى</span>
                    </div>
                    <div class="col-5 text-center">
                        {{ $company->tax_id_number }}
                        <span class="d-inline-block ml-3">رقم التسجيل فى الضريبة على القيمة المضافة</span>
                    </div>
                    <div class="col-3 text-center">
                        {{ $company->commercial_registeration_number }}
                        <span class="d-inline-block ml-3">سجل التجارى</span>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-7">
                        <img src="{{ asset('Images/footer.png') }}" class=" w-100" alt="">
                    </div>
                    <div class="col-4">
                        <img src="{{ asset('Images/New.jpg') }}" class="invoice_print_footer" alt="">
                    </div>
                </div>
            </div>

            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="t-foot">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
    <script>
        window.print();

    </script>
@endsection

@php
    App::setLocale($currentLang);
@endphp
