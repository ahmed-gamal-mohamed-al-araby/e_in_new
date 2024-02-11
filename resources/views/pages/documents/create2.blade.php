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
                    <h1> @lang('site.documents')
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.add_document') </li>
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

                    <div class="fieldset-content pr-0">
                        <div class="row mb-3 ml-2">
                            <div class="col-md-6 mb-3">
                                {{-- <div class="form-group mb-3"> --}}
                                <label for="type" class="form-label">@lang('site.date')</label>
                                {{-- <label for="date" class="form-label mr-3">@lang('site.date')</label> --}}
                                <input type="date" name="date" id="date" class="d-block" placeholder="@lang('site.date')"
                                    onfocus="(this.type='date')" data-date-format="DD/MM/YYYY" />
                                {{-- </div> --}}
                            </div>
                          
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">@lang('site.document_type')</label>
                                <select name="type" class="custom-select m-0" required>
                                    <option selected value="I">RQ Invoice (I)</option>
                                </select>
                            </div>
                            
                        </div>

                        <input type="hidden" value="1" name="approved">
                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

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
                                                    id="reg_label">@lang('site.company_name')</label>
                                                <select id="from_registration_number" disabled class="form-control require"
                                                    name="company_id">
                                                    <option selected disabled hidden>@lang('site.choose')</option>
                                                        <option selected value="{{ $company->id }}">{{ $company->company_name }}
                                                        </option>
                                                </select>
                                                <div class="search-company spinner-border text-success" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <p class="vaild-company-register-tax"></p>
                                        </div>
                                        <div class="row mb-2 mt-3">
                                            <div class="col-md-4">
                                                <label class="form-label w-100"
                                                    id="show_reg_label">@lang('site.tax_id_number_only')</label>
                                                <input type="text" value="{{ $company->tax_id_number }}" name0="from_register_tax" id="from_register_tax"
                                                    placeholder="@lang('site.registration_number')" readonly>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="from_company_name" class="form-label"
                                                    id="company_label">@lang('site.company_name')</label>
                                                <input type="text" class="form control" value="{{ $company->company_name }}" name0="from_company_name"
                                                    id="from_company_name" placeholder="@lang('site.company_name')"
                                                    readonly />
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <label for="from_address_company" class="form-label"
                                                    id="company_address_label">@lang('site.company_address')</label>
                                                <input type="text" class="form control" value="{{ $company->address->country->name . ' ,' . $company->address->city->name . ', ' . $company->address->region_city . ', ' . $company->address->street . ', ' . $company->address->building_no }}"
                                                    name0="from_address_company" id="from_address_company"
                                                    placeholder="@lang('site.company_address')" readonly />
                                            </div>
                                            <div class="col-md-2" id="company-id-container">
                                                <label class="form-label">@lang('site.id')</label>
                                                <input type="text" id="company_id" value="{{ $company->id }}" readonly>
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
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group ">
                                                <label class="form-label"
                                                    id="reg_label">@lang('site.client')</label>
                                                <p class="vaild-client-register-tax"></p>
                                                <select id='client_type' name="client_type" class="form-control require">
                                                    <option selected disabled>@lang('site.select') @lang('site.client_type')
                                                    </option>
                                                    <option value="b" data-label="@lang('site.tax_id_number_only')"
                                                        data-validate="@lang('site.validate_Tax_id_number')">
                                                        @lang('site.the_businessClient')</option>
                                                    <!-- <option value="p" data-label="@lang('site.national_id')"
                                                        data-validate="@lang('site.validate_national_id')">
                                                        @lang('site.person_client')</option> -->
                                                    <option value="f">@lang('site.foreigner_client')</option>
                                                </select>
                                            @error('client_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="select-foreigner-client d-none">
                                                <div class="row mb-3">
                                                    <div class="col-md-10 input-group mb-3">
                                                        <label class="form-label d-block w-100">@lang('site.client')</label>
                                                        <select id='foreigner-client' class="form-control require" disabled>
                                                            <option selected hidden disabled>@lang('site.select')
                                                                @lang('site.client_type')
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                                </div>
                                            </div>
                                            @error('foreigner-client')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            <div class="card-body p-0 client-details d-none">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 no-gutters">

                                                        <div class="row mb-1 no-gutters">
                                                            <div class="col-md-11">
                                                                <label for="tax_id_number_or_national_id"
                                                                    class="form-label"></label>
                                                               
                                                                    <select name="" id="tax_id_number_or_national_id">
                                                                    <OPtion value=""></OPtion>

                                                                    @foreach($businessClients as $businessClient) 
                                                                    <OPtion value="{{$businessClient->tax_id_number}}">{{$businessClient->name}}({{$businessClient->tax_id_number}})</OPtion>
                                                                    @endforeach
                                                                    </select>
                                                            </div>

                                                            <div class="col-md-1 bank-spinner"
                                                                style="padding:32px 0 0 10px">
                                                                <div class="search-bank spinner-border spinner-border-sm text-success"
                                                                    role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-8 document-client">
                                                        <label for="name" class="form-label w-100"
                                                            id="min_payment_label">@lang('site.name') </label>
                                                        <p style="line-height: 1.4rem" class="display" id="client_name"></p>
                                                    </div>


                                                    <div class="col-md-10 document-client">
                                                        <label for="address" class="form-label"
                                                            id="payment_label">@lang('site.address')</label>
                                                        <p style="line-height: 1.4rem" class="display" id="client_address"></p>
                                                    </div>


                                                    <div class="col-md-2" id="client-id-container">
                                                        <label for="address" class="form-label">@lang('site.id')</label>
                                                        <input type="text" name="client_id" id="client_id" readonly>
                                                    </div>

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
                                <div class="col-4">
                                    <label for="registration_number" class="form-label" id="reg_label">
                                        @lang('site.select') @lang('site.purchaseorder')
                                    </label>
                                    {{--  <div class="form-group ">  --}}
                                        <select id="select_purchase_order" name="purchase_order_id"
                                            class="form-control require">
                                            <option disabled selected>@lang('site.choose')</option>
                                        </select>
                                        <div class="search-company spinner-border text-success" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    {{--  </div>  --}}
                                    <p class="vaild-company-register-tax"></p>
                                </div>
                                <div class="col-4">
                                    <label for="name" class="form-label w-100" id="min_payment_label">@lang('site.items_counter') </label>
                                    <input type="text" id="items" name="items_counter" readonly class="mb-2">
                                </div>

                            </div>
                            <hr>
                        </div>

                        <div class="col-6">
                            <h5> @lang('site.items') </h5>
                        </div>
                        <div class="col-6 text-right items-links">
                            <a href="#" data-toggle="modal" data-target="#addline" class="addNewItem d-none"><i
                                    class="fa fa-plus"></i>@lang('site.add_item')</a>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover text-center m-0 tableForItems">
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
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-4">
                            <div class="summery mt-2">
                                <label for="">@lang('site.discount_invoice')</label>
                                <input type="number" value="0" name="extra_invoice_discount" id="invoice-discount">
                                <p class="text-danger text-bold d-none validate">
                                    @lang('site.discount_invoice_overflow_error')</p>
                            </div>
                        </div>
                        <div class="offset-4 col-4">
                            <div class="summery mt-2">
                                <label for="">@lang('site.total_invoice')</label>
                                <input type="text" disabled value="" id="invoice-total">
                            </div>
                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_2_4')</span>
                    </div>
                </fieldset>
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
                                <input type="number" value="1" name="curreny_rate" placeholder="@lang('site.rate')" 
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
                                            <p class="text-danger text-bold d-none validate" id="validate-quantity-overflow-credit">@lang('site.quantity_credit_overflow_error')</p>
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
                                    <div class="input-group-append">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="item_price" id="item_price"
                                        class="item_price input-group-item"
                                        onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                        placeholder="@lang('site.unit_price_eg')" readonly>
                                    <p class="text-danger text-bold d-none validate" id="validate-price-overflow">
                                        @lang('site.price_invoice_overflow_error')</p>
                                    <p class="text-danger text-bold d-none validate" id="validate-price-overflow-credit">@lang('site.price_invoice_overflow_error')</p>
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
                                        placeholder="@lang('site.discount_rate')" readonly>
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

@endsection


@section('scripts')
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    
    <script src="{{ asset('invoice/14_9_V2_taxItems/taxItems.js') }}"></script>

    <script>
        Date.prototype.toDateInputValue = (function() {
            var local = new Date(this);
            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
            return local.toJSON().slice(0, 10);
        });

        $('#date').val(new Date().toDateInputValue());

        $('.search-company.spinner-border').hide();
        $('.search-client.spinner-border').hide();
        $('.search-product.spinner-border').hide();

        // get Foreign Data on Select change
        $('#foreigner-client').on('change', function() {
            var searchContent = $(this).val();
            that = $(this);
            $.ajax({
                type: 'GET',
                url: `${subFolderURL}/${urlLang}/clients/getDocumentForeignerPurchaseOrder/` + searchContent,
                success: function(client) {
                    // $('#select_purchase_order').prop('selectedIndex', 0);
                    $('#select_purchase_order :not(:first)').remove();
                    var responses = JSON.parse(client);
                    if (responses.length != 0) {
                        $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger(
                            'change');
                        for (let i = 0; i < responses.length; i++) {
                            $('#select_purchase_order').append(
                                `<option value="${responses[i].purchase_order_reference}">${responses[i].purchase_order_reference}</option>`
                            );
                        }
                    } else {
                        $('#client_id').val('').parent().next().removeClass('d-none').text(
                            "@lang('site.purchaseOrder_of_client_empty')");
                    }
                },
                error: function() {
                    $('.vaild-company-register-tax').text("{{ trans('site.error') }}");
                },
                complete: function() {
                    $('.search-company.spinner-border').hide();
                }
            });

        });


        $('#client_type').on('change', function() {
            $('#client_id').val('');

            $('.client-details .text-danger').addClass('d-none');
            $('#tax_id_number_or_national_id').val('');
            $('#client_name').text('');
            $('#client_address').text('');

            let selectValue = $(this).val();
            // check selector value
            if (selectValue == 'b' || selectValue == 'p') {
                $('.client-details').removeClass('d-none');
                $('.select-foreigner-client').addClass('d-none');
                $('#client-id-container').insertBefore(".client-details .row .text-danger");
                let labelOrInputParent = $('#tax_id_number_or_national_id').parent();
                // get data(label and name) value in option selected
                let dataLabel = $(this).find('option:selected').data('label');
                //change label text and input name
                labelOrInputParent.find('label').text(dataLabel);
            } else {
                $('.select-foreigner-client').removeClass('d-none');
                $('.client-details').addClass('d-none');
                $('#client-id-container').insertBefore(".select-foreigner-client .row .text-danger");
            }
            let targetSelector = $('#foreigner-client');
            if (targetSelector != '') {
                const urlInputId = $(this).val();
                const url = `${subFolderURL}/${urlLang}/clients/getClientsFromclientType`;

                if ($(this).val() == 'f')
                    sendAjax('GET', url, urlInputId, targetSelector, getForeignerClient)
            }
        });

        function sendAjax(method, url, urlInputId, targetSelector, successFunction) {
            targetSelector.attr('disabled', true);
            $.ajax({
                type: method,
                url: `${url}`,
                success: function(response) {
                    successFunction(response, targetSelector);
                }
            });
        }

        function getForeignerClient(response, targetSelector) {
            targetSelector.attr('disabled', false);
            var response = JSON.parse(response);
            // $('#select_purchase_order').prop('selectedIndex', 0);
            $('#select_purchase_order :not(:first)').remove();
            $('#foreigner-client :not(:first)').remove();
            for (const key in response) {
                if (response.hasOwnProperty.call(response, key)) {
                    targetSelector.append(
                        `<option value="${key}">${response[key]}</option>`
                    );
                }
            }
        }

        function getBusinessOrPersonClientData(client) {
            if (client.basic) {
                $('#client_name').text(client.basic.name);
                $('#client_address').text(client.basic.address);

                $('#select_purchase_order :not(:first)').remove();

                if (client.purchaseOrders.length != 0) {
                    $('#client_id').val(client.basic.id);
                    $('#client_id').trigger('keyup');
                    // $('#client_id').val(that.val()).parent().next().addClass('d-none').trigger('change');
                    for (let i = 0; i < client.purchaseOrders.length; i++) {
                        $('#select_purchase_order').append(
                            `<option value="${client.purchaseOrders[i].purchase_order_reference}">${client.purchaseOrders[i].purchase_order_reference}</option>`
                        );
                    }
                } else {
                    $('#client_id').val('').parent().next().removeClass('d-none').text(
                        "@lang('site.purchaseOrder_of_client_empty')");
                }

            } else {
                $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                $('#client_name').text('');
                $('#client_address').text('');
            }
            $('.search-bank.spinner-border').hide();
        }

        $('#tax_id_number_or_national_id').change(function(e) {
            let key = e.which;

                $('#client_id').val('');
                let clientType = $('#client_type').val(),
                    searchContent = $(this).val().trim(),
                    valid = false,
                    sendData = {
                        clientType: clientType,
                        searchContent: searchContent,
                    };

                const validateError = $('#client_type').find('option:selected').data('validate'),
                    taxIdNumberRegex = /^[\d]{3}-[\d]{3}-[\d]{3}$/,
                    //nationalIdRegex = /^(2|3)[0-9][1-9][0-1][1-9][0-3][1-9](01|02|03|04|11|12|13|14|15|16|17|18|19|21|22|23|24|25|26|27|28|29|31|32|33|34|35|88)\d\d\d\d\d$/;
                    nationalIdRegex = /^[0-9]{14}$/;
                if (clientType == 'b') { // Validate Tax Id Number
                    console.log("123333");
                    if (taxIdNumberRegex.test(searchContent)) { // valid
                        $('.client-details .text-danger').addClass('d-none')
                        valid = true;
                    } else {
                        $('.client-details .text-danger').removeClass('d-none').text(validateError);

                    }
                } else if (clientType == 'p') { // Validate National Id Number
                    if (nationalIdRegex.test(searchContent)) { // valid
                        $('.client-details .text-danger').addClass('d-none');
                        valid = true;
                    } else {
                        $('.client-details .text-danger').removeClass('d-none').text(validateError);
                        $('#client_name').text('');
                        $('#client_address').text('');
                    }
                }

                if (valid) { // If Valid for
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $('.search-bank.spinner-border').show();
                    $.ajax({
                        type: 'POST',
                        url: `${subFolderURL}/${urlLang}/clients/getDocumentBusinessOrPersonClientData`,
                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'JSON',
                        success: function(client) {
                            // reset items
                            items = [];
                            $('#items').val(''); // number of addeditem
                            $('#invoice-discount').val('');
                            $('#invoice-total').val('');
                            $(".tableForItems tbody").html(''); // clear item in table
                            $('#select_purchase_order').prop('selectedIndex', 0);
                            getBusinessOrPersonClientData(client);
                        }
                    });
                }
        });

        $('.search-bank.spinner-border').hide();

        $('#purchase_order_reference').keydown(function(e) {
            var key = e.which;
            var searchContent = $(this).val();
            if (key == 13) {
                e.preventDefault();
                $('.search-bank.spinner-border').show();
                $('#purchaseorder_id').val('');
                // $('#purchase_order_reference').val('');
                $('#purchase_order_description').val('');
                $('#sales_order_reference').val('');
                $('#sales_order_description').val('');
                $('#proforma_invoice_number').val('');
                $('#payment_terms').val('');
                $('#delivery_approach').html('');
                $('#delivery_packaging').html('');
                $('#delivery_validate_date').val('');
                $('#delivery_export_port').val('');
                $('#delivery_country_origin').val('');
                $('#delivery_gross_weight').val('');
                $('#delivery_net_weight').val('');
                $('#delivery_terms').html('');
                $('#bank_id').val('');
                $('#bank_name').val('');
                $('#bank_account_number').val('');
                $('#swift_code').val('');
                $('#bank_account_iban').val('');
                $('#bank_address').html('');
                $('.vaild-client-register-tax').text("");
                $.ajax({
                    type: 'GET',
                    url: `${subFolderURL}/${urlLang}/getPOData/` + searchContent,
                    success: function(response) {
                        var responses = JSON.parse(response);
                        if (response.length > 0) {
                            for (const key in responses[0]) {
                                if (responses[0].hasOwnProperty(key)) {
                                    // console.log(`${key}: ${responses[0][key]}`);
                                    $('#purchaseorder_id').val(responses[0]['id']);
                                    $('#purchase_order_reference').val(responses[0][
                                        'purchase_order_reference'
                                    ]);
                                    $('#purchase_order_description').val(responses[0][
                                        'purchase_order_description'
                                    ]);
                                    $('#sales_order_reference').val(responses[0][
                                        'sales_order_reference'
                                    ]);
                                    $('#sales_order_description').val(responses[0][
                                        'sales_order_description'
                                    ]);
                                    $('#proforma_invoice_number').val(responses[0][
                                        'proforma_invoice_number'
                                    ]);
                                    $('#payment_terms').val(responses[0]['payment_terms']);
                                    $('#delivery_approach').html(responses[0]['delivery_approach']);
                                    $('#delivery_packaging').html(responses[0]['delivery_packaging']);
                                    $('#delivery_validate_date').val(responses[0][
                                        'delivery_validate_date'
                                    ]);
                                    $('#delivery_export_port').val(responses[0][
                                        'delivery_export_port'
                                    ]);
                                    $('#delivery_country_origin').val(responses[0][
                                        'delivery_country_origin'
                                    ]);
                                    $('#delivery_gross_weight').val(responses[0][
                                        'delivery_gross_weight'
                                    ]);
                                    $('#delivery_net_weight').val(responses[0]['delivery_net_weight']);
                                    $('#delivery_terms').html(responses[0]['delivery_terms'])
                                }
                                for (const key in responses[1]) {
                                    if (responses[1].hasOwnProperty(key)) {
                                        // console.log(`${key}: ${responses[0][key]}`);
                                        $('#bank_id').val(responses[1]['id']);
                                        $('#bank_name').val(responses[1]['bank_name']);
                                        $('#bank_account_number').val(responses[1][
                                            'bank_account_number'
                                        ]);
                                        $('#swift_code').val(responses[1]['swift_code']);
                                        $('#bank_account_iban').val(responses[1]['bank_account_iban']);
                                        $('#bank_address').html(responses[1]['bank_address']);
                                    }
                                }
                            }
                        } else {
                            $('.vaild-client-register-tax').text("{{ trans('site.check_again') }}");
                        }
                    },
                    error: function() {
                        $('.vaild-client-register-tax').text("{{ trans('site.error') }}");
                    },
                    complete: function() {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }
        });

        $(".actions a[href$='#next']").text("{{ trans('site.next') }}");
        $(".actions a[href$='#previous']").text("{{ trans('site.prev') }}");
        $(".actions a[href$='#finish']").text("{{ trans('site.save') }}");

        // $('#item_select').select2();
        $('#tax_id_number_or_national_id').select2({
            placeholder: '@lang("site.choose") ',
        });

        language['selectItemPlaceholder'] = "@lang('site.choose_item')";
        language['save'] = "@lang('site.save')";
        language['send_data'] = "@lang('site.send_data')";
        language['data_sent'] = "@lang('site.data_sent')";
        language['send_data_error'] = "@lang('site.send_data_error')";
        editDocumentMode = false;

    </script>
@endsection
