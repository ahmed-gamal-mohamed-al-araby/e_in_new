@php
    $currentLanguage = app()->getLocale();
    $currentIndex = $purchaseorders->firstItem();
    // $summation = array_sum($purchaseOrderTotal);
    // dump($summation);
@endphp

<table id="datatableTemplate" class="table table-bordered table-striped text-center sort-table">

    {{-- Table Header --}}
    <thead>
        <tr>
            <th>
                @lang('site.serial')
            </th>
            <th class="text-nowrap">
                @lang('site.purchase_order_reference')
            </th>

            <th class="text-nowrap">
                @lang('site.purchaseOrder_type')
            </th>
            <th class="text-nowrap">
                @lang('site.client_name')
            </th>
            <th calss="text-nowrap">
               @lang('site.main_project_name')
            </th>
            <th calss="text-nowrap">
               @lang('site.po_project_name')
            </th>
            <th calss="text-nowrap">
            @lang('site.product_code')
            </th>
            <th class="text-nowrap">
                @lang('site.total_amount')
            </th>
            <th class="text-nowrap">
                @lang('site.created_at')
            </th>
            <th style="width: 150px">
                @lang('site.actions')
            </th>
        </tr>
    </thead>

    {{-- Table body --}}
    <tbody>
        @foreach ($purchaseorders as $purchaseorder)
            <tr data-entry-id="{{ $purchaseorder->id }}">
                <td>
                    {{ $currentIndex++ }}
                </td>
                <td>
                    {{ $purchaseorder->purchase_order_reference }}
                </td>
                <td>
                    {{ $purchaseorder->type }}
                </td>
                <td>
                    @if ($purchaseorder->client_type == 'b')
                    <a href="{{ route('businessClients.profile', ['id' => $purchaseorder->businessClient->id]) }}">{{ $purchaseorder->businessClient->name }}</a>
                    @elseif ($purchaseorder->client_type == 'p')
                    <a href="{{ route('personClient.index') }}">
                    {{ $purchaseorder->personClient->name }} </a>
                    @elseif ($purchaseorder->client_type == 'f')
                    <a href="{{ route('foreignerClient.index') }}">
                        {{ $purchaseorder->foreignerClient->company_name }} </a>
                    @endif
                </td>
                @if($purchaseorder->main_project_name)
                <td>
                    {{ $purchaseorder->project["name_ar"] }}
                </td>
                @else

                <td></td>
                @endif
                <td>
                    {{ $purchaseorder->project_name }}
                </td>
               
                <td>

                {{ $purchaseorder->items[0]->product->standard_code_type }}
                   
              </td>
           
                <td>
                     {{ number_format($purchaseOrderTotal[$loop->index],2) }}
                </td>

                <td>
                    {{ date('d-m-Y', strtotime($purchaseorder->created_at)) }}
                </td>

                <td>
                    <div class="row service-option justify-content-center">
                        <div class="col-md-1     col-sm-6 m-2">
                            <a href="{{route('purchaseorders.show', $purchaseorder->id)}}"
                                class="btn btn-success btn-sm " target="_blank"><i class="fa fa-eye"></i></a>
                        </div>
                        @if ($pageType == 'index')
                            @if(auth()->user()->hasPermission('po_update'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a href="{{ route('purchaseorders.edit',$purchaseorder->id) }}" class=" btn btn-warning"><i
                                        class="fa fa-edit"></i>
                                    </a>
                                </div>
                            @endif
                            @if(auth()->user()->hasPermission('po_delete'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class="btn btn-danger btn-sm" data-purchaseorder_id="{{ $purchaseorder->id }}"
                                        data-type='delete' data-toggle="modal" data-target="#confirm_modal"><i
                                            class="fa fa-trash-alt"></i>
                                    </a>
                                </div>
                            @endif
                        @elseif($pageType == 'archived')
                            @if(auth()->user()->hasPermission('po_restore'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class=" btn btn-secondary btn-sm" data-purchaseorder_id="{{ $purchaseorder->id }}"
                                        data-type='restore' data-toggle="modal" data-target="#confirm_modal"
                                        tooltip="@lang('site.restore')"><i
                                            class="fas fa-trash-restore-alt"></i>
                                    </a>
                                </div>
                            @endif
                            @if(auth()->user()->hasPermission('po_delete'))
                                <div class="col-md-1 col-sm-6 m-2">
                                    <a class=" btn btn-danger btn-sm our-tooltip" data-purchaseorder_id="{{$purchaseorder->id}}"
                                        data-type='permanent_delete' data-toggle="modal" data-target="#confirm_modal"
                                        tooltip="@lang('site.actual_delete')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
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
@php
    $summation = array_sum($purchaseOrderTotal);
@endphp
<input type="hidden" name="summation" id="hidden_summation" value="{{ $summation }}">

{{-- Pagination --}}
<div class="row m-0 justify-content-between panination_container">
    <div class="">
        <div class="dataTables_info" id="datatableTemplate_info" role="status" aria-live="polite">@lang('site.show')
            {{ $purchaseorders->currentPage() }} @lang('site.from') {{ $purchaseorders->lastPage() }}
            {{-- Handle plural or singular for page word --}}
            @if ($purchaseorders->lastPage() > 1)
                @lang('site.pages')
            @else
                @lang('site.page')
            @endif
        </div>
    </div>
    <div class="">
        {!! $purchaseorders->links('vendor.pagination.default') !!}
    </div>
    {{-- <input type="hidden" name="summation" id="summation" value="{{ $summation }}"> --}}
</div>

