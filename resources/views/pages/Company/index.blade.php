@extends('pages.layouts.master')

@section('title')
    @lang('site.companies')
@endsection

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.companies')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.companies')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content service-content">
        @if ($errors->any())
            {{ implode('', $errors->all('<div>:message</div>')) }}
        @endif
        <div class="container-fluid">
            <div class="row">
                {{-- Edit Company --}}
                @if ($data)
                    @if (auth()->user()->hasPermission('company_update'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form class="form-create-edit" action="{{ route('company.update', $data->id) }}"
                                      method="Post">
                                    @csrf

                                    {{-- company name --}}
                                    <div class="form-group">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="company_name" value="{{ $data->company_name }}"
                                               class="form-control" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                               oninput="setCustomValidity('')">
                                        @error('company_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    {{-- tax id number --}}
                                    <div class="form-group">
                                        <label>@lang('site.tax_id_number_only')</label>
                                        <input type="text" name="tax_id_number" value="{{ $data->tax_id_number }}"
                                               class="form-control validate-Tax-id-number" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.tax_id_number_only')')"
                                               oninput="setCustomValidity('')"
                                               placeholder="@lang('site.tax_id_number')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.validate_Tax_id_number')
                                        </div>
                                        @error('tax_id_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- commercial registeration number --}}
                                    <div class="form-group">
                                        <label>@lang('site.commercial_registeration_number_only')</label>

                                        <input type="text" name="commercial_registeration_number"
                                               value="{{ $data->commercial_registeration_number }}"
                                               class="form-control validate_commercial_registeration_number" required
                                               placeholder="@lang('site.commercial_registeration_number')"
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.commercial_registeration_number_only')')"
                                               oninput="setCustomValidity('')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.validate_commercial_registeration_number_error')</div>
                                        @error('commercial_registeration_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- tax file number --}}
                                    <div class="form-group">
                                        <label>@lang('site.tax_file_number')</label>
                                        <input type="text" name="tax_file_number" value="{{ $data->tax_file_number }}"
                                               class="form-control" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.tax_file_number')')"
                                               oninput="setCustomValidity('')"
                                               placeholder="@lang('site.tax_id_number')">
                                        @error('tax_file_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_country')</label>
                                        <div class="input-group mb-3">
                                            <select id='country_id' name="country_id" required
                                                    class="form-control require">
                                                <option></option>
                                                @foreach ($countries as $country)
                                                    <option
                                                        value='{{ $country->id }}' @if ($country->id == $data->address->country->id) {{ 'selected' }} @endif>
                                                        {{ ucfirst($country->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('country_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- City --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_city')</label>
                                        <div class="input-group mb-3">
                                            <select id='city_id' name="city_id" required class="form-control require"
                                                    disabled>
                                                <option disabled>@lang('site.small_city')
                                                </option>
                                                {{-- <option value='{{ $country->id }}' @if ($city->id == $data->address->country->id) {{ 'selected' }} @endif>
                                                {{ ucfirst($city->name) }}</option> --}}
                                            </select>
                                        </div>
                                        @error('city_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- region city --}}
                                    <div class="form-group">
                                        <label>@lang('site.the_region_city')</label>
                                        <input type="text" class="form-control" name="region_city"
                                               value="{{ $data->address->region_city }}" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_region_city')')"
                                               oninput="setCustomValidity('')">
                                        @error('region_city')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- street --}}
                                    <div class="form-group">
                                        <label>@lang('site.the_street')</label>
                                        <input type="text" class="form-control" name="street"
                                               value="{{ $data->address->street }}" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_street')')"
                                               oninput="setCustomValidity('')">
                                        @error('street')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- building number --}}
                                    <div class="form-group">
                                        <label>@lang('site.building_no')</label>
                                        <input type="text" class="form-control" name="building_no"
                                               value="{{ $data->address->building_no }}" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.building_no')')"
                                               oninput="setCustomValidity('')">
                                        @error('building_no')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        {{ method_field('PUT') }}
                                        <input type="submit" class="btn btn-success" value="@lang('site.edit')">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    {{-- Add Company --}}
                @else
                    @if (auth()->user()->hasPermission('company_create'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form class="form-create-edit" action="{{ route('company.store') }}" method="POST">
                                    @csrf

                                    {{-- company name --}}
                                    <div class="form-group">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                                               class="form-control" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                               oninput="setCustomValidity('')">
                                        @error('company_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- tax id number --}}
                                    <div class="form-group">
                                        <label>@lang('site.tax_id_number_only')</label>

                                        <input type="text" name="tax_id_number" value="{{ old('tax_id_number') }}"
                                               class="form-control validate-Tax-id-number" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.tax_id_number_only')')"
                                               oninput="setCustomValidity('')"
                                               placeholder="@lang('site.tax_id_number')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.validate_Tax_id_number')
                                        </div>
                                        @error('tax_id_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- commercial registeration number --}}
                                    <div class="form-group">
                                        <label>@lang('site.commercial_registeration_number_only')</label>

                                        <input type="text" name="commercial_registeration_number"
                                               value="{{ old('commercial_registeration_number') }}"
                                               class="form-control validate_commercial_registeration_number" required
                                               placeholder="@lang('site.commercial_registeration_number')"
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.commercial_registeration_number_only')')"
                                               oninput="setCustomValidity('')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.validate_commercial_registeration_number_error')</div>
                                        @error('commercial_registeration_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- tax file number --}}
                                    <div class="form-group">
                                        <label>@lang('site.tax_file_number')</label>

                                        <input type="text" name="tax_file_number" value="{{ old('tax_file_number') }}"
                                               class="form-control"
                                               placeholder="@lang('site.commercial_registeration_number')"
                                               required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.commercial_registeration_number_only')')"
                                               oninput="setCustomValidity('')">
                                        @error('tax_file_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_country')</label>
                                        <div class="input-group mb-3">
                                            <select id='country_id' name="country_id" required
                                                    class="form-control require">
                                                <option></option>
                                                @foreach ($countries as $country)
                                                    <option value='{{ $country->id }}'>
                                                        {{ ucfirst($country->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('country_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- City --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_city')</label>
                                        <div class="input-group mb-3">
                                            <select id='city_id' name="city_id" required class="form-control require"
                                                    disabled>
                                                <option disabled>@lang('site.small_city')
                                                </option>
                                            </select>
                                        </div>
                                        @error('city_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- region city --}}
                                    <div class="form-group">
                                        <label>@lang('site.the_region_city')</label>
                                        <input type="text" class="form-control" name="region_city"
                                               value="{{ old('region_city') }}" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_region_city')')"
                                               oninput="setCustomValidity('')">
                                        @error('region_city')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- street --}}
                                    <div class="form-group">
                                        <label>@lang('site.the_street')</label>
                                        <input type="text" class="form-control" name="street"
                                               value="{{ old('street') }}"
                                               required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_street')')"
                                               oninput="setCustomValidity('')">
                                        @error('street')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- building number --}}
                                    <div class="form-group">
                                        <label>@lang('site.building_no')</label>
                                        <input type="text" class="form-control" name="building_no"
                                               value="{{ old('building_no') }}" required
                                               oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.building_no')')"
                                               oninput="setCustomValidity('')">
                                        @error('building_no')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="@lang('site.add')">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
                @if (auth()->user()->hasPermission('company_create') ||
        auth()->user()->hasPermission('company_update'))
                    @if ($data)
                        <div class="col-md-8">
                            @else
                                @if (auth()->user()->hasPermission('company_create'))
                                    <div class="col-md-8">
                                        @else
                                            <div class="col-12">
                                                @endif
                                                @endif
                                                @else
                                                    <div class="col-12">
                                                        @endif
                                                        <div class="card">
                                                            <div class="card-body">

                                                                <table id="example1"
                                                                       class="table table-bordered table-striped text-center">
                                                                    <thead>
                                                                    <tr style="text-align:center;">
                                                                        <th> @lang('site.id')</th>
                                                                        <th> @lang('site.company') </th>
                                                                        <th>@lang('site.tax_id_number_only')</th>
                                                                        <th> @lang('site.commercial_registeration_number_only') </th>
                                                                        <th> @lang('site.tax_file_number') </th>
                                                                        <th> @lang('site.address') </th>
                                                                        <th width="28%"> @lang('site.actions')</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach ($companies as $company)

                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $company->company_name }}</td>
                                                                            <td>{{ $company->tax_id_number }}</td>
                                                                            <td>{{ $company->commercial_registeration_number }}</td>
                                                                            <td>{{ $company->tax_file_number }}</td>
                                                                            <td>{{ $company->address->country->name . ' ,' . $company->address->city->name . ', ' . $company->address->region_city . ', ' . $company->address->street . ', ' . $company->address->building_no }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="service-option">

                                                                                    <a href="{{ route('company.edit', $company->id) }}"
                                                                                       class=" btn btn-warning"><i
                                                                                            class="fa fa-edit"></i>
                                                                                        @lang('site.edit') </a>

                                                                                    <a class=" btn btn-danger"
                                                                                       data-company_id="{{ $company->id }}"
                                                                                       data-toggle="modal"
                                                                                       data-target="#company_delete"><i
                                                                                            class="fa fa-trash-alt"></i> @lang('site.delete')
                                                                                    </a>

                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                    </div>
    </section>
    {{-- Delete modal --}}
    <div class="modal fade text-center" id="company_delete" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('site.delete') @lang('site.company')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>?@lang('site.confirm') @lang('site.delete') @lang('site.company')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('company.destroy', ['company' => 'delete']) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="company_id" id="company_id" value="">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete')
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
{{-- Custom scripts --}}
@section('scripts')
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "@lang('site.all')"]
                ],
                columnDefs: [{
                    targets: "hiddenCols",
                    visible: false
                }],
                "language": {
                    search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                    searchPlaceholder: ' @lang("site.search")',
                    "lengthMenu": "@lang('site.show') _MENU_  @lang('site.records')",
                    "paginate": {
                        "previous": "@lang('site.prev')",
                        "next": "@lang('site.next')",

                    },
                    "info": "@lang('site.show') _START_  @lang('site.from') _TOTAL_  @lang('site.record')",

                    buttons: {
                        colvis: ' @lang("site.show_data")',
                        'print': ' @lang("site.print")',
                        'copy': ' @lang("site.copy")',
                        'excel': '@lang("site.excel")'
                    },
                    "emptyTable": "@lang('site.no_data')",
                    "infoEmpty": "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                    "infoFiltered": "( @lang('site.search_in') _MAX_  @lang('site.records'))",
                }

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
        $('#company_delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var companyid = button.data('company_id');
            $('.modal #company_id').val(companyid);
        })

    </script>

    <script>
            @if ($data)
        const oldCityId = {{ $data->address->city->id ?? '' }};
            @endif
        let validateCRN = true,
            validateTIN = true;
        $('.validate_commercial_registeration_number').on('focusout', function (e) {
            validateCRN = validate_commercial_registeration_number($(this));
        });

        // validate Tax id number and value add registeration number
        $('.validate-Tax-id-number').on('focusout', function (e) {
            validateTIN = validateTax_id_number($(this))
        });


        function validate_commercial_registeration_number(that) {
            if (that.val().trim() == '') {
                that.next().addClass('d-none');
                that.removeClass("invalid is-invalid");
                return true;
            } else
                return validate(that, /^[\d]{4,7}$/, 'commercial-registeration-number');
        }

        function validateTax_id_number(that) {
            return validate(that, /^[\d]{3}-[\d]{3}-[\d]{3}$/, 'Tax-id-number');
        }

        function validate(object, regex, className) {
            const element = object.val().trim();
            if (regex.test(element)) { // element match
                object.next().addClass('d-none');
                object.removeClass("invalid is-invalid");
                return true;
            } else {
                object.next().removeClass('d-none');
                object.addClass("invalid is-invalid");
                return false;
            }
        }

        $(".form-create-edit").submit(function (e) {
            $('.validate_commercial_registeration_number').trigger('focusout');
            if (!(validateCRN && validateTIN)) {
                $('.validate_commercial_registeration_number').trigger('focusout');
                $('.validate-Tax-id-number').trigger('focusout');
                e.preventDefault();
            }
        });

        // Country
        select2Function($('#country_id'), "@lang('site.capital_select') @lang('site.small_country')",
            `${subFolderURL}/${urlLang}/country/getcitiesFromCountry`);
            {{--"{{ route('citiesOfcountry') }}");--}}
        // City
        select2Function($('#city_id'), "@lang('site.capital_select') @lang('site.small_city')");

        function select2Function(selector, placeholder, url = '') {
            const targetSelector = $(selector).parent().parent().next().find('select');
            $(selector).select2();
            $(selector).select2({
                allowClear: true,
                placeholder: placeholder,
            });
            if (url)
                $(selector).on('change', function () {
                    if (targetSelector != '') {
                        const urlInputId = $(this).val();
                        sendAjax('GET', url, urlInputId, targetSelector)
                        $(targetSelector).empty();
                    }
                });
        }

        function sendAjax(method, url, urlInputId, targetSelector) {
            targetSelector.attr('disabled', true);
            $.ajax({
                type: method,
                url: `${url}/${urlInputId}`,
                success: function (response) {
                    targetSelector.attr('disabled', false);
                    var response = JSON.parse(response);
                    targetSelector.empty();
                    targetSelector.append(`<option value=""></option>`);
                    response.forEach(element => {
                        targetSelector.append(
                            `<option value="${element['id']}">${element['name']}</option>`
                        );
                    });
                    @if ($data)
                    if (targetSelector.is($('#city_id'))) {
                        const options = targetSelector.find('option');
                        for (let index = 0; index < options.length; index++) {
                            if ($(options[index]).val() == oldCityId) {
                                $(options[index]).prop('selected', true);
                            }
                        }
                    } @endif

                }
            });
        }

        @if ($data)
        $('#country_id').trigger('change');
        @endif

    </script>
@endsection
