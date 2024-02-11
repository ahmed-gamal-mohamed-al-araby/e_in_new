@extends('pages.layouts.master')

@section('title')
    @lang('site.documents')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('po/css/style.css') }}">
    @if (Config::get('app.locale') == 'ar')
        <style>
            .dataDirection {
                direction: rtl !important;
            }

            .textDirection {
                text-align: right;
            }

        </style>
    @else
        <style>
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
            <div class="col-md-4">
                <h1>
                    @if ($sent)
                        @lang('site.sent_documents')
                    @else
                        @lang('site.documents')
                        @if ($archive)
                            (@lang('site.archive'))
                        @elseif($waitingApprove)
                            (@lang('site.all_waiting'))
                        @endif
                    @endif
                </h1>
            </div>
            <div class="col-md-4 my-2 my-md-0 text-md-center">
                @if (!$sent)
                    <span class="badge badge-success py-2 display-4" style="font-size: 1.5rem">{{ number_format(($totalDocument), 2) }}</span>
                @endif
            </div>
            <div class="col-md-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        @if ($sent)
                            @lang('site.sent_documents')
                        @else
                            @lang('site.documents')
                            @if ($archive)
                                (@lang('site.archive'))
                            @elseif($waitingApprove)
                                (@lang('site.all_waiting'))
                            @endif
                        @endif
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card">
    <div class="card-body">
        <div class="dataDirection textDirection">
            <div id="alert" class="alert alert-danger alert-dismissible fade dataDirection textDirection d-none" role="alert">
                <strong>@lang('site.please') -  @lang('site.no_selected_row')</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="false">&times;</span>
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped text-center dataDirection">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            @lang('site.document_number')
                        </th>
                        <th>
                            @lang('site.document_type')
                        </th>
                        <th>
                            @lang('site.date')
                        </th>
                        <th>
                            @lang('site.document_version')
                        </th>
                        <th>
                           @lang('site.issuer_from')
                        </th>
                        <th>
                            @lang('site.receiver_to')
                        </th>
                        <th>
                            @lang('site.client_type')
                         </th>
                        <th>
                           @lang('site.items_number')
                        </th>
                        <th>
                            @lang('site.total_amount')
                        </th>
                        <th width="150px">
                            @lang('site.actions')
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($documents as $document)

                    <tr class="justify-content-center" data-entry-id="{{ $document->id }}" data-entry-number="{{ $document->document_number }}" >
                        <td>

                        </td>
                        <td>
                            {{ $document->document_number }}
                        </td>
                        <td>
                            ({{ $document->type }})
                            @if($document->type == 'I')
                            Invoice
                            @elseif($document->type == 'C')
                            Credit
                            @else
                            Debit
                            @endif
                        </td>
                        <td>
                            {{ $document->date }}
                        </td>
                        <td>
                            {{ 'V ' . $document->version }}
                        </td>
                        <td>
                            {{ $document->company->company_name }}
                        </td>
                        <td>
                            @if ($document->purchaseOrder->client_type == 'f')
                                <a href="{{ route('foreignerClient.index') }}">
                                    {{ $document->purchaseOrder->foreignerClient->company_name }} </a>
                            @elseif($document->purchaseOrder->client_type == 'p')
                                <a href="{{ route('personClient.index') }}">
                                    {{ $document->purchaseOrder->personClient->name }} </a>
                            @else
                                <a
                                    href="{{ route('businessClients.profile', ['id' => $document->purchaseOrder->businessClient->id]) }}">{{ $document->purchaseOrder->businessClient->name }}</a>
                            @endif
                        </td>
                        <td>
                            @if ($document->purchaseOrder->client_type == 'f')
                                {{ 'Foreigner' }}
                            @elseif($document->purchaseOrder->client_type == 'p')
                                {{'Person' }}
                            @else
                                {{ 'Business' }}
                            @endif
                        </td>
                        <td>
                            {{ $document->items->count() }}
                        </td>
                        <td>
                            @php $totalSumatiom=0 @endphp
                            @foreach ($document->items as $item)
                                 @php $totalSumatiom +=$item->total_amount @endphp
                            @endforeach
{{--                            {{ number_format(($totalSumatiom - $document->extra_invoice_discount), 2) }}--}}
                            {{ number_format(($totalSumatiom), 2) }}
                        </td>

                        <td class="requests-btn">
                            <div class="service-option-document text-center">
                                <a href="{{ route('documents.show',$document->id) }}" class=" btn btn-success"><i class="fa fa-eye"></i> </a>
                                @if($document->approved==0 && $document->archive==0)
                                @if(auth()->user()->hasPermission('document_delete'))
                                    <a  data-document_id="{{$document->id}}" class=" btn btn-danger" data-toggle="modal" data-target="#archive" tooltip="@lang('site.archive_this')"><i class="fa fa-trash-alt"></i></a>
                                @endif
                                @endif
                                        @if(!$sent && !$waitingApprove)
                                           @if(!$archive)
                                                @if(auth()->user()->hasPermission('document_update') )
                                                        <a href="{{ route('documents.edit',$document->id) }}" class=" btn btn-warning"><i class="fa fa-edit"></i></a>
                                                @endif
                                                @if(auth()->user()->hasPermission('document_request') && auth()->user()->id == $document->user_id )
                                                        <a href="{{ route('documents.edit',$document->id) }}" class=" btn btn-warning"><i class="fa fa-edit"></i></a>
                                                @endif
                                                @if(auth()->user()->hasPermission('document_delete'))
                                                    <a  data-document_id="{{$document->id}}" class=" btn btn-danger" data-toggle="modal" data-target="#archive" tooltip="@lang('site.archive_this')"><i class="fa fa-trash-alt"></i></a>
                                                @endif
                                            @else
                                                @if(auth()->user()->hasPermission('document_delete'))
                                                <a data-document_id="{{$document->id}}" class=" btn btn-danger" data-toggle="modal" data-target="#restore" tooltip="@lang('site.take_back')">
                                                    <i class="fa fa-trash-restore-alt"></i>
                                                </a>
                                                @endif
                                                @if(auth()->user()->hasPermission('document_delete'))
                                                <a data-document_id="{{$document->id}}" class=" btn btn-danger" data-toggle="modal" data-target="#actual-delete" tooltip="@lang('site.actual_delete')">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                                @endif
                                            @endif
                                        @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Archive document model --}}
