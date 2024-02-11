@extends('pages.layouts.master')

@section('title')
    @lang('site.users')
@endsection

{{-- Page content --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">@lang('site.reset_password')</div>

                    <div class="card-body">
                        @if($user)
                            <form method="POST" action="{{route('users.reset_password',$user->id)}}" id="form">
                        @else
                            <form method="POST" action="{{route('users.reset_password')}}" id="form">
                        @endif
                            @csrf
                            @method('put')

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">@lang('site.password')</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">@lang('site.confrim_password')</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <p id="confirm-password-error" class="alert alert-danger d-none">@lang('site.confirm_password_not_match')</p>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-warning">
                                        @lang('site.edit')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 @endsection


 @section('scripts')
    <script>
        $('#form').on('submit', function (e) {
            if($('#password-confirm').val() != $('#password').val()) {
                e.preventDefault();
                $('#confirm-password-error').removeClass('d-none');
            } else {
                $('#confirm-password-error').addClass('d-none');
            }
        })

    </script>
 @endsection
