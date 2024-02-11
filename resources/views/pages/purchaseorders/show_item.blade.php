@extends('pages.layouts.master')

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
@endsection

@section('content')
    <section class="content-header prequestHeader">

        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1> @lang('site.show_purchaseorder') </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item active">  (  {{ $purchaseorder->purchase_order_reference }} ) @lang('site.purchaseorder_details')</li> --}}
                        <li class="breadcrumb-item active"><a href="#"> @lang('site.all_purchaseorders')</a> </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card show-one-request">
        <div class="card-header parent">
            <h3>@lang('site.show_purchaseorder') </h3>
        </div>

        <div class="row mt-2  p-3">
            <div class="col-5">
                <table class="table table-bordered table-striped table-hover float-right table-striped ">
                    <tbody>
                        <tr>
                            <th width="30%">
                                @lang('site.name')
                            </th>
                            <th>
                                {{ $poItem->product->product_name }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.product_code')
                            </th>
                            <th>
                                {{ $poItem->product->product_code }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.description')
                            </th>
                            <th>
                                {{ $poItem->description }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.quantity')
                            </th>
                            <th>
                                {{ $poItem->quantity }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.unit')
                            </th>
                            <th>
                                {{ $poItem->unit }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.price')
                            </th>
                            <th>
                                {{ $poItem->item_price }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.discount_rate')
                            </th>
                            <th>
                                {{ $poItem->discount_item_rate }} %
                            </th>
                        </tr>
                        <tr>
                        </tr>

                        <tr>
                            <th>
                                @lang('site.created_at')
                            </th>
                            <th>
                                {{ $poItem->created_at }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-7">
                @if (count($poItem->purchaseOrderTaxes))
                    <table class="table table-bordered table-striped table-hover float-right table-striped ">
                        <tbody>
                            <tr>
                                <th>
                                    @lang('site.tax_type')
                                </th>
                                <th>
                                    @lang('site.tax_sub_type')
                                </th>
                                <th>
                                    @lang('site.rate')
                                </th>
                                <th>
                                    @lang('site.value')
                                </th>
                            </tr>
                            @foreach ($poItem->purchaseOrderTaxes as $taxes)
                                <tr>
                                    <td>
                                        @if ($taxes->tax_type == 1)
                                            Value added tax (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 2)
                                            Table tax (percentage) (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 3)
                                            Table tax (Fixed Amount) (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 4)
                                            Withholding tax (WHT) (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 5 || $taxes->tax_type == 13)
                                            Stamping tax (percentage) (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 6 || $taxes->tax_type == 14)
                                            Stamping Tax (amount) (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 7 || $taxes->tax_type == 15)
                                            Entertainment tax (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 8 || $taxes->tax_type == 16)
                                            Resource development fee (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 9 || $taxes->tax_type == 17)
                                            Service charges (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 10 || $taxes->tax_type == 18)
                                            Municipality Fees (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 11 || $taxes->tax_type == 19)
                                            Medical insurance fee (T{{ $taxes->tax_type }})
                                        @elseif($taxes->tax_type == 12 || $taxes->tax_type == 20)
                                            Other fees (T{{ $taxes->tax_type }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ $taxes->subtype }}
                                    </td>
                                    <td>
                                        {{ $taxes->tax_rate }}
                                    </td>
                                    <td>
                                        {{ $taxes->amount_tax }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="alert alert-default-info">@lang('site.no_taxes')</p>
                @endif
                <table class="table table-bordered table-striped table-hover float-right table-striped ">
                    <tbody>
                        <tr>
                            <th width="30%">
                                @lang('site.taxable_fees')
                            </th>
                            <th>
                                {{ $poItem->taxable_fees }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.value_diffrence')
                            </th>
                            <th>
                                {{ $poItem->value_difference }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.item_discount')
                            </th>
                            <th>
                                {{ $poItem->items_discount }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.net_total')
                            </th>
                            <th>
                                {{ $poItem->net_total }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                @lang('site.total_amount')
                            </th>
                            <th>
                                {{ $poItem->total_amount }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>



        </div>



    </div>

@endsection
