@extends('pages.layouts.master')

@section('title')
    @lang('site.businessClients')
@endsection

{{-- Custom Styles --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/css/rate.css') }}">

@endsection


{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header optimization-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-between">
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <h1>@lang('site.profile_page')</h1>
                    {{-- </div> --}}
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('site.home')</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('businessClients.index') }}">@lang('site.businessClients')</a>
                            </li>
                            <li class="breadcrumb-item active">@lang('site.profile_page') </li>
                        </ol>
                    {{-- </div> --}}
                </div>
            </div> {{-- /.end of row --}}
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content profile-page" style="direction0: rtl !important; text-align0: right !important;">
        <div class="container-fluid">
            <div class="row flex-row-reverse0">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if ($businessClient->logo)
                                    <img class="profile-user-img img-fluid img-circle"
                                        src={{ asset("client/business/logo/$businessClient->logo") }} alt='@lang(' site.logo')'>
                                @else
                                    <img class="profile-user-img-not img-fluid img-circle"
                                        src="{{ asset('Images/user_profile.png') }}" alt='@lang(' site.logo')'>
                                @endif
                            </div>
                            <h3 class="profile-username text-center">
                                {{ $businessClient->name }}
                            </h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>@lang('site.phone')</b> <a class="float-right">{{ $businessClient->phone }}</a>
                                </li>
                                <li class="list-group-item">
                                    @if ($businessClient->mobile)
                                        <b>@lang('site.mobile')</b> <a class="float-right">{{ $businessClient->mobile }}</a>
                                    @else
                                        <b>@lang('site.mobile')</b> <a class="float-right"> <span class="text-danger">
                                                @lang('site.not_available')</span> </a>
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    @if ($businessClient->fax)
                                        <b>@lang('site.fax')</b> <a class="float-right">{{ $businessClient->fax }}</a>
                                    @else
                                        <b>@lang('site.fax')</b> <a class="float-right"> <span class="text-danger">
                                                @lang('site.not_available')</span> </a>
                                    @endif
                                </li>
                                <li class="list-group-item mb-3">
                                    <b> @lang('site.date')</b> <a class="float-right">
                                        {{ \Carbon\Carbon::parse($businessClient->created_at)->format('d/m/Y') }}</a>
                                </li>
                            </ul>
                                {{-- Show edit for user that have this notification --}}
                                {{-- * Show if active user is notification owner
                                        * and this record is not approved
                                        * notification have comment or notification type is a => for action not n => for normal --}}
                                @if ($notification->user_id == auth()->user()->id && $businessClient->approved == 0 && ($notification->type == 'a' || $notification->comment))
                                    <a class="btn btn-warning btn-block"
                                        href="{{ route('businessClients.edit', $businessClient->id) }}">@lang('site.go_for_edit')<i
                                            class="ml-2 fas fa-edit"></i>
                                    </a>
                                @endif
                        </div>

                    </div>


                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">
                                        @lang('site.about')</a></li>
                                <li class="nav-item"><a class="nav-link " href="#persons"
                                        data-toggle="tab">@lang('site.responsible')</a></li>
                                <li class="nav-item"><a class="nav-link" href="#accredite"
                                        data-toggle="tab">@lang('site.businessClient_approval')</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                {{-- Business Client basic data --}}
                                <div class="active tab-pane" id="activity">

                                    {{-- email --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fas fa-envelope-square"></i> @lang('site.email')
                                        </div>
                                        <div class="body-info">
                                            @if ($businessClient->email)
                                                {{ $businessClient->email }}
                                            @else
                                                <span class="text-danger bg-transparent"> @lang('site.not_available')</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- country --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fas fa-flag"></i> @lang('site.state')
                                        </div>
                                        <div class="body-info">
                                            {{ $businessClient->address->country->name }}
                                        </div>
                                    </div>

                                    {{-- City --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fas fa-city"></i> @lang('site.governorate')
                                        </div>
                                        <div class="body-info">
                                            {{ $businessClient->address->city->name }}
                                        </div>
                                    </div>

                                    {{-- address --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fas fa-location-arrow"></i> @lang('site.address')
                                        </div>
                                        <div class="body-info">
                                            {{ $businessClient->address->country->name . ' ,' . $businessClient->address->city->name . ', ' . $businessClient->address->region_city . ', ' . $businessClient->address->street . ', ' . $businessClient->address->building_no }}
                                        </div>
                                    </div>

                                    {{-- google_map_link --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fas fa-map-marked-alt"></i> @lang('site.google_map_link')
                                        </div>
                                        <div class="body-info">
                                            @if ($businessClient->gmap_url)
                                                @lang('site.go_address') <a target="_blank"
                                                    href="{{ $businessClient->gmap_url }}"> @lang('site.here')</a>
                                            @else
                                                <span class="text-danger bg-transparent"> @lang('site.not_available')</span>
                                            @endif

                                        </div>
                                    </div>

                                    {{-- website --}}
                                    <div class="supplier-info">
                                        <div class="header-info">
                                            <i class="fab fa-chrome"></i> @lang('site.website')
                                        </div>
                                        <div class="body-info">
                                            @if ($businessClient->website_url)
                                                @lang('site.go_website') <a target="_blank"
                                                    href="{{ $businessClient->website_url }}"> @lang('site.here')</a>
                                            @else
                                                <span class="text-danger bg-transparent"> @lang('site.not_available')</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Responsible Persons --}}
                                <div class="tab-pane" id="persons">

                                    <div class="timeline timeline-inverse person-info">

                                        @if ($persons->count() == 1)
                                            @foreach ($persons as $preson)
                                                @if ($preson->name == null && $preson->whatsapp == null && $preson->national_id == null && $preson->person_email == null && $preson->job_title == null && $preson->mobile == null)
                                                    <div class="time-label">
                                                        <span class="bg-danger">
                                                            <i class="fas fa-users"></i> لا يوجد أشخاص
                                                            مسئولة
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="time-label">
                                                        <span class="bg-danger">
                                                            <i class="fas fa-users"></i>
                                                            @lang('site.responsible_person')
                                                            ({{ $preson_count }})

                                                        </span>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-user bg-warning"></i>
                                                        <div class="timeline-item">
                                                            <h3 class="timeline-header">
                                                                {{ $preson->name }} </h3>
                                                            <div class="timeline-body body-info">

                                                                <div class="persons">

                                                                    <p> <span
                                                                            class="person-info">@lang('site.job')
                                                                            :</span>
                                                                        {{ $preson->job_title }} </p>
                                                                    <p> <span
                                                                            class="person-info ">@lang('site.mobile')
                                                                            :</span> {{ $preson->mobile }}
                                                                    </p>
                                                                    <p> <span class="person-info">
                                                                            @lang('site.whatsapp') :</span>
                                                                        @if ($preson->whatsapp)
                                                                            {{ $preson->whatsapp }} <a
                                                                                target="_blank"
                                                                                href="https://api.whatsapp.com/send?phone={{ $preson->whatsapp }}"><i
                                                                                    class="fab fa-whatsapp"></i></a>
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </p>
                                                                    <p> <span
                                                                            class="person-info">@lang('site.email')
                                                                            :</span>
                                                                        @if ($preson->person_email)
                                                                            {{ $preson->person_email }}
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </p>
                                                                    <p> <span class="person-info">رقم
                                                                            @lang('site.national_id') :</span>
                                                                        @if ($preson->national_id)
                                                                            {{ $preson->national_id }}
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </p>
                                                                </div>


                                                            </div>
                                                        </div>

                                                    </div>
                                                @endif
                                            @endforeach

                                        @else
                                            <div class="time-label">
                                                <span class="bg-danger">
                                                    <i class="fas fa-users"></i>
                                                    @lang('site.responsible_person') ({{ $preson_count }})

                                                </span>
                                            </div>
                                            @foreach ($persons as $preson)
                                                <div>
                                                    <i class="fas fa-user bg-warning"></i>
                                                    <div class="timeline-item">
                                                        <h3 class="timeline-header">
                                                            {{ $preson->name }} </h3>
                                                        <div class="timeline-body body-info">

                                                            <div class="persons">

                                                                <p> <span
                                                                        class="person-info">@lang('site.job')
                                                                        :</span> {{ $preson->job_title }}
                                                                </p>
                                                                <p> <span
                                                                        class="person-info ">@lang('site.mobile')
                                                                        :</span> {{ $preson->mobile }} </p>
                                                                <p> <span class="person-info">
                                                                        @lang('site.whatsapp') :</span>
                                                                    @if ($preson->whatsapp)
                                                                        {{ $preson->whatsapp }} <a
                                                                            target="_blank"
                                                                            href="https://api.whatsapp.com/send?phone={{ $preson->whatsapp }}"><i
                                                                                class="fab fa-whatsapp"></i></a>
                                                                    @else
                                                                        --
                                                                    @endif
                                                                </p>
                                                                <p> <span
                                                                        class="person-info">@lang('site.email')
                                                                        :</span>
                                                                    @if ($preson->person_email)
                                                                        {{ $preson->person_email }}
                                                                    @else
                                                                        --
                                                                    @endif
                                                                </p>
                                                                <p> <span class="person-info">رقم البطاقة
                                                                        :</span>
                                                                    @if ($preson->national_id)
                                                                        {{ $preson->national_id }}
                                                                    @else
                                                                        --
                                                                    @endif
                                                                </p>
                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        @endif

                                        @if ($businessClient->person_note)
                                            <div class="time-label">
                                                <span class="bg-danger">
                                                    @lang('site.note')
                                                </span>
                                            </div>
                                            <div>
                                                <i class="fas fa-clipboard bg-success"></i>
                                                <div class="timeline-item">
                                                    <div class="timeline-body">
                                                        <ul class="product-item">
                                                            <li> <span class="profile_type_service">
                                                                    {{ $businessClient->person_note }}</span></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif

                                    </div>


                                </div>

                                {{-- Required file Business Client --}}
                                <div class="tab-pane" id="accredite">
                                    <!-- The timeline -->
                                    <div class="timeline timeline-inverse">
                                        <div class="time-label">
                                            <span class="bg-danger">
                                                @lang('site.businessClient_approval') ( 3 )
                                            </span>
                                        </div>

                                        {{-- tax_id_number --}}
                                        <div>
                                            <i class="fas fa-file-pdf bg-gradient-danger"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header"> @lang('site.tax_id_number_only')</h3>
                                                <div class="timeline-body">
                                                    @if (file_exists(public_path('client/business/tax_id_number/' . $businessClient->id . '.pdf')))
                                                        <a class="btn btn-success mb-2" style="font-size: 12px"
                                                        href="{{ asset("client/business/tax_id_number/$businessClient->id.pdf") }}"
                                                        target="_blank">@lang('site.show') @lang('site.file')
                                                        @lang('site.tax_id_number_only')</a>
                                                    @else
                                                        <span class="text-bold">@lang('site.file')
                                                            @lang('site.tax_id_number_only') :</span>
                                                        <span class="text-danger"> @lang('site.not_available')</span>
                                                    @endif
                                                    <p> <span class="text-bold">@lang('site.tax_id_number_only') :</span>
                                                        {{ $businessClient->tax_id_number }} </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- commercial_registeration_number --}}
                                        <div>
                                            <i class="fas fa-file-pdf bg-gradient-danger"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header">
                                                    @lang('site.commercial_registeration_number_only')</h3>
                                                <div class="timeline-body">
                                                    @if (file_exists(public_path('client/business/commercial_registeration_number/' . $businessClient->id . '.pdf')))
                                                        <a class="btn btn-success mb-2" style="font-size: 12px"
                                                        href="{{ asset("client/business/commercial_registeration_number/$businessClient->id.pdf") }}"
                                                        target="_blank">@lang('site.show') @lang('site.file')
                                                        @lang('site.commercial_registeration_number_only')</a>
                                                    @else
                                                        <span class="text-bold"> @lang('site.file')
                                                            @lang('site.commercial_registeration_number_only') :</span> <span
                                                            class="text-danger"> @lang('site.not_available')</span>
                                                    @endif

                                                    @if ($businessClient->commercial_registeration_number)
                                                        <p> <span
                                                            class="text-bold">@lang('site.commercial_registeration_number_only')
                                                            :</span> {{ $businessClient->commercial_registeration_number }}
                                                        </p>
                                                    @else
                                                        <p>
                                                            <span class="text-bold">
                                                                @lang('site.commercial_registeration_number_only') :</span> <span
                                                                class="text-danger"> @lang('site.not_available')</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        {{-- tax_file_number --}}
                                        <div>
                                            <i class="fas fa-file-pdf bg-gradient-danger"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header">
                                                    @lang('site.tax_file_number')</h3>
                                                <div class="timeline-body">

                                                    @if (file_exists(public_path('client/business/tax_file_number/' . $businessClient->id . '.pdf')))
                                                        <a class="btn btn-success mb-2" style="font-size: 12px"
                                                            href="{{ asset("client/business/tax_file_number/$businessClient->id.pdf") }}"
                                                            target="_blank">@lang('site.show')
                                                            @lang('site.tax_file_number')</a>
                                                    @else
                                                        <span class="text-bold"> @lang('site.file')
                                                            @lang('site.tax_file_number') :</span> <span
                                                            class="text-danger"> @lang('site.not_available')</span>
                                                    @endif

                                                    @if ($businessClient->tax_file_number)
                                                        <p> <span
                                                            class="text-bold">@lang('site.tax_file_number')
                                                            :</span> {{ $businessClient->tax_file_number }}
                                                        </p>
                                                    @else
                                                        <p>
                                                            <span class="text-bold">
                                                                @lang('site.tax_file_number') :</span> <span
                                                                class="text-danger"> @lang('site.not_available')</span>
                                                        </p>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Note --}}
                                        <div class="time-label">
                                            <span class="bg-danger">
                                                @lang('site.note')
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-clipboard bg-success"></i>
                                            <div class="timeline-item">
                                                <div class="timeline-body">
                                                    <ul class="product-item">
                                                        <li>
                                                            @if ($businessClient->accredite_note)
                                                                <span class="profile_type_service">
                                                                    {{ $businessClient->accredite_note }}</span>
                                                            @else
                                                                <span class="text-danger">
                                                                    @lang('site.not_available')</span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-12 mr-2 ml-1">
                    {{-- Change status to be approved --}}
                    @if ($notification->user_id != auth()->user()->id && $businessClient->approved == 0 && !$notification->comment && $notification->type == 'a')
                        <form method="POST"
                            action="{{ route('business_client_approved', $businessClient->id) }}?n_id={{ $notification->id }}">
                            @csrf
                            @method('put')
                            <div class="fieldset-footer pb-1 px-0">
                                <div class="row">
                                    <div class="col-md-12 mb-3 m-0">
                                        {{-- Show approve button if active user not user make this notification && this purchase order is not approved --}}
                                        <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-success ">@lang('site.approve') <i
                                                class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="col-12 mr-2 ml-1">
                    {{-- Reply with comment --}}
                    {{-- * Show if active user is not that notification owner
                        * and this record is not approved
                        * notification type is a => for action not n => for normal
                    --}}
                    @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $businessClient->approved == 0)
                        <form action="{{ route('notification.reply') }}" method="post">
                            @csrf
                            <input type="hidden" name="n_id" value="{{ $notification->id }}">
                            <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                            <div class="py-3">
                                <button type="submit" class="btn btn-danger mt-2 d-block">@lang('site.send')
                                    @lang('site.comment') <i class="fas fa-times"></i></button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

@endsection
