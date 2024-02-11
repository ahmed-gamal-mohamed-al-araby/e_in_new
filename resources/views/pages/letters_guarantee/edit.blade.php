@extends('pages.layouts.master')

@section('title')
    @lang('site.letter_guarantee')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/stylee.css') }}">
    <style>
    #edit-btn{
        margin-top: 10px;
    }
    #image{
        margin-top: 30px;
    }
    #link{
        margin-top: 30px;
    }
    </style>
@endsection
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>  @lang('site.edit_letter')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.edit_letter')  </li>
                        <li class="breadcrumb-item "><a
                                href="{{route('letter_guarantee.index')}}">  @lang('site.letter_guarantee') </a></li>
                        <li class="breadcrumb-item"><a href="{{route('home')}}">  @lang('site.home')</a></li>
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
                    <form action="{{route('letter_guarantee.update',$letter_guarantee->id)}}" method="POST" enctype="multipart/form-data" id="commentForm">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body text-right">
                                @csrf
                                <div class="row">
                                    {{-- Purchase Order Client Type --}}
                                    <div class="col-md-4 col-12 textDirection">
                                        <div class="input-group ">
                                            <label class="form-label d-block w-100 textDirection"
                                                   id="order_label">@lang('site.client_type')</label>
                                            <select id='client_type' name="client_type" class="form-control require">
                                                <option selected disabled>@lang('site.select') @lang('site.client_type')
                                                </option>
                                                <option value="b" {{ $letter_guarantee->client_type == "b" ? 'selected': '' }} data-label="@lang('site.tax_id_number_only')"
                                                        data-validate="@lang('site.validate_Tax_id_number')">
                                                    @lang('site.the_businessClient')</option>
                                                <option value="p" {{ $letter_guarantee->client_type == "p" ? 'selected': '' }} data-label="@lang('site.national_id')"
                                                        data-validate="@lang('site.validate_national_id')">
                                                    @lang('site.person_client')</option>
                                                <option value="f" {{ $letter_guarantee->client_type == "f" ? 'selected': '' }} data-label="@lang('site.vat_id')"
                                                        data-validate="@lang('site.validate_vat_id')">
                                                    @lang('site.foreigner_client')</option>
                                            </select>
                                        </div>
                                        @error('client_type')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="col-md-5 col-12 textDirection">
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
                                              @if($letter_guarantee->client_type == 'f')
                                           <option value="{{$client->id}}" {{ $letter_guarantee->client_id == $client->id ? 'selected': '' }}>{{$client->company_name}}</option>
                                                @else
                                                  <option value="{{$client->id}}" {{ $letter_guarantee->client_id == $client->id ? 'selected': '' }}>{{$client->name}}</option>
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

                                    <div class="col-md-4 form-group">
                                        <label class="form-label" for="">@lang('site.side')</label>
                                        <input value="{{ $letter_guarantee->side }}"
                                               type="text" name="side" id="" required
                                               class="form-control @error('side') is-invalid @enderror"
                                               placeholder="@lang('site.side')">

                                        <!-- Way 2: Display Error Message -->
                                        @error('side')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                

                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.supply_order')</label>
                                        <select required
                                                value="{{ $letter_guarantee->supply_order }}" id=""
                                                class="form-control pt-0 supply_order @error('supply_order') is-invalid @enderror"
                                                name="supply_order">
                                            <option selected hidden value="">@lang('site.choose')</option>
                                            @foreach($purchaseOrders as $purchaseOrder)
                                                <option value="{{$purchaseOrder->id}}" {{ $letter_guarantee->supply_order == $purchaseOrder->id ? 'selected': '' }}>{{$purchaseOrder->project_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.project_number')</label>
                                        <input required
                                               value="{{ $letter_guarantee->project_number }}"
                                               type="text" name="project_number" id="" class="form-control
                                            @error('project_number') is-invalid @enderror"
                                               placeholder="@lang('site.project_number')">
                                        <!-- Way 2: Display Error Message -->
                                        @error('project_number')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label class="form-label" for="">@lang('site.purpose')</label>
                                        <input required  value="{{ $letter_guarantee->purpose }}" type="text" name="purpose"
                                               id=""
                                               class="form-control @error('purpose') is-invalid @enderror"
                                               placeholder="@lang('site.purpose')">
                                        <!-- Way 2: Display Error Message -->
                                        @error('purpose')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.letter_guarantee_value')</label>
                                        <input required  value="{{ $letter_guarantee->value }}"
                                               type="text"
                                               name="value"
                                               id=""
                                               class="form-control @error('value') is-invalid @enderror"
                                               placeholder="@lang('site.letter_guarantee_value')">
                                        <!-- Way 2: Display Error Message -->
                                        @error('value')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.cash_margin')</label>
                                        <input required value="{{ $letter_guarantee->cash_margin }}" type="text"
                                               name="cash_margin"
                                               id=""
                                               class="form-control @error('cash_margin') is-invalid @enderror"
                                               placeholder="@lang('site.cash_margin')">
                                        <!-- Way 2: Display Error Message -->
                                        @error('cash_margin')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.type')</label>
                                        <select required id="type" class="form-control pt-0 type @error('type') is-invalid @enderror" name="type">
                                            <option selected disabled>@lang('site.choose') </option>
                                            <option value="prepaid" {{ $letter_guarantee->type == 'prepaid'? 'selected': '' }}>@lang('site.prepaid')</option>
                                            <option value="primary_insurance" {{ $letter_guarantee->type == 'primary_insurance'? 'selected': '' }}>@lang('site.primary_insurance')</option>
                                            <option value="final_insurance" {{ $letter_guarantee->type == 'final_insurance'? 'selected': '' }}>@lang('site.final_insurance')</option>
                                            <option value="instant" {{ $letter_guarantee->type == 'instant'? 'selected': '' }}>@lang('site.instant')</option>
                                            <option value="maritime" {{ $letter_guarantee->type == 'maritime'? 'selected': '' }}>@lang('site.maritime')</option>
                                            <option value="financial" {{ $letter_guarantee->type == 'financial'? 'selected': '' }}>@lang('site.financial')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="">@lang('site.bank')</label>

                                        <select required value="{{ $letter_guarantee->bank_id }}" id="bank"
                                                class="form-control pt-0 bank_id @error('bank_id') is-invalid @enderror"
                                                name="bank_id">
                                            <option selected hidden value="">@lang('site.choose')</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}" {{ $letter_guarantee->bank_id == $bank->id ? 'selected': '' }}>{{$bank->bank_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"
                                               for="">@lang('site.letter_guarantee_num')</label>
                                        <input value="{{ $letter_guarantee->letter_guarantee_num }}" required
                                               type="text"
                                               name="letter_guarantee_num"
                                               id=""
                                               class="form-control @error('letter_guarantee_num') is-invalid @enderror"
                                               placeholder="@lang('site.letter_guarantee_num')">
                                        <!-- Way 2: Display Error Message -->
                                        @error('letter_guarantee_num')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>





                                    <div class="col-md-4 ">
                                        <label for="validationCustom02">@lang('site.release_date')</label>
                                        <input value="{{ $letter_guarantee->release_date }}" id="release_date" type="date" required
                                               placeholder="@lang('site.release_date')" class="form-control"
                                               name="release_date">

                                        @error('release_date')
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>


                                    <div class="col-md-4 ">
                                        <label class="form-label" for="">@lang('site.expiry_date')</label>

                                        <input id="expiry_date" type="date" required
                                               placeholder="@lang('site.expiry_date')" id=""
                                               class="form-control @error('image') is-invalid @enderror"
                                               name="expiry_date" value="{{ $letter_guarantee->expiry_date }}">

                                        @error('expiry_date')
                                        <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                        @enderror

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label" for="">@lang('site.image')</label>
                                        <input value="{{ $letter_guarantee->image }}" type="file" name="image" id="image"
                                               class="@error('image') is-invalid @enderror"
                                               placeholder="@lang('site.image')">


                                        <!-- Way 2: Display Error Message -->
                                        @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <img src="{{asset('image_letter/'.$letter_guarantee->image)}}" height="150px" width="200px" id="link">
                                    <br>
                                    
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

        $(document).ready(function () {
            $('.supply_order').select2();


        });

        $('#release_date').on('change', function() {
      $('#expiry_date').val("");
      $("#expiry_date").prop('min',$(this).val());
      
    })
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
        $('#client_type').on('change', function () {
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
        $('#client_name').on('change', function () {
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
                success: function (response) {
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
                success: function (response) {
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
        $('#client_name').change(function (e) {
            $('#client_name').val() ? $('.client_name_error').addClass('d-none') : $('.client_name_error').removeClass('d-none');

            $('#client_id2').val('');
            $('.purchase_order_search_result').addClass('d-none');
            $('.client-details .text-danger').addClass('d-none');
            let clientType = $('#client_type').val();
            searchContent = $(this).val();
            sendData = {
                clientType: clientType,
                searchContent: searchContent,
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
            @if ($letter_guarantee->client_type == 'f')
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
        select2Function($('#supply_order'), "@lang('site.supply_order')");
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
        $('#deduction_id').on('change', function () {
            $('.deduction_id_error').addClass('d-none');
        })

        $('[type="submit"]').on('click', function () {
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
