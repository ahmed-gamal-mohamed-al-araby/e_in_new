<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-success sticky-top">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
    @if (!auth()->user()->hasRole('show_invoices') && !auth()->user()->hasRole('taxes_report_show'))
        @if (!auth()->user()->hasRole('document_request_user'))
            <!-- Notifications Dropdown Menu -->

                @if(auth()->user()->id != 18)
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">{{ $notificationsCounter }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span
                        class="dropdown-item dropdown-header">{{ $notificationsCounter }} @if($notificationsCounter > 1) @lang('site.notifications') @else @lang('site.notification') @endif</span>
                            @foreach ($notifications as $notification)
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notification.index') }}" class="dropdown-item">
                                    <i class="far fa-bell"></i> {!! nl2br(substr($notification->content, 0, 15).' ...') !!}
                                    <span
                                        class="float-right text-muted text-sm">{{ $diff = Carbon\Carbon::parse($notification->updated_at)->diffForHumans() }}</span>
                                </a>
                            @endforeach


                            <div @if (Lang::locale() == 'ar') style='direction: rtl' @endif>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notification.index') }}" class="dropdown-item dropdown-footer"><i
                                        class="far fa-eye"></i> @lang('site.see_all_new_notifications')
                                    ({{ $notificationsCounter }})</a>

                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notification.viewed') }}" class="dropdown-item dropdown-footer"><i
                                        class="far fa-eye-slash"></i> @lang('site.see_all_old_notifications')
                                    ({{ $notificationsReadCounter }})</a>

                                <div class="dropdown-divider"></div>
                                <a href="{{ route('notification.filtration') }}"
                                   class="dropdown-item dropdown-footer"><i
                                        class="fas fa-search"></i> @lang('site.notifications_filter')</a>
                                @if (auth()->user()->hasRole('admin'))
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('notification.archiveView') }}"
                                       class="dropdown-item dropdown-footer"><i
                                            class="fas fa-archive"></i> @lang('site.archive') @lang('site.small_notifications')
                                    </a>
                                @endif

                            </div>

                        </div>
                    </li>
                @endif
            @endif
        @endif


        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img class="profile-photo-nav" src="{{ asset('Images/user_profile.png') }}" alt="user profile">
                <span class="text-white">{{ Auth::user()->username }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <img class="profile-photo-dropdown" src="{{ asset('Images/user_profile.png') }}" alt="user profile">
                <p class="profile-name-dropdown"> {{  Auth::user()->username }} </p>

                <div class="dropdown-divider"></div>
                @if (!auth()->user()->hasPermission('get_recent_documents_received'))

                    <p class="m-2 text-center"><a href="{{ route('users.show_reset_password') }}"> <i
                                class="fas fa-key"></i> @lang('site.reset_password')</a></p>
                @endif
                {{-- <div class="dropdown-divider"></div>
                <p class="m-2 text-center"><a href="{{ route('users.show_reset_password', Auth::user()->id) }}"> <i class="fas fa-image"></i> @lang('site.edit_user_profile')</a></p> --}}

                <div class="dropdown-divider"></div>


                <a class="dropdown-item text-center" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="user-nav-icon float-right0 fas fa-sign-out-alt mr-2"></i> <span
                        class=" float-right0 text-muted text-sm">@lang('site.logout')</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <div class="dropdown-divider"></div>
            </div>

        </li>


        <!--language -->
        <li class="nav-item dropdown">
            @if(LaravelLocalization::getCurrentLocale() == 'en')
                <a class="nav-link" data-toggle="dropdown" href="#">
                    English <i class="far fa-flag"> </i>

                </a>
            @elseif(LaravelLocalization::getCurrentLocale() == 'ar')
                <a class="nav-link" data-toggle="dropdown" href="#">
                    العربية <i class="far fa-flag"></i>

                </a>
            @endif


            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a rel="alternate" hreflang="{{ $localeCode }}"
                       href="{{ LaravelLocalization::getLocalizedURL($localeCode)  }}"
                       class="dropdown-item">
                        <span class="float-left text-muted text-sm">{{ $properties['native'] }}</span>

                        <img style=" width: 30px;
                   height: 20px;
                   float: right;
                   margin-top: 3px;" src="{{asset('Images/flag_img/'.$localeCode.'.png')}}" alt="flag imgage"/>

                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach
            </div>
        </li>
    </ul>
</nav>

