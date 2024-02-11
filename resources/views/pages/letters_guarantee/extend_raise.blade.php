@extends('pages.layouts.master')

@section('title')
@lang('site.letter_guarantee')
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('invoice/css/stylee.css') }}">
<style>
    #edit-btn {
        margin-top: 10px;
    }

    #image {
        margin-top: 30px;
    }

    #link {
        margin-top: 30px;
    }
</style>
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> @lang('site.extend_raise') @lang('site.letter_guarantee')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.extend_raise') @lang('site.letter_guarantee') </li>
                    <li class="breadcrumb-item "><a href="{{route('letter_guarantee.index')}}"> @lang('site.letter_guarantee') </a></li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content service-content user-edit-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 form-group">
                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif

                @if(count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="col-12">
                <form action="{{route('letter_guarantee.extend_raise_store',$letter_guarantee->id)}}" method="POST" enctype="multipart/form-data" id="commentForm">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body text-right">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                <input type="hidden" name="letters_guarantee_id" value="{{$letter_guarantee->id}}">

                                <div class="col-md-6 ">
                                    <label for="validationCustom02">@lang('site.release_date')</label>
                                    <input value="{{ $letter_guarantee->release_date }}" id="release_date" type="date" readonly placeholder="@lang('site.release_date')" class="form-control" name="release_date">

                                    @error('release_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>




                                <div class="col-md-6 ">
                                    <label class="form-label" for="">@lang('site.expiry_date')</label>

                                    <input id="expiry_date" min="{{ $letter_guarantee->release_date }}" type="date" required placeholder="@lang('site.expiry_date')" id="" class="form-control @error('image') is-invalid @enderror" name="expiry_date" value="{{ $expiry_date }}">

                                    @error('expiry_date')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                    @enderror

                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">@lang('site.value')</label>
                                    <input required value="{{ $value }}" type="text" name="value" id="value" class="form-control @error('value') is-invalid @enderror" placeholder="@lang('site.value')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('value')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="">@lang('site.cash_margin')</label>
                                    <input required value="{{ $cash_margin }}" type="text" name="cash_margin" id="cash_margin" class="form-control @error('cash_margin') is-invalid @enderror" placeholder="@lang('site.cash_margin')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('cash_margin')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="">@lang('site.image')</label>
                                    <input value="{{ old('image') }}" type="file" name="image" id="image" class="@error('image') is-invalid @enderror" placeholder="@lang('site.image')">
                                    <!-- Way 2: Display Error Message -->
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-12 form-group">
                                <input type="submit" id="edit-btn" class="btn btn-success" value="@lang('site.edit')">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>


{{-- Client section --}}
<script type="text/javascript">
    $("#commentForm").validate();
</script>
<script>
    // Client Type
    select2Function($('#client_type'), "@lang('site.client_type')");
    // Client Name
    select2Function($('#foreigner-client'), "@lang('site.client_name')");
    select2Function($('#client_name'), "@lang('site.client_name')");
    select2Function($('#supply_order'), "@lang('site.supply_order')");
    select2Function($('#bank'), "@lang('site.bank')");
    select2Function($('#type'), "@lang('site.type')");

    function select2Function(selector, placeholder) {
        const targetSelector = $(selector).parent().parent().next().find('select');
        $(selector).select2();
        $(selector).select2({
            allowClear: true,
            placeholder: placeholder,
        });
    }
</script>
{{-- Submit section --}}
<script>
    $('[type="submit"]').on('click', function() {
        if (validate()) {
            submit();
        }
    })
</script>


<script>



</script>
@endsection