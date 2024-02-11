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
                                        @if ($document->purchaseOrder->foreignerClient->address->city_id)
                                            {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->city->name . ', ' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                        @else
                                            {{ $document->purchaseOrder->foreignerClient->address->country->name . ' ,' . $document->purchaseOrder->foreignerClient->address->region_city . ', ' . $document->purchaseOrder->foreignerClient->address->street . ', ' . $document->purchaseOrder->foreignerClient->address->building_no }}
                                        @endif

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
                                        {{ $item->description }}
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
                    <div class="col-7"></div>
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
                                    @lang('site.total_amount')
                                </th>
                                <th>
                                    @if(isset($item->basicItemData->currency))
                                        {{ number_format($total_amount, 2) }} / {{ $item->basicItemData->currency }}
                                    @endif
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
                                            {{ number_format($tax, 2) }}
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
                                        @php $discountSumation +=$item->discount_item_amount @endphp
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
                                    {{ number_format($totalSumatiom - $document->extra_invoice_discount, 2) }}
                                </th>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                {{-- Change status to be approved --}}
                @if ($notification->user_id != auth()->user()->id && $document->approved == 0 && !$notification->comment && $notification->type == 'a')
                    <form method="POST"
                          action="{{ route('document_approved', $document->id) }}?n_id={{ $notification->id }}">
                        @csrf
                        @method('put')
                        <div class="fieldset-footer pb-1 px-0">
                            <div class="row">
                                <div class="col-md-12 mb-3 row justify-content-end m-0">
                                    {{-- Show approve button if active user not user make this notification && this purchase order is not approved --}}
                                    <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                    <button type="submit" class="btn btn-success ">@lang('site.approve') <i
                                            class="fas fa-check"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                {{-- Reply with comment --}}
                {{-- * Show if active user is not that notification owner
                        * and this record is not approved
                        * notification type is a => for action not n => for normal --}}
                @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $document->approved == 0)
                    <form action="{{ route('notification.reply') }}" method="post">
                        @csrf
                        <input type="hidden" name="n_id" value="{{ $notification->id }}">
                        <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                        <div class="row justify-content-end py-3">
                            <button type="submit" class="btn btn-danger mt-2 mr-2 d-block">@lang('site.send')
                                @lang('site.comment') <i class="fas fa-times"></i></button>
                        </div>
                    </form>
                @endif

                {{-- Show edit for user that have this notification --}}
                {{-- * Show if active user is notification owner
                        * and this record is not approved
                        * notification have comment or notification type is a => for action not n => for normal --}}
                @if ($notification->user_id == auth()->user()->id && $document->approved == 0 && ($notification->type == 'a' || $notification->comment))
                    <div class="row justify-content-end">
                        <a class="btn btn-sm btn-warning mr-2"
                           href="{{ route('documents.edit', $document->id) }}">@lang('site.go_for_edit')<i
                                class="ml-2 fas fa-edit"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
