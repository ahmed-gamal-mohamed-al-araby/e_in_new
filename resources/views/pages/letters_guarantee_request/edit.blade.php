@extends('pages.layouts.master')

@section('title')
@lang('site.letter_guarantee_request')
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('invoice/css/stylee.css') }}">
<style>
    #edit-btn {
        margin-top: 10px;
    }

    #image {
        margin-top: 30px;
    }

    #link {
        margin-top: 30px;
    }
</style>
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> @lang('site.edit') @lang('site.letter_guarantee_request')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.edit') @lang('site.letter_guarantee_request') </li>
                    <li class="breadcrumb-item "><a href="{{route('letter_guarantee_request.index')}}"> @lang('site.letter_guarantee_request') </a></li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content service-content user-edit-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 form-group">
                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif

                @if(count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="col-12">
                <form action="{{route('letter_guarantee_request.update',$letter_guarantee_request->id)}}" method="POST" enctype="multipart/form-data" id="commentForm">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body text-right">
                            @csrf
                            <div class="row">

                            <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.type')</label>
                                    <select required id="type" class="form-control pt-0 type @error('type') is-invalid @enderror" name="type">
                                        <option selected disabled>@lang('site.choose') </option>
                                        <option value="prepaid" {{ $letter_guarantee_request->type == 'prepaid'? 'selected': '' }}>@lang('site.prepaid')</option>
                                        <option value="primary_insurance" {{ $letter_guarantee_request->type == 'primary_insurance'? 'selected': '' }}>@lang('site.primary_insurance')</option>
                                        <option value="final_insurance" {{ $letter_guarantee_request->type == 'final_insurance'? 'selected': '' }}>@lang('site.final_insurance')</option>
                                        <option value="instant" {{ $letter_guarantee_request->type == 'instant'? 'selected': '' }}>@lang('site.instant')</option>
                                        <option value="maritime" {{ $letter_guarantee_request->type == 'maritime'? 'selected': '' }}>@lang('site.maritime')</option>
                                         <option value="financial" {{ $letter_guarantee_request->type == 'financial'? 'selected': '' }}>@lang('site.financial')</option>
                                    </select>
                                </div>

                                {{-- Purchase Order Client Type --}}
                                <div class="col-md-4 col-12 textDirection">
                                    <div class="input-group ">
                                        <label class="form-label d-block w-100 textDirection" id="order_label">@lang('site.client_type')</label>
                                        <select id='client_type' name="client_type" class="form-control require">
                                            <option selected disabled>@lang('site.select') @lang('site.client_type')
                                            </option>
                                            <option value="b" {{ $letter_guarantee_request->client_type == "b" ? 'selected': '' }} data-label="@lang('site.tax_id_number_only')" data-validate="@lang('site.validate_Tax_id_number')">
                                                @lang('site.the_businessClient')</option>
                                            <option value="p" {{ $letter_guarantee_request->client_type == "p" ? 'selected': '' }} data-label="@lang('site.national_id')" data-validate="@lang('site.validate_national_id')">
                                                @lang('site.person_client')</option>
                                            <option value="f" {{ $letter_guarantee_request->client_type == "f" ? 'selected': '' }} data-label="@lang('site.vat_id')" data-validate="@lang('site.validate_vat_id')">
                                                @lang('site.foreigner_client')</option>
                                        </select>
                                    </div>
                                    @error('client_type')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="col-md-4 col-12 textDirection">
                                    <div class="card-body p-0 client-details ">
                                        <div class="row">
                                            <div class="col-md-9 col-12 no-gutters">
                                                {{-- tax_id_number for business client Or national ID person client --}}
                                                <div class="row no-gutters">
                                                    <div class="col-md-11 col-12 input-group">
                                                        <label class="form-label d-block w-100 textDirection">@lang('site.client_name')</label>
                                                        <select id='client_name' style="width: 100%" name="client_id" class="form-control rounded ">

                                                            <option selected disabled>@lang('site.select')
                                                                @lang('site.client_name')
                                                            </option>
                                                            
                                                            @if($letter_guarantee_request->client_type == 'f')
                                                            @foreach($allClients as $singleClient)
                                                            <option value="{{$singleClient->id}}">{{$singleClient->company_name}}</option>
                                                            @endforeach
                                                            @if(isset($client->id))
                                                            <option value="{{$client->id}}" {{ $letter_guarantee_request->client_id == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                                                            @else
                                                            <option value="-1" selected>@lang('site.add') @lang('site.client_name')</option>
                                                            @endif
                                                            @else
                                                            @foreach($allClients as $singleClient)
                                                            <option value="{{$singleClient->id}}">{{$singleClient->name}}</option>
                                                            @endforeach
                                                            @if(isset($client->id))
                                                            <option value="{{$client->id}}" {{ $letter_guarantee_request->client_id == $client->id ? 'selected': '' }}>{{$client->name}}</option>
                                                            @if($letter_guarantee_request->type == 'primary_insurance')
                                                            <option value="-1">@lang('site.add') @lang('site.client_name')</option>
                                                            @endif
                                                            @else
                                                            <option value="-1" selected>@lang('site.add') @lang('site.client_name')</option>
                                                            @endif
                                                            @endif
                                                            
                                                        </select>
                                                        @error('client_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="client_id2" id="client_id2">

                                            <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                        </div> <!-- End Of First Row-->

                                    </div> <!-- End Of Card Body-->

                                </div>
                                <!-- end here client -->

                                @if(isset($letter_guarantee_request->client_name))
                                <div class="col-md-4 new_client">
                                    <label class="form-label" for="">@lang('site.client_name')</label>
                                   
                                    <input type="text" name="new_client_name" placeholder="@lang('site.client_name')" value="{{ $letter_guarantee_request->client_name}}" id="new_client_name" class="form-control">
                                </div>

                                <div class="col-md-8 new_client">
                                    <label class="form-label" for="">@lang('site.address')</label>
                                   
                                    <input type="text" name="new_client_address" placeholder="@lang('site.address')" value="{{ $letter_guarantee_request->client_address}}" id="new_client_address" class="form-control">
                                </div>

                                @else

                                <div class="col-md-4 new_client" hidden>
                                    <label class="form-label" for="">@lang('site.client_name')</label>
                                   
                                    <input type="text" name="new_client_name" placeholder="@lang('site.client_name')" value="{{old('new_client_name')}}" id="new_client_name" class="form-control">
                                </div>

                                <div class="col-md-8 new_client" hidden>
                                    <label class="form-label" for="">@lang('site.address')</label>
                                   
                                    <input type="text" name="new_client_address" placeholder="@lang('site.address')" value="{{old('new_client_address')}}" id="new_client_address" class="form-control">
                                </div>

                                @endif
                              
                               
                                <div class="col-md-4 purchase_orders">
                                    <label class="form-label" for="">@lang('site.vat_amount_T1')</label>
                                    <select id="supply_order_tax" class="form-control" required name="supply_order_tax">

                                    <option value="" disabled selected>@lang('site.choose')</option>
                                    <option value="comprehensive" @if($letter_guarantee_request->supply_order_tax=="comprehensive") selected @endif>@lang('site.comprehensive')</option>
                                    <option value="excl" @if($letter_guarantee_request->supply_order_tax=="excl") selected @endif>@lang('site.excl')</option>
                                    </select>
                                </div>


                                @if(isset($letter_guarantee_request->supply_order))
                                <div class="col-md-4 purchase_orders" >
                                    <label class="form-label" for="">@lang('site.supply_order')</label>
                                   

                                    <select value="{{ $letter_guarantee_request->supply_order }}" id="supply_order" class="form-control pt-0 supply_order @error('supply_order') is-invalid @enderror" name="supply_order">
                                        <option selected hidden value="">@lang('site.choose')</option>
                                        @foreach($purchaseOrders as $purchaseOrder)
                                        <option value="{{$purchaseOrder->id}}" {{ $letter_guarantee_request->supply_order == $purchaseOrder->id ? 'selected': '' }}>{{$purchaseOrder->purchase_order_reference}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" hidden name="supply_order_name" placeholder="@lang('site.supply_order')" value="{{$letter_guarantee_request->supply_order_name}}" id="supply_order_name" class="form-control">

                                   
                                 
                                </div>
                                @else
                                <div class="col-md-4 purchase_orders" >
                                    <label class="form-label" for="">@lang('site.supply_order')</label>
                                   
                                    <input type="text" name="supply_order_name" placeholder="@lang('site.supply_order')" value="{{$letter_guarantee_request->supply_order_name}}" id="supply_order_name" class="form-control">
                                    <select hidden value="{{ $letter_guarantee_request->supply_order }}" id="supply_order" class="form-control pt-0 supply_order @error('supply_order') is-invalid @enderror" name="supply_order">
                                        <option selected hidden value="">@lang('site.choose')</option>
                                        @foreach($purchaseOrders as $purchaseOrder)
                                        <option value="{{$purchaseOrder->id}}" {{ $letter_guarantee_request->supply_order == $purchaseOrder->id ? 'selected': '' }}>{{$purchaseOrder->purchase_order_reference}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.main_project_name')</label>
                                    <input class="form-control" type="text" name="main_project_name" id="main_project_name" @if(isset($letter_guarantee_request->supply_order)) value="{{$purchaseOrder12->project->name_ar}}" readonly @else value="{{$letter_guarantee_request->main_project_name}}" @endif>
                                </div>
                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.po_project_name')</label>
                                    <input class="form-control" name="project_name" type="text" id="po_project_name" @if(isset($letter_guarantee_request->supply_order)) value="{{$purchaseOrder12->project_name}}" readonly @else value="{{$letter_guarantee_request->project_name}}" @endif>
                                </div>
                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.po_project_number')</label>
                                    <input class="form-control" name="project_number" type="text" id="po_project_number" @if(isset($letter_guarantee_request->supply_order)) value="{{$purchaseOrder12->project_number}}" readonly @else value="{{$letter_guarantee_request->project_number}}" @endif>
                                </div>
                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.total_amount')</label>
                                    <input class="form-control" name="total_amount" type="text" id="total_amount" @if(isset($letter_guarantee_request->supply_order)) value="{{$sum}}" readonly @else value="{{$letter_guarantee_request->total_amount}}" @endif>
                                </div>

                                <!-- <input type="hidden" name="user_id" value="{{auth()->user()->id}}"> -->
                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.purchase_order_percentage')</label>
                                    <input value="{{$letter_guarantee_request->purchase_order_percentage}}" type="number" max="100" min="0" name="purchase_order_percentage" id="purchase_order_percentage" class="form-control @error('purchase_order_percentage') is-invalid @enderror" placeholder="@lang('site.purchase_order_percentage')">

                                    <!-- Way 2: Display Error Message -->
                                    @error('purchase_order_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="col-md-4 supply_order_input">
                                    <label class="form-label" for="">@lang('site.letter_guarantee_value')</label>
                                    <input required value="{{ $letter_guarantee_request->value }}" type="text" name="value" id="value" class="form-control @error('value') is-invalid @enderror" placeholder="@lang('site.letter_guarantee_value')" @if(isset($letter_guarantee_request->supply_order)) readonly @else  @endif>
                                    <!-- Way 2: Display Error Message -->
                                    @error('value')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                             



                                <div class="col-md-4 ">
                                    <label for="validationCustom02">@lang('site.release_date')</label>
                                    <input value="{{ $letter_guarantee_request->release_date }}" min=<?php echo date('Y-m-d'); ?> id="release_date" type="date" required placeholder="@lang('site.release_date')" class="form-control" name="release_date">

                                    @error('release_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.duration_in_month')</label>
                                    <input class="form-control" value="{{ $letter_guarantee_request->duration_in_month }}" type="number" min="0" id="duration_in_month" name="duration_in_month">

                                    @error('duration_in_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 ">
                                    <label class="form-label" for="">@lang('site.expiry_date')</label>

                                    <input id="expiry_date" type="date" required readonly placeholder="@lang('site.expiry_date')" id="" class="form-control @error('image') is-invalid @enderror" name="expiry_date" value="{{ $letter_guarantee_request->expiry_date }}">

                                    @error('expiry_date')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                    @enderror

                                </div>



                            </div>

                            <div class="col-md-12 form-group">
                                <input type="submit" id="edit-btn" class="btn btn-success" value="@lang('site.edit')">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>


{{-- Client section --}}
<script type="text/javascript">
    $("#commentForm").validate();

    $(document).ready(function() {
        // $('.supply_order').select2();
        $('#supply_order_tax').select2();

    });

    $('#supply_order_tax').on('change', function() {
        $("#supply_order").empty();

        let clientType = $('#client_type').val();
        let client_id = $('#client_name').val();
        sendData = {
            clientType: clientType,
            client_id: client_id,
        };
        $.ajax({
                type: 'get',
                url: "{{ route('getSupplyOrder') }}",
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    $("#supply_order").append(`<option value="">@lang('site.choose')</option>`);

                    data.forEach(function(pro) {
                        $("#supply_order").append(`<option value="${pro.id}">${pro.purchase_order_reference}</option>`);
                    });
                },

            });
    });
    // $('.supply_order').select2({
    //     placeholder: 'Select an item',
    //     minimumInputLength: 3,
    //     ajax: {
    //         url: '/select2-autocomplete-ajax',
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function (data) {
    //             return {
    //                 results:  $.map(data, function (item) {
    //                     return {
    //                         text: item.project_name,
    //                         id: item.id
    //                     }
    //                 })
    //             };
    //         },
    //         cache: true
    //     }
    // });
</script>
<script>
    $('.search-bank.spinner-border').hide();

    // Handle Client Type Event (Select)
    $('#client_type').on('change', function() {
        $('#client_type').val() ? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass('d-none');


        let selectValue = $(this).val();
        // check selector value
        if (selectValue == 'b' || selectValue == 'p' || selectValue == 'f') {
            $('.client-details').removeClass('d-none');
            $('.select-foreigner-client').addClass('d-none');
            $('#client-id-container').insertBefore(".client-details .row .text-danger");
            let labelOrInputParent = $('#tax_id_number_or_national_id_or_vat_id').parent();
            // get data(label and name) value in option selected
            let dataLabel = $(this).find('option:selected').data('label');
            // change label text and input name
            labelOrInputParent.find('label').text(dataLabel);
        }
        let targetName = $('#client_name');

        if (targetName != '') {
            const urlInputType = $(this).val();
            const url = `${subFolderURL}/${urlLang}/reports/getClientsFromclientType`;
            if ($(this).val() == 'b' || $(this).val() == 'p' || $(this).val() == 'f')
                sendAjax('GET', url, urlInputType, targetName, getBusinessOrPersonClientDataByName)
        }
    });





    // Handle Client Name Event (Select)
    $('#client_name').on('change', function() {
        $('#client_id2').val($(this).val());
        $('#tax_id_number_or_national_id_or_vat_id').val($(this).val());
        $('#client_address').val($(this).val());
        $('#client_id2').trigger('change');
        $('#tax_id_number_or_national_id_or_vat_id').trigger('change');
        $('#client_address').trigger('change');
        // $('#purchase_order').trigger('change');
    })

    function sendAjax(method, url, urlInputType, target, successFunction) {
        target.attr('disabled', true);
        $.ajax({
            type: method,
            data: {
                clientType: urlInputType,
            },
            url: `${url}`,
            success: function(response) {
                successFunction(response, target);
            }
        });
    }

    function sendPostAjax(method, url, clientType, urlInputId, target, successFunction) {
        target.attr('disabled', true);
        $.ajax({
            type: method,
            data: {
                clientType: clientType,
                urlInputId: urlInputId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'JSON',
            url: `${url}`,
            success: function(response) {
                successFunction(response, target);
            }
        });
    }

    // Get Clients Data To Select
    function getBusinessOrPersonClientDataByName(response, targetName) {
        targetName.attr('disabled', false);
        var response = JSON.parse(response);
        targetName.empty();
        if ($('#type').val()=="primary_insurance") {
            targetName.append(`<option value="-1" >@lang('site.add') @lang('site.client_name')</option>`);
            targetName.append(`<option selected disabled>@lang('site.select') @lang('site.client_name')</option>`);
        }
        else{
            targetName.append(`<option selected disabled>@lang('site.select') @lang('site.client_name')</option>`);
        }
        for (const key in response) {
            if (response.hasOwnProperty.call(response, key)) {
                targetName.append(
                    `<option value="${key}">${response[key]}</option>`
                );
            }
        }
    }


    // Add Client Data To Blade
    function getBusinessOrPersonClientData(response) {
        if (response) {
            $('#client_id2').val(response.id);
            $('#client_id2').trigger('keyup');
            $('#tax_id_number_or_national_id_or_vat_id').val(response.tax_id_number_or_national_id_or_vat_id);
            $('#tax_id_number_or_national_id_or_vat_id').trigger('keyup');
        } else {
            
            $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
            $('#tax_id_number_or_national_id_or_vat_id').val('');
        }
        $('.search-bank.spinner-border').hide();
    }

    // Event To Handle Client Name
    $('#client_name').change(function(e) {
        $('#client_name').val() ? $('.client_name_error').addClass('d-none') : $('.client_name_error').removeClass('d-none');

        $('#client_id2').val('');
        $('.purchase_order_search_result').addClass('d-none');
        $('.client-details .text-danger').addClass('d-none');
        let clientType = $('#client_type').val();
        let client_id = $('#client_name').val();
        searchContent = $(this).val();
        sendData = {
            clientType: clientType,
            searchContent: searchContent,
            client_id: client_id,
        };
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if ($(this).val() != null) {
            $('.search-bank.spinner-border').show();
            $.ajax({
                type: 'POST',
                url: "{{ route('getBusinessOrPersonClientDataByName') }}",
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(response) {
                    $('.new_client').prop('hidden', true);

                    getBusinessOrPersonClientData(response);
                },
                error: function() {
                    $('.new_client').prop('hidden', false);

                    // $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                },
                complete: function() {
                    $('.search-bank.spinner-border').hide();
                }
            });
            $('.purchase_orders').prop('hidden', false);
            $("#supply_order").empty();
            // $('.supply_order_input').prop('hidden', true);

            $.ajax({
                type: 'get',
                url: "{{ route('getSupplyOrder') }}",
                data: sendData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function (data) {
                    $("#supply_order").append(`<option value="">@lang('site.choose')</option>`);

                    data.forEach(function (pro) {
                        $("#supply_order").append(`<option value="${pro.id}">${pro.purchase_order_reference}</option>`);
                    });
                },            
              
            });
        }
    });

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
        @if($letter_guarantee_request -> client_type == 'f')
        targetSelector.find(`option[value="${clientId}"]`).attr('selected', true);
        targetSelector.trigger('change');
        @endif
        // targetSelector.find(`input[value="client_id"]`).val(clientId);
    }



    // Client Type
    select2Function($('#client_type'), "@lang('site.client_type')");
    // Client Name
    select2Function($('#foreigner-client'), "@lang('site.client_name')");
    select2Function($('#client_name'), "@lang('site.client_name')");
    // select2Function($('#supply_order'), "@lang('site.supply_order')");
    select2Function($('#bank'), "@lang('site.bank')");
    select2Function($('#type'), "@lang('site.type')");

    function select2Function(selector, placeholder) {
        const targetSelector = $(selector).parent().parent().next().find('select');
        $(selector).select2();
        $(selector).select2({
            allowClear: true,
            placeholder: placeholder,
        });
    }
</script>
{{-- Submit section --}}
<script>
    $('#deduction_id').on('change', function() {
        $('.deduction_id_error').addClass('d-none');
    })

    $('#purchase_order_percentage').on('keyup', function() {

        $('#value').val(Math.ceil($(this).val() / 100 * $('#total_amount').val()));


    })

    $('#total_amount').on('keyup', function() {

$('#purchase_order_percentage').val("");
$('#value').val("");

})
    $('#value').on('keyup', function() {

$('#purchase_order_percentage').val("");
$('#total_amount').val("");

})

    $('#duration_in_month').on('keyup', function() {
        var duration = $(this).val();
        var release_date = $('#release_date').val();
        var expiry_date = addMonths(new Date(release_date), duration).toString();
        $('#expiry_date').val(new Date(expiry_date).toISOString().substring(0, 10));

    })

    $('#release_date').on('change', function() {
      $('#duration_in_month').val("");
      $('#expiry_date').val("");
      
    })

    $('#type').on('change', function() {
        var type = $(this).val();
        $('#client_type').prop('selectedIndex',-1).trigger( "change" );
        $('#client_name').prop('selectedIndex',-1).trigger( "change" );

        if (type == "primary_insurance") {

            $('.purchase_orders').prop('hidden', false);
            $('#supply_order').prop('hidden', true);
            $('#supply_order_name').prop('hidden', false);
            $('.supply_order_input').prop('hidden', false);
            // $('.new_client').prop('hidden', true);

            $('#new_client_name').val('');
            $('#new_client_address').val('');
            $('#supply_order').val('');
            $('#supply_order_name').val('');
            $('#main_project_name').val('');
            $('#po_project_name').val('');
            $('#po_project_number').val('');
            $('#total_amount').val('');
            $('#purchase_order_percentage').val('');
            $('#value').val('');

            $('#main_project_name').attr('readonly', false);
            $('#po_project_name').attr('readonly', false);
            $('#po_project_number').attr('readonly', false);
            $('#total_amount').attr('readonly', false);
            $('#value').attr('readonly', false);

         
          
        } else {

            $('.purchase_orders').prop('hidden', false);
            $('#supply_order').prop('hidden', false);
            $('#supply_order_name').prop('hidden', true);
            $('.supply_order_input').prop('hidden', true);
            $('.new_client').prop('hidden', true);

            $('#new_client_name').val('');
            $('#new_client_address').val('');
            $('#supply_order').val('');
            $('#supply_order_name').val('');
            $('#main_project_name').val('');
            $('#po_project_name').val('');
            $('#po_project_number').val('');
            $('#total_amount').val('');
            $('#purchase_order_percentage').val('');
            $('#value').val('');

            $('#main_project_name').attr('readonly', true);
            $('#po_project_name').attr('readonly', true);
            $('#po_project_number').attr('readonly', true);
            $('#total_amount').attr('readonly', true);
        }
    })

    function addMonths(date, months) {
        var d = date.getDate();
        date.setMonth(date.getMonth() + +months);
        if (date.getDate() != d) {
            date.setDate(0);
        }
        return date;
    }

    $('#supply_order').on('change', function() {
        var po_id = $("#supply_order").val();
        var po_tax = $("#supply_order_tax").val();

        $.ajax({
            type: "get",
            url: "{{route('get_supply_order_data')}}",
            data: {
                _token: '{{ csrf_token() }}',
                po_id: po_id,
                po_tax: po_tax,


            },

            success: function(data) {

                console.log(data);
                $('.supply_order_input').prop('hidden', false);
                $('#main_project_name').val(data.main_project_name);
                $('#po_project_name').val(data.project_name);
                $('#po_project_number').val(data.project_number);
                $('#total_amount').val(data.sum);
                $('#purchase_order_percentage').val('');
                $('#value').val('');


            }
        });
    });

    $('[type="submit"]').on('click', function() {
        if (validate()) {
            submit();
        }
    })



    function prepareDataToSubmit() {
        return {
            'clientType': $('#client_type').val() || null,
            'clientId': $('#client_id2').val() || null,
        }
    }


    function fixedTo5($number) {
        return +(Number($number).toFixed(5));
    }
</script>


<script>



</script>
@endsection