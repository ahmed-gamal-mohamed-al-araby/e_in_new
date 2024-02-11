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
            display:grid;
            position:fixed;
            top:0;
            left:0;
            bottom:0;
            right:0;
            border:solid;
            background: rgba(0, 0, 0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>

    {{-- Custom Styles --}}
    @if (Config::get('app.locale') == 'ar')
        <style>
            .date {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

            .flex_dir {
                flex-direction: row-reverse
            }

        </style>
    @endif
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

    <div class="card show-one-request textDirection">
        <div class="card-header parent">
            <h3>@lang('site.show_purchaseorder') </h3>
        </div>

        <div class="card-body show-request-id">
            <div class="mb-2">
                <div class="tab-content" id="pills-tabContent">
                    {{-- PO items --}}
                    <div class="tab-pane fade show active" id="po-items" role="tabpanel" aria-labelledby="po-items-tab">
                        <div class="card">
                            <h5 class="card-header bg-success">
                                @lang('site.products')
                            </h5>

                            {{-- add New Item --}}
                            <div class="col-12 text-center items-links my-2">
                                <a href="#" data-toggle="modal" data-target="#addline" class="addNewItem"
                                    id="_addNewItemBtn"><i class="fa fa-plus"></i> @lang('site.add_item')</a>

                                <!-- Button trigger modal to set items via excel sheet -->
                                <a href="#" data-toggle="modal" id="_items_excel_button_saveBtn"
                                    data-target="#addItemsViaExcel"><i class="fa fa-plus"></i>
                                    @lang('site.add_items_via_excel')</a>
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
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" id="submit-new-items">
                    <i class="fa fa-save"></i>
                    @lang('site.save')
                </button>
                <p class="text-danger text-bold d-none mt-2 mb-0 validate_items_counter_message">@lang('site.validate_items_counter_message')</p>
            </div>
        </div>
    </div>

    <!-- Modal to add item -->
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
                                    <option disabled selected>@lang('site.capital_select') @lang('site.currency')</option>
                                    <option value="EGP">EGP</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
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
                                        placeholder="@lang('site.quantity')" class="quantity input-group-item" />
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
                                        class="item_price input-group-item" placeholder="@lang('site.unit_price')">
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
                                    <div class="tax-items d-none">
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
                                                    <div> <input type="hidden" name="items[]" value="any"></div>
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
                                    <input type="number" name="total_amount" class=" total_amount form-control" readonly>
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
                                class="custom-file-input w-auto ml-auto" id="items_excel_id"
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
                    <button type="button" class="btn btn-primary" id="items_excel_button_save">@lang('site.save')</button>
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
        setUrl("{{ route('purchaseorders.index') }}")

        let productUnits = {!! json_encode($productUnits) !!},
            numOfTaxes = -1;
        productUnits.forEach(function(element) {
            delete element.name_ar;
        });
        productUnits = productUnits.map(unit => unit.code);

        const PO_id = "{{ $purchaseorder->id }}",
            filePlaceHolder =  "@lang('site.choose') @lang('site.file')";

    </script>

    <script src="{{ asset('invoice/taxItems/addItemViaExcel.js') }}"></script>

    <script>
        $('.search-product.spinner-border').hide();

        // Handle submit items
        $('#submit-new-items').on('click', function () {
            if(items.length >= 1) {
                // Submit new items

                $(this).next().addClass('d-none');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                const purchaseOrder = {
                    PO_id: PO_id,
                    items: items,
                }

                $(this).css("pointer-events", "none");
                $(this).text(language['send_data']);
                $.ajax({
                    url: `${subFolderURL}/${urlLang}/purchaseorders/edit/add-items-via-excel`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(purchaseOrder),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function (POId) {
                        if (PO_id == POId) { // If the purchase order is recorded
                            $(this).text(language['data_sent']);
                            window.location.href = url;
                        } else {
                            $(this).css("pointer-events", "none");
                            $(this).text(language['send_data_error']);
                        }
                    },
                    error: function (request, status, error) {
                        $(this).text(language['send_data_error']);
                    }
                });

                // End of submit new items
            } else {
                $(this).next().removeClass('d-none');
            }
        })

        $('body').on('DOMSubtreeModified', 'tbody', function(){
            if($(this).children().length >= 1) { // check if table is not empty
                $('.validate_items_counter_message').addClass('d-none');
            } else {
                $('.validate_items_counter_message').removeClass('d-none');
            }
        });

    </script>

@endsection
