<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EECGroup | الموردين</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">

    <style>
        body, h1, h2, h3, h4, h5, h6 {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini accent-success">
<div class="wrapper">

@include('layouts.navbar')

@include('layouts.sidebar')


<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="overlay"></div>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 title="جدول يوضح جميع المستخدمين ">جميع المستخدمين</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">الصفحة الشخصية</li>
                            <li class="breadcrumb-item "><a href="{{route('users.index')}}"> المستخدمين </a></li>
                            <li class="breadcrumb-item"><a href="{{route('home')}}">الصفحة الرئيسية</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content profile-page user-profile-page">

            {{--                <div class="container-fluid">--}}
            {{--                    <div class="row">--}}
            {{--                        <div class="col-md-3">--}}

            {{--                            <!-- Profile Image -->--}}
            {{--                            <div class="card card-primary card-outline">--}}
            {{--                                <div class="card-body box-profile">--}}
            {{--                                    <div class="image_header">--}}
            {{--                                    <div class="text-center">--}}
            {{--                                        @if($user->profile_image)--}}
            {{--                                            <img class="profile-user-img img-fluid img-circle"--}}
            {{--                                                 src="{{asset('Images/Profile/'.$sup->profile_image)}}"--}}
            {{--                                                 alt="صورة شخصية">--}}
            {{--                                        @else--}}
            {{--                                            <img class="profile-user-img-not img-fluid img-circle"--}}
            {{--                                                 src="{{asset('Images/user_profile.png')}}"--}}
            {{--                                                 alt="صورة شخصية">--}}
            {{--                                        @endif--}}
            {{--                                        <img class="profile-user-img-not img-fluid img-circle"--}}
            {{--                                              src="{{asset('Images/user_profile.png')}}"--}}
            {{--                                         alt="صورة شخصية">--}}
            {{--                                    </div>--}}

            {{--                                    <h3 class="profile-username text-center">--}}
            {{--                                        {{$user->name}}--}}
            {{--                                    </h3>--}}
            {{--                                </div>--}}

            {{--                                    <ul class="list-group list-group-unbordered mb-3">--}}

            {{--                                        <li class="list-group-item">--}}
            {{--                                            @if($sup->phone)--}}
            {{--                                                <b>تلفون</b> <a class="float-right">{{$sup->phone}}</a>--}}
            {{--                                            @else--}}
            {{--                                                <b>تلفون</b> <a class="float-right"> -- </a>--}}
            {{--                                            @endif--}}
            {{--                                            sssss--}}
            {{--                                        </li>--}}
            {{--                                        <li class="list-group-item">--}}
            {{--                                            @if($sup->fax)--}}
            {{--                                                <b>فاكس</b> <a class="float-right">{{$sup->fax}}</a>--}}
            {{--                                            @else--}}
            {{--                                                <b>فاكس</b> <a class="float-right"> -- </a>--}}
            {{--                                            @endif--}}
            {{--                                            nnnnsad--}}
            {{--                                        </li>--}}
            {{--                                        <li class="list-group-item">--}}

            {{--                                            <b>  تاريخ الأضافة</b> <a class="float-right">--}}
            {{--                                                {{ \Carbon\Carbon::parse($sup->created_at)->format('d/m/Y')}}</a>--}}
            {{--                                        </li>--}}

            {{--                                    </ul>--}}

            {{--                                    <a href="{{route('supplier.edit',$sup->id)}}" class="btn btn-warning btn-block"><b>تعديل</b></a>--}}
            {{--                                </div>--}}
            {{--                                <!-- /.card-body -->--}}
            {{--                            </div>--}}
            {{--                            <!-- /.card -->--}}


            {{--                        </div>--}}
            {{--                        <!-- /.col -->--}}
            {{--                        <div class="col-md-9">--}}
            {{--                            <div class="card">--}}

            {{--                                <div class="card-body">--}}
            {{--                                    <div class="tab-content">--}}
            {{--                                        <div class="active tab-pane" id="activity">--}}
            {{--                                            <!-- Post -->--}}
            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-envelope-square"></i>  البريد الإلكترونى--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    {{$sup->supplier_email}}--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}

            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-map-marked-alt"></i>  الدولة--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    {{$sup->country}}--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}

            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-map-marked-alt"></i>  المحافظة--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    {{$sup->city}}--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-map-marked-alt"></i>  العنوان--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    {{$sup->address}}--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-map-marked-alt"></i>  رابط العنوان--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    للذهاب إلى العنوان اضغط <a target="_blank" href="{{$sup->address_link}}">--}}
            {{--                                                        هنــا   <i class="fa fa-link"> </i> </a>--}}

            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                            <div class="supplier-info">--}}
            {{--                                                <div class="header-info">--}}
            {{--                                                    <i class="fas fa-map-marked-alt"></i> الموقع الإلكترونى--}}
            {{--                                                </div>--}}
            {{--                                                <div class="body-info">--}}
            {{--                                                    للذهاب إلى الموقع اضغط <a target="_blank" href="{{$sup->website}}">--}}
            {{--                                                        هنــا   <i class="fa fa-link"> </i> </a>--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                        </div>--}}



            {{--                                        <!-- /.tab-pane -->--}}

            {{--                                        <!-- /.tab-pane -->--}}
            {{--                                    </div>--}}
            {{--                                    <!-- /.tab-content -->--}}
            {{--                                </div><!-- /.card-body -->--}}
            {{--                            </div>--}}
            {{--                            <!-- /.card -->--}}
            {{--                        </div>--}}
            {{--                        <!-- /.col -->--}}
            {{--                    </div>--}}
            {{--                    <!-- /.row -->--}}
            {{--                </div><!-- /.container-fluid -->--}}
            <h1 style="text-align: center; margin-top: 20%">i'm Sorry mr. {{$user->name}} SOON </h1>

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@include('layouts.footer')


<!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- AdminLTE App -->

<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{asset('plugins/select2/dist/js/select2.min.js')}}" type="text/javascript"></script>
<!-- Page specific script -->


<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}
</body>
</html>
