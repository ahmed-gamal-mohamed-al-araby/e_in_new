@extends('pages.layouts.master')
{{--
new             ||  old
--------------------------------
force delete    || delete
delete          || archive
--}}

@section('title')
    @lang('site.add_businessClient')
@endsection

{{-- Custom Styles --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-formhelpers.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
@endsection


{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header optimization-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-between">
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <h1>@lang('site.add_businessClient')</h1>
                    {{-- </div> --}}
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('site.home')</a></li>
                            <li class="breadcrumb-item active">@lang('site.add_businessClient') </li>
                        </ol>
                    {{-- </div> --}}
                </div>
            </div> {{-- /.end of row --}}
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content form-i_request" dir="rtl">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">@lang('site.add_new_businessClient')</h3>
                        </div>
                        <main class="checkout">
                            <div class="card-data login-card">
                                <div class="row no-gutters">
                                    <div class="col-12 ">
                                        <div class="card-body">
                                            <form action="{{ route('businessClients.store') }}" method="post"
                                                enctype="multipart/form-data" id="regForm">
                                                @csrf
                                                {{-- Steps --}}
                                                <div class="header-step">
                                                    <span class="step step1"> 1 </span>
                                                    <span class="step step2"> 2 </span>
                                                    <span class="step step3"> 3 </span>
                                                </div>

                                                {{-- Supplier Basic Data --}}
                                                <div class="tab">
                                                    <h1>@lang('site.businessClient_data') </h1>
                                                    <div class="row row-page">

                                                        {{-- Company Name --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="name" id="company_name"
                                                                value="{{ old('name') }}" class="form-control require"
                                                                    placeholder="@lang('site.name')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-building"></i></span>
                                                                </div>
                                                            </div>
                                                            @error('name')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Fax --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="fax" class="form-control"
                                                                value="{{ old('fax') }}" placeholder="@lang('site.fax') ">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><i class="fas fa-fax"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @error('fax')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Country --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <select id='country_id' name="country_id"
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
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <select id='city_id' name="city_id"
                                                                    class="form-control require" disabled>
                                                                    <option disabled>@lang('site.small_city')
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            @error('city_id')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- region city --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" class="form-control require" name="region_city"
                                                                    value="{{ old('region_city') }}" placeholder="@lang('site.the_region_city')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-building"></i></span>
                                                                </div>
                                                            </div>
                                                            @error('region_city')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- street --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" class="form-control require" name="street"
                                                                    value="{{ old('street') }}" placeholder="@lang('site.the_street')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-building"></i></span>
                                                                </div>
                                                            </div>
                                                            @error('street')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- building number --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" class="form-control require" name="building_no"
                                                                    value="{{ old('building_no') }}" placeholder="@lang('site.building_no')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-building"></i></span>
                                                                </div>
                                                            </div>
                                                            @error('building_no')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Phone --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" id="company_phone" name="phone"
                                                                value="{{ old('phone') }}" class="form-control require validate-mobile"
                                                                    placeholder="@lang('site.phone')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-phone"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div class="text-danger d-none validate-mobile-error mb-3">
                                                                @lang('site.mobile_validation_error')
                                                            </div>
                                                            @error('phone')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Google map URL --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="url" name="gmap_url"
                                                                value="{{ old('gmap_url') }}" class="form-control validate-url"
                                                                    placeholder="@lang('site.google_map_link')">

                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-map-marked-alt"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div class="text-danger d-none validate-url-error mb-3">
                                                                @lang('site.url_validation_error')
                                                            </div>

                                                            @error('gmap_url')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Mobile --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" id="company_mobile" name="mobile"
                                                                value="{{ old('mobile') }}" class="form-control validate-mobile"
                                                                    placeholder="@lang('site.mobile')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-mobile-alt"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div class="text-danger d-none validate-mobile-error mb-3">
                                                                @lang('site.mobile_validation_error')
                                                            </div>
                                                            @error('mobile')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Email --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="email" name="email"
                                                                value="{{ old('email') }}" class="form-control validate-email"
                                                                    placeholder="@lang('site.email')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-envelope-square"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div class="text-danger d-none validate-email-error mb-3">
                                                                @lang('site.email_validation_error')
                                                            </div>

                                                            @error('email')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Website URL --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="url" name="website_url"
                                                                value="{{ old('website_url') }}" class="form-control validate-url"
                                                                    placeholder="@lang('site.website_link') ">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-globe"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div class="text-danger d-none validate-url-error mb-3">
                                                                @lang('site.url_validation_error')
                                                            </div>

                                                            @error('website_url')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Logo --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="logo" accept="image/*"
                                                                    class="custom-file-input w-auto ml-auto"
                                                                    id="logo_image_id">
                                                                <label class="custom-file-label pl-5 rounded-0 m-0"
                                                                    style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                                                    for="logo_image_id">
                                                                    @lang('site.choose') @lang('site.file')
                                                                    @lang('site.logo')
                                                                </label>
                                                                <div class="input-group-append" style="z-index: 5">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-images"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('logo')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                    </div>


                                                    <div class="search-result">
                                                    </div>

                                                </div>

                                                {{-- Responsible Person Data --}}
                                                <div class="tab">
                                                    <h1>@lang('site.person_data') </h1>

                                                    <div class="row row-page">

                                                        <div class="col-12 select-preson">
                                                            <div class="row"
                                                                style=" background: #f4f6f9; border: 1px solid #DDD; padding: 10px 0 0 0; margin: 10px 0 15px 0;">

                                                                {{-- name --}}
                                                                <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="persons[0][name]"
                                                                            class="form-control" disabled
                                                                            placeholder="@lang('site.name')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-user"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Job --}}
                                                                <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="persons[0][job]"
                                                                            class="form-control" disabled
                                                                            placeholder=" @lang('site.job')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-user-tie"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                  {{-- national_id --}}
                                                                  <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="persons[0][national_id]"
                                                                            class="form-control" disabled
                                                                            placeholder=" @lang('site.national_id')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-user-tie"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Mobile --}}
                                                                <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="persons[0][mobile]"
                                                                            class="form-control  validate-mobile" disabled
                                                                            placeholder="@lang('site.mobile')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="fas fa-mobile-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    {{-- Validation --}}
                                                                    <div
                                                                        class="text-danger d-none validate-mobile-error mb-3">
                                                                        @lang('site.mobile_validation_error')
                                                                    </div>
                                                                </div>

                                                                {{-- Whatapp --}}
                                                                <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="persons[0][whatsapp]"
                                                                            class="form-control validate-mobile" disabled
                                                                            placeholder=" @lang('site.whatsapp')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="fab fa-whatsapp"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    {{-- Validation --}}
                                                                    <div
                                                                        class="text-danger d-none validate-mobile-error mb-3">
                                                                        @lang('site.mobile_validation_error')
                                                                    </div>
                                                                </div>

                                                                {{-- person_email --}}
                                                                <div class="col-md-6 d-none">
                                                                    <div class="input-group mb-3">
                                                                        <input type="email" name="persons[0][email]"
                                                                            class="form-control validate-email" disabled
                                                                            placeholder="@lang('site.email')">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                    class="fas fa-envelope-square"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    {{-- Validation --}}
                                                                    <div
                                                                        class="text-danger d-none validate-email-error mb-3">
                                                                        @lang('site.email_validation_error')
                                                                    </div>
                                                                </div>

                                                                {{-- Add new person --}}
                                                                <div class="col-md-6">
                                                                    <div class="input-group mb-3">
                                                                        <button type="button"
                                                                            class="btn btn-success add-new-row ">@lang('site.add_preson')</button>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" name="person_note"
                                                                    placeholder=" @lang('site.note')">{{ old('person_note') }}</textarea>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                                {{-- Required file Supplier --}}
                                                <div class="tab">
                                                    <h1> @lang('site.businessClient_approval') </h1>
                                                    <div class="row row-page supplier-accepted">

                                                        {{-- tax id number input --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="tax_id_number"
                                                                value="{{ old('tax_id_number') }}" class="form-control require validate-Tax-id-number-and-value-add-registeration-number"
                                                                    placeholder="@lang('site.enter') @lang('site.tax_id_number')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-list-ol"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div
                                                                class="text-danger d-none validate-Tax-id-number-and-value-add-registeration-number-error mb-3">
                                                                @lang('site.validate_Tax_id_number_and_value_add_registeration_number_error')
                                                            </div>
                                                            @error('tax_id_number')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- tax id number file --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="tax_id_number_file"
                                                                    accept="application/pdf"
                                                                    class="custom-file-input w-auto ml-auto"
                                                                    id="tax_id_number_file_id"
                                                                    oninput="this.className = 'custom-file-input w-auto ml-auto'">
                                                                <label class="custom-file-label pl-5 rounded-0 m-0"
                                                                    style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                                                    for="tax_id_number_file_id">
                                                                    @lang('site.choose') @lang('site.file')
                                                                    @lang('site.tax_id_number')
                                                                </label>
                                                                <div class="input-group-append" style="z-index: 5">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-file"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('tax_id_number_file')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <!-- ------------------------- -->

                                                        {{-- Commercial registeration number input --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="commercial_registeration_number"
                                                                value="{{ old('commercial_registeration_number') }}" class="form-control validate_commercial_registeration_number"
                                                                    placeholder="@lang('site.enter') @lang('site.commercial_registeration_number')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-list-ol"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div
                                                                class="text-danger d-none validate-commercial-registeration-number-error mb-3">
                                                                @lang('site.validate_commercial_registeration_number_error')
                                                            </div>
                                                            @error('commercial_registeration_number')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- Commercial registeration number file --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="file"
                                                                    name="commercial_registeration_number_file"
                                                                    accept="application/pdf"
                                                                    class="custom-file-input w-auto ml-auto"
                                                                    id="commercial_registeration_number_file_id"
                                                                    oninput="this.className = 'custom-file-input w-auto ml-auto'">
                                                                <label class="custom-file-label pl-5 rounded-0 m-0"
                                                                    style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                                                    for="commercial_registeration_number_file_id">
                                                                    @lang('site.choose') @lang('site.file')
                                                                    @lang('site.commercial_registeration_number')
                                                                </label>
                                                                <div class="input-group-append" style="z-index: 5">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-file"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('commercial_registeration_number')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- ------------------------- -->

                                                        {{-- Tax file number --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="tax_file_number"
                                                                value="{{ old('tax_file_number') }}" class="form-control"
                                                                    placeholder="@lang('site.enter') @lang('site.tax_file_number')">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-list-ol"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            {{-- Validation --}}
                                                            <div
                                                                class="text-danger d-none mb-3">
                                                                @lang('site.tax_file_number')
                                                            </div>
                                                            @error('value_add_registeration_number')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        {{-- tax file number file --}}
                                                        <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="file" name="tax_file_number_file"
                                                                    accept="application/pdf"
                                                                    class="custom-file-input w-auto ml-auto"
                                                                    id="tax_file_number_file_file_id">
                                                                <label class="custom-file-label pl-5 rounded-0 m-0"
                                                                    style="text-align: left; text-overflow: ellipsis; overflow: hidden; color: #999"
                                                                    for="tax_file_number_file_file_id">
                                                                    @lang('site.choose') @lang('site.file')
                                                                    @lang('site.tax_file_number')
                                                                </label>
                                                                <div class="input-group-append" style="z-index: 5">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-file"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('tax_file_number_file')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" name="accredite_note"
                                                                    placeholder=" @lang('site.note')">{{ old('accredite_note') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="overflow:auto;">
                                                    <div>
                                                        <button class="form-btn" type="button" id="prevBtn"
                                                            onclick="nextPrev(-1, `@lang('site.next')`, `@lang('site.submit')`)">
                                                            @lang('site.prev') </button>

                                                        <button class="form-btn" type="button" id="nextBtn"
                                                            onclick="nextPrev(1, `@lang('site.next')`, `@lang('site.submit')`)">
                                                            @lang('site.next') </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

{{-- Custom scripts --}}
@section('scripts')

    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script src="{{ asset('dist/js/business_client.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap-formhelpers.min.js') }}"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
        $(document).ready(function() {
            showTab(currentTab, `@lang('site.next')`); // Display the current tab
            // Country
            select2Function($('#country_id'), "@lang('site.capital_select') @lang('site.small_country')", `${subFolderURL}/${urlLang}/country/getcitiesFromCountry`);
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
                    }
                });
            }

            // person
            // Start from index 1 becouse the first form will start from index 0.
            var index = 1; // to add new person in array persons[${index}][name]
            $('.select-preson').on('click', '.add-new-row', function() {
                let new_person = $(this).parents('.row').eq(0).clone(true).addClass('person-row');

                new_person.find('div input').attr('disabled', false);
                new_person.find('.col-md-6.d-none').removeClass('d-none');
                // change attribute name to the index, and remove data
                new_person.find('div input').each(function(i, element) {
                    $(element).attr('name', $(element).attr('name').replace(/[d{0,}]/i, index)).val(
                        '');
                })
                // replace add button with remove button
                new_person.children().last().find('.input-group.mb-3').html(
                    '<button type="button" class="btn btn-danger remove-person"> @lang('site.delete ')  </button>');
                new_person.find('[type=email]').removeClass("invalid is-invalid");
                new_person.find('.validate-email-error').addClass('d-none');
                $(".select-preson").append(new_person);
                index++;
            });
            $('.select-preson').on('click', '.remove-person', function() {
                $(this).parents(".person-row").remove();
            });
            // Inform the data entry that some supplier contain part of your supplier name input
            $(document).on('keyup', '#company_name', function() {
                var search_content = $(this).val();

                if (this.value.length > 2) {
                    $.ajax({
                        url: `${subFolderURL}/${urlLang}/businessClients/company_search`,
                        type: "GET",
                        data: {
                            search_content
                        },
                        dataType: 'json',
                        success: function(data) {
                            data = Object.values(data.businessClients);

                            if (data.length !== 0) {
                                showString = "@lang('site.show')";
                                editString = "@lang('site.edit')";
                                archiveString = "@lang('site.archive_this')";
                                restoreeString = "@lang('site.take_back')";
                                tableData = '<div class="container">\n' +
                                    '                <h4>  @lang("site.add_businessClient_attention")\n' +
                                    '                <div class="row">\n' +
                                    '                    <div class="col-12">\n' +
                                    '                        <table class="table table-bordered table-striped text-center">\n' +
                                    '                            <thead>\n' +
                                    '                            <tr style="text-align:center;">\n' +
                                    '                                <th > @lang("site.id")</th>\n' +
                                    '                                <th>@lang("site.name") </th>\n' +
                                    '                                <th>@lang("site.state") </th>\n' +
                                    '                                <th >@lang("site.governorate")</th>\n' +
                                    '                                <th >@lang("site.address")</th>\n' +
                                    '                                <th >@lang("site.date")</th>\n' +
                                    '                                <th >@lang("site.actions")</th>\n' +
                                    '                            </tr>\n' +
                                    '                            </thead>\n' +
                                    '                            <tbody class = "text-center">';
                                        for (let index = 0; index < data.length; index++) {
                                    tableData +=
                                        `<tr>
                                                <td>${data[index].id}</td>
                                                <td>${data[index].name}</td>
                                                <td>${data[index].country}</td>
                                                <td>${data[index].city}</td>
                                                <td>${data[index].address}</td>
                                                <td>${new Date(data[index].created_at).toLocaleDateString("en-US")}</td>
                                                <td class="options_suppliers row justify-content-center align-items-center border-00 m-0">
                                                  <a href="/businessClients/profile/${data[index].id}" class="btn btn-success"><i class="fa fa-eye"></i>&ensp;${showString}</a>
                                                </td>
                                            </tr>`;

                                }
                                // data.businessClients.forEach(businessClient => {
                                //     tableData +=
                                //         `<tr>
                                //                 <td>${businessClient.id}</td>
                                //                 <td>${businessClient.name}</td>
                                //                 <td>${businessClient.country}</td>
                                //                 <td>${businessClient.city}</td>
                                //                 <td>${businessClient.address}</td>
                                //                 <td>${new Date(businessClient.created_at).toLocaleDateString("en-US")}</td>
                                //                 <td class="options_suppliers row justify-content-center align-items-center border-00 m-0">
                                //                   <a href="/businessClients/profile/${businessClient.id}" class="btn btn-success"><i class="fa fa-eye"></i>&ensp;${showString}</a>
                                //                 </td>
                                //             </tr>`;
                                // });

                                tableData += ' </tbody>\n' +
                                    ' </table>\n' +
                                    ' </div>\n' +
                                    ' </div>\n' +
                                    ' </div>\n' +
                                    ' </div>';
                                $('.search-result').html(tableData);
                            } else {
                                $('.search-result').html('');
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $('.search-result').html('');
                }
            });

        });

    </script>



    <script>
        $('.checkout select.require').on('change', function() {
            $(this).removeClass('invalid');
        })

    </script>

    <script>
        // change label for input file
        $('input[type=file]').on('change', function() {
            $(this).next().text($(this).val());
        })

    </script>
@endsection
