@php
$x=1;
@endphp
@extends('pages.layouts.master')

@section('title')
@lang('site.warranty_checks')
@endsection

{{-- Page content --}}
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> @lang('site.warranty_checks')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.warranty_checks') </li>
                    <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home') </a></li>

                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content service-content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12 form-group">
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif

                @if(count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <form action="{{ route('warranty_checks.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control">
                            <br>
                            <button class="btn btn-success">@lang('site.add_warranty_checks_via_excel')</button>
                        </form>
                    </div>
                    <div class="card-body">
                        @if (auth()->user()->hasPermission('warranty_checks_create'))
                        <a href="{{route('warranty_checks.create')}}" class="btn btn-success btn-sm mb-4">
                            <i class="fa fa-user-plus nav-icon"></i> @lang('site.add') @lang('site.warranty_checks')
                        </a>
                        @endif
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="text-align:center;">
                                    <th>#</th>
                                    <th> @lang('site.side') </th>
                                    <th> @lang('site.purpose') </th>
                                    <th> @lang('site.project_number') </th>
                                    <th> @lang('site.check_date') </th>
                                    <th> @lang('site.reply_date') </th>

                                    <th width="200px">@lang('site.actions')</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($warranty_checks as $warranty_check)
                                <tr>
                                    <td>{{$x++}}</td>
                                    <td>{{$warranty_check->side}}</td>
                                    <td>{{$warranty_check->purpose}}</td>
                                    <td>{{$warranty_check->project_number}}</td>
                                    <td>{{$warranty_check->check_date}}</td>
                                    <td>{{$warranty_check->reply_date}}</td>

                                    <td>
                                        <div class="service-option">
                                        <a href="{{route('warranty_checks.show',$warranty_check->id)}}" class="btn btn-success"><i class="fa fa-show "></i> @lang('site.show') </a>
                                        @if(!isset($warranty_check->reply_date))

                                            <form action="{{ route('warranty_checks.destroy',$warranty_check->id) }}" method="POST">
                                                @if (auth()->user()->hasPermission('warranty_checks_update'))
                                                <a href="{{route('warranty_checks.edit',$warranty_check->id)}}" class="btn btn-warning"><i class="fa fa-edit "></i> @lang('site.edit') </a>
                                                @endif
                                                @csrf
                                                @method('DELETE')
                                                @if (auth()->user()->hasPermission('warranty_checks_delete'))
                                                <a href="" class="btn btn-danger delete-user" data-toggle="tooltip" data-placement="top" title="Delete"> <i class="fa fa-edit delete-user"></i> @lang('site.delete')</a>
                                                @endif
                                            </form>
                                            @endif

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