@php
    $currentLanguage = app()->getLocale();
@endphp

@extends('pages.layouts.master', [
    'parent' => 'Client.business',
    'child' => 'index',
])

{{-- Custome Title --}}
@section('title')
    @lang('site.businessClients')
@endsection

{{-- Custom Styles --}}
@section('styles')
    <link rel="stylesheet" href="{{asset('dist/css/rate.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('dist/css-rtl/bootstrap-rtl.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{asset('dist/css-rtl/custom-rtl.css')}}"> --}}
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
                    <h1>@lang('site.all_businessClients')</h1>
                {{-- </div> --}}
                {{-- <div class="col-sm-6 col-md-6"> --}}
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">@lang('site.home')</a></li>
                        <li class="breadcrumb-item active">@lang('site.all_businessClients') </li>
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
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between">
                                @if (auth()->user()->hasPermission('client_create'))
                                    <a href="{{ route('businessClients.create') }}" class="btn btn-success btn-sm header-btn ">
                                        @lang('site.add_businessClient')</a>
                                    <a href="{{ route('businessClients.archive_businessClients') }}"
                                        class="btn btn-warning btn-sm header-btn ">@lang('site.archive_client')
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
                            @include('pages.client.business.pagination_data', ['pageType' => 'index'])
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

             {{-- Form to Trash group --}}
             <form action="" method="POST" id="confirm_form">
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

{{-- Custom scripts --}}
@section('scripts')
<script src="{{ asset('plugins/tablesorter/js/jquery.tablesorter.combined.js') }}"></script>

<script>
    // Start defining languages
    let languages = [];

    languages['delete_businessClient_title'] = "@lang('site.delete') @lang('site.the_businessClient')";
    languages['delete_businessClient_body'] =
        "@lang('site.confirm') @lang('site.delete') @lang('site.the_businessClient') " +
        "{{ $currentLanguage == 'ar' ? '؟' : '?' }}";
    languages['delete_businessClient_url'] = "{{ route('businessClients.client_archive') }}";
    languages['delete_businessClient_action_btn_text'] = "@lang('site.archive')";
    // End defining languages

    // Start include pagination script
    const fetchDataURL =
        "{{ route('businessClients.pagination.fetch_data') }}", // This valriable used in pagination_script
        pageType = 'index';

    @include('pages.includes.pagination_script')
    // End include pagination script

    $( function () {
        // Start change modal data
        $('#confirm_modal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const businessClientId = button.data('businessclient_id');
                const confirmModalType = button.data('type');

                // Change form action attribute
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
            // const basicData = $('#confirm_form').serializeAssoc();
            const businessClient_id = $('#businessClient_id').val();
            // console.log(businessClient_id + 'aaaa');
            let data = {
              businessClient_id: businessClient_id
            }
            console.log(data);
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
                                `a[data-businessclient_id="${businessClient_id}"]`
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
    });
</script>
@endsection
