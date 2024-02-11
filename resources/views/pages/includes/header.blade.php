<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>EECGroup | @yield('title')</title>

    <!-------------------------------- Fonts -------------------------------->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,700">

    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}"> --}}

    <!-------------------------------- Styles -------------------------------->
    <!-- overlayScrollbars -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> --}}

    <!-- DataTables -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
    {{-- <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" /> --}}

    <!-- Theme style -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/loader.css') }}"> --}}


    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custome.css') }}">

    {{-- favicon --}}
    <link rel="shortcut icon" href="{{ asset('Images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('plugins/tablesorter/css/theme.materialize.min.css') }}">


    {{-- <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Cairo', sans-serif !important;
        }

        .alert-danger {
            color: #842029 !important;
            background-color: #f8d7da !important;
            border-color: #f5c2c7 !important;
        }

        .alert-success {
            color: #0f5132 !important;
            background-color: #d1e7dd !important;
            border-color: #badbcc !important;
        }

        .alert-dark {
            color: #141619;
            background-color: #d3d3d4;
            border-color: #bcbebf;
        }
        .select2-container {
            width: 100% !important;
        }

        .select2-container *, .select2-container .select2-results__options {
            text-align: inherit !important;
        }

        /* Sidebar */

        aside .nav-item {
            overflow: hidden !important;
            max-width: 98%;
        }

        .input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control, .input-group>.form-control-plaintext {
            /* width: inherit; */
        }
        .os-host, .os-host-textarea {
            position: absolute;
        }
        .scroll_ellipsis_text_on_hover {
            width: 100%;
            /* display: inline-block; */
            /* important */
            white-space: nowrap;
            overflow: hidden;
            /* when not hovering show ellipsis */
            /* animate on either hover or focus */
            /* define the animation */
        }
        .scroll_ellipsis_text_on_hover:not(:hover) {
            text-overflow: ellipsis;
        }
        .scroll_ellipsis_text_on_hover:hover p, .scroll_ellipsis_text_on_hover:focus p {
            display: inline-block;
            animation-name: scroll-text;
            animation-duration: 3s;
            animation-timing-function: linear;
            animation-delay: 0s;
            animation-iteration-count: infinite;
            animation-direction: normal;
            /* FYI this would be the shorthand: animation: scroll-text 5s ease 0s 2 normal;
            */
        }
        @keyframes scroll-text {
            0% {
                transform: translateX(0%);
            }
            50% {
                transform: translateX(-25%);
            }
            50% {
                transform: translateX(-25%);
            }
            100% {
                transform: translateX(0%);
            }
        }

         /* START TOOLTIP STYLES */

         [tooltip] {
            position: relative;
            /* opinion 1 */
        }

        /* Applies to all tooltips */

        [tooltip]::before, [tooltip]::after {
            text-transform: none;
            /* opinion 2 */
            font-size: 1.3em;
            /* opinion 3 */
            line-height: 1;
            user-select: none;
            pointer-events: none;
            position: absolute;
            display: none;
            opacity: 0;
        }

        [tooltip]::before {
            content: '';
            border: 5px solid transparent;
            /* opinion 4 */
            z-index: 1001;
            /* absurdity 1 */
        }

        [tooltip]::after {
            content: attr(tooltip);
            /* magic! */
            /* most of the rest of this is opinion */
            font-family: Helvetica, sans-serif;
            text-align: center;
            /*
            Let the content set the size of the tooltips
            but this will also keep them from being obnoxious
            */
            min-width: 3em;
            max-width: 21em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 1ch 1.5ch;
            border-radius: .3ch;
            box-shadow: 0 1em 2em -.5em rgba(0, 0, 0, 0.35);
            background: #333;
            color: #fff;
            z-index: 1000;
            /* absurdity 2 */
        }

        /* Make the tooltips respond to hover */

        [tooltip]:hover::before, [tooltip]:hover::after {
            display: block;
        }

        /* don't show empty tooltips */

        [tooltip='']::before, [tooltip='']::after {
            display: none !important;
        }

        /* FLOW: UP */

        [tooltip]:not([flow])::before, [tooltip][flow^="up"]::before {
            bottom: 100%;
            border-bottom-width: 0;
            border-top-color: #333;
        }

        [tooltip]:not([flow])::after, [tooltip][flow^="up"]::after {
            bottom: calc(100% + 5px);
        }

        [tooltip]:not([flow])::before, [tooltip]:not([flow])::after, [tooltip][flow^="up"]::before, [tooltip][flow^="up"]::after {
            left: 50%;
            transform: translate(-50%, -.5em);
        }

        /* FLOW: DOWN */

        [tooltip][flow^="down"]::before {
            top: 100%;
            border-top-width: 0;
            border-bottom-color: #333;
        }

        [tooltip][flow^="down"]::after {
            top: calc(100% + 5px);
        }

        [tooltip][flow^="down"]::before, [tooltip][flow^="down"]::after {
            left: 50%;
            transform: translate(-50%, .5em);
        }

        /* FLOW: LEFT */

        [tooltip][flow^="left"]::before {
            top: 50%;
            border-right-width: 0;
            border-left-color: #333;
            left: calc(0em - 5px);
            transform: translate(-.5em, -50%);
        }

        [tooltip][flow^="left"]::after {
            top: 50%;
            right: calc(100% + 5px);
            transform: translate(-.5em, -50%);
        }

        /* FLOW: RIGHT */

        [tooltip][flow^="right"]::before {
            top: 50%;
            border-left-width: 0;
            border-right-color: #333;
            right: calc(0em - 5px);
            transform: translate(.5em, -50%);
        }

        [tooltip][flow^="right"]::after {
            top: 50%;
            left: calc(100% + 5px);
            transform: translate(.5em, -50%);
        }

        /* KEYFRAMES */

        @keyframes tooltips-vert {
            to {
                opacity: .9;
                transform: translate(-50%, 0);
            }
        }

        @keyframes tooltips-horz {
            to {
                opacity: .9;
                transform: translate(0, -50%);
            }
        }

        /* FX All The Things */

        [tooltip]:not([flow]):hover::before, [tooltip]:not([flow]):hover::after, [tooltip][flow^="up"]:hover::before, [tooltip][flow^="up"]:hover::after, [tooltip][flow^="down"]:hover::before, [tooltip][flow^="down"]:hover::after {
            animation: tooltips-vert 300ms ease-out forwards;
        }

        [tooltip][flow^="left"]:hover::before, [tooltip][flow^="left"]:hover::after, [tooltip][flow^="right"]:hover::before, [tooltip][flow^="right"]:hover::after {
            animation: tooltips-horz 300ms ease-out forwards;
        }



        @if (app()->getLocale() == 'ar').text-direction-arabic {
            direction: rtl;
        }

        @endif

    </style> --}}
    <!-------------------------------- Styles -------------------------------->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    @if (app()->getLocale() == 'ar')
        {{-- <link rel="stylesheet" href="{{ asset('dist/css-rtl/main.css') }}"> --}}

        {{-- note this file must be update if you do changes in
            1. custom style => (dist/css/style.css) and use minifier (https://cssminifier.com)
            2. custom RTL style => (dist/css-rtl/custom-rtl.css) and use minifier (https://cssminifier.com) --}}

        {{-- <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}"> --}}
        
        {{-- <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('dist/css-rtl/style.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('dist/css-rtl/bootstrap-rtl.min.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ asset('dist/css-rtl/custom-rtl.css') }}"> --}}

        <style>
            #cash_check_id, #cheque_check_id  {
                margin-right : 25px
            }
            #bank_transfer_check_id {
                margin-right : 40px
            }
        </style>

    @else
        {{-- <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}"> --}}
        {{-- note this file must be update if you do changes in custom style => (dist/css/style.css) and use minifier (https://cssminifier.com) --}}


        {{-- <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}"> --}}


    @endif
    <!-- Custom style -->
    @yield('styles')
</head>
