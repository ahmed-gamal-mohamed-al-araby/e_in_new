@extends('pages.layouts.master')

@section('title')
    @lang('site.payment')
@endsection

{{-- Custom Styles --}}
@section('styles')
    @if (Config::get('app.locale') == 'ar')
        <style>
            .date {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

        </style>
    @endif
@endsection

{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.payment') @lang('site.Related_with')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.payment') @lang('site.Related_with')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="col-10 offset-1">
        <!-- Partner Requests-->
        <div class="card updates daily-feeds">
            <div id="feeds-box" role="tabpanel" class="collapse0 show m-3 mt-4" style="">
                <form action="{{ route('payment.related.get_data') }}" method="Post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <select name="table_name" class="custom-select" required
                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.table_name')')"
                                oninput="setCustomValidity('')">
                                <option selected disabled value="">@lang('site.please') @lang('site.select')
                                    @lang('site.table_name')</option>
                                <option @if ($request) {{ $request['table_name'] == 'PO' ? 'selected' : '' }} @endif value="PO">@lang('site.purchaseorder')</option>
                                <option @if ($request) {{ $request['table_name'] == 'D' ? 'selected' : '' }} @endif value="D">@lang('site.document')</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <input name="reference" type="text" required class="form-control" @if ($request) value="{{ $request['reference'] }}" @endif
                                placeholder="@lang('site.please') @lang('site.enter') @lang('site.reference')"
                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.reference')')"
                                oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <div class="textDirection">
                        <button type="submit" class="btn btn-success mb-2">@lang('site.filter')</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Partner Requests-->
    </div>
    <!-- /.content -->


    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th> @lang('site.id')</th>
                                        <th>@lang('site.reference')</th>
                                        <th>@lang('site.reference')</th>
                                        <th>@lang('site.client')</th>
                                        <th>@lang('site.payment')</th>
                                        <th>@lang('site.payment_date')</th>
                                        <th>@lang('site.value')</th>
                                        @if (auth()->user()->hasPermission('product_update') ||
        auth()->user()->hasPermission('product_delete'))
                                            <th> @lang('site.actions')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($payment->table == 'PO')
                                                    @lang('site.purchaseorder')
                                                @else
                                                    @lang('site._document')
                                                @endif
                                            </td>
                                            <td>
                                                @if ($payment->table == 'PO')
                                                    <a
                                                        href="{{ route('purchaseorders.show', $payment->table_id) }}">{{ $payment->purchaseOrder->purchase_order_reference }}</a>
                                                @else
                                                    <a
                                                        href="{{ route('documents.show', $payment->table_id) }}">{{ $payment->document->document_number }}</a>
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
                                                    <a
                                                        href="{{ route('businessClients.profile', $payment->client_id) }}">{{ $payment->businessClient->name }}</a>
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
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ number_format($payment->value, 2) }}</td>
                                            @if (auth()->user()->hasPermission('product_update') ||
        auth()->user()->hasPermission('product_delete'))
                                                <td>
                                                    <div class="service-option">
                                                        <a href="{{ route('payment.show', $payment->id) }}"
                                                            class=" btn btn-success my-1"><i class="fa fa-eye m-0"></i> </a>

                                                        @if (auth()->user()->hasPermission('product_update'))
                                                            <a href="{{ route('payment.edit', $payment->id) }}"
                                                                class=" btn btn-warning my-1"><i
                                                                    class="fa fa-edit m-0"></i></a>
                                                        @endif
                                                        @if (auth()->user()->hasPermission('product_delete'))
                                                            <a class=" btn btn-danger my-1"
                                                                data-payment_id="{{ $payment->id }}" data-toggle="modal"
                                                                data-target="#payment_delete"><i
                                                                    class="fa fa-trash-alt m-0"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <div class="modal fade text-center" id="payment_delete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('site.delete') {{ ' ' }}
                        @lang('site.payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('site.confirm') {{ ' ' }} @lang('site.small_delete') {{ ' ' }}
                        @lang('site.payment') {{ '?' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('payment.destroy', ['payment' => 'delete']) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="payment_id" id="payment_id" value="">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Custom scripts --}}
@section('scripts')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "ordering": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "@lang('site.all')"]
            ],
            columnDefs: [{
                targets: "hiddenCols",
                visible: false
            }],
            "language": {
                search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                searchPlaceholder: ' @lang("site.search")',
                "lengthMenu": "@lang('site.show') _MENU_  @lang('site.records')",
                "paginate": {
                    "previous": "@lang('site.prev')",
                    "next": "@lang('site.next')",

                },
                "info": "@lang('site.show') _START_  @lang('site.from') _TOTAL_  @lang('site.record')",

                buttons: {
                    colvis: ' @lang("site.show_data")',
                    'print': ' @lang("site.print")',
                    'copy': ' @lang("site.copy")',
                    'excel': '@lang("site.excel")'
                },
                "emptyTable": "@lang('site.no_data')",
                "infoEmpty": "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                "infoFiltered": "( @lang('site.search_in') _MAX_  @lang('site.records'))",
            }

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $('#payment_delete').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);
        var paymentId = button.data('payment_id');
        $('.modal #payment_id').val(paymentId);
    });
</script>
@endsection
