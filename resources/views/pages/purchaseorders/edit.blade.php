@extends('pages.layouts.master')

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
    <style>
        .table2{
            margin-top: 15px;
        }
        .noselect {
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Old versions of Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently
                                                              supported by Chrome, Opera and Firefox */
        }

    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.edit_purchaseorder')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.edit_purchaseorder') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="main">

        <div class="form-container">
            <form method="POST" id="PO-edit" action="{{ route('purchaseorders.update', $purchaseorder->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')
                <fieldset>
                    <div class="fieldset-content">

                        <h5 class="ml-2 mt-3 mb-4">
                            <span class="border-bottom border-success"> @lang('site.edit_purchaseorder') </span>
                        </h5>

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
                                                <select id='client_type' name="client_type"
                                                        class="form-control require">
                                                    <option value="b"
                                                            {{ $purchaseorder->client_type == 'b' ? 'selected' : '' }}
                                                            data-label="@lang('site.tax_id_number_only')"
                                                            data-validate="@lang('site.validate_Tax_id_number')">
                                                        @lang('site.the_businessClient')</option>
                                                    <option value="p"
                                                            {{ $purchaseorder->client_type == 'p' ? 'selected' : '' }}
                                                            data-label="@lang('site.national_id')"
                                                            data-validate="@lang('site.validate_national_id')">
                                                        @lang('site.person_client')</option>
                                                    <option value="f"
                                                        {{ $purchaseorder->client_type == 'f' ? 'selected' : '' }}>
                                                        @lang('site.foreigner_client')</option>
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
                                                        <label
                                                            class="form-label d-block w-100">@lang('site.client')</label>
                                                        <select id='foreigner-client' class="form-control require"
                                                                disabled>
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
                                        <input type="hidden" name="id" value="{{ $purchaseorder->id }}">

                                        {{-- Purchase Order Reference --}}
                                        <div class="col-md-4">
                                            <label for="purchase_order_reference" class="form-label w-100"
                                                   id="order_label">@lang('site.purchase_order_reference')</label>
                                            <input type="text" class="form control"
                                                   value="{{ $purchaseorder->purchase_order_reference }}"
                                                   name="purchase_order_reference" id="purchase_order_reference"/>
                                            <p class="text-center text-bold d-none purchase_order_reference_used_before_error"
                                               style="font-size: 11px; color: red">
                                                @lang('site.purchase_order_reference_used_before')</p>
                                        </div>

                                        {{--main Project Name --}}
                                    <div class="col-md-4">
                                        <label for="project_name" class="form-label w-100"
                                            id="order_label">@lang('site.main_project_name')</label>
                                        <select name="main_project_name" class="form control main_project">
                                            <option disabled selected value="">@lang('site.choose') @lang('site.main_project_name')</option>

                                            @foreach($projects as $project)

                                            <option value="{{$project->id}}" @if($purchaseorder->main_project_name == $project->id) selected @endif>{{$project->name_ar}}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                        {{-- subProject Name --}}
                                        <div class="col-md-4">
                                            <label for="project_name" class="form-label w-100"
                                                   id="order_label">@lang('site.po_project_name')</label>
                                            <input type="text" class="form control"
                                                   value="{{ $purchaseorder->project_name }}" name="project_name"
                                                   id="project_name"/>
                                            @error('project_name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div> <!-- End Of First Row-->

                                    <div class="row mb-3">

                                        {{-- Project Number --}}
                                        <div class="col-md-4">
                                            <label for="project_number" class="form-label w-100"
                                                   id="min_order_label">@lang('site.po_project_number')</label>
                                            <input type="text" class="form control"
                                                   value="{{ $purchaseorder->project_number }}" name="project_number"
                                                   id="project_number"/>
                                            @error('project_number')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>



                                        {{-- Project Contract Number --}}
                                        <div class="col-md-8">
                                            <label for="project_contract_number" class="form-label w-100"
                                                   id="min_order_label">@lang('site.po_project_contract_number')</label>
                                            <input type="text" class="form control"
                                                   value="{{ $purchaseorder->project_contract_number }}"
                                                   name="project_contract_number" id="project_contract_number"/>
                                            @error('project_contract_number')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div> <!-- End of Second Row-->

                                    <div class="row">

                                        {{-- Purchase Order Document --}}
                                        <div class="col-md-4">
                                            <div class="col-md-12">
                                                <label for="purchaseorder_document" class="form-label w-100 ml-n2"
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
                                            @if ($purchaseorder->purchaseorder_document != null)
                                                <a href="{{ $purchaseorder->document_path }}"
                                                   class="img-thumbnail image-preview d-inline-block mt-3"
                                                   target="_blank">@lang('site.purchaseorder_file')</a>

                                            @endif
                                        </div>



                                        {{-- Purchase Order type --}}
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <label class="form-label d-block w-100"
                                                       id="order_label">@lang('site.purchaseOrder_type')</label>
                                                <select disabled name="type" id="purchase-order-type" class="form-control require">
                                                    <option selected disabled>@lang('site.select')
                                                        @lang('site.small_purchaseOrder_type')
                                                    </option>
                                                    <option {{ $purchaseorder->type == 'budget'? 'selected': '' }} value="budget">budget</option>
                                                    <option {{ $purchaseorder->type == 'quantity'? 'selected': '' }} value="quantity">quantity</option>
                                                </select>
                                            </div>
                                            @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- deduction type 3 == resourses--}}
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <label class="form-label d-block w-100"
                                                       id="order_label">@lang('site.small_deduction')</label>
                                                <select name="deduction_id" id="deduction-type" class="form-control require">
                                                    <option selected disabled>@lang('site.select')
                                                        @lang('site.small_deduction')
                                                    </option>
                                                    @foreach($deduction as $ke=>$value)

                                                        <option value="{{$value->id}}" {{ $purchaseorder->deduction_id == $value->id ? 'selected': '' }}>{{ $value->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        {{-- Payment Terms --}}
                                        <div class="col-md-8">
                                            <label for="payment_terms"
                                                   class="form-label w-100 mb-2">@lang('site.payment_terms')</label>
                                            <textarea type="text" class="form control" name="payment_terms"
                                                      id="payment_terms"
                                                      rows="5">{{ $purchaseorder->payment_terms }}</textarea>
                                        </div>

                                    </div> <!-- End of Thied Row-->

                                </div> <!-- End Of Card Body-->

                            </div> <!-- End Of First Card -->

                            <div class="card">

                                <h5 class="card-header bg-success">
                                    @lang('site.bank_details')
                                </h5>

                                <div class="card-body">

                                    <div class="row mb-3">

                                        <div class="col-md-3 no-gutters">
                                            <div class="row mb-1 no-gutters">
                                                <div class="col-md-11">
                                                    <label for="bank_code" class="form-label w-100"
                                                           id="min_payment_label">@lang('site.bank_code')</label>
                                                    <input type="number" class="form control"
                                                           value="{{ $purchaseorder->bank->bank_code }}" name="bank_id"
                                                           id="bank_code"/>

                                                </div>
                                                <div class="col-md-1 bank-spinner" style="padding:32px 0 0 10px">
                                                    <div
                                                        class="search-bank spinner-border spinner-border-sm text-success"
                                                        role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="vaild-client-register-tax ml-2"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="bank_name" class="form-label w-100"
                                                   id="min_payment_label">@lang('site.bank_name') </label>
                                            <input type="text" id="bank_name"
                                                   value="{{ $purchaseorder->bank->bank_name }}" class="display"
                                                   readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="bank_account_number" class="form-label w-100"
                                                   id="payment_label">@lang('site.bank_account_number')</label>
                                            <input type="text" id="bank_account_number"
                                                   value="{{ $purchaseorder->bank->bank_account_number }}"
                                                   class="display"
                                                   readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="bank_currency"
                                                   class="min_payment_label">@lang('site.currency')</label>
                                            <input type="text" id="bank_currency"
                                                   value="{{ $purchaseorder->bank->currency }}" class="display"
                                                   readonly>
                                        </div>

                                    </div> <!-- End Of First Row-->

                                </div> <!-- End Of Card Body-->

                            </div> <!-- End Of Second Card -->
                            <div class="card ml-2">

                                <h5 class="card-header bg-success">
                                    @lang('site.purchaseorder_details')
                                </h5>

                                <div class="card-body">

                                    <div class="row mb-3">

                                        <div class="col-md-6">
                                            <label for="delivery_approach" class="form-label w-100"
                                                   id="min_textarea_delivery_label"> @lang('site.delivery_approach') </label>
                                            <textarea type="text" class="form control" value="" name="delivery_approach"
                                                      id="delivery_approach">{{ $purchaseorder->delivery_approach }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="delivery_packaging" class="form-label w-100"
                                                   id="min_textarea_delivery_label"> @lang('site.delivery_packaging') </label>
                                            <textarea type="text" class="form control" value=""
                                                      name="delivery_packaging"
                                                      id="delivery_packaging">{{ $purchaseorder->delivery_packaging }}</textarea>
                                        </div>

                                    </div> <!-- End of First Row-->

                                    <div class="row mb-3">

                                        <div class="col-md-6">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="delivery_validate_date" class="form-label w-100"
                                                           id="large_textarea_delivery_label">
                                                        @lang('site.delivery_validate_date') </label>
                                                    <input type="date" class="form control"
                                                           value="{{ $purchaseorder->delivery_validate_date }}"
                                                           name="delivery_validate_date" id="delivery_validate_date"/>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="delivery_export_port" class="form-label w-100"
                                                           id="large_textarea_delivery_label">
                                                        @lang('site.delivery_export_port') </label>
                                                    <input type="text" class="form control"
                                                           value="{{ $purchaseorder->delivery_export_port }}"
                                                           name="delivery_export_port" id="delivery_export_port"/>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                {{-- delivery_country_origin --}}
                                                <div class="col-md-4">
                                                    <label for="delivery_country_origin" class="form-label w-100"
                                                           id="xl_textarea_delivery_label">
                                                        @lang('site.delivery_country_origin')
                                                    </label>
                                                    <div class="input-group mb-3">
                                                        <select name="delivery_country_origin"
                                                                id="delivery_country_origin"
                                                                class="form-control require">
                                                            @foreach ($countries as $countryId => $countryName)
                                                                <option value='{{ $countryId }}'
                                                                    {{ $purchaseorder->delivery_country_origin == $countryId ? 'selected' : '' }}>
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
                                                    <input type="number" class="form control"
                                                           value="{{ $purchaseorder->delivery_gross_weight }}"
                                                           name="delivery_gross_weight" id="delivery_gross_weight"/>
                                                </div>
                                                {{-- delivery_net_weight --}}
                                                <div class="col-md-4">
                                                    <label for="delivery_net_weight" class="form-label w-100"
                                                           id="xl_textarea_delivery_label"> @lang('site.delivery_net_weight')
                                                    </label>
                                                    <input type="number" class="form control"
                                                           value="{{ $purchaseorder->delivery_net_weight }}"
                                                           name="delivery_net_weight" id="delivery_net_weight"/>
                                                </div>
                                            </div>
                                        </div>

                                    </div> <!-- End of Second Row-->

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="delivery_terms" class="form-label w-100"
                                                   id="large_textarea_delivery_label"> @lang('site.delivery_terms') </label>
                                            <textarea type="text" class="form control" value="" name="delivery_terms"
                                                      id="delivery_terms">{{ $purchaseorder->delivery_terms }}</textarea>
                                        </div>
                                    </div>


                                </div> <!-- End Of Card Body-->

                            </div> <!-- End Of Third Card -->


                                <!-- BEG Of forth Card -->
                            <div class="card delivery_details">

                                <h5 class="card-header bg-success">
                                    @lang('site.delivery_details')
                                </h5>

                                <div class="card-body row">

                                    <div class="col-md-4">
                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="tax_rate_letter_report"
                                               {{ $purchaseorder->tax_rate_letter_report ? 'checked' : '' }}
                                               id="tax_rate_letter_report">
                                        <label style=" padding-left: 10px" for="tax_rate_letter_report"
                                               class="noselect">
                                            @lang('site.tax_rate_letter_report')
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="primary_delivery_status"
                                               {{ $purchaseorder->primary_delivery_status ? 'checked' : '' }}
                                               id="primary_delivery_status">
                                        <label style=" padding-left: 10px" for="primary_delivery_status"
                                               class="noselect">
                                            @lang('site.primary_delivery_status')
                                        </label>
                                    </div>


                                    <div class="col-md-4">
                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="final_delivery_status"
                                               {{ $purchaseorder->final_delivery_status ? 'checked' : '' }}
                                               id="final_delivery_status">
                                        <label style=" padding-left: 10px" for="final_delivery_status" class="noselect">
                                            @lang('site.final_delivery_status')
                                        </label>
                                    </div>
                                    <div class="col-md-4">

                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="social_insurance_status"
                                               {{ $purchaseorder->social_insurance_status ? 'checked' : '' }}
                                               id="social_insurance_status">
                                        <label style=" padding-left: 10px" for="social_insurance_status"
                                               class="noselect">
                                            @lang('site.social_insurance_status')
                                        </label>
                                    </div>
                                    <div class="col-md-4">

                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="labor_insurance_status"
                                               {{ $purchaseorder->labor_insurance_status ? 'checked' : '' }}
                                               id="labor_insurance_status">
                                        <label style=" padding-left: 10px" for="labor_insurance_status"
                                               class="noselect">
                                            @lang('site.labor_insurance_status')
                                        </label>
                                    </div>

                                    <div class="col-md-4">

                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="tax_exemption_certificate_status"
                                               {{ $purchaseorder->tax_exemption_certificate_status ? 'checked' : '' }}
                                               id="tax_exemption_certificate_status">
                                        <label style=" padding-left: 10px" for="tax_exemption_certificate_status"
                                               class="noselect">
                                            @lang('site.tax_exemption_certificate_status')
                                        </label>
                                    </div>
                                    <div class="col-md-4">

                                        <input style="width:15px; float: left; margin-top: 3px;" type="checkbox"
                                               value="1"
                                               name="received_final_performance_bond_status"
                                               {{ $purchaseorder->received_final_performance_bond_status ? 'checked' : '' }}
                                               id="received_final_performance_bond_status">
                                        <label style=" padding-left: 10px" for="received_final_performance_bond_status">
                                            @lang('site.received_final_performance_bond_status')
                                        </label>
                                    </div>

                                </div> <!-- End Of First Row-->

                            </div>
                            <!-- End Of forth Card -->

                    <!-- BEG Of five Card -->
                    <div class="card delivery_details">
                    <h5 class="card-header bg-success">
                        @lang('site.products')
                    </h5>
                    <div>

                    {{-- PO items --}}
                    <div >

                        <div>

                            {{-- add New Item --}}
                            <div class="col-12 text-center items-links my-2">
                                <a data-toggle="modal" data-target="#edit-item" class="addNewItem"><i class="fa fa-plus"></i> @lang('site.add_item')</i></a>
                                <!-- Button trigger modal to set items via excel sheet -->
                                <a href="{{ route('purchaseorders.show_edit_po_add_items_via_excel',$purchaseorder->id) }}" ><i class="fa fa-plus"></i> @lang('site.add_items_via_excel')</a>


                            </div>

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
                                                @lang('site.currency')
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
                                        @php $itemCount = count($purchaseorder->items) @endphp
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
                                                    {{ $item->currency }}
                                                </td>

                                                <td>
                                                    {{ $item->productUnit->name_ar }}
                                                </td>
                                                <td>
                                                    {{ number_format($item->item_price,5) }}
                                                </td>
                                                <td>
                                                    {{ number_format(($item->quantity * $item->item_price),2) }}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{ route('item.show', $item->id) }}"
                                                        class="btn btn-success btn-sm"> <i class="fa fa-eye"></i></a>
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
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-7"></div>
                            <div class="col-5 table2" >
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

                    </div>
                    </div>
                  <!-- End Of five Card -->

                        </div>

                        <div class="fieldset-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" id="submit" class="btn btn-success float-right mr-4">
                                        @lang('site.update') <i class="far fa-save"></i></button>
                                </div>
                            </div>
                        </div>
                </fieldset> <!-- End Of Fieldset-->


            </form> <!-- End Of Form -->

        </div> <!-- End of form container-->

    </section> <!-- End of main section-->




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
        validationMessages['delivery_country_origin'] = "@lang('site.validate_delivery_country_origin_message')";
    </script>
    <script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script>
        $("#PO-edit").validate({
            errorPlacement: function errorPlacement(error, element) {
                element.after(error);
            },
            rules: {
                client_type: {
                    required: true,
                },
                client_id: {
                    required: true,
                },
                type: {
                    required: true
                },
                purchase_order_reference: {
                    required: true,
                },
                project_name: {
                    required: true
                },
                project_number: {
                    required: true
                },
                project_contract_number: {
                    required: true
                },
                payment_terms: {
                    required: true
                },
                bank_id: {
                    required: true
                },
                delivery_approach: {
                    required: true
                },
                delivery_terms: {
                    required: true
                },
                items_counter: {
                    required: true
                },
                delivery_country_origin: {
                    required: true
                },
            },
            messages: {
                client_type: {
                    required: validationMessages['client_type']
                },
                client_id: {
                    required: validationMessages['client_id']
                },
                type: {
                    required: validationMessages['type']
                },
                purchase_order_reference: {
                    required: validationMessages['purchase_order_reference']
                },
                project_name: {
                    required: validationMessages['project_name']
                },
                project_number: {
                    required: validationMessages['project_number']
                },
                project_contract_number: {
                    required: validationMessages['project_contract_number']
                },
                payment_terms: {
                    required: validationMessages['payment_terms']
                },
                bank_id: {
                    required: validationMessages['bank_id']
                },
                delivery_approach: {
                    required: validationMessages['delivery_approach']
                },
                delivery_terms: {
                    required: validationMessages['delivery_terms']
                },
                items_counter: {
                    required: validationMessages['items_counter']
                },
                delivery_country_origin: {
                    required: validationMessages['delivery_country_origin']
                },
            },
            onfocusout: function (element) {
                $(element).valid();
            },
        });
    </script>
    <script>
        $('.search-bank.spinner-border').hide();

        $('#bank_code').keydown(function (e) {
            var key = e.which;
            var searchContent = $(this).val();
            if (key == 13) {
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
                            $('.vaild-client-register-tax').text(
                                " There are no results, check again!");
                        }
                    },
                    error: function () {
                        $('.vaild-client-register-tax').text("Error occured!");
                    },
                    complete: function () {
                        $('.search-bank.spinner-border').hide();
                    }
                });
            }
        });

        $("input[type='file']").change(function () {
            $('#val').text(this.value.replace(/C:\\fakepath\\/i, ''));
        })

        // Check purchase order reference
        let purchaseOrderReferenceErrorMessage = '';
        $('#purchase_order_reference').on('focusout', function () {
            const that = $(this);
            that.val(that.val().trim());

            const data = {
                'purchase_order_reference': $(this).val(),
                'id': $('input[name="id"]').val(),
            }

            if (that.val().trim() == '')
                return;

            if (purchaseOrderReferenceErrorMessage == '') {
                purchaseOrderReferenceErrorMessage = that.parent().find(
                    '.purchase_order_reference_used_before_error').text().trim().split(' ');
            }
            $.ajax({
                type: 'post',
                url: `${subFolderURL}/${urlLang}/purchaseorders/check_purchase_order_reference`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: JSON.stringify(data),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',

                success: function (purchaseOrderReferenceCounter) {
                    if (purchaseOrderReferenceCounter == 0) {
                        that.parent().find('.purchase_order_reference_used_before_error').addClass(
                            'd-none');
                    } else {
                        let errorMessage = [...purchaseOrderReferenceErrorMessage];
                        errorMessage.splice(3, 0,
                            ` (${that.val()}) `); // add entered purchase order reference
                        errorMessage = errorMessage.join(' ');
                        that.val('');
                        that.parent().find('.purchase_order_reference_used_before_error').text(
                            errorMessage).removeClass('d-none');
                    }
                }
            });
        })

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

        const clientId = "{{ $client->id }}";

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
            @if ($purchaseorder->client_type == 'f')
            targetSelector.find(`option[value="${clientId}"]`).attr('selected', true);
            targetSelector.trigger('change');
            @endif
            // targetSelector.find(`input[value="client_id"]`).val(clientId);
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
                        {{--                        url: `${subFolderURL}/${urlLang}`. {{ route('getBusinessOrPersonClientData') }},--}}
                        url: "{{ route('getBusinessOrPersonClientData') }}",


                        data: sendData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            getBusinessOrPersonClientData(response);
                        },
                        error: function () {
                            $('.client-details .text-danger').removeClass('d-none').text(
                                '@lang("site.no_data")');
                        },
                        complete: function () {
                            $('.search-bank.spinner-border').hide();
                        }
                    });
                }
            }
        });

        // set client
        $('#client_type').trigger('change');
        $('#tax_id_number_or_national_id').val(
            "{{ $purchaseorder->client_type == 'b' ? $client->tax_id_number : $client->national_id }}")

        // Get data from Client reference
        $('#client_id').val('');
        let clientType = $('#client_type').val(),
            searchContent = $('#tax_id_number_or_national_id').val().trim(),
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
                {{--url: `${subFolderURL}/${urlLang}`. {{ route('getBusinessOrPersonClientData') }},--}}
                url: "{{ route('getBusinessOrPersonClientData') }}",
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function (client) {
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
    </script>
<script src="{{ asset('invoice/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('invoice/16_9_v1_taxitems/poEditTaxItems.js') }}"></script>

    <script>

$('.sales_amount').on('keyup input change', function () { // price of item

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


  let cannot_add_more_item = "@lang('site.cannot_add_more_item')";
  let purchaseOrderType = "{{ $purchaseorder->type }}";

  $('.search-product.spinner-border').hide();

  // delete item
  $('#item_delete').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var itemId = button.data('item-id');
      $('.modal #item_id').val(itemId);
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



  $(document).ready(function () {
            $('.main_project').select2();


        });


</script>
@endsection
