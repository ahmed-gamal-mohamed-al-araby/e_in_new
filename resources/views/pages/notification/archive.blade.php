@extends('pages.layouts.master')

@section('title')
    @lang('site.archive') @lang('site.small_notifications')
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

            .flex_dir {
                flex-direction: row-reverse
            }

        </style>
    @endif
@endsection

{{-- Page content --}}
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>
                        @lang('site.notifications_filter')
                    </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            @lang('site.notifications_filter')
                        </li>
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
                <form action="{{ route('notification.archive') }}" method="Post">
                    @csrf
                    {{-- Date section --}}
                    <div class="row date">
                        <div class="col-md-6 mb-3 textDirection">
                            <label for="type" class="form-label">@lang('site.from_date')</label>
                            <input type="date" name="from_date" id="from_date" class="d-block w-100 form-control"
                                placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.from_date')')"
                                oninput="setCustomValidity('')" required>
                            <div class="col-12 text-center text-danger d-none date-overflow">
                                @lang('site.date_overflow')</div>
                            <div class="text-center text-danger d-none from_date_error">
                                @lang('site.data-required')</div>
                        </div>

                        <div class="col-md-6 mb-3 textDirection">
                            <label for="type" class="form-label">@lang('site.to_date')</label>
                            <input type="date" name="to_date" id="to_date" class="d-block w-100 form-control"
                                placeholder="@lang('site.date')" data-date-format="DD/MM/YYYY"
                                oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.to_date')')"
                                oninput="setCustomValidity('')" required>
                            <div class="col-12 text-center text-danger d-none date-overflow">
                                @lang('site.date_overflow')</div>
                            <div class="text-center text-danger d-none to_date_error">
                                @lang('site.data-required')</div>
                        </div>
                        <div class="col-12 text-center text-danger d-none" id="from-date-greater-than-to-date">
                            @lang('site.from_date_greater_than_to_date')</div>
                    </div>
                    <div class="textDirection">
                        <button type="submit" class="btn btn-success mb-2">@lang('site.archive')</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Partner Requests-->
    </div>
    <!-- /.content -->

@endsection

@section('scripts')
<script>
    // Validate the entered date not greater than today
    (function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        today = yyyy + '-' + mm + '-' + dd;
        $("#to_date").attr("max", today);
        $("#from_date").attr("max", today);
    }());

    $('#to_date').on('change', function() {
        const toDate = $(this).val();
        $("#from_date").attr("max", toDate);
        if ($("#from_date").val() && $("#from_date").val() > $(this).val()) {
            $("#from_date").val('');
            $('#from-date-greater-than-to-date').removeClass('d-none');
        } else {
            $('#from-date-greater-than-to-date').addClass('d-none');
        }
    })

    $('#from_date').on('change', function() {
        const fromDate = $(this).val();
        $("#to_date").attr("min", fromDate);
        if ($("#to_date").val() && $("#to_date").val() < $(this).val()) {
            $("#to_date").val('');
            $('#from-date-greater-than-to-date').removeClass('d-none');
        } else {
            $('#from-date-greater-than-to-date').addClass('d-none');
        }
    })
</script>

@endsection
