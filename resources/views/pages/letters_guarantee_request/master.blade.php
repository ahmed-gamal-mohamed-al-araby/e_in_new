
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EECGroup | Requests</title>

    {{-- Links --}}
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">

    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Cairo', sans-serif !important;
        }

        body {
            margin: 0 auto;
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            /* max-width: 800px; */
            /* width: 100%; */
            border: 1px solid #ddd;
        }

        .print_header {
            position: fixed;
            top: 0;
            z-index: 9999
        }

        .print_footer {
            position: fixed;
            bottom: 0;
            z-index: 9990;
        }

        .t-head,
        .t-foot {
            height: 28px;
            /* max-width: 800px; */
            /* width: 100%; */
            text-align: center;
            background: #fff;
        }

        .t-head h5,
        .t-foot h5,
        .t-head h3,
        .t-foot h3 {
            margin: 5px;
            color: #555;
        }

        .footer,
        .t-foot {
            height: 25px;
        }

        .t-head,
        .t-foot {
            background: #fff;
        }

        table {
            width: 100%;
        }

        @media print {
            body {
                border: 0;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            button {
                display: none;
            }

            body {
                margin: 0;
            }

            @page {
                margin: 10mm 0mm 10mm 0mm;
            }
        }


        .number_arabic {
            unicode-bidi: plaintext;
            font-family: Arial, Helvetica, sans-serif;
            /* font-weight: 600; */
            /* font-size: 16px; */
        }

        th.number_arabic {
            /* font-size: 15px !important; */
        }

        .card_border,
        .card_border thead tr {
            border: 0.5px solid rgb(0 0 0 / 13%);
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .card_border th,
        .card_border tr,
        .card_border td,
        .card_border thead tr th,
        .card_border tbody {
            border: inherit;
        }

        @page {
            /* size: A4; */
            counter-increment: page;

            @bottom-center {
                content: counter(page);
            }
        }

        @media print {

            #tableTotal,
            #tableTotal tr td,
            #tableTotal tr th {
                page-break-inside: avoid;
            }
        }

    </style>
    @yield('styles')
</head>

<body>
    @yield('content')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/Tafqeet.js') }}"></script>


    @yield('scripts')
</body>

</html>
