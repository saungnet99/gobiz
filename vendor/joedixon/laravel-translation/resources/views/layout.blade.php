<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <!-- CSS files -->
    <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/demo.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
</head>

<body class="antialiased" data-bs-theme="{{ Auth::user()->choosed_theme == 'light' ? 'light' : 'dark' }}"
    dir="{{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr')}}">

    @php
    use App\Setting;
    $settings = Setting::where('status', 1)->first();
    @endphp

    {{-- Preloader --}}
    <div class="page page-center preloader-wrapper">
        <div class="container container-slim py-4">
            <div class="text-center">
                <div class="mb-3">
                    <a href="." class="navbar-brand navbar-brand-autodark"><img src="{{ asset($settings->favicon) }}"
                            height="36" alt="{{ $settings->site_name }}"></a>
                </div>
                <div class="text-secondary mb-3">{{ __('Loading') }}</div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-indeterminate"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="app">
        @include('admin.includes.header')
        @include('admin.includes.nav')

        @yield('body')
    </div>

    <script src="{{ asset('/vendor/translation/js/app.js') }}"></script>

    <!-- Tabler Core -->
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tabler.min.js') }}"></script>
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
</body>

</html>