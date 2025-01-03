<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $card_details->title }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ url($business_card_details->profile) }}" sizes="512x512" type="image/png" />
    <link rel="apple-touch-icon" href="{{ url($business_card_details->profile) }}">

    <meta name="theme-color" content="green" />

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="application-name" content="{{ $card_details->title }}">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-title" content="{{ $card_details->title }}">

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="green">
    <meta name="msapplication-TileImage" content="{{ url($business_card_details->profile) }}">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    <!-- CSS files -->
    <link rel="stylesheet" href="{{ url('templates/css/tailwind.min.css') }}" />
    <link rel="stylesheet" href="{{ url('templates/css/template-4.css') }}" />
    <link rel="stylesheet" href="{{ url('css/fontawesome.min.css') }}" />
    <script type="text/javascript" src="{{ url('js/sweetalert.min.js') }}"></script>

    <!-- Include the qrious library -->
    <script src="{{ url('js/qrious.min.js') }}"></script>

    {{-- Check business details --}}
    @if ($business_card_details != null)
    <style>
    {!! $business_card_details->custom_css !!}
    </style>
    @endif

    {{-- Check PWA --}}
    @if ($plan_details != null)
    @if ($plan_details['pwa'] == 1)

    @laravelPWA

    <!-- Web Application Manifest -->
    <link rel="manifest" href="{{ $manifest }}">

    @endif
    @endif
</head>

@php
use Illuminate\Support\Facades\Session;
@endphp

