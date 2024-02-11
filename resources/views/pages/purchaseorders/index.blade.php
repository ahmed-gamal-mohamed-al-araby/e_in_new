@php
    $currentLanguage = app()->getLocale();
@endphp

@extends('pages.layouts.master', [
    'parent' => 'purchaseorders',
    'child' => 'archive',
])

@section('title')
    @lang('site.purchaseorders')
@endsection

@section('styles')
    {{-- <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}"> --}}
@endsection

@section('content')
<section class="content-header prequestHeader">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-between">
                <h1> @lang('site.all_purchaseorders')</h1>

                @php
                    $summation = array_sum($purchaseOrderTotal)
                @endphp
                <h3><span class="badge badge-success" id="summation">{{ number_format($summation, 5) }}</span></h3>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                    <li class="breadcrumb-item active"> @lang('site.all_purchaseorders')
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 d-flex justify-content-between">
                        @if (auth()->user()->hasPermission('po_create'))
                            <a href="{{ route('purchaseorders.wating_approve') }}" class="btn btn-success btn-sm header-btn ">
                                @lang('site.all_waiting')
                                <span class="main-span"><span>
                            </a>
                            <a href="{{ route('purchaseorders.archive_purchaseorders') }}"
                                class="btn btn-warning btn-sm header-btn ">@lang('site.archive') {{ ' ' }} @lang('site.purchaseorder')
                                <span class="main-span"><span>
                            </a>
                        @endif
                    </div>
                </div>
                {{-- end of row header --}}

                <div class="row mb-2"></div>{{-- Wrapper --}}

                @include('pages.includes.pagination_data_filter')
                {{-- Table length filter --}}

                {{-- Table content --}}
                <div id="table-data" class="table-responsive">
                    @include('pages.purchaseorders.pagination_data', ['pageType' => 'index'])
                </div>
            </div>
        </div>

    </div>
</section>
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
                <form action="" method="POST" id="confirm_form" data-type="archive">
                    @csrf
                    <input type="hidden" name="purchaseorder_id" id="purchaseorder_id" value="">
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
<script>

    // Start defining languages
    let languages = [];

    languages['delete_purchaseorder_title'] = "@lang('site.archive') @lang('site.purchaseorder')";
    languages['delete_purchaseorder_body'] =
        "@lang('site.archive') @lang('site.purchaseorder')" +
        "{{ $currentLanguage == 'ar' ? '؟' : '?' }}";
    languages['delete_purchaseorder_url'] = "{{ route('purchaseorder.purchaseorder_archive') }}";
    languages['delete_purchaseorder_action_btn_text'] = "@lang('site.archive')";
    // End defining languages

    // Start include pagination script
    const fetchDataURL =
        "{{ route('purchaseorders.pagination.fetch_data') }}", // This valriable used in pagination_script
        pageType = 'index';
        @include('pages.includes.pagination_script')

    $(function () {


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

        $('#confirm_modal').on('show.bs.modal',function(event){
            var button = $(event.relatedTarget);
            var purchaseorderid = button.data('purchaseorder_id');
            const confirmModalType = button.data('type');
            // Change form data type attribute
            $('#confirm_form').data('type', confirmModalType);

            // Change action form action attribute
            $('#confirm_form').attr('action', languages[`${confirmModalType}_purchaseorder_url`]);

            // Change modal title
            $('#modal-title').text(languages[`${confirmModalType}_purchaseorder_title`]);

            // Change modal body
            $('#modal-body p').text(languages[`${confirmModalType}_purchaseorder_body`]);

            // Change modal action button text
            $('#action-btn-text').text(languages[`${confirmModalType}_purchaseorder_action_btn_text`]);

            $('.modal #purchaseorder_id').val(purchaseorderid);
            // console.log($('.modal #purchaseorder_id').val());
        })
        // End change modal data

        $('#confirm_form').on('submit', function(e) {
            e.preventDefault();
            const dataType = $(this).data('type');
            const purchaseorder_id = $('.modal #purchaseorder_id').val();
            // console.log(purchaseorder_id);
            let data = {
                purchaseorder_id: purchaseorder_id
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
                    // console.log(response);
                    // Start toastr notification
                    console.log(response);
                    if (response.status == 1) {
                        toastr.success(
                            "@lang('site.archive')" + "<br>" + $(
                                `table tr:first`).find('th').eq(1).text() + ': ' +
                            $(
                                `a[data-purchaseorder_id="${purchaseorder_id}"]`
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
                }
            });
        });

        // Sort table
        $.extend($.tablesorter.defaults, {
            theme: 'materialize',
        });
        $(".sort-table").tablesorter();
    });

 </script>

@endsection

