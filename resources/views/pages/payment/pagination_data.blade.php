@php
    $currentLanguage = app()->getLocale();
    $currentIndex = $payments->firstItem();
@endphp

<table id="datatableTemplate" class="table table-bordered table-striped text-center sort-table">

    {{-- Table Header --}}
    <thead>
    <tr>
        <th> @lang('site.id')</th>
        <th>@lang('site.reference')</th>
        <th>@lang('site.reference')</th>
        <th>@lang('site.client')</th>
        <th>@lang('site.payment')</th>
        <th class="text-nowrap">@lang('site.bank_name')</th>
        <th class="text-nowrap">@lang('site.payment_date')</th>
        <th>@lang('site.value')</th>
        <th>@lang('site.file')</th>
        @if (auth()->user()->hasPermission('document_read'))
            <th style="width: 150px"> @lang('site.actions')</th>
        @endif
    </tr>
    </thead>

    {{-- Table body --}}
    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td>{{ $currentIndex++ }}</td>
            <td>
                @if ($payment->table == 'PO')
                    @lang('site.purchaseorder')
                @else
                    @lang('site._document')
                @endif
            </td>
            <td>
                @if ($payment->table == 'PO')
                    <a href="{{ route('purchaseorders.show', $payment->table_id) }}">{{ $payment->purchaseOrder->purchase_order_reference }}</a>
                @else
                    <a href="{{ route('documents.show', $payment->table_id) }}">{{ $payment->document->document_number }}</a>
                @endif
            </td>
            <td>
                @if ($payment->client_type == 'f')
                    <a href="{{ route('foreignerClient.index') }}">
                        {{ $payment->foreignerClient->company_name }} </a>
                @elseif($payment->client_type == 'p')
                    <a href="{{ route('personClient.index') }}">
                        {{ $payment->personClient->name }} </a>
                @else
                    <a href="{{ route('businessClients.profile',  $payment->client_id) }}">{{ $payment->businessClient->name }}</a>
                @endif
            </td>
            <td>
                @if ($payment->payment_method == 'cashe')
                    @lang('site.cash')
                @elseif ($payment->payment_method == 'bank_transfer')
                    @lang('site.bank_transfer')
                @elseif ($payment->payment_method == 'cheque')
                    @lang('site.cheque')
                @else
                    @lang('site.deduction')
                @endif
            </td>
            <td>
                @if ($payment->payment_method == 'cashe')
                    _
                @elseif ($payment->payment_method == 'bank_transfer' && isset($payment->bank_transfer->bank->id))
                    <a href="{{ route('bank.show', $payment->bank_transfer->bank->id) }}">{{ $payment->bank_transfer->bank->bank_name }}</a>
                @elseif ($payment->payment_method == 'cheque')
                    <a href="{{ route('bank.show', $payment->cheque->bank->id) }}">{{ $payment->cheque->bank->bank_name }}</a>
                @else
                    _
                @endif
            </td>
            <td>{{ $payment->payment_date }}</td>
            <td>{{ number_format($payment->value, 2) }}</td>
            <td>

                @if ($payment->file && file_exists(public_path('payment/files/' . $payment->file)))
                    <a class="btn btn-success mb-2" style="font-size: 12px"
                       href='{{ asset("payment/files/$payment->file") }}'
                       target="_blank">@lang('site.show') @lang('site.file')</a>
                @else
                    <span class="btn btn-sm btn-danger"
                          style="cursor: default; font-size: 12px">@lang('site.not_available')</span>
                @endif

            </td>
            <td>
                <div class="row service-option justify-content-center">
                    <div class="col-md-2 col-sm-6 m-2">
                        <a href="{{ route('payment.show',$payment->id) }}" class=" btn btn-success btn-sm my-1"><i
                                class="fa fa-eye m-0"></i> </a>
                    </div>
                    @if ($pageType == 'index')
                        @if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator ') || auth()->user()->hasRole('normal'))
                            <div class="col-md-2 col-sm-6 m-2">
                                <a href="{{ route('payment.edit', $payment->id) }}"
                                   class=" btn btn-warning btn-sm my-1"><i class="fa fa-edit m-0"></i></a>
                            </div>
                        @endif
                        @if (auth()->user()->hasPermission('document_delete'))
                            <div class="col-md-2 col-sm-6 m-2">
                                <a class=" btn btn-danger btn-sm my-1" data-payment_id="{{ $payment->id }}"
                                   data-toggle="modal" data-type='delete' data-target="#confirm_modal"><i
                                        class="fa fa-trash-alt m-0"></i></a>
                            </div>
                        @endif
                        <div class="col-md-1"></div>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- Pagination --}}
<div class="row m-0 justify-content-between panination_container">
    <div class="">
        <div class="dataTables_info" id="datatableTemplate_info" role="status" aria-live="polite">@lang('site.show')
            {{ $payments->currentPage() }} @lang('site.from') {{ $payments->lastPage() }}
            {{-- Handle plural or singular for page word --}}
            @if ($payments->lastPage() > 1)
                @lang('site.pages')
            @else
                @lang('site.page')
            @endif
        </div>
    </div>
    <div class="">
        {!! $payments->links('vendor.pagination.default') !!}
    </div>
</div>
