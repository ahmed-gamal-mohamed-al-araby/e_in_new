@extends('pages.layouts.master')

@section('title')
    @lang('site.personClients')
@endsection

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.personClients')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.personClients')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Edit Person Client --}}
                @if (auth()->user()->hasPermission('client_read'))
                    <div class="col-12">
                        <form class="form-create-edit" action="{{ route('personClient.update', $personClient->id) }}"
                            method="Post">
                            <div class="add-service row">

                                @csrf
                                {{-- person Client name --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="name" value="{{ $personClient->name }}"
                                            class="form-control" required placeholder="@lang('site.name')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                            oninput="setCustomValidity('')">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- national id --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.national_id')</label>
                                        <input type="text" name="national_id" value="{{ $personClient->national_id }}"
                                            class="form-control" required placeholder="@lang('site.national_id')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.national_id')')"
                                            oninput="setCustomValidity('')">
                                        @error('national_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Mobile --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.mobile')</label>
                                        <input type="text" name="mobile" value="{{ $personClient->mobile }}"
                                            class="form-control validate_mobile" required placeholder="@lang('site.mobile')"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.mobile')')"
                                            oninput="setCustomValidity('')">
                                        <div class="validation-error text-danger d-none">
                                            @lang('site.mobile')</div>
                                        @error('mobile')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 border-bottom my-2"></div>

                                {{-- Country --}}
                                <div class="col-6">
                                    <label>@lang('site.the_country')</label>
                                    <div class="input-group mb-3">
                                        <select id='country_id' name="country_id" required class="form-control require">
                                            <option></option>
                                            @foreach ($countries as $country)
                                                <option value='{{ $country->id }}' @if ($country->id == $personClient->address->country->id) {{ 'selected' }} @endif>
                                                    {{ ucfirst($country->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- City --}}
                                <div class="col-6">
                                    <label>@lang('site.the_city')</label>
                                    <div class="input-group mb-3">
                                        <select id='city_id' name="city_id" required class="form-control require" disabled>
                                            <option disabled>@lang('site.small_city')
                                            </option>
                                            {{-- <option value='{{ $country->id }}' @if ($city->id == $personClient->address->country->id) {{ 'selected' }} @endif>
                                                {{ ucfirst($city->name) }}</option> --}}
                                        </select>
                                    </div>
                                    @error('city_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- region city --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.the_region_city')</label>
                                        <input type="text" class="form-control" name="region_city"
                                            value="{{ $personClient->address->region_city }}" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_region_city')')"
                                            oninput="setCustomValidity('')">
                                        @error('region_city')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- street --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.the_street')</label>
                                        <input type="text" class="form-control" name="street"
                                            value="{{ $personClient->address->street }}" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.small_street')')"
                                            oninput="setCustomValidity('')">
                                        @error('street')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- building number --}}
                                <div class="col-md-6 offset-md-6">
                                    <div class="form-group">
                                        <label>@lang('site.building_no')</label>
                                        <input type="text" class="form-control" name="building_no"
                                            value="{{ $personClient->address->building_no }}" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.building_no')')"
                                            oninput="setCustomValidity('')">
                                        @error('building_no')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mr-2">
                                    {{ method_field('PUT') }}
                                    <input type="submit" class="btn btn-success" value="@lang('site.edit')">
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
{{-- Custom scripts --}}
@section('scripts')
    <script>
        @if ($personClient)
            const oldCityId = {{ $personClient->address->city->id ?? '' }};
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
            if (url)
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
                    @if ($personClient)
                        if(targetSelector.is($('#city_id'))) {
                        const options= targetSelector.find('option');
                        for (let index = 0; index < options.length; index++) { if($(options[index]).val()==oldCityId ) {
                            $(options[index]).prop('selected', true); } } } @endif

                }
            });
        }
        @if ($personClient)
            $('#country_id').trigger('change');
        @endif

    </script>

@endsection
