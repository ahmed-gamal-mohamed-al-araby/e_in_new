@extends('pages.layouts.master')

@section('title')
    @lang('site.dashboard')
@endsection

{{-- Custom Styles --}}
@section('styles')
@endsection

{{-- Page content --}}
@section('content')


    <!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.dashboard')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.dashboard')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('site.home')</a></li>

                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                {{--  Document  --}}
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box  bg-success">
                        <div class="inner">
                            @if ($document_count)
                                <h3>{{ $document_count }}</h3>
                                <p>@lang('site.all_documents')</p>
                            @else
                                <h3 class="dashboard-alert"><i class="fa fa-exclamation"></i></h3>
                                <p>@lang('site.no_documents')</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="fa fa-receipt"></i>
                        </div>
                        @if (auth()->user()->hasPermission('document_read'))
                            <a href="{{ route('documents.index') }}" class="small-box-footer"> @lang('site.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        @else
                            <a class="small-box-footer" style="height: 33px"> </a>
                        @endif
                    </div>
                </div>

                {{--  purchase orders  --}}

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box  bg-info">
                        <div class="inner">
                            @if ($po_count)
                                <h3>{{ $po_count }}</h3>
                                <p>@lang('site.purchaseorders')</p>
                            @else
                                <h3 class="dashboard-alert"><i class="fa fa-exclamation"></i></h3>
                                <p>@lang('site.no_purchaseorders')</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="far fa-file"></i>
                        </div>
                        @if (auth()->user()->hasPermission('po_read'))
                            <a href="{{ route('purchaseorders.index') }}" class="small-box-footer"> @lang('site.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        @else
                            <a class="small-box-footer" style="height: 33px"> </a>
                        @endif
                    </div>
                </div>

                {{--  Clients  --}}
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box  bg-warning">
                        <div class="inner">
                            @if ($clients_count)
                                <h3>{{ $clients_count }}</h3>
                                <p>@lang('site.all_clients')</p>
                            @else
                                <h3 class="dashboard-alert"><i class="fa fa-exclamation"></i></h3>
                                <p>@lang('site.no_clients')</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        @if (auth()->user()->hasPermission('client_read'))
                            <a href="{{ route('businessClients.index') }}" class="small-box-footer"> @lang('site.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        @else
                            <a class="small-box-footer" style="height: 33px"> </a>
                        @endif
                    </div>
                </div>

                {{-- users --}}
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box  bg-danger">
                        <div class="inner">
                            @if ($users_count)
                                <h3>{{ $users_count }}</h3>
                                <p> @lang('site.all_users')</p>
                            @else
                                <h3 class="dashboard-alert"><i class="fa fa-exclamation"></i></h3>
                                <p> @lang('site.no_users')</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="fa fa-users-cog"></i>
                        </div>
                        @if (auth()->user()->hasPermission('user_read'))
                            <a href="{{ route('users.index') }}" class="small-box-footer"> @lang('site.details') <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        @else
                            <a class="small-box-footer" style="height: 33px"> </a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="row">
                     {{-- Products --}}
                 <div class="col-lg-3 col-6">
                        @if(auth()->user()->hasPermission("product_read"))
                            <a href="{{ route('product.index') }}">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fa fa-box-open"></i></span>
                                @if($product_count)
                                <div class="info-box-content">
                                <span class="info-box-text"> @lang('site.products')</span>
                                <span class="info-box-number">{{ $product_count }}</span>
                                </div>
                                @else
                                <div class="info-box-content">
                                <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                                <span class="info-box-number no-item"> @lang('site.no_product')</span>
                                </div>
                                @endif
                                <!-- /.info-box-content -->
                            </div>
                            </a>
                        @else

                        <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-box-open"></i></span>
                        @if($product_count)
                        <div class="info-box-content">
                            <span class="info-box-text"> @lang('site.products')</span>
                            <span class="info-box-number">{{ $product_count }}</span>
                        </div>
                        @else
                        <div class="info-box-content">
                            <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                            <span class="info-box-number no-item"> @lang('site.no_product')</span>
                        </div>
                        @endif
                        <!-- /.info-box-content -->
                        </div>

                        @endif
                 </div>

                  {{-- banks --}}
                 <div class="col-lg-3 col-6">
                    @if(auth()->user()->hasPermission("bank_read"))
                        <a href="{{ route('bank.index') }}" style="color: #17a2b8">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                            @if($bank_count)
                            <div class="info-box-content">
                            <span class="info-box-text"> @lang('site.banks')</span>
                            <span class="info-box-number">{{ $bank_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                            <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                            <span class="info-box-number no-item"> @lang('site.no_bank')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>
                        </a>
                    @else
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                            @if($bank_count)
                            <div class="info-box-content">
                                <span class="info-box-text"> @lang('site.banks')</span>
                                <span class="info-box-number">{{ $bank_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                                <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                                <span class="info-box-number no-item"> @lang('site.no_bank')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>

                    @endif
                </div>


                   {{-- country --}}
                   <div class="col-lg-3 col-6">
                    @if(auth()->user()->hasPermission("country_read"))
                        <a href="{{ route('country.index') }}" style="color: #ffc107">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-flag"></i></span>
                            @if($country_count)
                            <div class="info-box-content">
                            <span class="info-box-text"> @lang('site.countries')</span>
                            <span class="info-box-number">{{ $country_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                            <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                            <span class="info-box-number no-item"> @lang('site.no_country')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>
                        </a>
                    @else
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-flag"></i></span>
                            @if($country_count)
                            <div class="info-box-content">
                                <span class="info-box-text"> @lang('site.countries')</span>
                                <span class="info-box-number">{{ $country_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                                <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                                <span class="info-box-number no-item"> @lang('site.no_country')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>

                    @endif
                </div>

                   {{-- City --}}
                   <div class="col-lg-3 col-6">
                    @if(auth()->user()->hasPermission("city_read"))
                        <a href="{{ route('city.index') }}" style="color: #dc3545">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-city"></i></span>
                            @if($city_count)
                            <div class="info-box-content">
                            <span class="info-box-text"> @lang('site.cities')</span>
                            <span class="info-box-number">{{ $city_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                            <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                            <span class="info-box-number no-item"> @lang('site.no_city')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>
                        </a>
                    @else
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-city"></i></span>
                            @if($city_count)
                            <div class="info-box-content">
                                <span class="info-box-text"> @lang('site.cities')</span>
                                <span class="info-box-number">{{ $city_count }}</span>
                            </div>
                            @else
                            <div class="info-box-content">
                                <span class="info-box-text"><i class="fa fa-exclamation"></i></span>
                                <span class="info-box-number no-item"> @lang('site.no_city')</span>
                            </div>
                            @endif
                            <!-- /.info-box-content -->
                        </div>

                    @endif
                </div>





            </div>


        </div>
    </section>
@endsection



{{-- Custom scripts --}}
@section('scripts')
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection
