@extends('pages.letters_guarantee_request.master')

@php
$currentLang = Config::get('app.locale');
App::setLocale('en');
@endphp
@section('content')
{{-- content --}}
<table>
    {{-- Table header repeated in top of all pages --}}


    <tbody>
        {{-- Page content --}}
        <tr>
            <td>
                <div class=" card show-one-request  my-0" style="box-shadow: none !important">
                    <div class="card-body show-request-id">
                        <div class="mb-2 ">
                            <div class="mb-2">
                                <div id='DivIdToPrint'>
                                    <br>

                                    <h1 class="text-center"> Request For Letter Of Guarantee</h1>
                                    <br>
                                    <br>
                                    <div style="padding: 50px; font-size: 18px;">
                                        <div class="row ">

                                            <div class="col-md-2"><span> Date:</span></div>
                                            <div class="col-md-10">
                                                <p>{{date("d/m/Y")}}</p>
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> Co.Name:</span></div>
                                            <div class="col-md-10">
                                                @if(!isset($letter_guarantee_request->client_id))

                                                    <p>{{$letter_guarantee_request->client_name}}</p>

                                                @else

                                                    @if($letter_guarantee_request->client_type=="b")
                                                        <p>{{$letter_guarantee_request->businessClient->name}}</p>
                                                    @elseif($letter_guarantee_request->client_type=="p")
                                                        <p>{{$letter_guarantee_request->personClient->name}}</p>
                                                    @else
                                                        <p>{{$letter_guarantee_request->foreignerClient->name}}</p>
                                                    @endif

                                                @endif
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> Co.Address:</span></div>
                                            <div class="col-md-10">
                                                @if(isset($letter_guarantee_request->client_address))
                                                    <p>{{$letter_guarantee_request->client_address}}</p>
                                                @else

                                                    @if($letter_guarantee_request->client_type=="b")
                                                        <p>{{ $letter_guarantee_request->businessClient->address->country->name . ' ,' . $letter_guarantee_request->businessClient->address->city->name . ', ' . $letter_guarantee_request->businessClient->address->region_city . ', ' . $letter_guarantee_request->businessClient->address->street . ', ' . $letter_guarantee_request->businessClient->address->building_no }}</p>
                                                    @elseif($letter_guarantee_request->client_type=="p")
                                                        <p>{{ $letter_guarantee_request->personClient->address->country->name . ' ,' . $letter_guarantee_request->personClient->address->city->name . ', ' . $letter_guarantee_request->personClient->address->region_city . ', ' . $letter_guarantee_request->personClient->address->street . ', ' . $letter_guarantee_request->personClient->address->building_no }}</p>
                                                    @else
                                                        <p>{{ $letter_guarantee_request->foreignerClient->address->country->name . ' ,' . $letter_guarantee_request->foreignerClient->address->city->name . ', ' . $letter_guarantee_request->foreignerClient->address->region_city . ', ' . $letter_guarantee_request->foreignerClient->address->street . ', ' . $letter_guarantee_request->foreignerClient->address->building_no }}</p>
                                                    @endif
                                                @endif

                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> LG.Amount:</span></div>
                                            <div class="col-md-10">
                                                <p>{{$letter_guarantee_request->value}} EGP</p>
                                                <p>{{$var}} جنيها فقط لا غير</p>
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> LG.Type:</span></div>
                                            <div class="col-md-10">
                                                <input type="radio" class="btn-check" autocomplete="off" @if($letter_guarantee_request->type=="instant") checked @else disabled @endif>
                                                <label class="btn btn-light" for="option4">@lang('site.instant')</label>

                                                <input type="radio" class="btn-check" autocomplete="off" @if($letter_guarantee_request->type=="final_insurance") checked @else disabled @endif>
                                                <label class="btn btn-light" for="option3">@lang('site.final_insurance')</label>

                                                <input type="radio" class="btn-check" autocomplete="off" @if($letter_guarantee_request->type=="prepaid") checked @else disabled @endif>
                                                <label class="btn btn-light" for="option1">@lang('site.prepaid')</label>

                                                <input type="radio" class="btn-check" autocomplete="off" @if($letter_guarantee_request->type=="primary_insurance") checked @else disabled @endif>
                                                <label class="btn btn-light" for="option2">@lang('site.primary_insurance')</label>

                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>

                                        <br>

                                        <div class="row">
                                            <div class="col-md-2"><span> Diring Data:</span></div>
                                            <div class="col-md-10">
                                                <p>{{$letter_guarantee_request->duration_in_month}} months</p>
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> Requested For:</span></div>
                                            <div class="col-md-10">
                                                @if(isset($letter_guarantee_request->supply_order))
                                                    <p>{{$letter_guarantee_request->purchaseOrder->project_name}} - {{$letter_guarantee_request->purchaseOrder->project_number}} - {{$letter_guarantee_request->purchaseOrder->purchase_order_reference}}</p>
                                                @else
                                                    <p>{{$letter_guarantee_request->project_name}} - {{$letter_guarantee_request->project_number}} - {{$letter_guarantee_request->supply_order_name}}</p>

                                                @endif
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> Data of Issue:</span></div>
                                            <div class="col-md-4">
                                                <p>{{$letter_guarantee_request->release_date}}</p>
                                            </div>
                                            <div class="col-md-2"><span> End Data :</span></div>
                                            <div class="col-md-4">
                                                <p>{{$letter_guarantee_request->expiry_date}}</p>
                                            </div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-2"><span> Bank Name:</span></div>
                                            <div class="col-md-10"></div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>
                                        <br>
                                        <br>

                                        <div class="row">

                                            <div class="col-md-4"><span> Requested by:</span></div>
                                            <div class="col-md-4"><span> Auditing by:</span></div>
                                            <div class="col-md-4"><span> Approved by:</span></div>
                                            {{--                                        <div class="col-md-1"></div>--}}
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>

    {{-- Table footer repeated in bottom of all pages --}}

</table>
@endsection




@section('scripts')
<script>
    window.print();
</script>
@endsection
