@extends('pages.layouts.master')

@section('title')
    @lang('site.products')
@endsection

{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.products')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.products')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                @if (auth()->user()->hasPermission('product_read'))
                    <div class="col-12">
                        <div class="add-service">
                            <form action="{{ route('product.update', $product->id) }}" method="Post">
                                <div class="row">

                                    @csrf
                                    @method('put')

                                    {{-- product name --}}
                                    <div class="form-group col-12 col-md-6">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="product_name" value="{{ $product->product_name }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- standard code type --}}
                                    <div class="form-group col-12 col-md-6">
                                        <label>@lang('site.standard_code_type')</label>
                                        <select class="custom-select" name="standard_code_type" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.standard_code_type')')"
                                            oninput="setCustomValidity('')">
                                            <option value="">@lang('site.please') @lang('site.select')
                                                @lang('site.standard_code_type')</option>
                                            <option value="GS1"
                                                {{ $product->standard_code_type == 'GS1' ? 'selected' : '' }}>
                                                GS1
                                            </option>
                                            <option value="EGS"
                                                {{ $product->standard_code_type == 'EGS' ? 'selected' : '' }}>
                                                EGS
                                            </option>
                                        </select>
                                        @error('standard_code_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- product code --}}
                                    <div class="form-group col-12 col-md-6">
                                        <label>@lang('site.product_code')</label>
                                        <input type="text" name="product_code" value="{{ $product->product_code }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.product_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- internal code --}}
                                    <div class="form-group col-12 col-md-6">
                                        <label>@lang('site.internal_code')</label>
                                        <input type="text" name="internal_code" value="{{ $product->internal_code }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.internal_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('internal_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mr-2">
                                        <input type="submit" class="btn btn-success" value="@lang('site.edit')">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