<body class="antialiased text-body font-body lg:bg-green-500"
    dir="{{(App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr')}}">

    {{-- Check password protected --}}
    @if ($business_card_details->password == null || Session::get('password_protected') == true)
    {{-- Check business details --}}
    @if ($business_card_details != null)
    <section class="lg:py-5">
        <div class="lg:w-8/12 px-2 mx-auto">
            <div class="flex flex-wrap template-4 -m-2">
                <div class="w-full lg:w-1/2">
                    <!-- Profile -->
                    <div class="px-4 py-6 lg:bg-white lg:shadow rounded">
                         <div class="relative {{ $business_card_details->cover_type == "photo" ? 'pt-14' : 'pt-1' }} rounded overflow-hidden">
                            @if ($business_card_details->cover_type == "photo")
                            <img class="absolute top-0 left-0 w-full h-24 object-cover" src="{{ url($business_card_details->cover) }}" />
                            @endif

                            <img class="relative {{ $business_card_details->cover_type == "photo" ? 'w-16 h-16 mb-4' : 'w-48 h-48 mb-2' }}  mx-auto rounded-full object-cover object-right" src="{{ url($business_card_details->profile) }}" alt="{{ $business_card_details->title }}" />

                            <div class="text-center mb-4">
                                <div>
                                    <div class="mb-2">
                                        <h2 class="font-medium">{{ $business_card_details->title }}</h2>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $card_details->sub_title }}</p>
                                </div>
                            </div>

                            {{-- Business card description --}}
                            @if ($business_card_details->description != null || $business_card_details->address != null)
                            <div class="mb-7">
                                <div class="text-md text-left text-center">
                                    {!! $business_card_details->description !!}
                                </div>
                            </div>
                            @endif

                            {{-- Language Switcher --}}
                            @include('templates.includes.language-switcher')

                            <!-- Actions -->
                            <div class="flex flex-wrap -mx-2">
                                <!-- QR -->
                                <div class="w-1/2 md:w-1/2 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex qr-modal-open px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200"
                                        href="#"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-qrcode mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path>
                                            <path d="M7 17l0 .01"></path>
                                            <path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path>
                                            <path d="M7 7l0 .01"></path>
                                            <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path>
                                            <path d="M17 7l0 .01"></path>
                                            <path d="M14 14l3 0"></path>
                                            <path d="M20 14l0 .01"></path>
                                            <path d="M14 14l0 3"></path>
                                            <path d="M14 20l3 0"></path>
                                            <path d="M17 17l3 0"></path>
                                            <path d="M20 17l0 3"></path>
                                        </svg>
                                        {{ __('QR Code') }}
                                    </a>
                                </div>

                                <!-- Send -->
                                <div class="w-1/2 md:w-1/2 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex send-modal-open px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200"
                                        href="#"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 14l11 -11"></path>
                                            <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                                        </svg>
                                        {{ __('Send') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Social links -->
                        @if (count($feature_details) > 0)
                        <div class="lg:py-10 py-10">
                            <h2 class="w-full md:w-auto mt-4 mb-2 md:mb-0 text-2xl font-bold">
                                {{ __('Social links') }}
                            </h2>
                            <div class="flex flex-wrap items-center -mx-2 mt-3">
                                @foreach ($feature_details as $feature)
                                    @if ($feature->type != 'iframe' && $feature->type != 'youtube' && $feature->type != 'map' && $feature->type != 'text')
                                        <div class="w-1/2 md:w-1/3 px-2 mb-2 md:mb-0">
                                            {{-- Email --}}
                                            @if ($feature->type == 'email')
                                            <a class="flex px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200" href="mailto:{{ $feature->content }}">
                                            @endif

                                            {{-- Tel --}}
                                            @if ($feature->type == 'tel')
                                            <a class="flex px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200" href="tel:{{ $feature->content }}">
                                            @endif

                                            {{-- WhatsApp --}}
                                            @if ($feature->type == 'wa')
                                            <a class="flex px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200" href="http://wa.me/{{ $feature->content }}" rel="noopener nofollow noreferrer" target="_blank">
                                            @endif

                                            {{-- URL --}}
                                            @if ($feature->type == 'url' || $feature->type == 'facebook' || $feature->type == 'instagram' || $feature->type == 'x-twitter' || $feature->type == 'linkedin' || $feature->type == 'pinterest'
                                            || $feature->type == 'reddit' || $feature->type == 'tiktok' || $feature->type == 'threads' || $feature->type == 'snapchat' || $feature->type == 'wechat'
                                            || $feature->type == 'telegram' || $feature->type == 'tumblr' || $feature->type == 'qq' || $feature->type == 'discord' || $feature->type == 'quora')
                                            <a class="flex px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200" href="https://{{ str_replace('https://', '', $feature->content) }}" rel="noopener nofollow noreferrer" target="_blank">
                                            @endif

                                            <i class="{{ $feature->icon }}"></i>
                                            <span class="ml-3" data-config-id="auto-txt-4-1">{{ $feature->label }}</span>
                                            {{-- <p class="font-semibold text-gray-800 break-word text-sm">{{ $feature->content }}</p> --}}

                                            @if ($feature->type == 'url' || $feature->type == 'facebook' || $feature->type == 'instagram' || $feature->type == 'x-twitter' || $feature->type == 'linkedin' || $feature->type == 'pinterest'
                                            || $feature->type == 'reddit' || $feature->type == 'tiktok' || $feature->type == 'threads' || $feature->type == 'snapchat' || $feature->type == 'wechat'
                                            || $feature->type == 'telegram' || $feature->type == 'tumblr' || $feature->type == 'qq' || $feature->type == 'discord' || $feature->type == 'quora' || $feature->type == 'wa' || $feature->type == 'tel' || $feature->type == 'email')
                                            </a>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Share vcard -->
                        <div class="lg:py-5 pt-5">
                            <h2 class="w-full md:w-auto md:mb-0 text-2xl font-bold">
                                Share
                            </h2>
                            <div class="flex flex-wrap pt-6 -mx-2">
                                <!-- Facebook -->
                                <div class="w-1/5 md:w-1/5 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex justify-center py-2 text-sm text-white bg-green-500 hover:bg-green-600 rounded-xl transition duration-200"
                                        href="{{ $shareComponent['facebook'] }}" target="_blank"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                                        </svg>
                                    </a>
                                </div>

                                <!-- X (Twitter) -->
                                <div class="w-1/5 md:w-1/5 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex justify-center py-2 text-sm text-white bg-green-500 hover:bg-green-600 rounded-xl transition duration-200"
                                        href="{{ $shareComponent['twitter'] }}" target="_blank"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                                            <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Linked In -->
                                <div class="w-1/5 md:w-1/5 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex justify-center py-2 text-sm text-white bg-green-500 hover:bg-green-600 rounded-xl transition duration-200"
                                        href="{{ $shareComponent['linkedin'] }}" target="_blank"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-linkedin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                            <path d="M8 11l0 5"></path>
                                            <path d="M8 8l0 .01"></path>
                                            <path d="M12 16l0 -5"></path>
                                            <path d="M16 16v-3a2 2 0 0 0 -4 0"></path>
                                        </svg>
                                    </a>
                                </div>

                                <!-- Telegram -->
                                <div class="w-1/5 md:w-1/5 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex justify-center py-2 text-sm text-white bg-green-500 hover:bg-green-600 rounded-xl transition duration-200"
                                        href="{{ $shareComponent['telegram'] }}" target="_blank"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4"></path>
                                        </svg>
                                    </a>
                                </div>

                                <!-- WhatsApp -->
                                <div class="w-1/5 md:w-1/5 px-2 mb-2 md:mb-0">
                                    <a
                                        class="flex justify-center py-2 text-sm text-white bg-green-500 hover:bg-green-600 rounded-xl transition duration-200"
                                        href="{{ $shareComponent['whatsapp'] }}" target="_blank"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"></path>
                                            <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Branding --}}
                        @if ($plan_details['hide_branding'] == 1)
                        <div class="lg:pb-0 pb-16">
                            <div
                                class="flex pb-3 m-auto pt-5 font-semibold text-sm flex-col md:flex-row max-w-6xl">
                                <div class="mt-2">
                                    {{ __('Copyright') }} &copy; {{ now()->year }} {{ __('by') }}
                                    <a class="text-green-500"
                                        href="{{ url($card_details->card_url) }}"> {{ $card_details->title }} </a>
                                    <span id="year"></span>{{ __('. All Rights Reserved.') }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="pb-1">
                            <div
                                class="flex pb-5 m-auto pt-5 font-semibold text-sm flex-col md:flex-row max-w-6xl">
                                <div class="mt-2">
                                    {{ __('Copyright') }} &copy; {{ now()->year }}. {{ __('Made with') }}
                                    <a class="text-green-500"
                                        href="{{ env('APP_URL') }}"> {{ config('app.name') }} </a>
                                    <span id="year"></span>{{ __('. All Rights Reserved.') }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Add to Contact -->
                <div class="w-full px-2 mb-2 md:mb-0 add-to-contact border-t shadow-lg lg:hidden">
                    <a
                    class="flex px-5 py-3 items-center justify-center bg-green-500 hover:bg-green-600 text-sm leading-6 font-bold text-white rounded-xl transition duration-200"
                    href="{{ route('download.vCard', $business_card_details->card_id) }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-address-book mr-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z"></path>
                            <path d="M10 16h6"></path>
                            <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M4 8h3"></path>
                            <path d="M4 12h3"></path>
                            <path d="M4 16h3"></path>
                        </svg>
                        <span class="text-center">{{ __('ADD TO CONTACT') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Send vCard --}}
    <div
        class="send-modal opacity-0 transition duration-300 ease-in-out pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="send-modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-full md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="justify-between items-center">
                    <div class="text-left my-3">
                        <label class="mt-6 block text-gray-700 text-sm font-bold mb-2" for="phone_number">{{ __("Phone
                            Number") }}</label>
                        <input id="phone_number"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="{{ __('For ex: 91987654310') }}">
                        <small>{{ __('For ex: 91987654310 (With Country code) (Without +)') }}</small>
                    </div>
                    <button
                        class="flex justify-center items-center content-center bg-green-500 hover:bg-green-600 shadow-md hover:shadow-lg h-8 w-auto py-2 px-8 rounded fill-current text-white"
                        onclick="sendVcard()">
                        {{ __('Send') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scan QR --}}
    <div
        class="qr-modal opacity-0 transition duration-300 ease-in-out pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="qr-modal-overlay absolute w-full h-full bg-white opacity-50"></div>
        <div class="modal-container bg-white w-auto md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="justify-between items-center px-6 qr-code"></div>
                <a id="download" onclick="downloadQr('{{ route('dynamic.card', $business_card_details->card_id) }}', 500)" class="mt-3 cursor-pointer w-full flex justify-center items-center content-center bg-green-500 hover:bg-green-600 shadow-lg hover:shadow-lg h-8 w-8 rounded fill-current text-white qr-code-download">
                    <span>{{ __('Download') }}</span>
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="leading-tight min-h-screen bg-grey-lighter p-1">
        <br>
        <h4>{{ __('403') }}</h4>
        <h6>{{ __('Oops! Basic details are missing.') }}</h6>
    </div>
    @endif
    @endif

    <!-- Include PWA modal -->
    @if ($plan_details != null)
        {{-- Check PWA --}}
        @if ($plan_details['pwa'] == 1)
        @include('vendor.laravelpwa.pwa_modal_center')
        @endif
    @endif

    {{-- Check password protected --}}
    @if ($business_card_details->password != null && Session::get('password_protected') == false)
    <div class="bg-green-50 p-4 flex items-center justify-center h-screen">
        <div x-data="{ showModal: true }">
            <!-- Modal -->
            <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50 p-3">
                <div class="bg-white rounded-lg p-6 w-96 max-w-full shadow-lg transform transition-all duration-300" x-show.transition.opacity="showModal">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center border-b-2 border-gray-200 pb-4">
                        <h2 class="text-2xl font-semibold">{{ __("Password Protected") }}</h2>
                    </div>

                    <!-- Modal Content -->
                    <div class="mt-6 space-y-4">
                        <form action="{{ route('check.pwd', $business_card_details->card_id) }}" method="post">
                            @csrf
                            <p class="text-lg text-gray-600">{{ __('Enter your vcard Password')}}</p>
                            <div class="flex">
                                <input type="password" name="password" class="rounded rounded-r-lg bg-gray-50 border text-gray-800 focus:ring-green-100 focus:border-green-100 block flex-1 min-w-0 w-full text-sm border-gray-100 p-2.5" placeholder="{{ __('Password') }}" required>
                            </div>

                            {{-- Message --}}
                            @if(Session::has('message'))
                            <div class="flex items-center p-4 my-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                                <span class="sr-only">{{ __('Failed') }}</span>
                                <div>
                                <span class="font-medium">{{ Session::get('message') }}</span>
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-col space-y-4 mt-3">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 mt-2 rounded-lg hover:bg-green-600 transition duration-300">{{ __('Password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- JS files --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ url('js/smooth-scroll.polyfills.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('app/js/footer.js') }}"></script>
    <script src="{{ url('templates/js/template-4.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/jquery-qrcode.min.js') }}"></script>

    {{-- Custom JS --}}
    @yield('custom-js')

    {{-- Check business details --}}
    @if ($business_card_details != null)
    <script>
    {!! $business_card_details->custom_js !!}
    </script>
    @endif

    <script>
    function sendVcard() {
        "use strict";
        var phone_number = $('#phone_number').val();
        window.open('https://api.whatsapp.com/send/?phone='+phone_number+'&text='+ `{{ $shareContent }}`, '_blank');
        return false;
    }

    window.onload = function() {
        "use strict";

        updateQr(`{{ route("dynamic.card", $business_card_details->card_id) }}`);
    };
    </script>
</body>

</html>
