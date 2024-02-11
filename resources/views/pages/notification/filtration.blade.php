@extends('pages.layouts.master')

@section('title')
    @lang('site.notifications_filter')
@endsection

{{-- Custom Styles --}}
@section('styles')
    @if (Config::get('app.locale') == 'ar')
        <style>
            .date {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

        </style>
    @endif
@endsection

{{-- Page content --}}
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>
                        @lang('site.notifications_filter')
                    </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.notifications_filter')
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>

                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="col-10 offset-1">
        <!-- Partner Requests-->
        <div class="card updates daily-feeds">
            <div id="feeds-box" role="tabpanel" class="collapse0 show m-3 mt-4" style="">
                <form action="{{ route('notification.filtration') }}"method="Post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <select name="table_name" class="custom-select" required oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.table_name')')"
                            oninput="setCustomValidity('')">
                                <option selected disabled value="">@lang('site.please') @lang('site.select') @lang('site.table_name')</option>
                                <option @if($request) {{ $request['table_name'] == 'purchase_orders'? 'selected' : '' }} @endif value="purchase_orders">@lang('site.purchaseorder')</option>
                                <option @if($request) {{ $request['table_name'] == 'documents'? 'selected' : '' }} @endif value="documents">@lang('site.document')</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <input name="reference" type="text" required class="form-control" @if($request) value="{{ $request['reference'] }}" @endif placeholder="@lang('site.please') @lang('site.enter') @lang('site.reference')" oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.reference')')"
                            oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <div class="textDirection">
                        <button type="submit" class="btn btn-success mb-2">@lang('site.filter')</button>
                    </div>
                </form>
                <div class="feed-box">
                    <ul class="feed-elements list-unstyled mb-0">
                        <!-- List-->
                        @forelse ($allNotifications as $notification)
                            <li class="clearfix pb-2 @if (!$loop->last) border-bottom
                                mb-4 @endif">
                                <div class="feed d-flex justify-content-between">
                                    <div class="feed-body d-flex justify-content-between"><a href="#"
                                            class="feed-profile"><img src="{{ asset('Images/user_profile.png') }}"
                                                alt="person" class="profile-photo-nav mr-2"></a>
                                        <div class="content"><strong>{{ $notification->user->username }}</strong>
                                            @if ($notification->view_status)
                                                    <i class="far fa-eye-slash"></i>
                                            @else <i class="far fa-eye"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="date"><small>
                                            <span
                                                class="alert alert-info py-0 px-1 text-center mb-2 d-inline-block">@lang('site.notification_date')
                                            </span> <i>{{ $notification->created_at->diffForHumans() }}</i>
                                            <span
                                                class="alert alert-info py-0 px-1 text-center mb-2 d-inline-block">@lang('site.notification_action')
                                            </span> <i>{{ $notification->updated_at->diffForHumans() }}</i>
                                        </small>
                                    </div>
                                </div>
                                <div class="card mx-2 mt-2 p-3 text-right" style="direction:rtl !important;">
                                    <small>{!! nl2br($notification->content) !!}</small>

                                    @if ($notification->comment)
                                    <hr>
                                        <h4>@lang('site.comment')</h4>
                                        <small>{!! nl2br($notification->comment->content) !!}</small>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <div class="alert alert-dark textDirection" role="alert">
                                @lang('site.empty_notifications')
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Partner Requests-->
    </div>
    <!-- /.content -->

@endsection
