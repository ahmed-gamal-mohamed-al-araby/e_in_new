@extends('pages.layouts.master')

@section('title')
    @lang('site.deductions')
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
                    <h1>@lang('site.deductions')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.deductions') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Edit (Now is disabled) --}}
                @if ($data)
                    @if (auth()->user()->hasPermission('document_send'))
                        {{-- Edit Country --}}
                        <div class="col-md-4">
                            <div class="add-service">
                                <form action="{{ route('deduction.update', $data->id) }}" method="Post">
                                    @csrf
                                    {{-- Name --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.edit') {{ ' ' }}
                                            @lang('site.small_deduction')</label>
                                        <input type="text" name="name" value="{{ $data->name }}" class="form-control"
                                            required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.small_deduction')')"
                                            oninput="setCustomValidity('')">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Code --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.edit') {{ ' ' }}
                                            @lang('site.code')</label>
                                        <input type="text" name="code" value="{{ $data->code }}" class="form-control"
                                            required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.code')')"
                                            oninput="setCustomValidity('')">
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Type --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.add') {{ ' ' }}
                                            @lang('site.type')</label>
                                        <select class="custom-select" name="type" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.small_deduction')')"
                                            oninput="setCustomValidity('')">
                                            <option selected disabled value="">@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.small_deduction')</option>
                                            @foreach ($deductionTypes as $deductionType)
                                                <option {{ $deductionType->id == $data->deductionType_id?'selected': '' }} value="{{ $deductionType->id }}">{{ $deductionType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        {{ method_field('PUT') }}
                                        <input type="submit" class="btn btn-success" value="@lang('site.edit')">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                {{-- Add --}}
                @else
                    @if (auth()->user()->hasPermission('document_send'))
                        {{-- Add Country --}}
                        <div class="col-md-4">
                            <div class="add-service">
                                <form action="{{ route('deduction.store') }}" method="POST">
                                    @csrf
                                    {{-- Name --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.add') {{ ' ' }}
                                            @lang('site.small_deduction')</label>
                                        <input type="text" name="name" class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.small_deduction')')"
                                            oninput="setCustomValidity('')" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Code --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.add') {{ ' ' }}
                                            @lang('site.code')</label>
                                        <input type="text" name="code" class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.enter') {{ ' ' }} @lang('site.code')')"
                                            oninput="setCustomValidity('')" value="{{ old('code') }}">
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Type --}}
                                    <div class="form-group">
                                        <label for="Add Service ">@lang('site.add') {{ ' ' }}
                                            @lang('site.type')</label>
                                        <select class="custom-select" name="type" required
                                            oninvalid="this.setCustomValidity('@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.small_deduction')')"
                                            oninput="setCustomValidity('')">
                                            <option selected disabled value="">@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.small_deduction')</option>
                                            @foreach ($deductionTypes as $deductionType)
                                                <option {{ $deductionType->id == old('type')?'selected': '' }} value="{{ $deductionType->id }}">{{ $deductionType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="@lang('site.add')">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
                <!-- /.col -->

                {{-- View all countrys --}}
                @if (auth()->user()->hasPermission('document_send'))
                    @if ($data)
                        <div class="col-md-8">
                        @else
                            @if (auth()->user()->hasPermission('document_send'))
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

                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="text-align:center;">
                                    <th> @lang('site.id')</th>
                                    <th> @lang('site.the_deduction')</th>
                                    <th> @lang('site.code')</th>
                                    <th> @lang('site.type')</th>
                                    @if (auth()->user()->hasPermission('document_send'))
                                    <th> @lang('site.actions')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($deductions as $deduction)
                                    <tr>
                                        <td>{{ $deduction->id }}</td>
                                        <td>{{ $deduction->name }}</td>
                                        <td>{{ $deduction->code }}</td>
                                        <td>{{ $deduction->deductionType->name }}</td>
                                        @if (auth()->user()->hasPermission('document_send'))
                                        <td>
                                            <div class="service-option">
                                                <a href="{{ route('deduction.edit', $deduction->id) }}"
                                                    class=" btn btn-warning"><i class="fa fa-edit m-1"></i></a>
                                                <a class=" btn btn-danger" data-deduction_id="{{ $deduction->id }}"
                                                    data-toggle="modal" data-target="#deduction_delete"><i
                                                        class="fa fa-trash-alt m-1"></i></a>
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

        </div>
        <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    {{-- Delete deduction model --}}
    <div class="modal fade text-center" id="deduction_delete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.delete') {{ ' ' }}
                        @lang('site.the_deduction')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('site.confirm') {{ ' ' }} @lang('site.small_delete') {{ ' ' }}
                        @lang('site.the_deduction') {{ '?' }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('deduction.destroy', ['deduction' => 'delete']) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="deduction_id" id="deduction_id" value="">
                        <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete')
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Custom scripts --}}
@section('scripts')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "@lang('site.all')"]
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

            });
        });

    </script>
    <script>
        $('#deduction_delete').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var deductionId = button.data('deduction_id');
            $('.modal #deduction_id').val(deductionId);
        })
    </script>
@endsection
