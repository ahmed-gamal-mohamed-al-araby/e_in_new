@extends('pages.layouts.master')

@section('title')
    @lang('site.users')
@endsection

{{-- Page content --}}
@section('content')

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 >  @lang('site.add_user')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active"> @lang('site.add_user')  </li>
                            <li class="breadcrumb-item "><a href="{{route('users.index')}}">  @lang('site.users') </a> </li>
                            <li class="breadcrumb-item"><a href="{{route('home')}}">  @lang('site.home')</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content service-content user-edit-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body text-right">
                                <form action="{{route('users.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row ">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.login_user_name')</label>
                                            <input id="name" type="text" placeholder="@lang('site.login_user_name')" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.name')</label>
                                            <input id="name" type="text" placeholder="@lang('site.name')" class="form-control @error('name') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="name" autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.email')</label>
                                            <input id="email" type="email" placeholder="@lang('site.email')" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.password')</label>
                                            <input id="password" type="password" placeholder="@lang('site.password')" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.confrim_password')</label>
                                            <input id="password-confirm" placeholder="@lang('site.confrim_password')" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.permissions')</label>
                                            <select name="role"  class="form-control pt-0" required>
                                                <option selected hidden >@lang('site.choose')</option>
                                                @foreach ($roles as $role)
                                                     <option  value="{{ $role->name }}">{{ ucwords(str_replace ('_', ' ', $role->name)) }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">@lang('site.companies')</label>
                                            <select name="company_id"  class="form-control pt-0" required>
                                                <option selected hidden >@lang('site.choose')</option>
                                                @foreach ($companies as $company)
                                                     <option  value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>



                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit"  class="btn btn-success" value="@lang('site.save')">
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </section>

 @endsection

