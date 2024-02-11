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
                @if (auth()->user()->hasPermission('client_read'))
                    <div class="col-12">
                        <div class="add-service row">
                            {{-- foreigner Client company name --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.company_name')</label>
                                    <input readonly value="{{ $foreignerClient->company_name }}" class="form-control">
                                </div>
                            </div>

                            {{-- foreigner Client name --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.person_name')</label>
                                    <input readonly value="{{ $foreignerClient->person_name }}" class="form-control">
                                </div>
                            </div>

                            {{-- person email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.person_email')</label>
                                    <input readonly value="{{ $foreignerClient->person_email }}" class="form-control">
                                </div>
                            </div>

                            {{-- person mobile --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.person_mobile')</label>
                                    <input readonly value="{{ $foreignerClient->person_mobile }}" class="form-control">
                                </div>
                            </div>

                            {{-- VAT ID --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.vat_id')</label>
                                    <input readonly value="{{ $foreignerClient->vat_id }}" class="form-control">
                                </div>
                            </div>

                            <div class="col-12 border-bottom my-2"></div>
                            
                            {{-- Country --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.the_country')</label>
                                    <input readonly class="form-control" value="{{ ucfirst($foreignerClient->address->country->name) }}">
                                </div>
                            </div>

                            {{-- City --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.the_region_city')</label>
                                    @if($foreignerClient->address->city_id)
                                    <input readonly class="form-control" value="{{ ucfirst($foreignerClient->address->city->name) }}">
                                    @else
                                    <input readonly class="form-control" value="">
                                    @endif

                                </div>
                            </div>

                            {{-- region city --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.the_region_city')</label>
                                    <input class="form-control" readonly
                                        value="{{ $foreignerClient->address->region_city }}">
                                </div>
                            </div>

                            {{-- street --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('site.the_street')</label>
                                    <input class="form-control" readonly value="{{ $foreignerClient->address->street }}">
                                </div>
                            </div>


                            {{-- building number --}}
                            <div class="col-md-6 offset-md-6">
                                <div class="form-group">
                                    <label>@lang('site.building_no')</label>
                                    <input class="form-control" readonly
                                        value="{{ $foreignerClient->address->building_no }}">
                                </div>
                            </div>

                            <div class="col-12">
                                {{-- Change status to be approved --}}
                                {{-- * Show if active user is not that notification owner 
                                * and this record is not approved 
                                * notification not have comment
                                * notification type is a => for action not n => for normal --}}
                                @if ($notification->user_id != auth()->user()->id && $foreignerClient->approved == 0 && !$notification->comment && $notification->type == 'a')
                                    <form method="POST"
                                        action="{{ route('foreigner_client_approved', $foreignerClient->id) }}?n_id={{ $notification->id }}">
                                        @csrf
                                        @method('put')
                                        <div class="fieldset-footer pb-1">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    {{-- Show approve button if active user not user make this notification && this purchase order is not approved --}}
                                                    <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                                    <button type="submit" class="btn btn-success">@lang('site.approve') <i
                                                            class="fas fa-check"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                {{-- Reply with comment --}}
                                {{-- * Show if active user is not that notification owner 
                                * and this record is not approved 
                                * notification type is a => for action not n => for normal --}}
                                @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $foreignerClient->approved == 0)
                                    <form action="{{ route('notification.reply') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                        <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                                        <div class="row justify-content-start py-3 pr-2">
                                            <button type="submit" class="btn btn-danger mt-2 d-block">@lang('site.send')
                                                @lang('site.comment')
                                                <i class="fas fa-times"></i></button>
                                        </div>
                                    </form>
                                @endif

                                {{-- Show edit for user that have this notification --}}
                                {{-- * Show if active user is notification owner 
                                * and this record is not approved 
                                * notification have comment or notification type is a => for action not n => for normal --}}
                                @if ($notification->user_id == auth()->user()->id && $foreignerClient->approved == 0 && ($notification->type == 'a' || $notification->comment))
                                    <div class="row pb-3 justify-content-start">
                                        <a class="btn btn-sm btn-warning m-1"
                                            href="{{ route('business_client.approve_edit', $foreignerClient->id) }}"
                                            data-toggle="tooltip" data-placement="top" title="Edit">
                                            @lang('site.go_for_edit')<i class="ml-2 fas fa-edit"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                @endif
            </div>
        </div>
    </section>

@endsection
