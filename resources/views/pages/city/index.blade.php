@extends('pages.layouts.master')

@section('title')
@lang('site.cities')
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
        <h1>@lang('site.cities')</h1>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"> @lang('site.cities') </li>
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
      @if (auth()->user()->hasPermission('city_update'))
      {{-- Edit city --}}
      <div class="col-md-4">
        <div class="add-service">
          <form action="{{route('city.update',$data->id )}}" method="Post">
            @csrf
            <div class="form-group">
              <label>@lang('site.edit_sub_group')</label>
              <select id='country_select' name="country_id" class="form-control" required=""
                oninvalid="this.setCustomValidity('@lang('site.check_country')')" onchange="setCustomValidity('')">
                <option></option>
                @foreach($countries as $country)
                <option value='{{$country->id}}' {{ $data->country_id == $country->id ? 'selected' : '' }}>
                  {{$country->name}}</option>
                @endforeach
              </select>
              @error('country_id')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <label for="Add Service ">@lang('site.edit') {{ ' ' }} @lang('site.small_city')</label>
              <input type="text" name="name" value="{{$data->name}}" class="form-control" required=""
                oninvalid="this.setCustomValidity('@lang('site.check_city')')"
                oninput="setCustomValidity('')">
              @error('name')
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
      {{-- Add city --}}
      @if (auth()->user()->hasPermission('city_create'))
      <div class="col-md-4">
        <div class="add-service">
          <form action="{{route('city.store')}}" method="POST">
            @csrf
            <div class="form-group">
              <label>@lang('site.add_city')</label>
              <select id='country_select' name="country_id" class="form-control" required=""
                oninvalid="this.setCustomValidity('@lang('site.check_country')')" onchange="setCustomValidity('')">
                <option></option>
                @foreach($countries as $country)
                <option value='{{$country->id}}'>
                  {{$country->name}}</option>
                @endforeach
              </select>
              @error('country_id')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group">
              <input type="text" name="name" class="form-control" required=""
                oninvalid="this.setCustomValidity('@lang('site.check_city')')"
                oninput="setCustomValidity('')" placeholder=" @lang('site.enter') {{ ' ' }} @lang('site.small_city')">
              @error('name')
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

      {{-- View all citys --}}
      @if (auth()->user()->hasPermission('city_create') ||
      auth()->user()->hasPermission('city_update'))
          @if ($data)
              <div class="col-md-8">
              @else
                  @if (auth()->user()->hasPermission('city_create'))
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
                  <th> @lang('site.the_city')</th>
                  <th> @lang('site.the_country')</th>
                  <th width="28%"> @lang('site.actions')</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($cities as $city)
                <tr>
                  <td>{{$city->id}}</td>
                  <td>{{$city->name}}</td>
                  <td>{{$city->country->name}}</td>
                  <td>
                    <div class="service-option">
                      <a href="{{route('city.edit',$city->id)}}" class=" btn btn-warning"><i class="fa fa-edit"></i>
                        @lang('site.edit') </a>
                      <a class=" btn btn-danger" data-city_id="{{$city->id}}" data-toggle="modal"
                        data-target="#city_delete"><i class="fa fa-trash-alt"></i> @lang('site.delete') </a>
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

{{-- Delete city model --}}
<div class="modal fade text-center" id="city_delete" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true" dir="rtl">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"> @lang('site.delete') {{ ' ' }} @lang('site.the_city')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>@lang('site.confirm') {{ ' ' }} @lang('site.small_delete') {{ ' ' }} @lang('site.the_city') {{ '?' }}
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal"> @lang('site.no') ,
          @lang('site.cancel')</button>
        <form action="{{route('city.destroy', ['city' => 'delete'])}}" method="POST">
          @method('DELETE')
          @csrf
          <input type="hidden" name="city_id" id="city_id" value="">
          <button type="submit" class="btn btn-outline-dark"> @lang('site.yes') , @lang('site.delete') </button>
        </form>

      </div>
    </div>
  </div>
</div>

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
                "next" : "@lang('site.next')",

            },
            "info":   "@lang('site.show') _START_  @lang('site.from') _TOTAL_  @lang('site.record')",

            buttons: {
                colvis: ' @lang("site.show_data")',
                'print' : ' @lang("site.print")',
                'copy' : ' @lang("site.copy")',
                'excel' : '@lang("site.excel")'
            },
            "emptyTable":     "@lang('site.no_data')",
            "infoEmpty":      "@lang('site.show') 0 @lang('site.from') 0 @lang('site.record')",
            "infoFiltered":   "( @lang('site.search_in') _MAX_  @lang('site.records'))",
        }

    });
  });
</script>
<script>
  $('#country_select').select2({
      placeholder: '@lang('site.please') {{ ' ' }} @lang('site.select') {{ ' ' }} @lang('site.small_country')',
    });

  $('#city_delete').on('show.bs.modal',function(event){
    var button = $(event.relatedTarget);
    var serviceid = button.data('city_id');
    console.log(serviceid);
    $('.modal #city_id').val(serviceid);
})
</script>
@endsection
