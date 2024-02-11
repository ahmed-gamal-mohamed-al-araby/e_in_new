@php
    $currentLanguage = app()->getLocale();
    // dd($currentLanguage);
@endphp
@extends('pages.layouts.master')

@section('title')
    @lang('site.payment')
@endsection

{{-- Custom Styles --}}
@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/tablesorter/css/theme.materialize.min.css') }}"> --}}
@endsection
{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 d-flex justify-content-between">
                    <h1>@lang('site.payment')</h1>
                {{-- </div>
                <div class="col-md-6"> --}}
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                        <li class="breadcrumb-item active">@lang('site.payment')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-2"></div>{{-- Wrapper --}}

                            @include('pages.includes.pagination_data_filter')
                            {{-- Table length filter --}}

                            <div class="row mb-2"></div>{{-- Wrapper --}}

                            {{-- Table content --}}
                            <div id="table-data" class="table-responsive">
                                @include('pages.payment.pagination_data', ['pageType' => 'index'])
                            </div>
                            {{-- End of Table Content --}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Confirmation Model --}}
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
                    <p>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="" method="POST" id="confirm_form">
                        @csrf
                        <input type="hidden" name="payment_id" id="payment_id" value="">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end of confirmation model --}}

@endsection

{{-- Custom scripts --}}
@section('scripts')
    <script>
        // Start defining languages
        let languages = [];

        languages['delete_payment_title'] = "@lang('site.delete') @lang('site.payment')";
        languages['delete_payment_body'] =
            "@lang('site.confirm') @lang('site.small_delete') @lang('site.payment') " +
            "{{ $currentLanguage == 'ar' ? '؟' : '?' }}";
        languages['delete_payment_url'] = "{{ route('payment.permanent_delete') }}";
        // languages['delete_businessClient_action_btn_text'] = "@lang('site.archive')";
        // End defining languages
        // console.log(languages);

        // Start include pagination script
        const fetchDataURL =
            "{{ route('payments.pagination.fetch_data') }}", // This valriable used in pagination_script
            pageType = 'index';

        @include('pages.includes.pagination_script')
        // End include pagination script

        $('#confirm_modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var paymentId = button.data('payment_id');
            // console.log(paymentId);
            const confirmModalType = button.data('type');
            // console.log(confirmModalType);

            // Change form action attribute
            $('#confirm_form').attr('action', languages[`${confirmModalType}_payment_url`]);
            // console.log($(this).find('#confirm_form').attr('action'));

            // Change modal title
            $('#modal-title').text(languages[`${confirmModalType}_payment_title`]);

            // Change modal body
            $('#modal-body p').text(languages[`${confirmModalType}_payment_body`]);
            console.log($('#modal-body p').text());

            // Set input with button data-payment_id
            $('.modal #payment_id').val(paymentId);
        });

        $('#confirm_form').on('submit', function(e) {
            e.preventDefault();
            // const basicData = $('#confirm_form').serializeAssoc();
            const payment_id = $('#payment_id').val();
            // console.log(businessClient_id + 'aaaa');
            let data = {
                payment_id: payment_id
            }
            console.log($(this).attr('action'));
            // delete basicData._token;
            $('.loader-container').fadeIn();
            $.ajax({
                url: $(this).attr('action'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                // contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function(response) {
                    // Start toastr notification
                    console.log(response);
                    if (response.status == 1) {
                        toastr.success(
                            "@lang('site.delete')" + "<br>" + $(
                                `table tr:first`).find('th').eq(1).text() + ': ' +
                            $(
                                `a[data-payment_id="${payment_id}"]`
                            ).parents('tr').find('td').eq(1).text(),
                            "@lang('site.success')"
                        );
                        $('#search-content').trigger('click'); // To  fetch data
                    } else {
                        toastr.error(
                            response.errorMessage,
                            "@lang('site.sorry')"
                        );
                    }
                    // End toastr notification
                },
                complete: function() {
                    $('#confirm_modal').modal('hide');
                    $('.loader-container').fadeOut();
                }
            });
        });

        // Sort table
        $.extend($.tablesorter.defaults, {
            theme: 'materialize',
        });
        $(".sort-table").tablesorter();
    </script>
@endsection
