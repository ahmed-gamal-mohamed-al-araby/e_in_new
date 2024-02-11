@extends('pages.layouts.master')

@section('title')
@lang('site.warranty_checks')
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('invoice/css/stylee.css') }}">
<link rel="stylesheet" href="{{ asset('invoice/css/stylee.css') }}">
<style>
    #edit-btn {
        margin-top: 10px;
    }

    #image {
        margin-top: 35px;
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
                <h1> @lang('site.add') @lang('site.warranty_checks')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.add') @lang('site.warranty_checks') </li>
                    <li class="breadcrumb-item "><a href="{{route('warranty_checks.index')}}"> @lang('site.warranty_checks') </a></li>
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
                <form action="{{route('warranty_checks.store')}}" class="" id="commentForm" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body text-right">
                            @csrf
                            <div class="row">
                                {{-- Purchase Order Client Type --}}
                                <div class="col-md-4 col-12 textDirection">
                                    <div class="input-group ">
                                        <label class="form-label d-block w-100 textDirection" id="order_label">@lang('site.client_type')</label>
                                        <select id='client_type' name="client_type" class="form-control require">
                                            <option selected disabled>@lang('site.select') @lang('site.client_type')
                                            </option>
                                            <option value="b" data-label="@lang('site.tax_id_number_only')" data-validate="@lang('site.validate_Tax_id_number')">
                                                @lang('site.the_businessClient')</option>
                                            <option value="p" data-label="@lang('site.national_id')" data-validate="@lang('site.validate_national_id')">
                                                @lang('site.person_client')</option>
                                            <option value="f" data-label="@lang('site.vat_id')" data-validate="@lang('site.validate_vat_id')">
                                                @lang('site.foreigner_client')</option>
                                        </select>
                                    </div>
                                    @error('client_type')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="col-md-5 col-12 textDirection">
                                    <div class="card-body p-0 client-details d-none">
                                        <div class="row">
                                            <div class="col-md-9 col-12 no-gutters">
                                                {{-- tax_id_number for business client Or national ID person client --}}
                                                <div class="row no-gutters">
                                                    <div class="col-md-11 col-12 input-group">
                                                        <label class="form-label d-block w-100 textDirection">@lang('site.client_name')</label>
                                                        <select id='client_name' style="width: 100%" class="form-control rounded require" disabled>
                                                            <option selected disabled>@lang('site.select')
                                                                @lang('site.client_name')
                                                            </option>

                                                        </select>
                                                        @error('client_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="client_id" id="client_id">
                                            <p class="col-12 text-danger font-weight-bolder d-none pl-2"></p>
                                        </div> <!-- End Of First Row-->

                                    </div> <!-- End Of Card Body-->
                                </div>
                                <!-- end here client -->

                                <div class="col-md-4 form-group">
                                    <label class="form-label" for="">@lang('site.side')</label>
                                    <input value="{{ old('side') }}" type="text" name="side" id="" required class="form-control @error('side') is-invalid @enderror" placeholder="@lang('site.side')">

                                    <!-- Way 2: Display Error Message -->
                                    @error('side')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="col-md-4 purchase_orders" hidden>
                                    <label class="form-label" for="">@lang('site.supply_order')</label>
                                    <select required value="{{ old('supply_order') }}" id="supply_order" class="form-control pt-0 supply_order @error('supply_order') is-invalid @enderror" name="supply_order">
                                        <option selected hidden value="">@lang('site.choose')</option>
                                        @foreach($purchaseOrders as $purchaseOrder)
                                        <option value="{{$purchaseOrder->id}}">{{$purchaseOrder->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-4 supply_order_input" hidden>
                                    <label class="form-label" for="">@lang('site.project_number')</label>
                                    <input readonly required value="{{ old('project_number') }}" type="text" name="project_number" id="po_project_number" class="form-control
                                            @error('project_number') is-invalid @enderror" placeholder="@lang('site.project_number')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('project_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 form-group supply_order_input" hidden>
                                    <label class="form-label" for="">@lang('site.purpose')</label>
                                    <input required value="{{ old('purpose') }}" type="text" name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" placeholder="@lang('site.purpose')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('purpose')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="col-md-4 supply_order_input" hidden>
                                    <label class="form-label" for="">@lang('site.value')</label>
                                    <input required value="{{ old('value') }}" type="text" name="value" id="" class="form-control @error('value') is-invalid @enderror" placeholder="@lang('site.value')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('value')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.type')</label>
                                    <select name="type" required value="{{ old('type') }}" id="type" class="form-control pt-0 type @error('type') is-invalid @enderror" name="type">
                                        <option selected hidden value="">@lang('site.choose')</option>
                                        <option value="authentic_signature">@lang('site.authentic_signature')</option>
                                        <option value="notebook">@lang('site.notebook')</option>
                                        <option value="payment_accepted">@lang('site.payment_accepted')</option>
                                        <option value="bank_deposit">@lang('site.bank_deposit')</option>
                                    </select>

                                </div>



                                <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.bank')</label>
                                    <select required value="{{ old('bank_id') }}" id="bank" class="form-control pt-0 bank_id @error('bank_id') is-invalid @enderror" name="bank_id">
                                        <option selected value="">@lang('site.choose')</option>
                                        @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_name}} - {{$bank->bank_account_number}}</option>
                                        @endforeach
                                    </select>
                                    <input hidden placeholder="@lang('site.bank')" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="form-control pt-0 bank_id @error('bank_id') is-invalid @enderror">
                                </div>

                                <div class="col-md-4 name_on_cheque">
                                    <label class="form-label" for="">@lang('site.name_on_cheque')</label>

                                    <input required placeholder="@lang('site.name_on_cheque')" name="source_name" value="{{ old('name_on_cheque') }}" class="form-control pt-0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" id="cheque_number_name" for="">@lang('site.cheque_number')</label>
                                    <label hidden class="form-label" id="bank_account_number" for="">@lang('site.bank_account_number')</label>

                                    <input type="number" id="cheque_number" required placeholder="@lang('site.cheque_number')" name="cheque_number" value="{{ old('cheque_number') }}" class="form-control pt-0">
                                </div>

                                <div class="col-md-4 ">
                                    <label for="validationCustom02">@lang('site.check_date')</label>
                                    <input id="check_date" type="date" required placeholder="@lang('site.check_date')" class="form-control" name="check_date">

                                    @error('check_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.document_nature')</label>
                                    <select required name="document_nature" value="{{ old('document_nature') }}" class="form-control pt-0">
                                        <option selected value="">@lang('site.choose')</option>
                                        <option value="not_respond">@lang('site.not_respond')</option>
                                        <option value="respond">@lang('site.respond')</option>

                                    </select>

                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="">@lang('site.image')</label>
                                    <input value="{{ old('image') }}" type="file" name="image" id="image" class="@error('image') is-invalid @enderror" required placeholder="@lang('site.image')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                            </div>

                            <div class="col-md-12 form-group">
                                <input type="submit" class="btn btn-success" id="edit-btn" value="@lang('site.save')">
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
        $('.supply_order').select2();


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
        $('.items-from-ajax-load').fadeIn();
        $('#client_id').val('');
        $('.purchase_order_search_result').addClass('d-none');
        $('#tax_id_number_or_national_id_or_vat_id').val('');
        $('#purchase_order').empty();
        $('.items-from-ajax-load').fadeOut(250);

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
        $('#client_id').val($(this).val());
        $('#tax_id_number_or_national_id_or_vat_id').val($(this).val());
        $('#client_address').val($(this).val());
        $('#client_id').trigger('change');
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
        targetName.append(`<option selected disabled>@lang('site.select') @lang('site.client_name')</option>`);
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
            $('#client_id').val(response.id);
            $('#client_id').trigger('keyup');
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

        $('#client_id').val('');
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
                    getBusinessOrPersonClientData(response);
                },
                error: function() {
                    $('.client-details .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                },
                complete: function() {
                    $('.search-bank.spinner-border').hide();
                }
            });
            $('.purchase_orders').prop('hidden', false);
            $("#supply_order").empty();
            $('.supply_order_input').prop('hidden', true);

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

        }
    });

    $('#supply_order').on('change', function() {
        var po_id = $("#supply_order").val();

        $.ajax({
            type: "get",
            url: "{{route('get_supply_order_data')}}",
            data: {
                _token: '{{ csrf_token() }}',
                po_id: po_id,


            },

            success: function(data) {


                $('.supply_order_input').prop('hidden', false);
                $('#purpose').val(data.project_name + '-' + data.purchase_order_reference);
                $('#po_project_number').val(data.project_number);
                $('#value').val('');


            }
        });
    });
    // Client Type
    select2Function($('#client_type'), "@lang('site.client_type')");
    // Client Name
    select2Function($('#foreigner-client'), "@lang('site.client_name')");
    select2Function($('#client_name'), "@lang('site.client_name')");

    select2Function($('#supply_order'), "@lang('site.supply_order')");
    // select2Function($('#bank'), "@lang('site.bank')");
    // $('#bank').select2({
    //         placeholder: '@lang("site.choose") ',
    //     });
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
    $('#type').on('change', function() {
        let typeSelected = $(this).val();
        if (typeSelected == "payment_accepted" || typeSelected == "bank_deposit") {

            $('#bank').prop('required', false);
            $('#bank').prop('hidden', true);
            $('#bank_name').prop('hidden', false);
            $('#bank_name').prop('required', true);



        } else {
            $('#bank').prop('required', true);
            $('#bank').prop('hidden', false);
            $('#bank_name').prop('hidden', true);
            $('#bank_name').prop('required', false);

        }
        if (typeSelected == "bank_deposit") {
            $('.name_on_cheque').prop('hidden', true);
            $('#cheque_number_name').prop('hidden', true);
            $('#bank_account_number').prop('hidden', false);
            $('#cheque_number').attr("placeholder", "@lang('site.bank_account_number')");


        } else {
            $('.name_on_cheque').prop('hidden', false);
            $('#cheque_number_name').prop('hidden', false);
            $('#bank_account_number').prop('hidden', true);
            $('#cheque_number').attr("placeholder", "@lang('site.cheque_number')");


        }
    })

    $('[type="submit"]').on('click', function() {
        if (validate()) {
            submit();
        }
    })

    function validate() {
        $('#from_date').val() ? $('.from_date_error').addClass('d-none') : $('.from_date_error').removeClass('d-none');
        $('#side').val() ? $('.side').addClass('d-none') : $('.side').removeClass('d-none');

        // $('#to_date').val()? $('.to_date_error').addClass('d-none') : $('.to_date_error').removeClass('d-none');
        $('#client_type').val() ? $('.client_type_error').addClass('d-none') : $('.client_type_error').removeClass('d-none');
        $('#client_name').val() ? $('.client_name_error').addClass('d-none') : $('.client_name_error').removeClass('d-none');
        return $('#from_date').val() && $('#client_type').val() && $('#client_name').val() && $('#tax_id_number_or_national_id_or_vat_id').val();
    }

    function prepareDataToSubmit() {
        return {
            'clientType': $('#client_type').val() || null,
            'clientId': $('#client_id').val() || null,
        }
    }


    function fixedTo5($number) {
        return +(Number($number).toFixed(5));
    }
</script>
@endsection