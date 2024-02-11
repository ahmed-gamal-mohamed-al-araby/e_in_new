@extends('pages.layouts.master')

@section('title')
    @lang('site.notifications')
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

    <style>
            .alert {
                direction: ltr !important;
            }

    </style>
@endsection

{{-- Page content --}}
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>
                        @if (!$viewed)
                            @lang('site.see_all_new_notifications')
                        @else
                            @lang('site.see_all_old_notifications')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @if (!$viewed)
                                @lang('site.see_all_new_notifications')
                            @else
                                @lang('site.see_all_old_notifications')
                            @endif
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
                                            @if (!$viewed && $notification->user_id != auth()->user()->id && $notification->type == 'n')
                                                <a href="{{ route('notification.view_status', $notification->id) }}"
                                                    class="@if ($notification->view_status) disable-click @endif">
                                                    @if ($notification->view_status)
                                                    <i class="far fa-eye-slash"></i> @else <i class="far fa-eye"></i>
                                                    @endif
                                                </a>
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

                                {{-- @if (!$viewed && $notification->type == 'a' && $notification->user_id != auth()->user()->id)
                                    <form action="{{ route('notification.reply') }}" method="post" class="mx-5">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $notification->id }}">
                                        <textarea class="form-control text-right" name='comment' rows="3"></textarea>
                                        <button type="submit" class="btn btn-success mt-2 d-block"><i
                                                class="far fa-eye pr-2"></i>@lang('site.send')</button>
                                    </form>
                                @endif --}}
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
