@extends('pages.layouts.master')

@section('title')
    @lang('site.documents')
@endsection


@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('site.edit_document') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.edit_document') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="main">

        <div class="form-container">

            <h2 class="mb-2">@lang('site.new_document')</h2>

            <form method="POST" id="documentForm" class="documentForm">

                {{-- From TO --}}
                <h3>
                    <span class="title_text">@lang('site.from_to')</span>
                </h3>
                <fieldset>
                    <div class="fieldset-content">
                        <div class="row  mb-3 ml-2">
                            <div class="col-md-6 mb-3">
                                {{-- <div class="form-group mb-3"> --}}
                                <label for="type" class="form-label">@lang('site.date')</label>
                                {{-- <label for="date" class="form-label mr-3">@lang('site.date')</label> --}}
                                <input type="date" name="date" id="date" class="d-block" placeholder="@lang('site.date')"
                                value="{{ $document->date }}" />
                                {{-- </div> --}}
                            </div>
                            
                            <div class="col-md-6 mb-3">
                            @if (!auth()->user()->hasPermission('document_request'))
                                {{-- <div class="form-group mb-3"> --}}
                                <label for="type" class="form-label">@lang('site.document_number')</label>
                                {{-- <label for="date" class="form-label mr-3">@lang('site.date')</label> --}}
                                <input type="text" required name="document_number" id="document_number" class="d-block"
                                    placeholder="@lang('site.document_number')" value="{{ $document->document_number }}"/>
                                <p class="text-center text-bold d-none document_number_used_before_error"
                                    style="font-size: 11px; color: red">
                                    @lang('site.document_number_used_before')</p>
                                {{-- </div> --}}
                                @endif
                            </div>

                           
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">@lang('site.document_type')</label>
                                <select name="type" class="custom-select m-0" required disabled>
                                    <option selected disabled>@lang('site.choose') @lang('site.document_type')</option>
                                    <option value="I" {{ $document->type == 'I'? 'selected': '' }}>Invoice (I)</option>
                                    <option value="C" {{ $document->type == 'C'? 'selected': '' }}>Credit Note (C)</option>
                                    <option value="D" {{ $document->type == ''? 'selected': '' }}>Debit Note(D)</option>
                                </select>
                                

                            </div>
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <div class="col-md-6 mb-3">
                                <label for="version" class="form-label">@lang('site.document_version')</label>
                                <select name="version" class="custom-select m-0" required disabled>
                                    <option disabled selected>@lang('site.choose') @lang('site.document_version')</option>
                                    <option value="0.9" {{ $document->version == '0.9'? 'selected': '' }}>V 0.9</option>
                                    <option value="1.0" {{ $document->version == '1.0'? 'selected': '' }}>V 1.0</option>
                                </select>
                            </div>
                        </div>

                        <div class="row ml-2">
                            {{-- From company --}}
                            <div class="col-6">
                                <div class="card">
                                    <h6 class="card-header bg-success">
                                        @lang('site.issuer_from')
                                    </h6>
                                    <div class="card-body">
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group ">
                                                <label for="registration_number" class="form-label"
                                                    id="reg_label">@lang('site.registration_number')</label>
                                                <select id="from_registration_number" class="form-control require"
                                                    name0="company_id" disabled>
                                                    <option value="{{ $company->id }}">{{ $company->company_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <p class="vaild-company-register-tax"></p>
                                        </div>
                                        <div class="row mb-2 mt-3">
                                            {{-- registration_number --}}
                                            <div class="col-md-4">
                                                <label for="registration_number" class="form-label"
                                                    id="show_reg_label">@lang('site.registration_number')</label>
                                                <input type="text" value="{{ $company->commercial_registeration_number }}"
                                                    name0="from_register_tax" id="from_register_tax"
                                                    placeholder="@lang('site.registration_number')" readonly>
                                            </div>
                                            {{-- company_name --}}
                                            <div class="col-md-8">
                                                <label for="from_company_name" class="form-label"
                                                    id="company_label">@lang('site.company_name')</label>
                                                <input type="text" class="form control"
                                                    value="{{ $company->company_name }}" name0="from_company_name"
                                                    id="from_company_name" placeholder="@lang('site.company_name')"
                                                    readonly />
                                            </div>

                                        </div>
                                        <input type="hidden" name0="company_code" value="{{ $company->company_code }}"
                                            id="company_code">
                                        <div class="row">
                                            {{-- address --}}
                                            <div class="col-md-10">
                                                <label for="from_address_company" class="form-label"
                                                    id="company_address_label">@lang('site.company_address')</label>
                                                <input type="text" class="form control"
                                                    value="{{ $company->company_address }}" name0="from_address_company"
                                                    id="from_address_company" placeholder="@lang('site.company_address')"
                                                    readonly />
                                            </div>
                                            <div class="col-md-2" id="company-id-container">
                                                <label class="form-label">@lang('site.id')</label>
                                                <input type="text" name0="company_id" id="company_id"
                                                    value="{{ $document->company->id }}" readonly>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            {{-- To company --}}
                            <div class="col-6">
                                <div class="card">
                                    <h6 class="card-header bg-success">
                                        @lang('site.receiver_to')
                                    </h6>
                                    <div class="card-body">
                                        {{-- select-client --}}
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group ">
                                                <label for="registration_number" class="form-label"
                                                    id="reg_label">@lang('site.registration_number')</label>
                                            </div>
                                            <p class="vaild-client-register-tax"></p>
                                            <div class="input-group mb-3">

                                                <select id='client_type' name0="client_type" class="form-control require"
                                                    disabled>
                                                    <option value="b" data-label="@lang('site.tax_id_number_only')"
                                                        data-validate="@lang('site.validate_Tax_id_number')"
                                                        {{ $purchaseOrder->client_type == 'b' ? 'selected' : '' }}>
                                                        @lang('site.the_businessClient')</option>
                                                    <option value="p" data-label="@lang('site.national_id')"
                                                        data-validate="@lang('site.validate_national_id')"
                                                        {{ $purchaseOrder->client_type == 'p' ? 'selected' : '' }}>
                                                        @lang('site.person_client')</option>
                                                    <option value="f"
                                                        {{ $purchaseOrder->client_type == 'f' ? 'selected' : '' }}>
                                                        @lang('site.foreigner_client')</option>
                                                </select>
                                            </div>
                                            @error('client_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror


                                        </div>

                                        <div class="col-md-12">
                                            {{-- foreigner-client --}}
                                            <div
                                                class="select-foreigner-client {{ $purchaseOrder->client_type != 'f' ? 'd-none' : '' }}">
                                                <div class="row mb-3">
                                                    <div class="col-md-10 input-group mb-3">
                                                        <label class="form-label d-block w-100">@lang('site.client')</label>
                                                        <select id='foreigner-client' class="form-control require" disabled>
                                                            @foreach ($foreignerClients as $foreignerClient)
                                                                <option value="{{ $foreignerClient->id }}"
                                                                    {{ $foreignerClient->id == $client->id ? 'selected' : '' }}>
                                                                    {{ $foreignerClient->company_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($purchaseOrder->client_type == 'f')
                                                        <div class="col-md-2" id="client-id-container">
                                                            <label for="address" class="form-label">ID</label>
                                                            <input type="text" name0="client_id" id="client_id" readonly="">
                                                        </div>
                                                    @endif
                                                    <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                                </div>
                                            </div>
                                            @error('foreigner-client')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            {{-- business--client --}}
                                            <div
                                                class="card-body p-0 client-details {{ $purchaseOrder->client_type == 'f' ? 'd-none' : '' }}">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 no-gutters">
                                                        <div class="row mb-1 no-gutters">
                                                            <div class="col-md-11">
                                                                <label for="tax_id_number_or_national_id"
                                                                    class="form-label">
                                                                    @if ($purchaseOrder->client_type == 'b')
                                                                    @lang('site.tax_id_number_only') @else
                                                                        @lang('site.national_id') @endif
                                                                </label>
                                                                <input type="text" class="form control" readonly
                                                                id="tax_id_number_or_national_id" value="@if ($purchaseOrder->client_type == 'b') {{ $client->tax_id_number }} @else
                                                                {{ $client->national_id }} @endif">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-8 document-client">
                                                        <label for="name" class="form-label"
                                                            id="min_payment_label">@lang('site.name') </label>
                                                        <p style="line-height: 1.4rem" class="display" id="client_name">
                                                            {{ $client->name }}</p>
                                                    </div>


                                                    <div class="col-md-10 document-client">
                                                        <label for="address" class="form-label"
                                                            id="payment_label">@lang('site.address')</label>
                                                        <p style="line-height: 1.4rem" class="display" id="client_address">
                                                            {{ $client->name }}</p>
                                                    </div>


                                                    @if ($purchaseOrder->client_type != 'f')
                                                        <div class="col-md-2" id="client-id-container">
                                                            <label for="address" class="form-label">ID</label>
                                                            <input type="text" name0="client_id" id="client_id" readonly>
                                                        </div>
                                                    @endif

                                                    <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fieldset-footer">
                        <span>@lang('site.step_1_4')</span>
                    </div>
                </fieldset>


                {{-- Items --}}
                <h3>
                    <span class="title_text">@lang('site.add_products')</span>
                </h3>
                <fieldset>
                    <div class="row">
                        <div class="col-12 select_document_po">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group ">
                                        <label for="registration_number" class="form-label" id="reg_label">
                                            @lang('site.select') @lang('site.purchaseorder')
                                        </label>
                                        <select id="select_purchase_order" class="form-control require" disabled>
                                            <option selected value="{{ $purchaseOrder->purchase_order_reference }}">
                                                {{ $purchaseOrder->purchase_order_reference }}
                                            </option>
                                        </select>
                                    </div>
                                    <p class="vaild-company-register-tax"></p>
                                </div>

                            </div>
                            <hr>
                        </div>

                        <div class="col-6">
                            <h5> @lang('site.items') </h5>
                        </div>
                        <div class="col-3 text-right items-links">
                            <a href="#" data-toggle="modal" data-target="#addline" class="addNewItem"><i
                                    class="fa fa-plus"></i>@lang('site.add_item')</a>
                        </div>
                        <div class="col-3 text-right items-links">
                            <a href="#" data-toggle="modal" data-target="#addline1" class="d-none0"><i
                                    class="fa fa-plus"></i>@lang('site.add_items_via_excel')</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center m-0 tableForItems">
                            <thead>
                                <tr>
                                    <th scope="col" width="20px">
                                        #
                                    </th>
                                    <th scope="col">
                                        @lang('site.product_code')
                                    </th>
                                    <th scope="col">
                                        @lang('site.product_name')
                                    </th>
                                    <th scope="col">
                                        @lang('site.quantity')
                                    </th>
                                    <th scope="col">
                                        @lang('site.unit')
                                    </th>
                                    <th scope="col">
                                        @lang('site.price')
                                    </th>
                                    <th scope="col">
                                        @lang('site.sales_amount')
                                    </th>
                                    <th scope="col">
                                        @lang('site.discount')
                                    </th>
                                    <th scope="col">
                                        @lang('site.discount_amount')
                                    </th>
                                    <th scope="col">
                                        @lang('site.taxable_fees')
                                    </th>
                                    <th scope="col">
                                        @lang('site.value_diff')
                                    </th>
                                    <th scope="col">
                                        @lang('site.item_discount')
                                    </th>
                                    <th scope="col">
                                        @lang('site.net_total')
                                    </th>
                                    <th scope="col">
                                        @lang('site.total_amount')
                                    </th>
                                    <th width="100px" scope="col">
                                        @lang('site.actions')
                                    </th>
                                </tr>
                            </thead>
                            @php $totalInvoice = 0 @endphp
                            <tbody>
                                @foreach ($items as $item)
                                    <tr data-document-item-id={{ $item['item_id'] }}>
                                        <th scope="col" width="20px">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td scope="col">
                                            {{ $item['product_code'] }}
                                        </td>
                                        <td scope="col">
                                            {{ $item['product_name'] }}
                                        </td>
                                        <td scope="col">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td scope="col">
                                            {{ $item['unit'] }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['item_price'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['quantity'] * $item['item_price'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ $item['discount_items_rate'] }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['discount_items_number'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['taxable_fees'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['differ_value'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['items_discount'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['net_total'], 2) }}
                                        </td>
                                        <td scope="col">
                                            {{ number_format($item['total_amount'], 2) }}
                                            @php
                                                $totalInvoice += $item['total_amount'];
                                            @endphp
                                        </td>
                                        <td width="100px" scope="col">
                                            <button type="button" class="btn btn-danger tableItemsBtn deleteItem"
                                                data-item-id="{{ $loop->index }}"><i
                                                    class="fa fa-trash-alt"></i></button>
                                            <button type="button" data-toggle="modal" data-target="#addline"
                                                class="btn btn-warning tableItemsBtn editItem"
                                                data-item-id="{{ $loop->index }}"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-4">
                            <div class="summery mt-2">
                                <label for="">@lang('site.discount_invoice')</label>
                                <input type="number" value="{{ $document->extra_invoice_discount }}"
                                    name="extra_invoice_discount" id="invoice-discount">
                                <p class="text-danger text-bold d-none validate">
                                    @lang('site.discount_invoice_overflow_error')</p>
                            </div>
                        </div>
                        <div class="offset-4 col-4">
                            <div class="summery mt-2">
                                <label for="">@lang('site.total_invoice')</label>
                                <input type="text" disabled id="invoice-total"
                                    value="{{ number_format($totalInvoice - $document->extra_invoice_discount, 2) }}">
                            </div>
                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_2_4')</span>
                    </div>
                </fieldset>


                <input type="hidden" name0="version" value="{{ $document->version }}">
                <input type="hidden" name0="type" value="{{ $document->type }}">
            </form>
        </div>

    </section>
    {{-- End Of Main Section --}}

    {{-- modal add items --}}

    <div class="modal fade" id="addline" data-check-data="null" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-item-id=''>

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLongTitle" data-add-item="@lang('site.add_new_item')"
                        data-edit-item="@lang('site.edit_item')" data-add-status='1'></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- end of model header --}}

                <div class="modal-body add-invoice-items">

                    <form id="addItemsForm">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>@lang('site.choose_item')</label>
                                <select id='item_select' name="item_id" class="" required="">
                                    <option value="">@lang('site.item')</option>
                                </select>
                            </div>

                        </div>
                        {{-- end of row --}}
                        <div class="row mb-2">
                            <div class="col-6">
                                <label>@lang('site.product_code')</label>
                                <input type="text" class="form-control" name0="product_code" id="product_code" readonly>
                            </div>
                            <div class="col-6">
                                <label>@lang('site.product_or_service')</label>
                                <input type="text" class="form-control" name0="product_name" id="product_name" readonly>
                            </div>
                        </div>
                        {{-- end of row --}}
                        <div class="row">
                            <div class="col-12">
                                <label>@lang('site.description')</label>
                                <input type="text" class="form-control" name="description" id="description" readonly>
                            </div>
                        </div>
                        <hr>
                        {{-- Currency --}}
                        <div class="row currenty">
                            <div class="col-6">
                                <label>@lang('site.currency')</label>
                                <input class="currency" placeholder="@lang('site.currency')" readonly>
                            </div>
                            <div class="col-6">
                                <label>@lang('site.rate')</label>
                                <input class="rate" type="number" name="curreny_rate" placeholder="@lang('site.rate')"
                                    readonly />
                            </div>
                        </div>

                        <div class="row price">
                            <div class="col-6">
                                <div class="row no-gutters">
                                    <div class="col-md-6">
                                        <label>@lang('site.quantity')</label>
                                        <div class="input-group select">
                                            <input type="number" id="quantity" name="quantity"
                                                placeholder="@lang('site.quantity')"
                                                onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                class="quantity input-group-item" readonly />
                                            <p class="text-danger text-bold d-none validate"
                                                id="validate-quantity-overflow">
                                                @lang('site.quantity_invoice_overflow_error')</p>
                                            <p class="text-danger text-bold d-none validate"
                                                id="validate-quantity-overflow-credit">
                                                @lang('site.quantity_credit_overflow_error')</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.unit')</label>
                                        <input placeholder="@lang('site.unit')" id="item_unit" class="input-group-item "
                                            readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <label>@lang('site.unit_price_eg')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="item_price" id="item_price"
                                        class="item_price input-group-item"
                                        onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                        placeholder="@lang('site.unit_price_eg')" readonly>
                                    <p class="text-danger text-bold d-none validate" id="validate-price-overflow">
                                        @lang('site.price_invoice_overflow_error')</p>
                                    <p class="text-danger text-bold d-none validate" id="validate-price-overflow-credit">
                                        @lang('site.price_invoice_overflow_error')</p>
                                </div>
                            </div>
                        </div>
                        {{-- end of row --}}
                        <hr>
                        <div class="row">

                            <div class="col-5">
                                <label>@lang('site.total_amount')</label>
                                <input type="number" name="sales_amount" id="sales_amount"
                                    placeholder="@lang('site.total_amount')" class="sales_amount" readonly />
                            </div>

                            <div class="col-md-3">
                                <label>@lang('site.discount_rate')</label>
                                <div class="input-group md">
                                    <div class="input-group-append ">
                                        <span> % </span>
                                    </div>
                                    <input type="number" name="discount_items_rate"
                                        class="discount_items_rate input-group-item"
                                        placeholder="@lang('site.discount_rate')" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>@lang('site.discount_amount')</label>
                                <div class="input-group discount-amount">
                                    <div class="input-group-append">
                                        <span>@lang('site.egp')</span>
                                    </div>
                                    <input type="number" name="discount_items_number"
                                        class="discount_items_number input-group-item"
                                        placeholder="@lang('site.discount_amount')" readonly>
                                </div>
                            </div>

                        </div>
                        {{-- end of row --}}
                        <hr>
                        <div class="row">
                            <div id="items_table" class="tax-items-table">
                                {{-- <button type="button" id="add_tax_row"
                                        class="btn btn-dark pull-left rounded-pill add_new_row_tax" data-toggle="tooltip"
                                        data-placement="top" title="Add Row">
                                        <i class="fa fa-plus"></i> @lang('site.add_new_tax')
                                    </button> --}}
                                <table class="tax-table">
                                    <thead>
                                        <th>@lang('site.type')</th>
                                        <th>@lang('site.sub_type')</th>
                                        <th>@lang('site.rate')</th>
                                        <th>@lang('site.amount_egp')</th>
                                    </thead>
                                </table>
                                @foreach (old('items', ['']) as $index => $oldProduct)
                                    <div class="tax-items d-none">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row mr-1 mb-1 tax-row-container">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="tax_type[]" class="item_line_select tax-type"
                                                                value="{{ old('department') }}" data-toggle="tooltip"
                                                                data-placement="top" title="Sub Group" disabled>
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
                                                        <p class="typeName">@lang('site.type_name')</p>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="subtype" class="item_line_select subtype"
                                                                title="Item" disabled>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="tax_rate[]"
                                                                placeholder="@lang('site.rate')"
                                                                class="tax_rate input-tax-item" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="row_total_tax[]"
                                                                placeholder="@lang('site.amount_egp')"
                                                                class=" row_total_tax input-tax-item" readonly />
                                                        </div>
                                                    </div>
                                                    <div> <input type="hidden" name="items[]" value="any"></div>
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
                            {{-- <div class="row mb-2"> --}}
                            <div class="col-md-6 mb-2">
                                <label>@lang('site.total_taxable_fee')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="taxable_fees" id="taxable_fees"
                                        class="form-control taxable_fees" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>@lang('site.value_diffrence')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="differ_value" id="differ_value"
                                        class="form-control differ_value" readonly>
                                </div>
                            </div>

                            {{-- </div> --}}
                            {{-- <div class="row mb-2"> --}}
                            <div class="col-md-6 mb-2">
                                <label>@lang('site.value_diffrence_item_discount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="items_discount" id="items_discount"
                                        class="form-control itemsDiscount" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>@lang('site.net_total')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="net_total" id="net_total" class="net_total form-control"
                                        readonly>
                                </div>
                            </div>
                            {{-- </div> --}}
                            {{-- <div class="row"> --}}
                            <div class="col-md-6">
                                <label>@lang('site.total_amount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="total_amount" id="total_amount"
                                        class=" total_amount form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success save-form-item">
                                    <i class="fa fa-save"></i>
                                    @lang('site.save')
                                </button>
                            </div>
                            {{-- </div> --}}
                        </div>
                        {{-- end of row --}}
                    </form>

                </div>
                {{-- end of model body --}}
            </div>
            {{-- end of model content --}}

        </div>

    </div>

    <div class="modal fade" id="addline1" data-check-data="null" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-item-id=''>

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="exampleModalLongTitle" data-add-item="@lang('site.add_new_item')"
                                    data-edit-item="@lang('site.edit_item')" data-add-status='1'></h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    {{-- end of model header --}}

                        <div class="modal-body add-invoice-items">

                            <form action="{{ route('importExcelForCreateSpecial') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <!-- <label for="recipient-name" class="">download file</label> -->

                                        <a class="btn btn-secondary form-control btn-sm" download="" href="{{ asset('uploads/document_items.xls') }}"><i class="fa fa-download"> </i> Download file </a>
                                    </div>

                                    <div class="col-12">
                                        <label for="message-text" class="">upload file</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success btn-sm btn-flat">submit</button>
                                    <button type="button" class="btn btn-secondary btn-sm btn-flat" data-dismiss="modal">Close</button>
                                </div>

                            </form>

                        </div>
                    {{-- end of model body --}}
                </div>
                {{-- end of model content --}}

        </div>

    </div>
@endsection


@section('scripts')
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>

    {{-- <script src="{{ asset('invoice/js/main.js') }}"></script> --}}
    <script src="{{ asset('invoice/14_9_V2_taxItems/taxItems.js') }}"></script>

    <script>
        $('#client_id').val('{{ $client->id }}');


        $(".actions a[href$='#next']").text("{{ trans('site.next') }}");
        $(".actions a[href$='#previous']").text("{{ trans('site.prev') }}");
        $(".actions a[href$='#finish']").text("{{ trans('site.save') }}");

        language['selectItemPlaceholder'] = "@lang('site.choose_item')";
        language['save'] = "@lang('site.save')";
        language['send_data'] = "@lang('site.send_data')";
        language['data_sent'] = "@lang('site.data_sent')";
        language['send_data_error'] = "@lang('site.send_data_error')";

        let _items = {!! json_encode($items) !!};
        setItemForEdit(_items);

        documentId = {!! json_encode($document->id) !!};
        editDocumentMode = true;
        documentType = {!! json_encode($document->type) !!};

    </script>
@endsection