<div class="modal fade text-center" id="archive" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.confirm_document')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('site.archive_document')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') , @lang('site.cancel')</button>
                <form action="{{route('document.document_archive', ['id' => 'test'])}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="document_id" id="archive-document_id" value="">
                    <button  type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.archive_this') </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Restore PurchaseOrder model --}}
<div class="modal fade text-center" id="restore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.back_document')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('site.take_document')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">  @lang('site.no') ,  @lang('site.cancel')</button>
                <form action="{{route('document.document_restore', ['id' => 'test'])}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="document_id" id="restore-document_id" value="">
                    <button  type="submit" class="btn btn-outline-dark">  @lang('site.yes') ,  @lang('site.take_back') </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Delete document model --}}
<div class="modal fade text-center" id="actual-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.delete_document')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>@lang('site.confirm_delete_document')</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
          @lang('site.cancel')</button>
        <form action="{{route('document.destroy', ['document' => 'delete'])}}" method="POST">
          @method('DELETE')
          @csrf
          <input type="hidden" name="document_id" id="actual-delete-document_id" value="">
          <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete') </button>
        </form>

      </div>
    </div>
  </div>
</div>

{{-- confirm sent documents model --}}
<div class="modal fade text-center" id="confirm-send-documents" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.send') @lang('site.documents')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>@lang('site.sending_documents')</p>
        <p class="documents" style="white-space: pre-wrap;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
          @lang('site.cancel') !</button>
      </div>
    </div>
  </div>
