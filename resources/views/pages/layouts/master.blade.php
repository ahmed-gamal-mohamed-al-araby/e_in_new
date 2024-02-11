@include('pages.includes.header')

<body class="hold-transition sidebar-mini accent-success">

    <div class="loader">
        <svg viewBox="0 0 1350 600">
            <text x="50%" y="50%" fill="transparent" text-anchor="middle">
                EEC Group
            </text>
        </svg>
    </div>

    <div class="wrapper">

        @include('pages.includes.navbar')
        @include('pages.includes.sidebar')


        <!-- Page content -->
        {{-- @yield('content') --}}
        <div class="content-wrapper">
            <div class="overlay"></div>
            <div style="padding: 10px">
                @yield('content')
               {{-- Start loader --}}
                <div class="loader-container" style="display: none">
                    <div class="bouncing-loader">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                {{-- End loader --}}
            </div>
        </div>

        @include('pages.includes.footer')


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    
    <script src="{{ asset('js/_main.js') }}"></script>
    <script>
        const urlLang = window.location.href.includes('/ar/') ? 'ar' : 'en',
            subFolderURL = "{{ env('sub_Folder_URL', '') }}";
    </script>

    {{-- <script>
        const urlLang = window.location.href.includes('/ar/') ? 'ar' : 'en',
                subFolderURL = "{{ env('sub_Folder_URL', '') }}";

    </script> --}}
    <!-- jQuery -->
    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> --}}
    <!-- Bootstrap 4 -->
    {{-- <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
    <!-- DataTables  & Plugins -->
    {{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatablesButtons/buttons.flash.min.js') }}"></script> --}}
    {{-- <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script> --}}
    {{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script> --}}
    {{-- <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/datatableSelect/dataTables.select.min.js') }}"></script> --}}
    {{-- <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script> --}}

    <!-- overlayScrollbars -->
    {{-- <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script> --}}

    <!-- AdminLTE App -->
    {{-- <script src="{{ asset('dist/js/adminlte.min.js') }}"></script> --}}
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->

    {{-- Select2 --}}
    {{-- <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}" type="text/javascript"></script> --}}

    {{-- <script src="{{ asset('plugins/toastr/toastr.min.js')}}" ></script> --}}
    {!! Toastr::message() !!}
    <script src="{{ asset('plugins/tablesorter/js/jquery.tablesorter.combined.js') }}"></script>
    <!-- Custom scripts -->
    @yield('scripts')
</body>

</html>
