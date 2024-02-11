@extends('pages.layouts.master')

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
@endsection

@section('content')
    <section class="content-header prequestHeader">

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1> @lang('site.show_purchaseorder') </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> ( {{ $purchaseorder->purchase_order_reference }} )
                            @lang('site.purchaseorder_details')</li>
                        <li class="breadcrumb-item active"><a href="#"> @lang('site.all_purchaseorders')</a> </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card show-one-request">
        <div class="card-header parent">
            <h3>@lang('site.show_purchaseorder') </h3>
        </div>

        <div class="card-body show-request-id">
            <div class="mb-2">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="po-details-tab" data-toggle="pill" href="#po-details" role="tab"
                            aria-controls="po-details" aria-selected="true">@lang('site.purchaseorder_details')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#po-items" role="tab"
                            aria-controls="po-items" aria-selected="false">@lang('site.products')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#po-delivery" role="tab"
                            aria-controls="po-delivery" aria-selected="false">@lang('site.bank_and_delivery_details')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#po-client" role="tab"
                            aria-controls="po-delivery" aria-selected="false">@lang('site.client')</a>
                    </li>

                </ul>

                <div class="tab-content" id="pills-tabContent">
                    {{-- PO Details --}}
                    <div class="tab-pane fade show active" id="po-details" role="tabpanel" aria-labelledby="po-details-tab">

                        <div class="card">
                            <h5 class="card-header bg-success">
                                @lang('site.purchaseorder_details')
                            </h5>
                            <div class="table-responsive mb-3">
                                <table
                                    class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.purchase_order_reference')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->purchase_order_reference }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="col">
                                                @lang('site.po_project_name')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->project_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.po_project_number')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->project_number }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="col">
                                                @lang('site.po_project_contract_number')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->project_contract_number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                @lang('site.purchaseorder_document')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->purchaseorder_document != null)
                                                    <a href="{{ $purchaseorder->document_path }}"
                                                        class="img-thumbnail image-preview" target="_blank">PO Document</a>
                                                @else
                                                    <p class="text-danger">@lang('site.not_available')</p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                @lang('site.payment_terms')
                                            </th>
                                            <td>
                                                {!! nl2br(e($purchaseorder->payment_terms)) !!}

                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.primary_delivery_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->primary_delivery_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.final_delivery_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->final_delivery_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.social_insurance_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->social_insurance_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.labor_insurance_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->labor_insurance_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.tax_exemption_certificate_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->tax_exemption_certificate_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.received_final_performance_bond_status')
                                            </th>
                                            <td>
                                                @if ($purchaseorder->received_final_performance_bond_status)
                                                    <i class="fas fa-check text-success"></i>
                                                @else
                                                    <i class="fas fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.created_at')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->created_at }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                    {{-- PO items --}}
                    <div class="tab-pane fade" id="po-items" role="tabpanel" aria-labelledby="po-items-tab">
                        <div class="card">
                            <h5 class="card-header bg-success">
                                @lang('site.products')
                            </h5>

                            {{-- add New Item --}}
                            @if ($notification->user_id == auth()->user()->id && $purchaseorder->approved == 0 && ($notification->type == 'a' || $notification->comment))
                                <div class="col-6 text-right items-links my-2">
                                    <a data-toggle="modal" data-target="#edit-item" class="addNewItem"><i
                                            class="fa fa-plus"></i> @lang('site.add_item')</i></a>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                    <thead>

                                        <tr>
                                            <th scope="col" width="30px">
                                                @lang('site.id')
                                            </th>
                                            <th scope="col">
                                                @lang('site.name')
                                            </th>
                                            <th scope="col">
                                                @lang('site.product_code')
                                            </th>
                                            <th scope="col" width="350px">
                                                @lang('site.description')
                                            </th>
                                            <th scope="col" width="50px">
                                                @lang('site.quantity')
                                            </th>
                                            <th scope="col" width="50px">
                                                @lang('site.unit')
                                            </th>
                                            <th scope="col" width="50px">
                                                @lang('site.price')
                                            </th>
                                            <th scope="col" width="50px">
                                                @lang('site.total')
                                            </th>
                                            <th scope="col">
                                                @lang('site.actions')
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseorder->items as $item)
                                            <tr>
                                                <th>
                                                    {{ $loop->iteration }}
                                                </th>
                                                <td>
                                                    {{ $item->product->product_name }}
                                                </td>
                                                <td>

                                                    {{ $item->product->product_code }}
                                                </td>
                                                <td>
                                                    {{ $item->description }}
                                                </td>

                                                <td>
                                                    {{ $item->quantity }}
                                                </td>
                                                <td>
                                                    {{ $item->productUnit->name_ar }}
                                                </td>
                                                <td>
                                                    {{ number_format($item->item_price,2) }}
                                                </td>
                                                <td>
                                                    {{ number_format(($item->quantity * $item->item_price),2) }}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{ route('item.show', $item->id) }}"
                                                        class="btn btn-success btn-sm"> <i class="fa fa-eye"></i></a>
                                                    @if ($notification->user_id == auth()->user()->id && $purchaseorder->approved == 0 && ($notification->type == 'a' || $notification->comment))
                                                        <a data-toggle="modal" data-target="#edit-item"
                                                        data-item-id="{{ $item->id }}"
                                                        class="btn btn-warning btn-sm editItem"> <i
                                                            class="fa fa-edit"></i></a>

                                                        {{-- Show delete if no items added in any document --}}
                                                        @if ($item->documentItems->count() == 0)
                                                            <a data-item-id="{{ $item->id }}" data-toggle="modal"
                                                                data-target="#item_delete" class="btn btn-danger btn-sm"> <i
                                                                    class="fa fa-trash-alt"></i></a>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-7"></div>
                            <div class="col-5">
                                <table class="table table-bordered table-striped table-hover float-right table-striped ">
                                    <tbody>
                                        @php
                                            $taxes = [];
                                            $taxes[0] = 0;
                                            $taxes[1] = 0;
                                            $taxes[2] = 0;
                                            $taxes[3] = 0;
                                            $taxes[4] = 0;
                                            $taxes[5] = 0;
                                            $taxes[6] = 0;
                                            $taxes[7] = 0;
                                            $taxes[8] = 0;
                                            $taxes[9] = 0;
                                            $taxes[10] = 0;
                                            $taxes[11] = 0;
                                            $taxes[12] = 0;
                                            $taxes[13] = 0;
                                            $taxes[14] = 0;
                                            $taxes[15] = 0;
                                            $taxes[16] = 0;
                                            $taxes[17] = 0;
                                            $taxes[18] = 0;
                                            $taxes[19] = 0;
                                            $taxes[20] = 0;
                                        @endphp
                                        @foreach ($purchaseorder->items as $item)
                                            @foreach ($item->purchaseOrderTaxes as $index => $tax)
                                                @php $taxes[$tax->tax_type] += $tax->amount_tax  ; @endphp
                                                @php  @endphp
                                            @endforeach
                                        @endforeach

                                        @foreach ($taxes as $index => $tax)
                                            @if ($tax != 0)
                                                <tr>
                                                    <th>
                                                        @if ($index == 1)
                                                            Value added tax (T{{ $index }})
                                                        @elseif($index == 2)
                                                            Table tax (percentage) (T{{ $index }})
                                                        @elseif($index == 3)
                                                            Table tax (Fixed Amount) (T{{ $index }})
                                                        @elseif($index == 4)
                                                            Withholding tax (WHT) (T{{ $index }})
                                                        @elseif($index == 5 || $index == 13)
                                                            Stamping tax (percentage) (T{{ $index }})
                                                        @elseif($index == 6 || $index == 14)
                                                            Stamping Tax (amount) (T{{ $index }})
                                                        @elseif($index == 7 || $index == 15)
                                                            Entertainment tax (T{{ $index }})
                                                        @elseif($index == 8 || $index == 16)
                                                            Resource development fee (T{{ $index }})
                                                        @elseif($index == 9 || $index == 17)
                                                            Service charges (T{{ $index }})
                                                        @elseif($index == 10 || $index == 18)
                                                            Municipality Fees (T{{ $index }})
                                                        @elseif($index == 11 || $index == 19)
                                                            Medical insurance fee (T{{ $index }})
                                                        @elseif($index == 12 || $index == 20)
                                                            Other fees (T{{ $index }})
                                                        @endif
                                                    </th>
                                                    <th>
                                                        {{ number_format($tax,2) }}
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
                                                @foreach ($purchaseorder->items as $item)
                                                    @php $discountSumation +=$item->discount_item_amount @endphp
                                                @endforeach
                                                {{ number_format($discountSumation,2) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @lang('site.total_amount')
                                            </th>
                                            <th>
                                                @php $totalSumatiom=0 @endphp
                                                @foreach ($purchaseorder->items as $item)
                                                    @php $totalSumatiom +=$item->total_amount @endphp
                                                @endforeach
                                                {{ number_format($totalSumatiom,2) }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    {{-- Bank and Delivery --}}
                    <div class="tab-pane fade" id="po-delivery" role="tabpanel" aria-labelledby="po-delivery-tab">
                        <div class="card">

                            {{-- Bank details --}}
                            <h5 class="card-header bg-success">
                                @lang('site.bank_details')
                            </h5>
                            <div class="table-responsive mb-3">
                                <table
                                    class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.bank_code')
                                            </th>
                                            <th scope="col">
                                                @lang('site.bank_name')
                                            </th>
                                            <th scope="col">
                                                @lang('site.bank_account_number')
                                            </th>
                                            <th scope="col">
                                                @lang('site.bank_account_iban')
                                            </th>
                                            <th scope="col">
                                                @lang('site.swift_code')
                                            </th>
                                            <th scope="col">
                                                @lang('site.bank_address')
                                            </th>
                                        </tr>


                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->bank_code }}
                                            </td>
                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->bank_name }}
                                            </td>
                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->bank_account_number }}
                                            </td>
                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->bank_account_iban }}
                                            </td>

                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->swift_code }}
                                            </td>
                                            <td>
                                                {{ $purchaseorder->bank()->find($purchaseorder->bank_id)->bank_address }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            {{-- Delivery details --}}
                            <h5 class="card-header bg-success">
                                @lang('site.delivery_details')
                            </h5>
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                    <tbody>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.delivery_validate_date')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->delivery_validate_date }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.delivery_export_port')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->delivery_export_port }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.delivery_country_origin')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->deliveryCountryOrigin->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.delivery_gross_weight')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->delivery_gross_weight }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                @lang('site.delivery_net_weight')
                                            </th>
                                            <td>
                                                {{ $purchaseorder->delivery_net_weight }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                @lang('site.delivery_approach')
                                            </th>
                                            <td>
                                                {!! nl2br(e($purchaseorder->delivery_approach)) !!}

                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                @lang('site.delivery_packaging')
                                            </th>
                                            <td>
                                                {!! nl2br(e($purchaseorder->delivery_packaging)) !!}

                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                @lang('site.delivery_terms')
                                            </th>
                                            <td>
                                                {!! nl2br(e($purchaseorder->delivery_terms)) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    {{-- PO Client --}}
                    <div class="tab-pane fade " id="po-client" role="tabpanel" aria-labelledby="po-client-tab">

                        <div class="card">
                            <h5 class="card-header bg-success">
                                @lang('site.client_details')
                            </h5>
                            <div class="table-responsive mb-3">
                                <table
                                    class="table table-bordered table-striped table-hover justify-content-center text-center m-0">
                                    @if ($purchaseorder->client_type == 'b')
                                        <tbody>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.client_type')
                                                </th>
                                                <td>
                                                    {{ 'Business' }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.company')
                                                </th>
                                                <td>
                                                    {{ $client->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.phone')
                                                </th>
                                                <td>
                                                    {{ $client->phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.email')
                                                </th>
                                                <td>
                                                    {{ $client->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.tax_id_number_only')
                                                </th>
                                                <td>
                                                    {{ $client->tax_id_number }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.commercial_registeration_number_only')
                                                </th>
                                                <td>
                                                    {{ $client->commercial_registeration_number }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.tax_file_number')
                                                </th>
                                                <td>
                                                    {{ $client->tax_file_number }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.address')
                                                </th>
                                                @if($client->address->city_id)
                                                <td>
                                                    {{ $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no }}
                                                </td>
                                                @else
                                                <td>
                                                    {{ $client->address->country->name . ' ,' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no }}
                                                </td>
                                                @endif

                                            </tr>
                                        </tbody>

                                        {{-- Foreigner --}}
                                    @elseif ($purchaseorder->client_type == 'f')
                                        <tbody>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.client_type')
                                                </th>
                                                <td>
                                                    {{ 'Foreigner' }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.responsible_person')
                                                </th>
                                                <td>
                                                    {{ $client->person_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.mobile')
                                                </th>
                                                <td>
                                                    {{ $client->person_mobile }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.email')
                                                </th>
                                                <td>
                                                    {{ $client->person_email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.company')
                                                </th>
                                                <td>
                                                    {{ $client->company_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.address')
                                                </th>
                                                @if($client->address->city_id)
                                                <td>
                                                    {{ $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no }}
                                                </td>
                                                @else
                                                <td>
                                                    {{ $client->address->country->name . ' ,' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no }}
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>

                                        {{-- Person --}}
                                    @elseif ($purchaseorder->client_type == 'p')
                                        <tbody>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.client_type')
                                                </th>
                                                <td>
                                                    {{ 'Person' }}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.name')
                                                </th>
                                                <td>
                                                    {{ $client->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.national_id')
                                                </th>
                                                <td>
                                                    {{ $client->national_id }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    @lang('site.mobile')
                                                </th>
                                                <td>
                                                    {{ $client->mobile }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    @lang('site.address')
                                                </th>
                                                <td>
                                                    {{ $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endif

                                </table>

                            </div>
                        </div>

                    </div>

                    {{-- Change status to be approved --}}
                    @if ($notification->user_id != auth()->user()->id && $purchaseorder->approved == 0 && !$notification->comment && $notification->type == 'a')
                        <form method="POST" action="{{ route('purchaseorder_approved', $purchaseorder->id) }}?n_id={{ $notification->id }}">
                            @csrf
                            @method('put')
                            <div class="fieldset-footer pb-1 px-0">
                                <div class="row">
                                    <div class="col-md-12 mb-3 row justify-content-end m-0">
                                        {{-- Show approve button if active user not user make this notification && this purchase order is not approved --}}
                                        <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-success ">@lang('site.approve') <i class="fas fa-check"></i></button>                                </div>
                                </div>
                            </div>
                        </form>
                    @endif

                    {{-- Reply with comment --}}
                    {{-- 
                        * Show if active user is not that notification owner 
                        * and this record is not approved 
                        * notification type is a => for action not n => for normal
                    --}}
                    @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $purchaseorder->approved == 0)
                        <form action="{{ route('notification.reply') }}" method="post">
                            @csrf
                            <input type="hidden" name="n_id" value="{{ $notification->id }}">
                            <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                            <div class="row justify-content-end py-3">
                                <button type="submit" class="btn btn-danger mt-2 mr-2 d-block">@lang('site.send') @lang('site.comment') <i class="fas fa-times"></i></button>
                            </div>
                        </form>
                    @endif
        
                    {{-- Show edit for user that have this notification --}}
                    {{-- 
                        * Show if active user is notification owner 
                        * and this record is not approved 
                        * notification have comment or notification type is a => for action not n => for normal
                    --}}
                    @if ($notification->user_id == auth()->user()->id && $purchaseorder->approved == 0 && ($notification->type == 'a' || $notification->comment))
                        <div class="row justify-content-end">
                            <a class="btn btn-sm btn-warning mr-2" href="{{ route('purchaseorders.edit',$purchaseorder->id) }}">@lang('site.go_for_edit')<i class="ml-2 fas fa-edit"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit items --}}
    <div class="modal fade" id="edit-item" data-check-data="null" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLongTitle">@lang('site.edit_item')</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- end of model header --}}

                <div class="modal-body add-invoice-items">
                    <form id="editItemsForm" action="{{ route('item.update', ['id' => 1]) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="row" id="item-search-container" data-purchase-order-id="{{ $purchaseorder->id }}">
                            <div class="col-12">
                                <div class="search-items">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <label> @lang('site.internal_code') </label>
                                                <input type="text" class="form-control " name="internal_code"
                                                    id="internal_code">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="search-product spinner-border text-success" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <p class="vaild-product-register-tax"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            {{-- product_code --}}
                            <div class="col-6">
                                <label>@lang('site.product_code')</label>
                                <input type="text" class="form-control" name="product_code" id="product_code" readonly>
                            </div>
                            {{-- product_name --}}
                            <div class="col-6">
                                <label>@lang('site.product_or_service')</label>
                                <input type="text" class="form-control" name="product_name" id="product_name" readonly>
                            </div>
                            {{-- description --}}
                            <div class="col-12">
                                <label>@lang('site.description')</label>
                                <input type="text" required class="form-control" name="description" id="description">
                            </div>
                        </div>
                        {{-- end of row --}}
                        <hr>

                        <div class="row currenty-type my-3">
                            {{-- currency --}}
                            <div class="col-6  input-group select">
                                <label>@lang('site.currency')</label>
                                <select class="currenty-type-select" name="currency" id="currency">
                                    <option disabled selected>@lang('site.capital_select') @lang('site.currency')</option>
                                    <option value="EGP">EGP</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">Euro</option>
                                    <option value="SAR">SAR</option>
                                    <option value="RUB">RUB</option>
                                    <option value="JPY">JPY</option>
                                    <option value="GBP">GBP</option>
                                    <option value="CHF">CHF</option>
                                    <option value="CAD">CAD</option>
                                    <option value="AUD/NZD">AUD/NZD</option>
                                    <option value="ZAR">ZAR</option>
                                </select>
                            </div>
                            {{-- unit --}}
                            <div class="col-6 input-group select">
                                <label>@lang('site.unit')</label>
                                <select name="unit[]" class="unit item_line_select" id="unit" data-toggle="tooltip"
                                    data-placement="top" title="Unit">
                                    <option value="" disabled selected>@lang('site.capital_select') @lang('site.unit')
                                    </option>
                                    @foreach ($productUnits as $unit)
                                        <option value="{{ $unit->code }}">{{ $unit->name_ar }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row price mb-2">
                            {{-- quantity --}}
                            <div class="col-6">
                                <label>@lang('site.quantity')</label>
                                <div class="input-group select">
                                    <input type="number" name="quantity" id="item-quantity"
                                        placeholder="@lang('site.quantity')" class="quantity input-group-item" />
                                </div>
                                <p class="text-center text-bold d-none quantity_edit_error"
                                    style="font-size: 11px; color: red">
                                    @lang('site.quantity_edit_error')</p>
                            </div>

                            {{-- unit price --}}
                            <div class="col-6">
                                <label>@lang('site.unit_price')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="item_price" class="item_price input-group-item"
                                        placeholder="@lang('site.unit_price')">
                                </div>
                                <p class="text-center text-bold d-none item_price_edit_error"
                                    style="font-size: 11px; color: red">
                                    @lang('site.item_price_edit_error')</p>
                            </div>
                        </div>
                        {{-- end of row --}}
                        <hr>
                        <div class="row mb-2">
                            {{-- total_amount --}}
                            <div class="col-5">
                                <label>@lang('site.total_amount')</label>
                                <input type="number" name="sales_amount" placeholder="@lang('site.total_amount')"
                                    class="sales_amount" readonly />
                            </div>
                            {{-- discount_rate --}}
                            <div class="col-md-3">
                                <label>@lang('site.discount_rate')</label>
                                <div class="input-group md">
                                    <div class="input-group-append ">
                                        <span> % </span>
                                    </div>
                                    <input type="number" name="discount_items_rate"
                                        class="discount_items_rate input-group-item"
                                        placeholder="@lang('site.discount_rate')">
                                </div>
                            </div>
                            {{-- discount_amount --}}
                            <div class="col-md-4">
                                <label>@lang('site.discount_amount')</label>
                                <div class="input-group discount-amount">
                                    <div class="input-group-append">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="discount_items_number"
                                        class="discount_items_number input-group-item"
                                        placeholder="@lang('site.discount_amount')">
                                </div>
                            </div>

                        </div>
                        {{-- end of row --}}
                        <hr>
                        <div class="row">

                            <div id="items_table" class="tax-items-table">
                                <button type="button" id="add_tax_row"
                                    class="btn btn-dark pull-left rounded-pill add_new_row_tax" data-toggle="tooltip"
                                    data-placement="top" title="Add Row">
                                    <i class="fa fa-plus"></i> @lang('site.add_new_tax')
                                </button>
                                <table class="tax-table">
                                    <thead>
                                        <th>@lang('site.type')</th>
                                        <th>@lang('site.sub_type')</th>
                                        <th>@lang('site.rate')</th>
                                        <th>@lang('site.amount_egp')</th>
                                    </thead>
                                </table>
                                @foreach (old('items', ['']) as $index => $oldProduct)
                                    <div class="tax-items d-none" data-tax-id=''>
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="row mr-1 mb-1 tax-row-container">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="tax_type[]" class="item_line_select tax-type"
                                                                value="{{ old('department') }}" data-toggle="tooltip"
                                                                data-placement="top" title="Sub Group">
                                                                <option selected disabled>@lang('site.choose')...</option>
                                                                <option value="1">T1</option>
                                                                <option value="2">T2</option>
                                                                <option value="3">T3</option>
                                                                <option value="4">T4</option>
                                                                <option value="5">T5</option>
                                                                <option value="6">T6</option>
                                                                <option value="7">T7</option>
                                                                <option value="8">T8</option>
                                                                <option value="9">T9</option>
                                                                <option value="10">T10</option>
                                                                <option value="11">T11</option>
                                                                <option value="12">T12</option>
                                                                <option value="13">T13</option>
                                                                <option value="14">T14</option>
                                                                <option value="15">T15</option>
                                                                <option value="16">T16</option>
                                                                <option value="17">T17</option>
                                                                <option value="18">T18</option>
                                                                <option value="19">T19</option>
                                                                <option value="20">T20</option>
                                                            </select>

                                                        </div>
                                                        <p class="typeName">Type Name</p>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="subtype[]" class="item_line_select subtype"
                                                                itle="Item">

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="tax_rate[]" placeholder="Rate"
                                                                class="tax_rate input-tax-item" />
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="row_total_tax[]"
                                                                placeholder="Total Amount EGY"
                                                                class=" row_total_tax input-tax-item" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="row-form ">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove_new_row_tax delete_tax_row"
                                                        data-tax-id='0' data-toggle="tooltip" data-placement="top"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="total_budget" id="total_budget">

                        </div>
                        {{-- end of row --}}
                        <div class="row">
                            {{-- total_taxable_fee --}}
                            <div class="col-md-6">
                                <label>@lang('site.total_taxable_fee')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="taxable_fees" class="form-control taxable_fees" readonly>
                                </div>
                            </div>
                            {{-- value_diffrence --}}
                            <div class="col-md-6">
                                <label>@lang('site.value_diffrence')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="differ_value" class="form-control differ_value">
                                </div>
                            </div>
                            {{-- value_diffrence_item_discount --}}
                            <div class="col-md-6">
                                <label>@lang('site.value_diffrence_item_discount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="items_discount" class="form-control itemsDiscount">
                                </div>
                            </div>
                            {{-- net_total --}}
                            <div class="col-md-6">
                                <label>@lang('site.net_total')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="net_total" class="net_total form-control" readonly>
                                </div>
                            </div>
                            {{-- total_amount --}}
                            <div class="col-md-6">
                                <label>@lang('site.total_amount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="total_amount" class=" total_amount form-control" readonly>
                                </div>
                            </div>
                            <div class="input-group mt-4">
                                <button type="submit" class="btn btn-success save-form-PoItem">
                                    <i class="fa fa-save"></i>
                                    @lang('site.save')
                                </button>
                            </div>
                        </div>
                        {{-- end of row --}}
                    </form>

                </div>
                {{-- end of model body --}}
            </div>
            {{-- end of model content --}}

        </div>
    </div>

    {{-- delete item --}}
    <div class="modal fade text-center" id="item_delete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('site.delete_item')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('site.confirm_item')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('item.destroy') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="item_id" id="item_id" value="">
                        <input type="hidden" name="purchaseOrder_id" value="{{ $purchaseorder->id }}">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete')
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let cannot_add_more_item = "@lang('site.cannot_add_more_item')";
        let purchaseOrderType = "{{ $purchaseorder->type }}";

        $('.search-product.spinner-border').hide();

        // delete item
        $('#item_delete').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var itemId = button.data('item-id');
            $('.modal #item_id').val(itemId);
        })

    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/16_9_v1_taxitems/poEditTaxItems.js') }}"></script>

@endsection
