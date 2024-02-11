@extends('pages.layouts.master')

@section('title')
    @lang('site.documents')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
    <style>
        @keyframes bouncing-loader {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0.1;
                transform: translateY(-1rem);
            }
        }
        .bouncing-loader {
            display: flex;
            justify-content: center;
        }
        .bouncing-loader > div {
            width: 1rem;
            height: 1rem;
            margin: 3rem 0.2rem;
            background: rgb(4, 182, 4);
            border-radius: 50%;
            animation: bouncing-loader 0.8s infinite alternate;
        }
        .bouncing-loader > div:nth-child(2) {
            animation-delay: 0.2s;
        }
        .bouncing-loader > div:nth-child(3) {
            animation-delay: 0.4s;
        }
        .bouncing-loader > div:nth-child(4) {
            animation-delay: 0.6s;
        }
        .bouncing-loader > div:nth-child(5) {
            animation-delay: 0.8s;
        }
        .items-from-ajax-load {
            display:grid;
            position:fixed;
            top:0;
            left:0;
            bottom:0;
            right:0;
            border:solid;
            background: rgba(0, 0, 0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #get-recent{
            border-radius: 0;
        }
    </style>
    @if (Config::get('app.locale') == 'ar')
        <style>
            .result-per-page {
                margin-left: 2%;
                margin-right: 50%;
            }
            .qrcode span{
                left: 50px;
            }
            .dataDirection {
                direction: rtl !important;
            }
            .textDirection {
                text-align: right;
            }
        </style>
    @else
        <style>
            .result-per-page {
                margin-left: 50%;
                margin-right: 2%;
            }
            .qrcode span{
                right: 50px;
            }
            .dataDirection {
                direction: ltr !important;
            }

            .textDirection {
                text-align: left;
            }
        </style>
    @endif
@endsection

@section('content')
<section class="content-header prequestHeader">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1>
                    @lang('site.get_recent_documents')
                </h1>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        @lang('site.documents')
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card">
    <div class="card-body">
        <div class="row dataDirection">
            <div class="col-md-3">

            </div>
            <div class="col-md-4 mb-3">
                <input id="get-recent" type="submit"
                        class="btn btn-success" value="@lang('site.received_documents') (@lang('site.from_eta'))">
            </div>

            <div class="col-md-5 mt-1 ">
                <div class="row dataDirection textDirection">
                    <span  style=" padding-top: 5px" class="result-per-page">@lang('site.result_per_page')</span>
                    <div class="form-group ml-2">
                        <select class="form-control form-control-sm" name="pageSize" id="page-size" style="">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        {{-- End Of Dates Section --}}

        <div id="table-data">

        </div>
        {{-- @endif --}}
    </div>
</div>

{{-- Loader for loading purchase order items from excel sheet --}}
<div class="items-from-ajax-load" style="display: none">
    <div class="bouncing-loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

@endsection
@section('scripts')
    <script>

        // Handle Submit Button To Create Report
        $('#get-recent').on('click', function(e) {

            var pageSize = $('#page-size').val();
            // console.log(pageSize);
            sendData = {
                pageSize: pageSize,
            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.items-from-ajax-load').fadeIn();
            $.ajax({
                type: 'GET',
                url: "{{ route('documents.getRecentDocumentsReceivedFromServer') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: sendData,
                success: function(response) {
                    getDocuments(response);
                    // runDataTable();
                    $('.items-from-ajax-load').fadeOut(250);
                },
                error: function() {
                    $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                },
                complete: function() {
                    $('.search-bank.spinner-border').hide();
                }
            });
        });

        // Add Documents To Blade View
        function getDocuments(response) {
            // var dt1 = $.fn.dataTable.tables()[0];
            // $(dt1).DataTable().destroy();
            var tableArea = $('#table-data');
            tableArea.empty();
            // table.find('thead').removeClass('d-none');
            tableArea.append(response);
            $(".qrcode").parent().each(function () {
                $(this).find('span').css({'display': 'none', 'color': '#000', 'background': '#fff', 'padding': '5px'});
                $(this).hover(function () {
                    $(this).find('span').css({"display": "inline", 'position': 'absolute', "text-align": "center"});
                }, function(){
                    $(this).find('span').css({'display': 'none'});
                });
            });
            $('#page-no').on('change', function () {
                // alert('page num')
                var pageNo= $(this).val();
                var pageSize = $('#page-size').val();
                sendAJAX(pageNo, pageSize);
            });

            $('#next-pagination').on('click', function (e) {
                e.preventDefault();
                var pageNo= $('#page-no').val();
                var pageSize = $('#page-size').val();
                var nextPageNo = (parseInt(pageNo) + 1).toString();
                sendAJAX(nextPageNo, pageSize);
            })

            $('#prev-pagination').on('click', function (e) {
                e.preventDefault();
                var pageNo= $('#page-no').val();
                var pageSize = $('#page-size').val();
                var nextPageNo = (parseInt(pageNo) - 1).toString();
                sendAJAX(nextPageNo, pageSize);
            })

            $('.cancel').on('click', function (e) {
                e.preventDefault();
                var pageNo= $('#page-no').val();
                var pageSize = $('#page-size').val();
                var uuid= $(this).parent().find('.uuid').val();
                var status = $(this).parent().find('.status').val();
                cancelOrRejectWithAjax(uuid, status);
            });

            $('.reject').on('click', function (e) {
                e.preventDefault();
                var pageNo= $('#page-no').val();
                var pageSize = $('#page-size').val();
                var uuid= $(this).parent().find('.uuid').val();
                var status = $(this).parent().find('.status').val();
                cancelOrRejectWithAjax(uuid, status);
            });

        }

        // Send Ajax Request to get recent documents
        function sendAJAX(pageNo, pageSize) {
            sendData = {
                pageSize: pageSize,
                pageNo: pageNo,
            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.items-from-ajax-load').fadeIn();
            $.ajax({
                type: 'GET',
                url: "{{ route('documents.getRecentDocumentsReceivedFromServer') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: sendData,
                success: function(response) {
                    getDocuments(response);
                    // runDataTable();
                    $('.items-from-ajax-load').fadeOut(250);
                },
                error: function() {
                    $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                },
                complete: function() {
                    $('.search-bank.spinner-border').hide();
                }
            });
        }

        // Send Ajax Request to get cancel or reject documents
        function cancelOrRejectWithAjax(uuid, status) {
            var pageNo= $('#page-no').val();
            // console.log(pageNo);
            var pageSize = $('#page-size').val();
            // console.log(pageSize);

            sendData = {
                uuid: uuid,
                status: status,
            };
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.items-from-ajax-load').fadeIn();
            $.ajax({
                type: 'PUT',
                url: "{{ route('documents.cancelOrRejectDocument') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: sendData,
                success: function(response) {
                    // getDocuments(response);
                    sendAJAX(pageNo, pageSize);
                    // runDataTable();
                    $('.items-from-ajax-load').fadeOut(250);
                },
                error: function() {
                    $('.client-detail .text-danger').removeClass('d-none').text('@lang("site.no_data")');
                },
                complete: function() {
                    $('.search-bank.spinner-border').hide();
                }
            });
        }

    </script>

@endsection
