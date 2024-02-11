@php
$x=1;
@endphp
@extends('pages.layouts.master')

@section('title')
@lang('site.letters_guarantee_request')
@endsection

{{-- Page content --}}
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> @lang('site.letters_guarantee_request')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.letter_guarantee_request') </li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home') </a></li>

                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content service-content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">
                <div class="card">


                    <div class="card-body">

                        @if (auth()->user()->hasPermission('letter_guarantee_request_create'))

                        <a href="{{route('letter_guarantee_request.create')}}" class="btn btn-success btn-sm mb-4">
                            <i class="fa fa-user-plus nav-icon"></i>@lang('site.add') @lang('site.letter_guarantee_request')
                        </a>
                        @endif

                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="text-align:center;">
                                    <th>#</th>
                                    <th> @lang('site.client') </th>
                                    <th> @lang('site.purchase_order_reference') </th>
                                    <th> @lang('site.letter_guarantee_value') </th>
                                    <th> @lang('site.release_date') </th>
                                    <th> @lang('site.expiry_date') </th>

                                    <th width="200px">@lang('site.actions')</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($letters_guarantee_request as $letter_guarantee_request)
                                <tr>
                                    <td>{{$x++}}</td>
                                    @if(isset($letter_guarantee_request->client_name))
                                    <td>{{$letter_guarantee_request->client_name}}</td>
                                    @else
                                    @if($letter_guarantee_request->client_type=="b")
                                    <td>{{$letter_guarantee_request->businessClient->name}}</td>
                                    @elseif($letter_guarantee_request->client_type=="p")
                                    <td>{{$letter_guarantee_request->personClient->name}}</td>

                                    @else
                                    <td>{{$letter_guarantee_request->foreignerClient->company_name}}</td>

                                    @endif
                                    @endif
                                    @if(isset($letter_guarantee_request->supply_order))
                                    <td>{{$letter_guarantee_request->purchaseOrder->purchase_order_reference}}</td>
                                    @else
                                    <td>{{$letter_guarantee_request->supply_order_name}}</td>
                                    @endif
                                    <td>{{$letter_guarantee_request->value}}</td>
                                    <td>{{$letter_guarantee_request->release_date}}</td>
                                    <td>{{$letter_guarantee_request->expiry_date}}</td>

                                    <td>
                                        <div class="service-option">
                                            <form action="{{ route('letter_guarantee_request.destroy',$letter_guarantee_request->id) }}" method="POST">
                                                @if (auth()->user()->hasPermission('letter_guarantee_request_update'))

                                                <a href="{{route('letter_guarantee_request.edit',$letter_guarantee_request->id)}}" class="btn btn-warning"><i class="fa fa-edit "></i> @lang('site.edit') </a>
                                                @endif

                                                @if (auth()->user()->hasPermission('letter_guarantee_request_letter_guarantee'))

                                                <a href="{{route('letter_guarantee_request.letter_guarantee_create_from_request',$letter_guarantee_request->id)}}" class="btn btn-success"><i class="fa fa-user-plus"></i> @lang('site.letter_guarantee') </a>
                                                @endif

                                                @if (auth()->user()->hasPermission('letter_guarantee_request_print'))

                                                <a href="{{route('letter_guarantee_request.print',$letter_guarantee_request->id)}}" class="btn btn-primary" target="_blank"><i class="fa fa-edit "></i> @lang('site.print') </a>
                                                @endif
                                                @if (isset($letter_guarantee_request->supply_order) && $letter_guarantee_request->purchaseOrder->purchaseorder_document != null)
                                                <a href="{{ $letter_guarantee_request->purchaseOrder->document_path }}" class="img-thumbnail image-preview" target="_blank">PO Document</a>
                                                @endif
                                                @csrf
                                                @method('DELETE')
                                                @if (auth()->user()->hasPermission('letter_guarantee_request_delete'))

                                                <a href="" class="btn btn-danger delete-user" data-toggle="tooltip" data-placement="top" title="Delete"> <i class="fa fa-trash delete-user"></i> @lang('site.delete')</a>

                                                @endif

                                            </form>

                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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

@endsection
@section('scripts')
<script>
    $('.delete-user').click(function(e) {
        e.preventDefault() // Don't post the form, unless confirmed
        if (confirm('@lang("site.confirm_delete")')) {
            // Post the form
            $(e.target).closest('form').submit() // Post the surrounding form
        }
    });
</script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            order: false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [{
                targets: "hiddenCols",
                visible: false
            }],
            "language": {
                search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                searchPlaceholder: ' @lang("site.search")',
                "lengthMenu": "@lang('site.show') _MENU_  @lang('site.records')",
                "paginate": {
                    "previous": "@lang('site.prev')",
                    "next": "@lang('site.next')",

                },
                "info": "@lang('site.show') _START_  @lang('site.from') _TOTAL_  @lang('site.record')",

                buttons: {
                    colvis: ' @lang("site.show_data")',
                    'print': ' @lang("site.print")',
                    'copy': ' @lang("site.copy")',
                    'excel': '@lang("site.excel")'
                },
                "emptyTable": "@lang('site.no_data')",
                "infoEmpty": "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                "infoFiltered": "( @lang('site.search_in') _MAX_  @lang('site.records'))",
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection