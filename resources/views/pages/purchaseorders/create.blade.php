@extends('pages.layouts.master')

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
    <style>
        @keyframes bouncing-loader {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0.1;
                transform: translateY(-1rem);
            }
        }

        .bouncing-loader {
            display: flex;
            justify-content: center;
        }

        .bouncing-loader > div {
            width: 1rem;
            height: 1rem;
            margin: 3rem 0.2rem;
            background: rgb(4, 182, 4);
            border-radius: 50%;
            animation: bouncing-loader 0.8s infinite alternate;
        }

        .bouncing-loader > div:nth-child(2) {
            animation-delay: 0.2s;
        }

        .bouncing-loader > div:nth-child(3) {
            animation-delay: 0.4s;
        }

        .bouncing-loader > div:nth-child(4) {
            animation-delay: 0.6s;
        }

        .bouncing-loader > div:nth-child(5) {
            animation-delay: 0.8s;
        }

        .items-from-excel-sheet-loader {
            display: grid;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            border: solid;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>

    @if (Config::get('app.locale') == 'ar')
        <style>
            .date {
                direction: rtl !important;
            }

        </style>
    @endif
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.add_purchaseorder')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.add_purchaseorder') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="main">
        <div class="form-container">
            <h2 class="mb-2">@lang('site.add_purchaseorder')</h2>
            <form method="POST" id="PoForm" class="PoForm" action="{{ route('purchaseorders.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- purchaseorder details --}}

                <h3>
                    <span class="title_text">@lang('site.purchaseorder_details')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content">
                        <div class="card">

                            <h5 class="card-header bg-success">
                                @lang('site.purchaseorder_details')
                            </h5>

                            <div class="card-body">

                                {{-- Client section --}}
                                <div class="row mb-3">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                   id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require">
                                                <option selected disabled>@lang('site.select') @lang('site.client_type')
                                                </option>
                                                <option value="b" data-label="@lang('site.tax_id_number_only')"
                                                        data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" data-label="@lang('site.national_id')"
                                                        data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f">@lang('site.foreigner_client')</option>
                                            </select>
                                        </div>
                                        @error('client_type')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Purchase Order Client id --}}
                                    <div class="col-md-8">
                                        <div class="select-foreigner-client d-none">
                                            <div class="row mb-3">
                                                <div class="col-md-8 input-group mb-3">
                                                    <label class="form-label d-block w-100">@lang('site.client')</label>
                                                    <select id='foreigner-client' class="form-control require" disabled>
                                                        <option selected disabled>@lang('site.select')
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
                                                <div class="col-md-3 no-gutters">
                                                    {{-- tax_id_number for business client Or national ID person client --}}
                                                    <div class="row mb-1 no-gutters">
                                                        <div class="col-md-11">
                                                            <label for="tax_id_number_or_national_id"
                                                                   class="form-label w-100"></label>
                                                            <input type="text" class="form control"
                                                                   id="tax_id_number_or_national_id"/>
                                                        </div>

                                                        <div class="col-md-1 bank-spinner"
                                                             style="padding:32px 0 0 10px">
                                                            <div
                                                                class="search-bank spinner-border spinner-border-sm text-success"
                                                                role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- name --}}
                                                <div class="col-md-3">
                                                    <label for="name" class="form-label w-100"
                                                           id="min_payment_label">@lang('site.name') </label>
                                                    <input type="text" id="client_name" class="display" readonly>
                                                </div>

                                                {{-- address --}}
                                                <div class="col-md-4">
                                                    <label for="address" class="form-label w-100"
                                                           id="payment_label">@lang('site.address')</label>
                                                    <input type="text" id="client_address" class="display" readonly>
                                                </div>

                                                {{-- client_id --}}
                                                <div class="col-md-2" id="client-id-container">
                                                    <label for="address" class="form-label">@lang('site.id')</label>
                                                    <input type="text" name="client_id" id="client_id" readonly>
                                                </div>

                                                <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>

                                            </div> <!-- End Of First Row-->

                                        </div> <!-- End Of Card Body-->
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    {{-- Purchase Order Reference --}}
                                    <div class="col-md-4">
                                        <label for="purchase_order_reference" class="form-label w-100"
                                               id="order_label">@lang('site.purchase_order_reference')</label>
                                        <input type="text" class="form control" value="" name="purchase_order_reference"
                                               id="purchase_order_reference"/>
                                        <p class="text-center text-bold d-none purchase_order_reference_used_before_error"
                                           style="font-size: 11px; color: red">
                                            @lang('site.purchase_order_reference_used_before')</p>
                                    </div>
                                    {{--main Project Name --}}
                                    <div class="col-md-4">
                                        <label for="project_name" class="form-label w-100"
                                            id="order_label">@lang('site.main_project_name')</label>
                                        <select name="main_project_name" class="form-scontrol main_project require">
                                            <option disabled selected value="">@lang('site.choose') @lang('site.main_project_name')</option>

                                            @foreach($projects as $project)

                                            <option value="{{$project->id}}">{{$project->name_ar}}</option>
                                            @endforeach

                                        </select>
                                        @error('main_project_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- subProject Name --}}
                                    <div class="col-md-4">
                                        <label for="project_name" class="form-label w-100"
                                               id="order_label">@lang('site.po_project_name')</label>
                                        <input type="text" class="form control" value="" name="project_name"
                                               id="project_name"/>
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    {{-- Project Number --}}
                                    <div class="col-md-4">
                                        <label for="project_number" class="form-label w-100"
                                               id="min_order_label">@lang('site.po_project_number')</label>
                                        <input type="text" class="form control" value="" name="project_number"
                                               id="project_number"/>
                                    </div>
                                    {{-- Project Contract Number --}}
                                    <div class="col-md-8">
                                        <label for="project_contract_number" class="form-label w-100"
                                               id="min_order_label">@lang('site.po_project_contract_number')</label>
                                        <input type="text" class="form control" value="" name="project_contract_number"
                                               id="project_contract_number"/>
                                    </div>
                                </div>

                                <div class="row">

                                    {{-- Purchase Order Document --}}
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <label for="purchaseorder_document" class="form-label ml-n2 w-100"
                                                   id="po_document_label">@lang('site.purchaseorder_document')</label>
                                            <div class="row">
                                                <div class="custom-file">
                                                    <input type="file" name="purchaseorder_document"
                                                           id="purchaseorder_document" class="custom-file-input"/>
                                                    <span id='val'></span>
                                                    <span id='button'>@lang('site.select_file')</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Purchase Order type --}}
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <label class="form-label d-block w-100"
                                                   id="order_label">@lang('site.purchaseOrder_type')</label>
                                            <select name="type" id="purchase-order-type" class="form-control require">
                                                <option selected disabled>@lang('site.select')
                                                    @lang('site.small_purchaseOrder_type')
                                                </option>
                                                <option value="budget">budget</option>
                                                <option value="quantity">quantity</option>
                                            </select>
                                        </div>
                                        @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="payment_terms"
                                               class="form-label w-100 mb-2">@lang('site.payment_terms')</label>
                                        <textarea type="text" class="form control" name="payment_terms"
                                                  id="payment_terms"
                                                  rows="5"></textarea>
                                    </div>


                                    {{-- Comment --}}
                                    <div class="col-md-6">
                                        <label for="payment_terms"
                                               class="form-label w-100 mb-2">@lang('site.comment')</label>
                                        <textarea type="text" class="form control" name="comment" id="comment"
                                                  rows="5"></textarea>
                                    </div>


                                    </div>

                            </div>

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_1_4')</span>
                    </div>
                </fieldset>


                {{-- add products --}}
                <h3>
                    <span class="title_text">@lang('site.add_products')</span>
                </h3>

                <fieldset class="pl-3 pr-4">
                    <div class="row">
                        <div class="col-md-3 ml-3">
                            <label for="name" class="form-label w-100"
                                   id="min_payment_label">@lang('site.items_counter') </label>
                            <input type="text" id="items" name="items_counter" readonly class="mb-2">
                        </div>
                    </div>


                    <div class="row align-items-center">
                        <h5 class="p-3 mx-2 bg-success rounded-top col-12">
                            @lang('site.items')
                        </h5>
                        {{-- add New Item --}}
                        <div class="col-12 text-center items-links mb-2 date">

                            <a href="#" data-toggle="modal" data-target="#addline" class="addNewItem" id="_addNewItemBtn"><i class="fa fa-plus"></i> @lang('site.add_item')</a>
                            <!-- Button trigger modal to set items via excel sheet -->
                            <a href="{{url('/downloadExcel')}}"><i
                                    class="fa fa-download"></i> @lang('site.download_excel_template')</a>

                            <a href="#" data-toggle="modal" id="_items_excel_button_saveBtn"
                               data-target="#addItemsViaExcel"><i
                                    class="fa fa-plus"></i> @lang('site.add_items_via_excel')</a>
                            <a href="javascript:" id="_delete_allBtn" class="text-danger"><i
                                    class="fa fa-trash"></i> @lang('site.delete_items')</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        {{-- Table for view addded items --}}
                        <table
                            class="table table-bordered table-striped table-hover justify-content-center text-center m-0 tableForItems">
                            <thead>

                            <tr>
                                <th scope="col" width="20px">
                                    #
                                </th>
                                <th scope="col">
                                    @lang('site.product_code')
                                </th>
                                <th scope="col">
                                    @lang('site.item')
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

                    <div class="row justify-content-end">
                        <div class="col-3 mb-2">
                            <div class="summery mt-2">
                                <label for="">@lang('site.purchase_order_total')</label>
                                <input type="text" disabled value="" id="purchase-order-total">
                            </div>
                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_2_4')</span>
                    </div>
                </fieldset>

                {{-- bank details --}}
                <h3>
                    <span class="title_text"> @lang('site.bank_details')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content">
                        <div class="card">

                            <h5 class="card-header bg-success">
                                @lang('site.bank_details')
                            </h5>

                            <div class="card-body">

                                <div class="row mb-3">

                                    {{-- Bank code --}}
                                    <div class="col-md-3 no-gutters">
                                        <div class="row mb-1 no-gutters">
                                            <div class="col-md-11">
                                                <label for="bank_code" class="form-label w-100"
                                                       id="min_payment_label">@lang('site.bank_code')</label>
                                                <input type="number" class="form control" id="bank_code"/>
                                            </div>
                                            <div class="col-md-1 bank-spinner" style="padding:32px 0 0 10px">
                                                <div class="search-bank spinner-border spinner-border-sm text-success"
                                                     role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="vaild-client-register-tax ml-2"></p>
                                    </div>

                                    {{-- bank_name --}}
                                    <div class="col-md-3">
                                        <label for="bank_name" class="form-label w-100"
                                               id="min_payment_label">@lang('site.bank_name') </label>

                                        <input type="text" id="bank_name" class="display" readonly>


                                    </div>
                                    {{-- bank_account_number --}}
                                    <div class="col-md-3">
                                        <label for="bank_account_number" class="form-label w-100"
                                               id="payment_label">@lang('site.bank_account_number')</label>
                                        <input type="text" id="bank_account_number" class="display" readonly>
                                    </div>
                                    {{-- bank currency --}}
                                    <div class="col-md-2">
                                        <label for="bank_currency"
                                               class="min_payment_label">@lang('site.currency')</label>
                                        <input type="text" id="bank_currency" class="display" readonly>
                                    </div>

                                    {{-- Bank id --}}
                                    <div class="col-md-1">
                                        <label for="address" class="form-label w-100">@lang('site.id')</label>
                                        <input type="text" name="bank_id" value="" id="bank_id" readonly>
                                    </div>

                                </div> <!-- End Of First Row-->

                            </div> <!-- End Of Card Body-->

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_3_4')</span>
                    </div>
                </fieldset>

                {{-- delivery details --}}
                <h3>
                    <span class="title_text"> @lang('site.delivery_details')</span>
                </h3>

                <fieldset>
                    <div class="fieldset-content">
                        <div class="card ml-2">

                            <h5 class="card-header bg-success">
                                @lang('site.delivery_details')
                            </h5>

                            <div class="card-body">
                                <div class="row mb-3">
                                    {{-- delivery_approach --}}
                                    <div class="col-md-6">
                                        <label for="delivery_approach" class="form-label w-100"
                                               id="min_textarea_delivery_label">
                                            @lang('site.delivery_approach') </label>
                                        <textarea type="text" class="form control" value="" name="delivery_approach"
                                                  id="delivery_approach"></textarea>
                                    </div>
                                    {{-- delivery_packaging --}}
                                    <div class="col-md-6">
                                        <label for="delivery_packaging" class="form-label w-100"
                                               id="min_textarea_delivery_label">
                                            @lang('site.delivery_packaging') </label>
                                        <textarea type="text" class="form control" value="" name="delivery_packaging"
                                                  id="delivery_packaging"></textarea>
                                    </div>

                                </div> <!-- End of First Row-->

                                <div class="row mb-3">

                                    <div class="col-md-6">

                                        <div class="row">
                                            {{-- delivery_validate_date --}}
                                            <div class="col-md-4">
                                                <label for="delivery_validate_date" class="form-label w-100"
                                                       id="large_textarea_delivery_label"> @lang('site.delivery_validate_date')
                                                </label>
                                                <input type="date" class="form control" value=""
                                                       name="delivery_validate_date" id="delivery_validate_date"/>
                                            </div>
                                            {{-- delivery_export_port --}}
                                            <div class="col-md-8">
                                                <label for="delivery_export_port" class="form-label w-100"
                                                       id="large_textarea_delivery_label"> @lang('site.delivery_export_port')
                                                </label>
                                                <input type="text" class="form control" value=""
                                                       name="delivery_export_port"
                                                       id="delivery_export_port"/>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            {{-- delivery_country_origin --}}
                                            <div class="col-md-4">
                                                <label for="delivery_country_origin" class="form-label w-100"
                                                       id="xl_textarea_delivery_label"> @lang('site.delivery_country_origin')
                                                </label>
                                                <div class="input-group mb-3">
                                                    <select name="delivery_country_origin" id="delivery_country_origin"
                                                            class="form-control require">
                                                        <option disabled
                                                                selected>@lang('site.select') @lang('site.country')
                                                        </option>
                                                        @foreach ($countries as $countryId => $countryName)
                                                            <option value='{{ $countryId }}'>
                                                                {{ ucfirst($countryName) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('delivery_country_origin')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- delivery_gross_weight --}}
                                            <div class="col-md-4">
                                                <label for="delivery_gross_weight" class="form-label w-100"
                                                       id="xl_textarea_delivery_label"> @lang('site.delivery_gross_weight')
                                                </label>
                                                <input type="number" class="form control" value=""
                                                       name="delivery_gross_weight" id="delivery_gross_weight"/>
                                            </div>
                                            {{-- delivery_net_weight --}}
                                            <div class="col-md-4">
                                                <label for="delivery_net_weight" class="form-label w-100"
                                                       id="xl_textarea_delivery_label"> @lang('site.delivery_net_weight')
                                                </label>
                                                <input type="number" class="form control" value=""
                                                       name="delivery_net_weight" id="delivery_net_weight"/>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- End of Second Row-->

                                {{-- delivery_terms --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="delivery_terms" class="form-label w-100"
                                               id="large_textarea_delivery_label">
                                            @lang('site.delivery_terms') </label>
                                        <textarea type="text" class="form control" value="" name="delivery_terms"
                                                  id="delivery_terms"></textarea>
                                    </div>
                                </div>

                            </div> <!-- End Of Card Body-->

                        </div>
                    </div>

                    <div class="fieldset-footer">
                        <span>@lang('site.step_4_4')</span>
                    </div>
                </fieldset>

            </form>
        </div>
    </section>

    {{-- modal add items --}}
    <div class="modal fade" id="addline" data-check-data="null" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLongTitle">@lang('site.add_new_item')</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- end of model header --}}

                <div class="modal-body add-invoice-items">

                    <form id="addItemsForm">
                        <div class="row">
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
                        {{-- end of row --}}
                        <div class="row mb-2">
                            {{-- product_code --}}
                            <div class="col-6">
                                <label>@lang('site.product_code')</label>
                                <input type="text" class="form-control" name="product_code" data-product-id=''
                                       id="product_code" readonly>
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

                        <div class="row currenty-type mb-2">
                            {{-- currency --}}
                            <div class="col-6  input-group select">
                                <label>@lang('site.currency')</label>
                                <select name="currency" id="currency" class="currenty-type-select">
                                    <option disabled
                                            selected>@lang('site.capital_select') @lang('site.currency')</option>
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

                        <div class="row price  mb-2">

                            {{-- quantity --}}
                            <div class="col-6">
                                <label>@lang('site.quantity')</label>
                                <div class="input-group select">
                                    <input type="number" name="quantity" id="item-quantity"
                                           placeholder="@lang('site.quantity')" class="quantity input-group-item"/>
                                </div>
                                <p class="text-center text-bold d-none quantity_edit_error"
                                   style="font-size: 11px; color: red">
                                    @lang('site.quantity_edit_error')</p>
                            </div>
                            {{-- Price --}}
                            <div class="col-6">
                                <label>@lang('site.unit_price')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="item_price" id="item-price"
                                           class="item_price input-group-item"
                                           placeholder="@lang('site.unit_price')">
                                </div>
                            </div>
                        </div>
                        {{-- end of row --}}
                        <hr>
                        <div class="row">
                            {{-- total_amount --}}
                            <div class="col-5">
                                <label>@lang('site.total_amount')</label>
                                <input type="number" name="sales_amount" placeholder="@lang('site.total_amount')"
                                       class="sales_amount" readonly/>
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
                                        class="btn btn-dark pull-left rounded-pill add_new_row_tax"
                                        data-toggle="tooltip"
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
                                    <div class="tax-items d-none">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="row mr-1 mb-1 tax-row-container">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="tax_type[]" class="item_line_select tax-type"
                                                                    value="{{ old('department') }}"
                                                                    data-toggle="tooltip"
                                                                    data-placement="top" title="Sub Group">
                                                                <option selected disabled>@lang('site.choose')...
                                                                </option>
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
                                                        <p class="typeName">Type Name </p>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <select name="subtype" class="item_line_select subtype"
                                                                    itle="Item">

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="tax_rate[]" placeholder="Rate"
                                                                   class="tax_rate input-tax-item"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <input type="number" name="row_total_tax[]"
                                                                   placeholder="Total Amount EGY"
                                                                   class=" row_total_tax input-tax-item" readonly/>
                                                        </div>
                                                    </div>
                                                    <div><input type="hidden" name="items[]" value="any"></div>
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
                            <div class="col-md-6">
                                <label>@lang('site.total_taxable_fee')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="taxable_fees" class="form-control taxable_fees" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('site.value_diffrence')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="differ_value" class="form-control differ_value">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('site.value_diffrence_item_discount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="items_discount" class="form-control itemsDiscount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('site.net_total')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="net_total" class="net_total form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('site.total_amount')</label>
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <span class="current-currency-text">@lang('site.currency')</span>
                                    </div>
                                    <input type="number" name="total_amount" class=" total_amount form-control"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success save-form-item">
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

    <!-- Modal to set items via excel sheet -->
    <!-- Modal -->
    <div class="modal fade" id="addItemsViaExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('site.add_items_via_excel')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Excel file --}}
                        <div class="input-group col-10 offset-1">
                            <input type="file" name="items_excel"
                                   accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                   class="custom-file-input w-auto ml-auto"
                                   id="items_excel_id"
                                   oninput="this.className = 'custom-file-input w-auto ml-auto'">
                            <label class="custom-file-label m-0"
                                   style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                   for="items_excel_id">
                                @lang('site.choose') @lang('site.file')
                            </label>
                        </div>

                        {{-- Json data --}}
                        {{-- <div class="col-12">
                            <pre id="jsondata"></pre>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.cancel')</button>
                    <button type="button" class="btn btn-primary"
                            id="items_excel_button_save">@lang('site.save')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loader for loading purchase order items from excel sheet --}}
    <div class="items-from-excel-sheet-loader" style="display: none">
        <div class="bouncing-loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let cannot_add_more_item = "@lang('site.cannot_add_more_item')";
        let language = [];
        language['save'] = "@lang('site.save')";
        language['send_data'] = "@lang('site.send_data')";
        language['data_sent'] = "@lang('site.data_sent')";
        language['send_data_error'] = "@lang('site.send_data_error')";

        let validationMessages = [];
        validationMessages['client_type'] = "@lang('site.validate_client_type_message')";
        validationMessages['client_id'] = "@lang('site.validate_client_id_message')";
        validationMessages['type'] = "@lang('site.validate_type_message')";
        validationMessages['purchase_order_reference'] = "@lang('site.validate_purchase_order_reference_message')";
        validationMessages['project_name'] = "@lang('site.validate_project_name_message')";
        validationMessages['project_number'] = "@lang('site.validate_project_number_message')";
        validationMessages['project_contract_number'] = "@lang('site.validate_project_contract_number_message')";
        validationMessages['payment_terms'] = "@lang('site.validate_payment_terms_message')";
        validationMessages['bank_id'] = "@lang('site.validate_bank_id_message')";
        validationMessages['delivery_approach'] = "@lang('site.validate_delivery_approach_message')";
        validationMessages['delivery_terms'] = "@lang('site.validate_delivery_terms_message')";
        validationMessages['items_counter'] = "@lang('site.validate_items_counter_message')";
        validationMessages['deduction_id'] = "@lang('site.validate_deduction')";
        validationMessages['delivery_country_origin'] = "@lang('site.validate_delivery_country_origin_message')";


        validationMessages['quantity'] = "@lang('site.validate_quantity')";
        validationMessages['item_price'] = "@lang('site.validate_item_price')";
        validationMessages['product_code'] = "@lang('site.validate_product_code')";
        validationMessages['product_name'] = "@lang('site.validate_product_name')";
        validationMessages['currency'] = "@lang('site.validate_currency')";

    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="{{ asset('invoice/vendor/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('invoice/16_9_v1_taxitems/poTaxItems.js') }}"></script>
    <script src="{{ asset('plugins/xlsx.full.min.js') }}"></script>

    <script>


        // delete all items
        $('#_delete_allBtn').on('click', function () {
            console.log(items);
            if (items.length == 0)
                alert('There is no Items');
            else {
                items = [];
                $('#items').val('');
                resetItemCounter();
                total = 0;
                $(".tableForItems tbody").html('');
                $('#purchase-order-total').val(total.toLocaleString('us', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }
        });

        $(".actions a[href$='#next']").text("{{ trans('site.next') }}");
        $(".actions a[href$='#previous']").text("{{ trans('site.prev') }}");
        $(".actions a[href$='#finish']").text("{{ trans('site.save') }}");

        $('.search-bank.spinner-border').hide();
        $('.search-product.spinner-border').hide();

        $('#bank_code').keydown(function (e) {
            var key = e.which;
            var searchContent = $(this).val();
            if (key == 13) {
                $('#bank_id').val('');
                e.preventDefault();
                $('.search-bank.spinner-border').show();
                $('.vaild-client-register-tax').text("");
                $.ajax({
                    type: 'GET',
                    url: `${subFolderURL}/${urlLang}/getBankData/` + searchContent,
                    success: function (response) {
                        var responses = JSON.parse(response);
                        if (responses.length > 0) {
                            responses.forEach(element => {
                                $('#bank_id').val(element['id']);
                                $('#bank_id').trigger('keyup');
                                $('#bank_name').val(element['bank_name']);
                                $('#bank_account_number').val(element['bank_account_number']);
                                $('#bank_currency').val(element['currency']);
                            });
                        } else {
                            $('#bank_id').val('');
                            $('#bank_id').val('')
                            $('#bank_name').val('');
                            $('#bank_account_number').val('');
                            $('#bank_currency').val('');
                            $('.vaild-client-register-tax').text("{{ trans('site.check_again') }}");
                        }
                    },
                    error: function () {
                        $('.vaild-client-register-tax').text("{{ trans('site.error') }}");
                    },
                    complete: function () {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }
        });

        $("input[type='file']").change(function () {
            $('#val').text(this.value.replace(/C:\\fakepath\\/i, ''));
        });


        $('.item_price').on('keyup input change', function () { // price of item

            const that = $(this);
            let value = that.val().trim();

            if (value) {
                if (!(value.match(/^\d*\.\d{0,5}$/) || value.match(/^\d+$/))) { // if not integer or decimal with more than 5 digits after decimal point (.)
                    value = roundNumberToNDigitsAfterDecimalPoint(value);
                    that.val(value);
                }
            }
            calcItemsTotalSales($(this));
            netTotalByQuantity();
        });

    </script>

    <script>
        $('#foreigner-client').on('change', function () {
            $('#client_id').val($(this).val());
            $('#client_id').trigger('change');
        })

        $('#client_type').on('change', function () {
            $('#client_id').val('');

            $('.client-details .text-danger').addClass('d-none');
            $('#tax_id_number_or_national_id').val('');
            $('#client_name').val('');
            $('#client_address').val('');

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
                success: function (response) {
                    successFunction(response, targetSelector);
                }
            });
        }

        function getForeignerClient(response, targetSelector) {
            targetSelector.attr('disabled', false);
            var response = JSON.parse(response);
            targetSelector.empty();
            targetSelector.append(`<option selected disabled>@lang('site.select') @lang('site.client_type')</option>`);
            for (const key in response) {
                if (response.hasOwnProperty.call(response, key)) {
                    targetSelector.append(
                        `<option value="${key}">${response[key]}</option>`
                    );
                }
            }
        }

        function getBusinessOrPersonClientData(response) {

            if (response) {
                $('#client_id').val(response.id);
                $('#client_id').trigger('keyup');
                $('#client_name').val(response.name);
                $('#client_address').val(response.address);
            } else {
                $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                $('#client_name').val('');
                $('#client_address').val('');
            }
            $('.search-bank.spinner-border').hide();
        }

        $('#tax_id_number_or_national_id').keydown(function (e) {
            let key = e.which;
            if (key == 13) {
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
                        url: `${subFolderURL}/${urlLang}/clients/getBusinessOrPersonClientData`,
                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            getBusinessOrPersonClientData(response);
                        },
                        error: function () {
                            $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                        },
                        complete: function () {
                            $('.search-bank.spinner-border').hide();
                        }
                    });
                }
            }
        });

        setUrl("{{ route('purchaseorders.index') }}")

    </script>

    <script>
        let productUnits = {!! json_encode($productUnits) !!}, numOfTaxes = -1;
        productUnits.forEach(function (element) {
            delete element.name_ar;
        });
        productUnits = productUnits.map(unit => unit.code);
        const filePlaceHolder = "@lang('site.choose') @lang('site.file')";

        $(document).ready(function () {
            $('.main_project').select2();


        });

    </script>

    <script src="{{ asset('invoice/taxItems/addItemViaExcel.js') }}"></script>
{{--    <script>--}}
{{--        $(document).ready(function () {--}}

{{--            var counter = 0;--}}

{{--            $("#addrow").on("click", function () {--}}
{{--                var newRow = $("<tr>");--}}
{{--                var cols = "";--}}

{{--                cols += '<td><input type="text" class="form-control" name="name' + counter + '"/></td>';--}}
{{--                cols += '<td><input type="text" class="form-control" name="mail' + counter + '"/></td>';--}}
{{--            --}}

{{--                cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';--}}
{{--                newRow.append(cols);--}}
{{--                $("table.order-list").append(newRow);--}}
{{--                counter++;--}}
{{--            });--}}



{{--            $("table.order-list").on("click", ".ibtnDel", function (event) {--}}
{{--                $(this).closest("tr").remove();--}}
{{--                counter -= 1--}}
{{--            });--}}


{{--        });--}}



{{--        function calculateRow(row) {--}}
{{--            var price = +row.find('input[name^="price"]').val();--}}

{{--        }--}}

{{--        function calculateGrandTotal() {--}}
{{--            var grandTotal = 0;--}}
{{--            $("table.order-list").find('input[name^="price"]').each(function () {--}}
{{--                grandTotal += +$(this).val();--}}
{{--            });--}}
{{--            $("#grandtotal").text(grandTotal.toFixed(2));--}}
{{--        }--}}

{{--    </script>--}}

@endsection
