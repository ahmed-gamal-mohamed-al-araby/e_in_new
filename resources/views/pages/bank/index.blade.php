@extends('pages.layouts.master')

@section('title')
    @lang('site.banks')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('invoice/css/style.css') }}">
@endsection

@section('content')

    <section class="content-header prequestHeader">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h1> @lang('site.banks') </h1>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"> @lang('site.banks') </li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('site.home')</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>
                                @lang('site.serial')
                            </th>
                            <th>
                                @lang('site.bank_name')
                            </th>
                            <th>
                                @lang('site.bank_code')
                            </th>
                            <th>
                                @lang('site.bank_account_number')
                            </th>
                            <th>
                                @lang('site.bank_account_iban')
                            </th>
                            <th>
                                @lang('site.swift_code')
                            </th>
                            <th>
                                @lang('site.bank_address')
                            </th>
                            <th>
                                @lang('site.currency')
                            </th>
                            @if (auth()->user()->hasPermission('bank_update') || auth()->user()->hasPermission('bank_read'))
                                <th width="150px">
                                    @lang('site.actions')
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banks as $bank)

                            <tr class="justify-content-center" data-entry-id="{{ $bank->id }}">
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $bank->bank_name ?? '' }}
                                </td>
                                <td>
                                    {{ $bank->bank_code }}
                                </td>
                                <td>
                                    {{ $bank->bank_account_number ?? '' }}
                                </td>

                                <td>
                                    {{ $bank->bank_account_iban ?? '' }}
                                </td>

                                <td>
                                    {{ $bank->swift_code ?? '' }}
                                </td>

                                <td>
                                    {{ $bank->bank_address ?? '' }}
                                </td>

                                <td>
                                    {{ $bank->currency ?? '' }}
                                </td>
                                <td class="requests-btn">
                                    <div class="row justify-content-center">
                                        @if (auth()->user()->hasPermission('bank_update'))
                                            <a class="btn btn-sm btn-warning m-1"
                                                href="{{ route('bank.edit', $bank->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('bank.show', $bank->id) }}" class=" btn btn-sm btn-success m-1"><i class="fa fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
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
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "@lang('site.all')"]
                ],
                columnDefs: [{
                        targets: "hiddenCols",
                        visible: false
                    }

                ],
                "buttons": ["copy", "excel", "print", "colvis"],
                "language": {
                    search: '<i class="fa fa-filter" aria-hidden="true"></i>',
                    searchPlaceholder: '@lang("site.search") ',
                    "lengthMenu": "@lang('site.show')  _MENU_ @lang('site.records') ",
                    "paginate": {
                        "previous": "@lang('site.prev')",
                        "next": "@lang('site.next')",

                    },
                    "emptyTable": "@lang('site.no_data')",
                    "info": "@lang('site.show')  _END_ @lang('site.from') _TOTAL_ @lang('site.record')",
                    "infoEmpty": "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
                    "infoFiltered": "(@lang('site.search_in')  _MAX_  @lang('site.record'))",

                    buttons: {
                        colvis: '@lang("site.show_data") <i class="fa fa-eye-slash "> </i> ',
                        'print': '@lang("site.print") <i class="fa fa-print "> </i> ',
                        'copy': '@lang("site.copy") <i class="fa fa-copy "> </i>',
                        'excel': '@lang("site.excel") <i class="fa fa-file-excel "> </i>',

                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-primary'
                            },
                            {
                                extend: 'excel',
                                className: 'excelButton'
                            }
                        ]
                    },

                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });

    </script>
@endsection
