@php
$x=1;
@endphp
@extends('pages.layouts.master')

@section('title')
@lang('site.letter_guarantee')
@endsection

{{-- Page content --}}
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> @lang('site.letter_guarantee')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"> @lang('site.letter_guarantee') </li>
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
                    Session::forget('error');
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
                        <form action="{{ route('letter_guarantee.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control">
                            <br>
                            <button class="btn btn-success">@lang('site.add_letters_guarantee_via_excel')</button>
                        </form>
                    </div>
                    <hr>
                    <div class="card-header">
                        <form action="{{ route('letter_guarantee_changing.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control">
                            <br>
                            <button class="btn btn-success">@lang('site.add_extend_raise_letters_guarantee_via_excel')</button>
                        </form>
                    </div>
                    <div class="card-body">

                        @if (auth()->user()->hasPermission('letter_guarantee_create'))
                        <a href="{{route('letter_guarantee.create')}}" class="btn btn-success btn-sm mb-4">
                            <i class="fa fa-user-plus nav-icon"></i> @lang('site.add_letter')
                        </a>
                        @endif
                        <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr style="text-align:center;">
                                    <th>#</th>
                                    <th> @lang('site.client_name') </th>
                                    <th> @lang('site.side') </th>
                                    <th> @lang('site.purpose') </th>
                                    <th> @lang('site.project_number') </th>
                                    <th> @lang('site.letter_guarantee_num') </th>
                                    <th> @lang('site.letter_guarantee_value') </th>
                                    <th> @lang('site.cash_margin') </th>
                                    <th> @lang('site.release_date') </th>
                                    <th> @lang('site.expiry_date') </th>
                                    <th> @lang('site.reply_date') </th>

                                    <th width="200px">@lang('site.actions')</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($letters_guarantee as $letter_guarantee)
                                @php

                                $letter_guarantee_changing = DB::table('letters_guarantee_changing')
                                ->where('letters_guarantee_id', $letter_guarantee->id)
                                ->orderBy('id', 'DESC') ->first();

                                @endphp
                                <tr>
                                    <td>{{$x++}}</td>
                                    @if(isset($letter_guarantee->client_id))
                                    @if($letter_guarantee->client_type=="b")
                                    <td>{{$letter_guarantee->businessClient->name}}</td>
                                    @elseif($letter_guarantee->client_type=="p")
                                    <td>{{$letter_guarantee->personClient->name}}</td>

                                    @else
                                    <td>{{$letter_guarantee->foreignerClient->company_name}}</td>

                                    @endif
                                    @else
                                    <td>{{$letter_guarantee->client_name}}</td>

                                    @endif
                                    <td>{{$letter_guarantee->side}}</td>
                                    <td>{{$letter_guarantee->purpose}}</td>
                                    <td>{{$letter_guarantee->project_number}}</td>
                                    <td>{{$letter_guarantee->letter_guarantee_num}}</td>
                                    @if(isset($letter_guarantee_changing))
                                    <td>{{$letter_guarantee_changing->value}}</td>
                                    <td>{{$letter_guarantee_changing->cash_margin}}</td>
                                    @else
                                    <td>{{$letter_guarantee->value}}</td>
                                    <td>{{$letter_guarantee->cash_margin}}</td>
                                    @endif
                                    <td>{{$letter_guarantee->release_date}}</td>
                                    @if(isset($letter_guarantee_changing))
                                    <td>{{$letter_guarantee_changing->expiry_date}}</td>
                                    @else
                                    <td>{{$letter_guarantee->expiry_date}}</td>
                                    @endif
                                    @if(isset($letter_guarantee->reply_date))
                                    <td>{{$letter_guarantee->reply_date}}</td>
                                    @else
                                    <td>@lang('site.not_answered')</td>
                                    @endif
                                    <td>

                                        <div class="service-option">
                                            <a href="{{route('letter_guarantee.show',$letter_guarantee->id)}}" class="btn btn-success"><i class="fa fa-show "></i> @lang('site.show') </a>
                                            @if(!isset($letter_guarantee->reply_date))

                                            <form action="{{ route('letter_guarantee.destroy',$letter_guarantee->id) }}" method="POST">
                                                @if (auth()->user()->hasPermission('letter_guarantee_update'))
                                                <a href="{{route('letter_guarantee.edit',$letter_guarantee->id)}}" class="btn btn-warning"><i class="fa fa-edit "></i> @lang('site.edit') </a>
                                                @endif
                                                @if (auth()->user()->hasPermission('letter_guarantee_answered'))

                                                <button class="open-AddAnswered btn btn-warning" type="button" data-release_date="{{$letter_guarantee->release_date}}" data-id="{{$letter_guarantee->id}}" data-toggle="modal" data-target="#myModal">@lang('site.answered')</button>
                                                <button class="open-bank_commissions btn btn-secondary" type="button" data-id="{{$letter_guarantee->id}}" data-toggle="modal" data-target="#myModal2">@lang('site.bank_commissions')</button>
                                                @endif

                                                @if (auth()->user()->hasPermission('letter_guarantee_extend_raise'))

                                                <a href="{{route('letter_guarantee.extend_raise',$letter_guarantee->id)}}" class="btn btn-info"><i class="fa fa-edit "></i> @lang('site.extend_raise') </a>
                                                @endif


                                                @csrf
                                                @method('DELETE')
                                                @if (auth()->user()->hasPermission('letter_guarantee_delete'))

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

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('site.answered')</h4>
                </div>
                <form action="{{route('letter_guarantee_answered.store')}}" class="" id="commentForm" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('put')

                    <div class="modal-body">
                        <div class="col-md-4 ">
                            <label class="form-label" for="">@lang('site.reply_date')</label>

                            <input type="hidden" value="" name="letterGuaranteeID" id="letterGuaranteeID">
                            <input required id="reply_date" type="date" placeholder="@lang('site.reply_date')" class="form-control" name="reply_date">

                        </div>
                        <div class="col-md-4 ">
                            <label class="form-label" for="">@lang('site.recipient_name')</label>

                            <input id="recipient_name" type="text" placeholder="@lang('site.recipient_name')" class="form-control" name="recipient_name">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" id="edit-btn" value="@lang('site.save')">
                    </div>



                </form>
            </div>
        </div>
    </div>
    <!-- Modal2 -->
    <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('site.add') @lang('site.bank_commissions')</h4>
                </div>
                <form action="{{route('letter_guarantee.bank_commissions')}}" class="" id="commentForm" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('put')

                    <div class="modal-body2">
                        <div class="col-md-8 ">
                            <label class="form-label" for="">@lang('site.date')</label>

                            <input type="hidden" value="" name="letterGuaranteeID" id="letterGuaranteeID">
                            <input required id="date" type="date" placeholder="@lang('site.date')" class="form-control" name="date">

                        </div>
                        <div class="col-md-8 ">
                            <label class="form-label" for="">@lang('site.value_commission')</label>

                            <input id="value_commission" type="text" placeholder="@lang('site.value_commission')" class="form-control" name="value_commission">

                        </div>
                        <div class="col-md-8 ">
                            <label class="form-label" for="">@lang('site.statement')</label>

                            <textarea class="form-control" name="statement" id="statement" cols="30" rows="2" placeholder="@lang('site.statement')"></textarea>

                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="">@lang('site.image')</label>
                            <input value="{{ old('image') }}" type="file" name="image" id="image" class="form-control" placeholder="@lang('site.image')">
                            <!-- Way 2: Display Error Message -->
                            @error('image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" id="edit-btn" value="@lang('site.save')">
                    </div>



                </form>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script>
    $(document).on("click", ".open-AddAnswered", function() {
        var letter_guarantee_id = $(this).data('id');
        var release_date = $(this).data('release_date');

        $(".modal-body #letterGuaranteeID").val(letter_guarantee_id);
        $(".modal-body #reply_date").prop('min', release_date);

        // As pointed out in comments, 
        // it is unnecessary to have to manually call the modal.
        // $('#addBookDialog').modal('show');
    });
    $(document).on("click", ".open-bank_commissions", function() {
        var letter_guarantee_id = $(this).data('id');

        $(".modal-body2 #letterGuaranteeID").val(letter_guarantee_id);

        // As pointed out in comments, 
        // it is unnecessary to have to manually call the modal.
        // $('#addBookDialog').modal('show');
    });

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