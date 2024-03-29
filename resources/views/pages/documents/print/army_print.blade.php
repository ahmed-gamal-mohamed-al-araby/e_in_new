@extends('pages.documents.print.master')
@php
$currentLang = Config::get('app.locale');
App::setLocale('ar');
@endphp
@section('content')
    {{-- content --}}
    <table>
        {{-- Table header repeated in top of all pages --}}
        <thead>
            <div class="print_header">
                <img src="{{ asset('Images/army.png') }}" class="w-100 print_invoice_header" alt="">
                <div class="card show-one-request print" style="box-shadow: none !important">
                    <div class="card-body show-request-id">
                        <div class="mb-2 public_print_invoice">
                            <div class="row my-0">
                                <div class="col-12">
                                    <p class="d-inline-block">رقم الفاتوره : <span class="header_number number_arabic"
                                            style="unicode-bidi: embed;">{{ $document->document_number }}</span> </p>
                                    <p class="d-inline-block  mr-5">تاريخ الفاتوره : <span class="header_number number_arabic"
                                            style="unicode-bidi: plaintext;">{{ $document->date }}</span> </p>
                                </div>

                                <div class="col-6">
                                    <div class="card card_border m-0">
                                        <div class="card-header">
                                            بيانات العميل
                                        </div>
                                        <div class="card-body"
                                            style="max-height: 110px; line-height: 1rem; padding: .75rem 1.25rem; overflow: hidden;">
                                            @if ($document->purchaseOrder->client_type == 'f')
                                                <p class="header_number text-bold">
                                                    {{ $document->purchaseOrder->foreignerClient->company_name }} </p>
                                                <p class="header_number mb-1" style="line-height: 1rem;">
                                                    @if($document->purchaseOrder->foreignerClient->address->city_id)
                                                    {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->city->name . ', ' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                                    @else
                                                    {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                                    @endif
                                                </p>
                                            @elseif($document->purchaseOrder->client_type == 'p')
                                                <p class="header_number text-bold">
                                                    {{ $document->purchaseOrder->personClient->name }} </p>
                                                <p class="header_number mb-1" style="line-height: 1rem;">
                                                    {{ $document->purchaseOrder->personClient->address->country->name . ' ,' . $document->purchaseOrder->personClient->address->city->name . ', ' . $document->purchaseOrder->personClient->address->region_city . ', ' . $document->purchaseOrder->personClient->address->street . ', ' . $document->purchaseOrder->personClient->address->building_no }}
                                                </p>
                                            @else
                                                <p class="header_number">
                                                    <span
                                                        class="text-bold ml-2">{{ $document->purchaseOrder->businessClient->name }}</span> </p>
                                                    {{ $document->purchaseOrder->businessClient->tax_id_number }}
                                                    <span class="text-bold ml-3" style="padding-right:5px;">: ب.ض  </span>
                                               
                                                <p class="header_number mb-1" style="line-height: 1rem;">
                                                    {{ $document->purchaseOrder->businessClient->address->country->name . ' ,' . $document->purchaseOrder->businessClient->address->city->name . ', ' . $document->purchaseOrder->businessClient->address->region_city . ', ' . $document->purchaseOrder->businessClient->address->street . ', ' . $document->purchaseOrder->businessClient->address->building_no }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="card card_border" style="max-height: 100px; overflow: hidden;">
                                        <div class="card-header">
                                            أمر توريد رقم : <span
                                                class="header_number number_arabic">{{ $document->purchaseOrder->purchase_order_reference }}</span>
                                        </div>
                                        <div class="card-header border-0">
                                            مشروع : <span
                                                class="header_number">{{ $document->purchaseOrder->project_name }}</span>
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
                            <div class="mb-2 public_print_invoice">
                                <div class="mb-3">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                            {{-- Table header repeated in top of all pages --}}
                                            <thead>
                                                <tr>
                                                    <th scope="col" width0="30px">
                                                        م
                                                    </th>
                                                    @if ($columnsName)
                                                        @for ($i = 0; $i < count($columnsName); $i++)
                                                            <th scope="col" width0="350px">
                                                                {{ $columnsName[$i] }}
                                                            </th>
                                                        @endfor
                                                    @else
                                                        <th scope="col" width0="350px">
                                                            الوصف
                                                        </th>
                                                    @endif
                                                    <th scope="col" width0="50px">
                                                        الوحدة
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        سعر البند
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        الكمية
                                                    </th>
                                                    <th scope="col" width0="50px">
                                                        مبلغ المبيعات
                                                    </th>
                                                </tr>
                                            </thead>
                                            {{-- Table body (contant) --}}
                                            <tbody>
                                                @php $total_amount=0 @endphp
                                                @foreach ($document->items as $item)
                                                    <tr>
                                                        <th>
                                                            <span class="iteration number_arabic"> {{ $loop->iteration }}
                                                            </span>
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
                                                            {{ $item->basicItemData->productUnit->name_ar }}
                                                        </td>
                                                        <td>
                                                            <span class="price number_arabic">
                                                                {{ $currency }}
                                                                {{ number_format($item->item_price, 2) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="quantity number_arabic">
                                                                {{ $item->quantity }}</span>
                                                        </td>
                                                        <td>
                                                            @php $total_amount += $item->quantity * $item->item_price @endphp
                                                            <span class="sales_amount number_arabic">
                                                                {{ $currency }}
                                                                {{ number_format($item->quantity * $item->item_price, 2) }}
                                                                <span>
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
                                                        المبلغ الإجمالي
                                                    </th>
                                                    <th class="relative-arabic-number number_arabic">
                                                        {{ $currency }} {{ number_format($total_amount, 2) }}
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
                                                            <th class="relative-arabic-number number_arabic">
                                                                {{ $currency }}
                                                                {{ number_format($tax / $item->rate, 2) }}
                                                            </th>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                <tr>
                                                    <th>
                                                        إجمالى الخصم
                                                    </th>
                                                    <th class="relative-arabic-number number_arabic">
                                                        {{ $currency }} {{ number_format($discountSumation, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        خصم الفاتورة
                                                    </th>
                                                    <th class="relative-arabic-number number_arabic">
                                                        {{ $currency }}
                                                        {{ number_format($document->extra_invoice_discount, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        الإجمالى المستحق
                                                    </th>
                                                    <th class="relative-arabic-number number_arabic">
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
                        رئيس القطاع المالي و الإداري
                    </div>
                    <div class="col-6 text-center">
                    @if(!isset($document->document_number))
                        Requested By
                        @else
                        رئيس قطاع الحسابات و المراجعة 
                        @endif
                    </div>
                </div> --}}

                {{-- With signature name --}}
                {{-- <div class="row justify-content-center text-center my-5 pt-5 pb-2">
                    <div class="col-6 text-center">
                        رئيس القطاع المالي و الإداري
                        <p class="text-bold m-0 p-0 pt-3">{{ $finance_and_administration_director }}</p>
                    </div>
                    <div class="col-6 text-center">
                    @if(!isset($document->document_number))
                        Requested By
                        @else
                        رئيس قطاع الحسابات و المراجعة 
                        @endif
                        <p class="text-bold m-0 p-0 pt-3">{{ $accounting_and_auditing_director }}</p>
                    </div>
                </div> --}}

                {{-- One signature --}}

                {{-- Without signature name --}}
                {{-- <div class="row justify-content-end my-5 py-5">
                    <div class="col-6 text-center">
                    @if(!isset($document->document_number))
                        Requested By
                        @else
                        رئيس قطاع الحسابات و المراجعة 
                        @endif
                    </div>
                </div> --}}

                {{-- With signature name --}}
                <div class="row justify-content-end mt-5 pt-5 pb-1">
                    <div class="col-6 text-center">
                    @if(!isset($document->document_number))
                        Requested By
                        @else
                        رئيس قطاع الحسابات و المراجعة 
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

@section('define_fraction')
    @if ($tafqeet == true)
        <script>
            let fraction = "{{ number_format($totalSumatiom / $itemRate - $document->extra_invoice_discount, 2) }}";
        </script>
    @endif
@endsection

@section('scripts')
    <script>
        const relativeArabicArray = ['۰', '۱', '۲', '۳', '٤', '٥', '٦', '۷', '۸', '۹']

        function relativeArabicNumber(number) {
            number = number.trim();
            let arabicNumber = '';
            for (let i = 0; i < number.length; i++) {
                if (number[i].match(/\d/)) {
                    arabicNumber += relativeArabicArray[number[i]];
                } else {
                    arabicNumber += number[i];
                }
            }
            return arabicNumber;
        }

        String.prototype.toIndiaDigits = function() {
            var id = ['۰', '۱', '۲', '۳', '٤ ', '٥', '٦', '۷', '۸', '۹']
            return this.replace(/[0-9]/g, function(w) {
                return id[+w]
            });
        }

        // test

        var val = 0;
        $('.quantity').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.price').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.sales_amount').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.iteration').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.description').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.summery_price').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.header_number').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        $('.relative-arabic-number').each(function(index, value) {
            val = $(this).text();
            $(this).text(relativeArabicNumber(val));
            val = 0;
        });

        window.print();

    </script>
@endsection

@php
App::setLocale($currentLang);
@endphp
