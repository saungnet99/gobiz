<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings->site_name }}</title>

    <link rel="icon" href="{{ asset($settings->favicon) }}" sizes="96x96" type="image/png" />

    <!-- CSS files -->
    <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/demo.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    @yield('css')
</head>

<body class="antialiased" data-bs-theme="{{ Auth::user()->choosed_theme == 'light' ? 'light' : 'dark' }}"
    dir="{{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr')}}">

    {{-- Preloader --}}
    <div class="page page-center preloader-wrapper">
        <div class="container container-slim py-4">
            <div class="text-center">
                <div class="mb-3">
                    <img src="{{ asset($settings->favicon) }}" height="36" alt="{{ $settings->site_name }}">
                </div>
                <div class="mb-3 h3">{{ __('Loading') }}</div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-indeterminate"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="wrapper" class="page">
        @if (isset($header) && $header)
        @include('admin.includes.header')
        @endif
        @if (isset($nav) && $nav)
        @include('admin.includes.nav')
        @endif
        
        @yield('content')
    </div>

    <!-- Tabler Core -->
    <script type="text/javascript" src="{{ asset('js/tabler.min.js') }}"></script>
    @if (isset($demo) && $demo)
    <script type="text/javascript" src="{{ asset('js/admin-delete-query.js') }}"></script>
    @endif
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable.js') }}"></script>
    {{-- Preloader --}}
    <script>
        $(document).ready(function() {
            "use strict";
            $('.preloader-wrapper').fadeOut();
        });
        // Choose langages
        $('#chooseLang').change(function () {
            "use strict";
            // set the window's location property to the value of the option the user has selected
            window.location = `?change_language=` + $(this).val();
        });
    </script>
    @yield('scripts')
</body>

</html>