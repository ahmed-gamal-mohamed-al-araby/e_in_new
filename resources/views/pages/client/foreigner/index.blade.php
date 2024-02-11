@extends('pages.layouts.master')

@section('title')
    @lang('site.foreignerClients')
@endsection

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.foreignerClients')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.foreignerClients')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Edit Foreigner Client --}}
                @if ($data)
                    @if (auth()->user()->hasPermission('client_update'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form class="form-create-edit" action="{{ route('foreignerClient.update', $data->id) }}"
                                    method="Post">
                                    @csrf
                                    {{-- foreigner Client company name --}}
                                    <div class="form-group">
                                        <label>@lang('site.company_name')</label>
                                        <input type="text" name="company_name" value="{{ $data->company_name }}"
                                            class="form-control" required placeholder="@lang('site.company_name')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.company_name')')"
                                            oninput="setCustomValidity('')">
                                        @error('company_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- foreigner Client name --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_name')</label>
                                        <input type="text" name="person_name" value="{{ $data->person_name }}"
                                            class="form-control" placeholder="@lang('site.person_name')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_name')')"
                                            oninput="setCustomValidity('')">
                                        @error('person_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- person email --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_email')</label>
                                        <input type="email" name="person_email" value="{{ $data->person_email }}"
                                            class="form-control" placeholder="@lang('site.person_email')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_email')')"
                                            oninput="setCustomValidity('')">
                                        @error('person_email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- person mobile --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_mobile')</label>

                                        <input type="text" name="person_mobile" value="{{ $data->person_mobile }}"
                                            class="form-control validate_person_mobile"
                                            placeholder="@lang('site.person_mobile')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_mobile')')"
                                            oninput="setCustomValidity('')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.person_mobile')</div>
                                        @error('person_mobile')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- VAT ID --}}
                                    <div class="form-group">
                                        <label>@lang('site.vat_id')</label>
                                        <input type="text" name="vat_id" value="{{ $data->vat_id }}"
                                            class="form-control" placeholder="@lang('site.vat_id')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.vat_id')')"
                                            oninput="setCustomValidity('')">
                                        @error('vat_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_country')</label>
                                        <div class="input-group mb-3">
                                            <select id='country_id' name="country_id" required class="form-control require">
                                                <option></option>
                                                @foreach ($countries as $country)
                                                    <option value='{{ $country->id }}' @if ($country->id == $data->address->country->id) {{ 'selected' }} @endif>
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
                                            <select id='city_id' name="city_id" class="form-control require"
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
                                            value="{{ $data->address->region_city }}"
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
                                            value="{{ $data->address->street }}"
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
                                            value="{{ $data->address->building_no }}"
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
                    {{-- Add Foreigner Client --}}
                @else
                    @if (auth()->user()->hasPermission('client_create'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form class="form-create-edit" action="{{ route('foreignerClient.store') }}"
                                    method="POST">
                                    @csrf

                                    {{-- foreigner Client company name --}}
                                    <div class="form-group">
                                        <label>@lang('site.company_name')</label>
                                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                                            class="form-control" required placeholder="@lang('site.company_name')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.company_name')')"
                                            oninput="setCustomValidity('')">
                                        @error('company_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- foreigner Client name --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_name')</label>
                                        <input type="text" name="person_name" value="{{ old('person_name') }}"
                                            class="form-control" placeholder="@lang('site.person_name')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_name')')"
                                            oninput="setCustomValidity('')">
                                        @error('person_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- person email --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_email')</label>
                                        <input type="email" name="person_email" value="{{ old('person_email') }}"
                                            class="form-control" placeholder="@lang('site.person_email')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_email')')"
                                            oninput="setCustomValidity('')">
                                        @error('person_email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- person mobile --}}
                                    <div class="form-group">
                                        <label>@lang('site.person_mobile')</label>

                                        <input type="text" name="person_mobile" value="{{ old('person_mobile') }}"
                                            class="form-control validate_person_mobile"
                                            placeholder="@lang('site.person_mobile')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.person_mobile')')"
                                            oninput="setCustomValidity('')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.person_mobile')</div>
                                        @error('person_mobile')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- VAT ID --}}
                                    <div class="form-group">
                                        <label>@lang('site.vat_id')</label>
                                        <input type="text" name="vat_id" value="{{ old('vat_id') }}"
                                            class="form-control" placeholder="@lang('site.vat_id')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.vat_id')')"
                                            oninput="setCustomValidity('')">
                                        @error('vat_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12">
                                        <label>@lang('site.the_country')</label>
                                        <div class="input-group mb-3">
                                            <select id='country_id' name="country_id" required class="form-control require">
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
                                            <select id='city_id' name="city_id" class="form-control require"
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
                                            value="{{ old('region_city') }}"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_region_city')')"
                                            oninput="setCustomValidity('')">
                                        @error('region_city')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- street --}}
                                    <div class="form-group">
                                        <label>@lang('site.the_street')</label>
                                        <input type="text" class="form-control" name="street" value="{{ old('street') }}"
                                            
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
                                            value="{{ old('building_no') }}"
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
                @if (auth()->user()->hasPermission('client_create') ||
        auth()->user()->hasPermission('client_update'))
                    @if ($data)
                        <div class="col-md-8">
                        @else
                            @if (auth()->user()->hasPermission('client_create'))
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

                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="text-align:center;">
                                    <th> @lang('site.id')</th>
                                    <th> @lang('site.company_name') </th>
                                    <th> @lang('site.person_name') </th>
                                    <th>@lang('site.person_email')</th>
                                    <th>@lang('site.person_mobile')</th>
                                    <th>@lang('site.vat_id')</th>
                                    <th> @lang('site.address') </th>
                                    @if (auth()->user()->hasPermission('client_update') ||
        auth()->user()->hasPermission('client_delete'))
                                        <th> @lang('site.actions')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($foreigners as $foreigner)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $foreigner->company_name }}</td>
                                        <td>{{ $foreigner->person_name }}</td>
                                        <td>{{ $foreigner->person_email }}</td>
                                        <td>{{ $foreigner->person_mobile }}</td>
                                        <td>{{ $foreigner->vat_id }}</td>
                                        @if($foreigner->address->city_id)
                                        <td>{{ $foreigner->address->country->name . ' ,' . $foreigner->address->city->name . ', ' . $foreigner->address->region_city . ', ' . $foreigner->address->street . ', ' . $foreigner->address->building_no }} </td>
                                        @else
                                        <td>{{ $foreigner->address->country->name . ' ,' . $foreigner->address->region_city . ', ' . $foreigner->address->street . ', ' . $foreigner->address->building_no }} </td>
                                        @endif

                                        @if (auth()->user()->hasPermission('client_update') ||
        auth()->user()->hasPermission('client_delete'))
                                            <td class="text-center">
                                                <div class="service-option">
                                                    @if (auth()->user()->hasPermission('client_update'))
                                                        <a href="{{ route('foreignerClient.edit', $foreigner->id) }}"
                                                            class=" btn btn-warning m-0 my-1"><i class="fa fa-edit m-0"></i></a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('client_delete'))
                                                        <a class=" btn btn-danger m-0 my-1"
                                                            data-foreigner_id="{{ $foreigner->id }}" data-toggle="modal"
                                                            data-target="#foreigner_delete"><i class="fa fa-trash-alt m-0"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
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
    <div class="modal fade text-center" id="foreigner_delete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('site.delete_foreigner_client') </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        @lang('site.comfirm_delete_client')
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('foreignerClient.destroy', ['foreignerClient' => 'delete']) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="foreigner_id" id="foreigner_id" value="">
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
        $(function() {
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


        $('#foreigner_delete').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var foreignerid = button.data('foreigner_id');
            $('.modal #foreigner_id').val(foreignerid);
        })

    </script>

    <script>
        @if ($data)
            const oldCityId = {{ $data->address->city->id ?? '' }};
        @endif

        // Country
        select2Function($('#country_id'), "@lang('site.capital_select') @lang('site.small_country')",
            `${subFolderURL}/${urlLang}/country/getcitiesFromCountry`);
        // City
        select2Function($('#city_id'), "@lang('site.capital_select') @lang('site.small_city')");

        function select2Function(selector, placeholder, url = '') {
            const targetSelector = $(selector).parent().parent().next().find('select');
            $(selector).select2();
            $(selector).select2({
                allowClear: true,
                placeholder: placeholder,
            });
            if(url)
            $(selector).on('change', function() {
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
                success: function(response) {
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
                        if(targetSelector.is($('#city_id'))) {
                        const options= targetSelector.find('option');
                        for (let index = 0; index < options.length; index++) { if($(options[index]).val()==oldCityId ) {
                            $(options[index]).prop('selected', true); } } } @endif

                }
            });
        }
        @if ($data)
            $('#country_id').trigger('change');
        @endif

    </script>

@endsection
