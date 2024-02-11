<div class="table-responsive received">
    <table id="get-recent-documents" class="table table-bordered table-striped dataDirection text-center">
        <thead class="">
            <tr>
                <th>
                    @lang('site.eta_id') / @lang('site.document_number')
                </th>
                <th>
                    @lang('site.date_time_issued')
                </th>
                <th>
                    @lang('site.date_time_received')
                </th>
                <th>
                    @lang('site.document_type') / @lang('site.document_version')
                </th>
                <th>
                    @lang('site.issuer')
                </th>
                <th>
                    @lang('site.receiver')
                </th>
                <th>
                    @lang('site.sales_amount')
                </th>
                <th>
                    @lang('site.total') (@lang('site.egp'))
                </th>
                <th>
                    @lang('site.status')
                </th>
                <th class="not-export-col">
                    @lang('site.shared_link')
                </th>
            </tr>
        </thead>

        <tbody class="document_results">
            @foreach ($documents as $document)
                @if ($document->issuerId != 311045022)
                    <tr class="justify-content-center data-row" data-entry-internalid="{{$document->uuid}}" data-entry-issuerid="{{$document->issuerId}}">
                        <td>
                            <u>{{ $document->uuid }}</u>
                            <br>
                            {{ $document->internalId }}
                        </td>
                        <td>
                            <b>{{ date('Y-m-d', strtotime($document->dateTimeIssued)) }}</b>
                            <br>
                            {{ date('H:i:s', strtotime($document->dateTimeIssued)) }}
                        </td>
                        <td>
                            <b>{{ date('Y-m-d', strtotime($document->dateTimeReceived)) }}</b>
                            <br>
                            {{ date('H:i:s', strtotime($document->dateTimeReceived)) }}
                        </td>
                        <td>
                            <b>{{ $document->documentTypeNamePrimaryLang }}</b>
                            <br>
                            {{ $document->typeVersionName}}
                        </td>
                        <td>
                            {{ $document->issuerName }}
                        </td>
                        <td>
                            {{ $document->receiverName }}
                        </td>
                        <td>
                            {{ number_format($document->totalSales, 2) }}
                        </td>
                        <td>
                            {{ number_format($document->total, 2) }}
                        </td>
                        <td>
                            {{-- <b>{{ $document->status }}</b> --}}
                            @if ($document->status == "Valid")
                                <span class="badge badge-success">{{$document->status}}</span><br>
                                @if ($document->cancelRequestDate)
                                    <span class="badge badge-warning">Cancelled</span>
                                @elseif ($document->rejectRequestDate)
                                    <span class="badge badge-warning">Rejected</span>
                                @endif
                            @elseif ($document->status == "Invalid")
                                <span class="badge badge-danger">{{$document->status}}</span>
                            @else
                                <span class="badge badge-info">{{$document->status}}</span>
                            @endif
                        </td>
                        <td class="requests-btn">
                            <div class="service-option-document text-center mb-2" >
                                <a href="{{ $document->publicUrl }}" id="qrcode"
                                    class=" btn btn-secondary qrcode" target="_blank">
                                    <i class="fas fa-link"></i>
                                </a>
                            </div>
                            @if ( $document->issuerId == "311045022" && auth()->user()->hasPermission("document_send"))
                            <form action="{{ route('documents.cancelOrRejectDocument') }}" method="PUT">
                                <input type="hidden" name="uuid" class="uuid" value="{{ $document->uuid }}">
                                <input type="hidden" name="status" class="status" value="cancelled">
                                @method('put')
                                @if (!$document->cancelRequestDate)
                                    <button class="btn btn-danger cancel" title="Cancel"><i class="fas fa-window-close"></i></button>
                                @endif
                            </form>
                            @elseif (auth()->user()->hasPermission("document_send"))
                            <form action="{{ route('documents.cancelOrRejectDocument') }}" method="PUT">
                                <input type="hidden" name="uuid" class="uuid" value="{{ $document->uuid }}">
                                <input type="hidden" name="status" class="status" value="rejected">
                                @method('put')
                                @if (!$document->rejectRequestDate)
                                    <button class="btn btn-danger reject" title="Reject"><i class="fas fa-window-close"></i></button>
                                @endif
                            </form>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    @include('vendor.pagination.bootstrap-4', ['paginator' => $paginator])

</div>


