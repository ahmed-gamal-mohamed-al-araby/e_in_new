@extends('pages.layouts.master')
@section('title')
    {{ $document->document_number }}
@endsection

@section('content')
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.show_document')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> ( {{ $document->document_number }} )
                            @lang('site.show_document')</li>
                        <li class="breadcrumb-item active"><a href="{{ route('documents.index') }}">
                                @lang('site.documents')</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <div class="card show-one-request">
        <div class="card-header parent">
            <h3>@lang('site.show_document')</h3>
        </div>

        <div class="card-body show-request-id">
            <div class="mb-2">

                @if(is_object(session()->get('messages')) || is_array(session()->get('messages')))

                    <div class="alert alert-danger">

                        @foreach($steps = session()->get('messages') as $key => $value)
                            <tr>
                                {!!   implode(" : ",$steps[$key])."<br>"  !!}
                            </tr>
                        @endforeach
                    </div>

                @endif

                <div class="row">
                    <div class="col-md-6">
                        <table style="width: 85%" class="table table-bordered table-striped text-center table-sm p-3">
                            <tbody>
                            <tr>
                                <th scope="col">
                                    @lang('site.document_number')
                                </th>
                                <th scope="col">
                                    {{ $document->document_number }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @lang('site.date')
                                </th>
                                <th>
                                    {{ $document->date }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @lang('site.issuer_from')
                                </th>
                                <th>
                                    {{ $document->company->company_name }}
                                </th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table style="width:90%" class="table table-bordered float-right table-striped table-sm p-3">
                            <tbody>
                            <tr>
                                <th>
                                    @lang('site.purchase_order_reference')
                                </th>
                                <th>
                                    <a href="{{ route('purchaseorders.show',$document->purchaseOrder->id) }}">{{ $document->purchaseOrder->purchase_order_reference }}</a>
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    @lang('site.receiver_to')
                                </th>
                                <th>
                                    @if ($document->purchaseOrder->client_type == 'f')
                                        <a href="{{ route('foreignerClient.index') }}">
                                            {{ $document->purchaseOrder->foreignerClient->company_name }} </a>
                                    @elseif($document->purchaseOrder->client_type == 'p')
                                        <a href="{{ route('personClient.index') }}">
                                            {{ $document->purchaseOrder->personClient->name }} </a>
                                    @else
                                        <a
                                            href="{{ route('businessClients.profile', ['id' => $document->purchaseOrder->businessClient->id]) }}">{{ $document->purchaseOrder->businessClient->name }}</a>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @lang('site.address')
                                </th>
                                <th>
                                    @if ($document->purchaseOrder->client_type == 'f')
                                        {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->city->name . ', ' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                    @elseif($document->purchaseOrder->client_type == 'p')
                                        {{ $document->purchaseOrder->personClient->address->country->name . ' ,' . $document->purchaseOrder->personClient->address->city->name . ', ' . $document->purchaseOrder->personClient->address->region_city . ', ' . $document->purchaseOrder->personClient->address->street . ', ' . $document->purchaseOrder->personClient->address->building_no }}
                                    @else
                                        {{ $document->purchaseOrder->businessClient->address->country->name . ' ,' . $document->purchaseOrder->businessClient->address->city->name . ', ' . $document->purchaseOrder->businessClient->address->region_city . ', ' . $document->purchaseOrder->businessClient->address->street . ', ' . $document->purchaseOrder->businessClient->address->building_no }}
                                    @endif
                                </th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center m-0">
                            <thead>

                            <tr>
                                <th scope="col" width="30px">
                                    @lang('site.id')
                                </th>
                                <th scope="col">
                                    @lang('site.product_name')
                                </th>
                                <th scope="col">
                                    @lang('site.product_code')
                                </th>
                                <th scope="col" width="350px">
                                    @lang('site.description')
                                </th>
                                <th scope="col" width="50px">
                                    @lang('site.currency')
                                </th>
                                <th scope="col" width="50px">
                                    @lang('site.unit')
                                </th>
                                <th scope="col" width="50px">
                                    @lang('site.price')
                                </th>
                                <th scope="col" width="50px">
                                    @lang('site.quantity')
                                </th>
                                <th scope="col" width="50px">
                                    @lang('site.sales_amount')
                                </th>
                            </tr>

                            </thead>
                            <tbody>
                            @php $total_amount=0 @endphp
                            @foreach ($document->items as $item)
                                <tr>
                                    <th>
                                        {{ $loop->iteration }}
                                    </th>
                                    <td>
                                        {{ $item->basicItemData->product->product_name }}
                                    </td>
                                    <td>

                                        {{ $item->basicItemData->product->product_code }}
                                    </td>
                                    <td>
                                        {{ $item->basicItemData->description }}
                                    </td>
                                    <td>
                                        {{ $item->basicItemData->currency }}
                                    </td>
                                    <td>
                                        {{ $item->basicItemData->unit }}
                                    </td>
                                    <td>
                                        {{ number_format($item->item_price, 2) }}
                                    </td>
                                    <td>
                                        {{ $item->quantity }}
                                    </td>
                                    <td>
                                        @php $total_amount += $item->quantity * $item->item_price @endphp
                                        {{ number_format($item->quantity * $item->item_price, 2) }}
                                    </td>


                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{-- </div> --}}

                </div>
                <div class="row">
                    <div class="col-7">
                        @if ($document->documentinvalidsteps->invalid_steps ?? '')
                            @php
                                $invalid_steps = $document->documentinvalidsteps->invalid_steps;
                                // $invalid_steps =  json_decode($invalid_steps);
                            @endphp
                            @foreach ($invalid_steps as $key => $invalid_step)
                                <div class="p-3 mb-1 alert alert-danger text-white">
                                    Step Name : {{ $invalid_step['Step_Name'] }} <br>
                                    Error Messsage : {{ $invalid_step['Error_Messsage'] }} <br>
                                    Inner Error : {{ $invalid_step['inner_Error'] }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-5">
                        <table class="table table-bordered table-striped  table-hover float-right table-striped ">
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
                                          $tax_rate_first = \App\Models\PurchaseOrderTax::where('item_id',$doc_item->basicItemData->id)->first();
                                            $tax_rate =$tax_rate_first->tax_rate;
                                          $taxes[$tax_rate_first->tax_type] += ($doc_item->discount_item_rate / 100) * ( $doc_item->quantity * $doc_item->item_price ) ;
                                          $totalTaxes += $doc_item->basicItemData->discount_item_amount ;
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
                                    @lang('site.total_amount')
                                </th>
                                <th>
                                    {{ number_format($total_amount, 2) }} / {{ $item->basicItemData->currency }}
                                </th>
                            </tr>


                            @foreach ($taxes as $index => $tax)
                                @if ($tax !== 0)
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
                                            {{ number_format( ($total_amount - $tax ) * ($tax_rate / 100)  , 2) }}
                                        </th>
                                    </tr>
                                @endif

                            @endforeach


                            <tr>
                                <th>
                                    @lang('site.total_discount')
                                </th>
                                <th>
                                    @php $discountSumation=0 @endphp
                                    @foreach ($document->items as $item)

                                        @php
                                            $discountSumation +=  ($item->discount_item_rate / 100) * ( $item->quantity * $item->item_price );
                                         @endphp

                                    @endforeach
                                    {{ number_format($discountSumation, 2) }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @lang('site.discount_invoice')
                                </th>
                                <th>
                                    {{ number_format($document->extra_invoice_discount, 2) }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @lang('site.total_due')
                                </th>
                                <th>
                                    @php $totalSumatiom=0 @endphp
                                    @foreach ($document->items as $item)
                                        @php $totalSumatiom +=$item->total_amount @endphp
                                    @endforeach

                                    @if($item->basicItemData->currency != 'EGP')

                                        {{--                                        {{ number_format(($totalSumatiom - $document->extra_invoice_discount) /  ($item->basicItemData->documentItems[0]->rate), 2) }}--}}
                                        {{--                                    {{ $item->basicItemData->documentItems[0] }}--}}
                                        {{ number_format(($item->basicItemData->documentItems[0]->rate * $total_amount) / $item->basicItemData->documentItems[0]->rate, 2) }}
                                    @else
                                        {{ number_format($totalSumatiom - $document->extra_invoice_discount , 2) }}

                                    @endif
                                </th>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    {{--{{dd($document->approved)}}--}}
                    @if ($document->submit_status)
                        <div class="offset-md-6"></div>
                    @else
                        <div class="row col-md-6 mt-2 mb-2">
                            <div class="dropdown mr-1 ml-1">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('site.send_document')
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <form action="{{ route('documents.submitToServer2') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="document_id" value="{{ $document->id }}">
                                        @if (auth()->user()->hasPermission('document_send') && $document->approved)
                                            <button type="submit" class="form-control">فاتورة مختصرة</button>
                                            {{--                                                   value="@lang('site.virtual_send_document')"--}}
                                        @endif
                                    </form>

                                    <form action="{{ route('documents.submitToServer') }}" method="post"
                                          class="d-inline">
                                        @csrf
                                        <input type="hidden" name="document_id" value="{{ $document->id }}">
                                        @if (auth()->user()->hasPermission('document_send') && $document->approved)
                                            <input type="submit" class="form-control" value="فاتورة تفصيلية">
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <form action="{{ route('documents.virtualSubmit') }}" method="post" class="d-inline">
                                @csrf
                                <input type="hidden" name="document_id" value="{{ $document->id }}">
                                @if (auth()->user()->hasPermission('document_send') && $document->approved)
                                    <input type="submit" class="btn btn-danger"
                                           value="@lang('site.virtual_send_document')">
                                @endif
                            </form>

                        </div>
                    @endif

                    <div class="col-md-6">
                        <form action="{{ route('documents.print', $document->id) }}" method="get" target="_blank"
                              class="row justify-content-md-end justify-content-between align-content-center">
                            {{-- Accounting and auditing director --}}
                            <div class="form-group col-md-10 offset-md-2">
                                {{-- Default value Amira Anwar --}}
                                <input type="text" name="accounting_and_auditing_director" class="form-control" required
                                       value="Amira Anwar"
                                       placeholder="@lang('site.accounting_and_auditing_director')"
                                       oninvalid="this.setCustomValidity('@lang('site.accounting_and_auditing_director')')"
                                       oninput="setCustomValidity('')">
                            </div>

                            {{-- Head of the financial and administrative sector --}}
                            {{-- If we want to add it remove d-none and remove disabled attribute from input --}}
                            <div class="form-group col-md-10 offset-md-2 d-none">
                                <input type="text" name="finance_and_administration_director" class="form-control"
                                       required disabled value=""
                                       placeholder="@lang('site.finance_and_administration_director')"
                                       oninvalid="this.setCustomValidity('@lang('site.finance_and_administration_director')')"
                                       oninput="setCustomValidity('')">
                            </div>
                            <button type="submit" data-document-id="{{ $document->id }}" target="_blank"
                                    class="btn btn-success anchor_print col-3 p-0">@lang('site.print')</button>
                            <select class="custom-select col-5 mx-1" name="print_type" id="print_type" required
                                    oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.type')')"
                                    oninput="setCustomValidity('')">
                                <option selected disabled value="">@lang('site.please') @lang('site.select')
                                    @lang('site.type')</option>
                                <option value="public">Public Comany</option>
                                <option value="special">Special Company</option>
                                <option value="army">Army</option>
                                <option value="percentage">Percentage invoice (%)</option>
                            </select>
                            <div class="form-check col-2 d-none row align-items-center m-0">
                                <input type="checkbox" disabled name="tafqeet" class="form-check-input" id="tafqeet">
                                <label class="form-check-label" for="tafqeet">تفقيط</label>
                            </div>
                            <div class="input-group offset-6 col-6 mt-2 d-none">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">%</span>
                                </div>
                                <input type="number" min="1" max="100" class="form-control" required disabled
                                       id="invoice-percentage-print"
                                       name="invoicePercentage" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </form>
                    </div>
                </div>


                {{-- <a  href="{{ route('documents.index') }}" type="button" class="btn btn-warning">@lang('site.send_document')</a> --}}
            </div>

        </div>


    </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
            // Choose print type and validate on percentage type
            const invoicePercentagePrint = $('#invoice-percentage-print'),
                tafqeet = $('#tafqeet');
            $('#print_type').on('change', function () {
                const value = $(this).val();
                if (value == 'percentage') {
                    invoicePercentagePrint.parent().removeClass('d-none');
                    invoicePercentagePrint.removeAttr("disabled");
                    invoicePercentagePrint.val('');
                } else {
                    invoicePercentagePrint.parent().addClass('d-none');
                    invoicePercentagePrint.attr("disabled", "disabled");
                }
                if (value == 'public' || value == 'army') {
                    tafqeet.parent().removeClass('d-none');
                    tafqeet.removeAttr("disabled");
                    tafqeet.prop('checked', false);
                } else {
                    tafqeet.parent().addClass('d-none');
                    tafqeet.attr("disabled", "disabled");

                }
            });
        });

    </script>
@endsection
