@php
// Queries
use Illuminate\Support\Facades\DB;

$config = DB::table('config')->get();
@endphp

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
    <link href="{{ asset('css/fontawesome-iconpicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    @yield('css')

    {{-- Check Google Analytics is "enabled" --}}
    @if (!empty($settings->google_analytics_id))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->google_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', '{{ $settings->google_analytics_id }}');
    </script>
    @endif
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
                <div class="h3 mb-3">{{ __('Loading') }}</div> 
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-indeterminate"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="wrapper" class="page">
        @if (isset($header) && $header)
            @include('user.includes.header')
        @endif
        
        @if (isset($nav) && $nav)
            @include('user.includes.nav')
        @endif

        {{-- Check email verification --}}
        @if ($config[43]->config_value == '1' && auth()->user()->email_verified_at == null)
        <div class="container-xl">
            <div class="mt-3">
                @include('auth.verification-notice')
            </div>
        </div>
        @else
            @yield('content')
        @endif
    </div>

    <!-- Tabler Core -->
    <script type="text/javascript" src="{{ asset('js/tabler.min.js') }}"></script>
    @if (isset($demo) && $demo)
    <script type="text/javascript" src="{{ asset('js/user-delete-query.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-qrcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sweetalert.all.js') }}"></script>
    @endif
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
    @stack('custom-js')
</body>

</html>