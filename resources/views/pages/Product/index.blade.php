@extends('pages.layouts.master')

@section('title')
    @lang('site.products')
@endsection

{{-- Page content --}}
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1>@lang('site.products')</h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('site.products')</li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content service-content">
        <div class="container-fluid">
            <div class="row">
                @if ($data)
                    @if (auth()->user()->hasPermission('product_update'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form action="{{ route('product.update', $data->id) }}" method="Post">
                                    @csrf

                                    {{-- product name --}}
                                    <div class="form-group">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="product_name" value="{{ $data->product_name }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- standard code type --}}
                                    <div class="form-group">
                                        <label>@lang('site.standard_code_type')</label>
                                        <select class="custom-select" name="standard_code_type" required oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.standard_code_type')')" oninput="setCustomValidity('')">
                                            <option value="">@lang('site.please') @lang('site.select') @lang('site.standard_code_type')</option>
                                            <option value="GS1" {{ $data->standard_code_type == "GS1" ? 'selected' : '' }}>GS1</option>
                                            <option value="EGS" {{ $data->standard_code_type == "EGS" ? 'selected' : '' }}>EGS</option>
                                        </select>
                                        @error('standard_code_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- product code --}}
                                    <div class="form-group">
                                        <label>@lang('site.product_code')</label>
                                        <input type="text" name="product_code" value="{{ $data->product_code }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.product_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- internal code --}}
                                    <div class="form-group">
                                        <label>@lang('site.internal_code')</label>
                                        <input type="text" name="internal_code" value="{{ $data->internal_code }}"
                                            class="form-control" required=""
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.internal_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('internal_code')
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
                @else
                    @if (auth()->user()->hasPermission('product_create'))
                        <div class="col-md-4">
                            <div class="add-service">
                                <form action="{{ route('product.store') }}" method="POST">
                                    @csrf

                                    {{-- product name --}}
                                    <div class="form-group">
                                        <label>@lang('site.name')</label>
                                        <input type="text" name="product_name" class="form-control" required="" value="{{ old('product_name') }}"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.name')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- standard code type --}}
                                    <div class="form-group">
                                        <label>@lang('site.standard_code_type')</label>
                                        <select class="custom-select" name="standard_code_type" required oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.select') @lang('site.standard_code_type')')" oninput="setCustomValidity('')">
                                            <option value="" disabled selected>@lang('site.please') @lang('site.select') @lang('site.standard_code_type')</option>
                                            <option value="GS1" {{ old('standard_code_type') =='GS1' ? 'selected' : '' }}>GS1</option>
                                            <option value="EGS" {{ old('standard_code_type') =='EGS' ? 'selected' : '' }}>EGS</option>
                                        </select>
                                        @error('standard_code_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- product code --}}
                                    <div class="form-group">
                                        <label>@lang('site.product_code')</label>
                                        <input type="text" name="product_code" class="form-control" required="" value="{{ old('product_code') }}"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.product_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('product_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- internal code --}}
                                    <div class="form-group">
                                        <label>@lang('site.internal_code')</label>
                                        <input type="text" name="internal_code" class="form-control" required="" value="{{ old('internal_code') }}"
                                            oninvalid="this.setCustomValidity('@lang('site.please') @lang('site.enter') @lang('site.internal_code')')"
                                            oninput="setCustomValidity('')">
                                        @error('internal_code')
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
                @if (auth()->user()->hasPermission('product_create') ||
               auth()->user()->hasPermission('product_update'))
                    @if ($data)
                        <div class="col-md-8">
                        @else
                            @if (auth()->user()->hasPermission('product_create'))
                                <div class="col-md-8">
                                @else
                                    <div class="col-12">
                            @endif
                    @endif
                @else
                    <div class="col-12">
                @endif
                <div class="card">
                    <div class="card-body">

                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th> @lang('site.id')</th>

                                    <th>@lang('site.name')</th>
                                    <th>@lang('site.product_code')</th>
                                    <th>@lang('site.internal_code')</th>
                                    <th>@lang('site.standard_code_type')</th>
                                    @if (auth()->user()->hasPermission('product_update') ||
        auth()->user()->hasPermission('product_delete'))
                                        <th width="28%"> @lang('site.actions')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        </td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->internal_code }}</td>
                                        <td>{{ $product->standard_code_type }}</td>
                                        @if (auth()->user()->hasPermission('product_update') ||
        auth()->user()->hasPermission('product_delete'))
                                            <td>
                                                <div class="service-option">
                                                    @if (auth()->user()->hasPermission('product_update'))
                                                        <a href="{{ route('product.edit', $product->id) }}"
                                                            class=" btn btn-warning"><i class="fa fa-edit"></i>
                                                            @lang('site.edit') </a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('product_delete'))
                                                        <a class=" btn btn-danger" data-product_id="{{ $product->id }}"
                                                            data-toggle="modal" data-target="#products_delete"><i
                                                                class="fa fa-trash-alt"></i> @lang('site.delete')
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
                </div>
            </div>

        </div>
        </div>

    </section>

    <div class="modal fade text-center" id="products_delete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('site.delete') {{ ' ' }}
                        @lang('site.product')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('site.confirm') {{ ' ' }} @lang('site.small_delete') {{ ' ' }}
                        @lang('site.product') {{ '?' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
                        @lang('site.cancel')</button>
                    <form action="{{ route('product.destroy', ['product' => 'delete']) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="product_id" id="product_id" value="">
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
                "ordering": true,
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

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $('#products_delete').on('show.bs.modal', function(event) {

            var button = $(event.relatedTarget);
            var productid = button.data('product_id');
            $('.modal #product_id').val(productid);
        });



    </script>
@endsection
