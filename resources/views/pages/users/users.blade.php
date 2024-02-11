@extends('pages.layouts.master')

@section('title')
    @lang('site.users')
@endsection

{{-- Page content --}}
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('site.users')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.users') </li>
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
                            @if (auth()->user()->hasPermission('user_create'))
                                <a href="{{route('users.create')}}" class="btn btn-success btn-sm mb-4">
                                    <i class="fa fa-user-plus nav-icon"></i> @lang('site.add_user')
                                </a>
                            @endif
                            <table id="example1" class="table table-bordered table-striped text-center">
                                <thead>
                                <tr style="text-align:center;">
                                    <th>  @lang('site.id')</th>
                                    <th>  @lang('site.login_user_name') </th>
                                    <th>  @lang('site.name') </th>
                                    <th>  @lang('site.email') </th>
                                    <th>  @lang('site.permissions') </th>
                                    <th>  @lang('site.company') </th>
                                    @if(auth()->user()->hasPermission("product_update"))
                                        <th width="200px">@lang('site.actions')</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->username}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->roles[0]->name}}</td>
                                        <td>{{$user->company->company_name}}</td>

                                        @if(auth()->user()->hasPermission("user_update"))
                                            <td>
                                                <div class="service-option">
                                                    @if(auth()->user()->hasPermission('user_update'))
                                                        <a href="{{route('users.edit',$user->id)}}"
                                                           class="btn btn-warning"><i
                                                                class="fa fa-edit "></i> @lang('site.edit') </a>
                                                        <a href="{{route('users.show_reset_password',$user->id)}}"
                                                           class="btn btn-danger"><i
                                                                class="fa fa-edit "></i> @lang('site.reset_password')
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
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
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                order: false,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                columnDefs: [
                    {
                        targets: "hiddenCols", visible: false
                    }
                ],
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
