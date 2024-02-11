

{{--@section('content')--}}
{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Login') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('login') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                    <label class="form-check-label" for="remember">--}}
{{--                                        {{ __('Remember Me') }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-8 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Login') }}--}}
{{--                                </button>--}}

{{--                                @if (Route::has('password.request'))--}}
{{--                                    <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                        {{ __('Forgot Your Password?') }}--}}
{{--                                    </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endsection--}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="{{asset('dist/css/login.css')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('dist/css/loader.css')}}" />
    <style>
        body, h1, h2, h3, h4, h5, h6 {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
    <title>@lang('site.system_title')</title>
</head>
<body>
<div class="login-loader">
    <svg viewBox="0 0 1350 600">
        <text x="50%" y="50%" fill="transparent" text-anchor="middle">
            EEC  Group
        </text>

    </svg>
</div>
<div class="container sign-up-mode">
    <div class="overlay"></div>
    <div class="forms-container">

        <div class="signin-signup" dir="rtl">

            {{--  @isset($_GET['email'])
            {{$_GET['email']}}
          @endisset  --}}
          {{--  @if(request()->email)
          {{ request()->email }}
          @endif  --}}

          @if($validUser ?? '' == 2)
          <form class="sign-up-form" method="POST" action="{{ route('login') }}">
            @csrf
        <h2 class="title">@lang('site.login')</h2>

            <div class="input-field">
                <i class="fas fa-user"></i>
                <input id="email" type="text" required placeholder="@lang('site.name')" name="name" oninvalid="this.setCustomValidity('من فضلك أدخل الاسم')" oninput="setCustomValidity('')">

            </div>
            <p class="login-error-message">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </p>

            <div class="input-field">
                <i class="fas fa-lock"></i>
                    <input id="password" type="password"  placeholder="@lang('site.password')"  class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                           required="" oninvalid="this.setCustomValidity('من فضلك أدخل كلمة المرور')"  oninput="setCustomValidity('')" >
            </div>
            <p class="login-error-message">
                @error('password')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
            </p>
            <p class="login-error-message">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </p>
            <input type="submit" class="btn" value="@lang('site.save_login')" />


        </form>

          @elseif($validUser ?? '' == 1)
                <div class="message-permission">
                    <span>  عفوا !</span>
                <br>    لا يمكنك الدخول للتطبيق
                    <br>  الرجاء الرجوع للشخص المسئول

                </div>
          @else
          <form class="sign-up-form" method="POST" action="{{ route('login') }}">
            @csrf
        <h2 class="title">@lang('site.login')</h2>

            <div class="input-field">
                <i class="fas fa-user"></i>
                <input id="email" type="text" required  placeholder="@lang('site.name')" name="name" oninvalid="this.setCustomValidity('من فضلك أدخل الاسم')" oninput="setCustomValidity('')">

            </div>
            <p class="login-error-message">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </p>

            <div class="input-field">
                <i class="fas fa-lock"></i>
                    <input id="password" type="password"  placeholder="@lang('site.password')"  class=" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                           required="" oninvalid="this.setCustomValidity('من فضلك أدخل كلمة المرور')"  oninput="setCustomValidity('')" >
            </div>
            <p class="login-error-message">
                @error('password')
                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                @enderror
            </p>
            <p class="login-error-message">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </p>
            <input type="submit" class="btn" value="@lang('site.save_login')" />


        </form>

          @endif



        </div>
    </div>

    <div class="panels-container">

        <div class="panel left-panel">

        </div>
        <div class="panel right-panel">
            <div class="content">
                <h3>EEC Group</h3>
                <p>
                    <br> @lang('site.system_title')
                </p>

            </div>
            <img src="{{asset('Images/register.svg')}}" class="image" alt="" />
        </div>


    </div>
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('dist/js/demo.js')}}"></script>
</body>
</html>
