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
                            <div class="row">
                                {{-- product name --}}
                                <div class="form-group col-12 col-md-6">
                                    <label>@lang('site.name')</label>
                                    <input  readonly value="{{ $product->product_name }}"
                                        class="form-control">
                                </div>

                                {{-- standard code type --}}
                                <div class="form-group col-12 col-md-6">
                                    <label>@lang('site.standard_code_type')</label>
                                    <select disabled class="custom-select">
                                        <option value="">@lang('site.please') @lang('site.select')
                                            @lang('site.standard_code_type')</option>
                                        <option value="GS1" {{ $product->standard_code_type == 'GS1' ? 'selected' : '' }}>
                                            GS1
                                        </option>
                                        <option value="EGS" {{ $product->standard_code_type == 'EGS' ? 'selected' : '' }}>
                                            EGS
                                        </option>
                                    </select>
                                </div>

                                {{-- product code --}}
                                <div class="form-group col-12 col-md-6">
                                    <label>@lang('site.product_code')</label>
                                    <input readonly value="{{ $product->product_code }}"
                                        class="form-control">
                                </div>

                                {{-- internal code --}}
                                <div class="form-group col-12 col-md-6">
                                    <label>@lang('site.internal_code')</label>
                                    <input readonly value="{{ $product->internal_code }}"
                                        class="form-control">
                                </div>
                            </div>

                            {{-- Change status to be approved --}}
                            {{-- 
                                * Show if active user is not that notification owner 
                                * and this record is not approved 
                                * notification not have comment
                                * notification type is a => for action not n => for normal
                            --}}
                            @if ($notification->user_id != auth()->user()->id && $product->approved == 0 && !$notification->comment && $notification->type == 'a')
                                <form method="POST" action="{{ route('product_approved', $product->id) }}?n_id={{ $notification->id }}">
                                    @csrf
                                    @method('put')
                                    <div class="fieldset-footer pb-1">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                {{-- Show approve button if active user not user make this notification && this purchase order is not approved --}}
                                                <input type="hidden" name="n_id" value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-success">@lang('site.approve') <i class="fas fa-check"></i></button>                                </div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            {{-- Reply with comment --}}
                            {{-- 
                                * Show if active user is not that notification owner 
                                * and this record is not approved 
                                * notification type is a => for action not n => for normal
                            --}}
                            @if ($notification->type == 'a' && $notification->user_id != auth()->user()->id && $product->approved == 0)
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
                            {{-- 
                                * Show if active user is notification owner 
                                * and this record is not approved 
                                * notification have comment or notification type is a => for action not n => for normal
                            --}}
                            @if ($notification->user_id == auth()->user()->id && $product->approved == 0 && ($notification->type == 'a' || $notification->comment))
                                <div class="row pb-3 justify-content-start">
                                    <a class="btn btn-sm btn-warning m-1"
                                        href="{{ route('product.approve_edit', $product->id) }}" data-toggle="tooltip"
                                        data-placement="top" title="Edit">
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
