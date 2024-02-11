@php
    $currentLanguage = app()->getLocale();
@endphp

@extends('pages.layouts.master', [
    'parent' => 'purchaseorders',
    'child' => 'waiting_approve',
])
{{-- Custom Title --}}
@section('title')
    @lang('site.all_waiting')
@endsection

{{-- Custom Styles --}}
@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/tablesorter/css/theme.materialize.min.css') }}">
@endsection

{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header optimization-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-between">
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <h1>@lang('site.all_waiting') @lang('site.purchaseorders')</h1>
                        @php
                            $summation = array_sum($purchaseOrderTotal)
                        @endphp
                        <h3><span class="badge badge-success" id="summation">{{ number_format($summation, 5) }}</span></h3>

                    {{-- </div> --}}
                    {{-- <div class="col-sm-6 col-md-6"> --}}
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('site.home')</a></li>
                            <li class="breadcrumb-item active">@lang('site.all_waiting')</li>
                        </ol>
                    {{-- </div> --}}
                </div>
            </div> {{-- /.end of row --}}
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-12 d-flex justify-content-between">
                                    @if (auth()->user()->hasPermission('po_create'))
                                        <a href="{{ route('purchaseorders.index') }}" class="btn btn-success btn-sm header-btn ">
                                            @lang('site.purchaseorders')</a>
                                        <a href="{{ route('purchaseorders.archive_purchaseorders') }}"
                                            class="btn btn-warning btn-sm header-btn ">
                                            @lang('site.archive') @lang('site.purchaseorders')
                                            <span class="main-span"><span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            {{-- end of row --}}

                            <div class="row mb-2"></div>{{-- Wrapper --}}

                            {{-- Table length filter --}}
                            @include('pages.includes.pagination_data_filter')

                            {{-- Table content --}}
                            <div id="table-data" class="table-responsive">
                                @include('pages.purchaseorders.pagination_data', ['pageType' => 'waiting_approve'])
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    {{-- Confirm modal --}}
    <div class="modal fade text-center" id="confirm_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>

                    {{-- Form to permanent delete or restore group --}}
                    <form action="" method="POST" id="confirm_form" data-type="restore">
                        @csrf
                        <input type="hidden" name="businessClient_id" id="businessClient_id" value="">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , <span
                                id="action-btn-text"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('plugins/tablesorter/js/jquery.tablesorter.combined.js') }}"></script>

    <script>

        // Start include pagination script
        const fetchDataURL =
            "{{ route('purchaseorders.pagination.fetch_data') }}", // This valriable used in pagination_script
            pageType = 'waiting_approve';

        @include('pages.includes.pagination_script')
        // End include pagination script

        // Start handle action modal
        $(document).ready(function() {

            $(window).on('load', function() {
                let summation = parseFloat($('#hidden_summation').val());
                let format = summation.toLocaleString(
                undefined, // leave undefined to use the visitor's browser
                            // locale or a string like 'en-US' to override it.
                { minimumFractionDigits: 5 }
                );
                $('#summation').text(format);
            }); //  update summation text when start loading window

            $(document).bind("ajaxComplete", function(){
                let summation = parseFloat($('#hidden_summation').val());
                let format = summation.toLocaleString(
                    undefined, // leave undefined to use the visitor's browser
                                // locale or a string like 'en-US' to override it.
                    { minimumFractionDigits: 5 }
                );
                $('#summation').text(format);
            }); //  bind ajax update (Update total sum of purchase order)


            // Start change modal data
            $('#confirm_modal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const businessClientId = button.data('businessclient_id');
                console.log(businessClientId);
                const confirmModalType = button.data('type');

                // Change form data type attribute
                $('#confirm_form').data('type', confirmModalType);

                // Change action form action attribute
                $('#confirm_form').attr('action', languages[`${confirmModalType}_businessClient_url`]);

                // Change modal title
                $('#modal-title').text(languages[`${confirmModalType}_businessClient_title`]);

                // Change modal body
                $('#modal-body p').text(languages[`${confirmModalType}_businessClient_body`]);

                // Change modal action button text
                $('#action-btn-text').text(languages[`${confirmModalType}_businessClient_action_btn_text`]);

                // Set input with button data-group_id
                $('.modal #businessClient_id').val(businessClientId);
            });
            // End change modal data

            $('#confirm_form').on('submit', function(e) {
                e.preventDefault();
                const dataType = $(this).data('type');
                const businessClient_id = $('#businessClient_id').val();
                // console.log(businessClient_id);
                let data = {
                    businessClient_id: businessClient_id
                }
                // console.log(data);
                $.ajax({
                    url: $(this).attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    data: data,
                    // contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(response) {
                        // Start toastr notification
                        if (dataType == 'restore') {
                            if (response.status == 1) {
                                toastr.success(
                                    "@lang('site.restore_successfully')" + "<br>" + $(
                                        `table tr:first`).find('th').eq(1).text() + ': ' +
                                    $(
                                        `a[data-businessclient_id="${businessClient_id}"]`
                                    ).parents('tr').find('td').eq(1).text(),
                                    "@lang('site.success')"
                                );;
                                $('#search-content').trigger('click'); // To  fetch data
                            } else {
                                toastr.info(
                                    "@lang('site.Founded')",
                                    ""
                                );
                            }
                        }
                    },
                    complete: function() {
                        $('#confirm_modal').modal('hide');
                    }
                });
            });
        });
        // End handle action modal

        // Sort table
        $.extend($.tablesorter.defaults, {
            theme: 'materialize',
        });
        $(".sort-table").tablesorter();
    </script>
@endsection
