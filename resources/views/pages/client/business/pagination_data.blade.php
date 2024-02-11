@php
    $currentLanguage = app()->getLocale();
    $currentIndex = $businessClients->firstItem();
@endphp

<table id="datatableTemplate" class="table table-bordered table-striped text-center sort-table">

    {{-- Table Header --}}
    <thead>
        <tr class="">
            <th>@lang('site.id')</th>
            <th>@lang('site.company')</th>
            <th class="text-nowrap">@lang('site.tax_id_number_only')</th>
            <th>@lang('site.mobile') </th>
            <th>@lang('site.phone')</th>
            <th>@lang('site.address')</th>
            <th style="width: 150px">@lang('site.actions')</th>
        </tr>
    </thead>

    {{-- Table body --}}
    <tbody>
        @foreach ($businessClients as $businessClient)
            <tr>
                <td>
                    {{ $currentIndex++ }}
                </td>
                <td>
                    {{$businessClient->name}}
                </td>
                <td>
                    {{$businessClient->tax_id_number}}
                </td>
                <td>
                    {{ $businessClient->mobile }}
                </td>
                <td>
                    {{ $businessClient->phone }}
                </td>
                <td>{{ $businessClient->address->country->name . ' ,' . $businessClient->address->city->name . ', ' . $businessClient->address->region_city . ', ' . $businessClient->address->street . ', ' . $businessClient->address->building_no }}
                <td>
                    <div class="row service-option justify-content-center">
                        <div class="col-md-1 col-sm-6 m-2">
                            <a href="{{route('businessClients.profile', ['id' => $businessClient->id])}}"
                                class="btn btn-success btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                        </div>
                        @if ($pageType == 'index')
                            @if(auth()->user()->hasPermission('client_update'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class=" btn btn-warning btn-sm" href="{{ route('businessClients.edit', $businessClient->id) }}"><i
                                        class="fa fa-edit"></i>
                                    </a>
                                </div>
                            @endif
                            @if(auth()->user()->hasPermission('client_delete'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class="btn btn-danger btn-sm " data-businessclient_id="{{ $businessClient->id }}"
                                        data-type='delete' data-toggle="modal" data-target="#confirm_modal"><i
                                            class="fa fa-trash-alt"></i>
                                    </a>
                                </div>
                            @endif
                        @elseif($pageType == 'archived')
                                <div class="col-md-1"></div>
                            @if(auth()->user()->hasPermission('client_restore'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class=" btn btn-secondary btn-sm" data-businessclient_id="{{ $businessClient->id }}"
                                        data-type='restore' data-toggle="modal" data-target="#confirm_modal"><i
                                            class="fas fa-trash-restore-alt"></i>
                                    </a>
                                </div>
                            @endif
                            {{-- @if(auth()->user()->hasPermission('client_delete'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class=" btn btn-danger btn-sm our-tooltip" data-businessclient_id="{{$businessClient->id}}"
                                        data-type='permanent_delete' data-toggle="modal" data-target="#confirm_modal"
                                        tooltip="@lang('site.actual_delete')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </div>
                            @endif --}}
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
            {{ $businessClients->currentPage() }} @lang('site.from') {{ $businessClients->lastPage() }}
            {{-- Handle plural or singular for page word --}}
            @if ($businessClients->lastPage() > 1)
                @lang('site.pages')
            @else
                @lang('site.page')
            @endif
        </div>
    </div>
    <div class="">
        {!! $businessClients->links('vendor.pagination.default') !!}
    </div>
</div>
