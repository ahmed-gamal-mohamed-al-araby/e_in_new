@extends('pages.layouts.master')

@section('title')
    @lang('site.countries')
@endsection

{{-- Custom Styles --}}
@section('styles')
@endsection

{{-- Page content --}}
@section('content')


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.countries')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.countries') </li>
                        <li class="breadcrumb-item"><a href="{{route('home')}}"> @lang('site.home')</a></li>

                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                
                @if($data)
                    @if (auth()->user()->hasPermission('country_update'))
                        {{-- Edit Country --}}
                        <div class="col-md-4">
                            <div class="add-service">
                                <form action="{{route('country.update',$data->id )}}" method="Post">
                                    @csrf
                                    <div class="form-group">
                                        <label
                                            for="Add Service ">@lang('site.edit') {{ ' ' }} @lang('site.small_country')</label>
                                        <input type="text" name="name" value="{{$data->name}}" class="form-control"
                                               required=""
                                               oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.small_country')')"
                                               oninput="setCustomValidity('')">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="Add Service ">@lang('site.edit') {{ ' ' }} @lang('site.code')</label>
                                        <input type="text" name="code" value="{{$data->code}}" class="form-control"
                                               required=""
                                               oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.code')')"
                                               oninput="setCustomValidity('')">
                                        @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <a href="https://sdk.preprod.invoicing.eta.gov.eg/codes/countries/"
                                           target="_blank">@lang('site.you_can_get_country_code_from_here')</a>
                                    </div>

                                    <div class="form-group">
                                        {{ method_field('PUT') }}
                                        <input type="submit" class="btn btn-success" value="@lang('site.edit')">
                                    </div>
                                </form>
                            </div>
                            </div>
                            @endif

                            @else
                                @if (auth()->user()->hasPermission('country_create'))
                                    {{-- Add Country --}}
                                    <div class="col-md-4">
                                        <div class="add-service">
                                            <form action="{{route('country.store')}}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label
                                                        for="Add Service ">@lang('site.add') {{ ' ' }} @lang('site.small_country')</label>
                                                    <input type="text" name="name" class="form-control" required=""
                                                           oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.small_country')')"
                                                           oninput="setCustomValidity('')">
                                                    @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        for="Add Service ">@lang('site.add') {{ ' ' }} @lang('site.code')</label>
                                                    <input type="text" name="code" class="form-control" required=""
                                                           oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.code')')"
                                                           oninput="setCustomValidity('')">
                                                    @error('code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <a href="https://sdk.preprod.invoicing.eta.gov.eg/codes/countries/"
                                                       target="_blank">@lang('site.you_can_get_country_code_from_here')</a>
                                                </div>

                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success"
                                                           value="@lang('site.add')">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        <!-- /.col -->

                            {{-- View all countrys --}}
                            @if (auth()->user()->hasPermission('country_create') ||
                                      auth()->user()->hasPermission('country_update'))
                                @if ($data)
                                    <div class="col-md-8">
                                        @else
                                            @if (auth()->user()->hasPermission('country_create'))
                                                <div class="col-md-8">
                                                    @else
                                                        <div class="col-12">
                                                            @endif
                                                            @endif
                                                            @else
                                                                <div class="col-12">
                                                                    @endif
                                                                    <div class="card">
                                                                        <!-- /.card-header -->
                                                                        <div class="card-body">

                                                                            <table id="example1"
                                                                                   class="table table-bordered table-striped text-center">
                                                                                <thead>
                                                                                <tr style="text-align:center;">
                                                                                    <th> @lang('site.id')</th>
                                                                                    <th> @lang('site.the_country')</th>
                                                                                    <th> @lang('site.code')</th>
                                                                                    <th width="28%"> @lang('site.actions')</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody class="text-center">
                                                                                @foreach($countries as $country)
                                                                                    <tr>
                                                                                        <td>{{$country->id}}</td>
                                                                                        <td>{{$country->name}}</td>
                                                                                        <td>{{$country->code}}</td>
                                                                                        <td>
                                                                                            <div class="service-option">
                                                                                                <a href="{{route('country.edit',$country->id)}}"
                                                                                                   class=" btn btn-warning"><i
                                                                                                        class="fa fa-edit"></i> @lang('site.edit')
                                                                                                </a>
                                                                                                <a class=" btn btn-danger"
                                                                                                   data-country_id="{{$country->id}}"
                                                                                                   data-toggle="modal"
                                                                                                   data-target="#country_delete"><i
                                                                                                        class="fa fa-trash-alt"></i> @lang('site.delete')
                                                                                                </a>
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

                                                        </div>
                                                        <!-- /.row -->
                                                </div>
                                                <!-- /.container-fluid -->


    </section>
    <!-- /.content -->


@endsection

{{-- Custom scripts --}}
@section('scripts')
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "ordering": false,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "@lang('site.all')"]],
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

            });
        });
    </script>
    <script>
        $('#country_delete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var serviceid = button.data('country_id');
            console.log(serviceid);
            $('.modal #country_id').val(serviceid);
        })
    </script>
@endsection