</div>

{{-- no sent documents model --}}
<div class="modal fade text-center" id="send-documents" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.send') @lang('site.documents')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>@lang('site.no_selected_row')</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
          @lang('site.cancel') !</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')

<script>
    $( function () {
        var table = $("#example1").DataTable({
       "responsive": true, "lengthChange": true, "autoWidth": false,
       "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "@lang('site.all')"]],
       columnDefs: [
            {
                orderable: false,
                className: 'select-checkbox mt-2',
                targets: 0
            }, {
                orderable: false,
                searchable: false,
                targets: -1
            }
         ],
         select: {
            style:    'multi+shift',
            selector: 'td:first-child',
        },
        order: [],
        // dom: "<'row'<'col-md-6'l><'col-md-10'B><'col-md-2'f>>" +
        // "<'row'<'col-sm-12'tr>>" +
        // "<'row'<'col-sm-5'i><'col-sm-7'p>>",
       "buttons": [
        {
                extend: 'selectAll',
                className: '@if(auth()->user()->hasPermission("document_send"))' +
                                'defult' +
                            '@else' +
                                'd-none' +
                            '@endif',
                text: '@lang("site.select_all")',
                exportOptions: {
                    columns: ':visible'
                },
                action: function(e, dt) {
                    e.preventDefault()
                    dt.rows().deselect();
                    dt.rows({ search: 'applied' }).select();
                }
            },
            {
                extend: 'selectNone',
                className: '@if(auth()->user()->hasPermission("document_send"))' +
                                'defult' +
                            '@else' +
                                'd-none' +
                            '@endif',
                text: '@lang("site.select_none")',
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                text:  "@lang('site.send') @lang('site.selected_documents')",
                className: '@if(auth()->user()->hasPermission("document_send"))' +
                                'defult' +
                            '@else' +
                                'd-none' +
                            '@endif'
                        ,
                url: "{{ route('documents.submitMultiDocuments') }}",
                action: function (e, dt, node, config) {

                    var ids = $.map(dt.rows( { selected: true } ).nodes(),function (entry) {
                        return $(entry).data('entry-id');
                    });

                    if (ids.length === 0) {
                        $('#send-documents').modal('show');
                        return
                    }

                    if (ids.length > 0) {
                        var documentNumbers = $.map(dt.rows( { selected: true } ).nodes(),function (entry) {
                            return $(entry).data('entry-number');
                        });

                        if (! confirm("@lang('site.confirm') @lang('site.sending_documents') "+ '\n' + documentNumbers.join('\r\n'))) {
                            // console.log('out');
                            return;
                        }
                        // console.log('OK');
                        $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{ route('documents.submitMultiDocuments') }}",
                        data: { ids: ids, _method: 'POST' }})
                        .done(function () { location.reload() })
                    }
                }

            },
            // "copy",
            // "excel",
            // "print",
            {
                className: '@if(auth()->user()->hasPermission("document_send") || auth()->user()->hasPermission("document_request"))' +
                                'defult' +
                            '@else' +
                                'd-none' +
                            '@endif',
                text: '@lang("site.document_request")',
                exportOptions: {
                    columns: ':visible'
                },
                url: "{{ route('documents.index_document_request') }}",
                action: function ( e, dt, node, config ){

            window.location.href='{{route("documents.index_document_request")}}'

            }

            },

            {
                text: '@if($waitingApprove) ' +
                ' @lang("site.all_documents")  <i class="fa fa-receipt">  </i>' +
                '@else' +
                    '@if(auth()->user()->hasPermission("document_read"))'+
                        '@lang("site.all_waiting")  <i class="fas fa-spinner fa-spin">  </i>' +
                    '@endif'+
                '@endif',
                className: '@if(auth()->user()->hasPermission("document_read"))'+
            'defult'+
            '@else'+
            'removeArchive'+
            '@endif',
            action: function ( e, dt, node, config ){
            @if($waitingApprove)
            window.location.href='{{route("documents.index")}}'
            @else
                window.location.href='{{route("documents.waiting_approve")}}'
            @endif
            }},{
            text: '@if($sent) ' +
                     '@lang("site.go_to") @lang("site.all_documents")  <i class="fa fa-receipt"></i>' +
                 '@else' +
                     '@if(auth()->user()->hasPermission("document_read"))'+
                         '@lang("site.go_to") @lang("site.all_sending_documents")  <i class="fas fa-paper-plane"></i>' +
                     '@endif'+
                 '@endif',
                 className: '@if(auth()->user()->hasPermission("document_read"))'+
                 'defult'+
                 '@else'+
                 'removeArchive'+
                 '@endif',
                action: function ( e, dt, node, config ){
               @if($sent)
               window.location.href='{{route("documents.index")}}'
               @else
                   window.location.href='{{route("documents.indexOFSentDocument")}}'
               @endif
           }},
           ,  {
           text: '@if($archive) ' +
                     '@lang("site.go_to") @lang("site.all_documents")  <i class="fa fa-receipt"></i>' +
                 '@else' +
                     '@if(auth()->user()->hasPermission("document_read"))'+
                         '@lang("site.go_to") @lang("site.archive")  <i class="fa fa-trash-alt"></i>' +
                     '@endif'+
                 '@endif',
                 className: '@if(auth()->user()->hasPermission("document_read"))'+
                 'defult'+
                 '@else'+
                 'removeArchive'+
                 '@endif',
                action: function ( e, dt, node, config ){
               @if($archive)
               window.location.href='{{route("documents.index")}}'
               @else
                   window.location.href='{{route("documents.indexOFarchiveDocument")}}'
               @endif
           }}, "colvis"],
          "language": {
              search: '<i class="fa fa-filter" aria-hidden="true"></i>',
              searchPlaceholder: '@lang("site.search") ',
              "lengthMenu": "@lang('site.show')  _MENU_ @lang('site.records') ",
              "paginate": {
                  "previous": "@lang('site.prev')",
                  "next" : "@lang('site.next')",
              },
              "emptyTable":     "@lang('site.no_data')",
              "info":   "@lang('site.show')  _END_ @lang('site.from') _TOTAL_ @lang('site.record')",
              "infoEmpty":      "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
              "infoFiltered":   "(@lang('site.search_in')  _MAX_  @lang('site.record'))",
              select: {
                    rows: {
                        _: "- @lang('site.selected_documents') %d ",
                        0: "- @lang('site.no_selected_row')",
                    }
                },

              buttons: {
                  colvis: '@lang("site.show_data") <i class="fa fa-eye-slash "> </i> ',
                  'print' : '@lang("site.print") <i class="fa fa-print "> </i> ',
                  'copy' : '@lang("site.copy") <i class="fa fa-copy "> </i>',
                  'excel' : '@lang("site.excel") <i class="fa fa-file-excel "> </i>',

                  buttons: [
                      { extend: 'copy', className: 'btn btn-primary' },
                      { extend: 'excel', className: 'excelButton' }
                  ]
              },

          }
     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    //  Show all button in full screen
     const position = $('.dataTables_wrapper .row:first').children().last();
        $('.dataTables_wrapper').find('.dt-buttons.btn-group.flex-wrap').insertAfter(position);

        });
 </script>
 <script>

    $('#archive').on('show.bs.modal',function(event){
         var button = $(event.relatedTarget);
         var documentId = button.data('document_id');
         $('.modal #archive-document_id').val(documentId);
    })

    $('#actual-delete').on('show.bs.modal',function(event){
         var button = $(event.relatedTarget);
         var documentId = button.data('document_id');
         $('.modal #actual-delete-document_id').val(documentId);
    })

    $('#restore').on('show.bs.modal',function(event){
        var button = $(event.relatedTarget);
        var documentId = button.data('document_id');
        $('.modal #restore-document_id').val(documentId);
    })

</script>

@endsection
