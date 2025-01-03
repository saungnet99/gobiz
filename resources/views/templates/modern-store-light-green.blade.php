<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $card_details->title }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Store icon and color --}}
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

    {{-- Fonts --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap">

    {{-- CSS --}}
    <link href="{{ url('css/tabler.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="{{ url('css/slick.css') }}" rel="stylesheet" />
    <link href="{{ url('app/css/store.css') }}" rel="stylesheet">

    {{-- Check business details --}}
    @if ($business_card_details != null)
        <style>
            {!! $business_card_details->custom_css !!}
        </style>
    @endif

    {{-- JS --}}
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
    <script src="{{ url('js/sweetalert.all.js') }}"></script>

    {{-- SEO Tags --}}
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    {{-- Check PWA --}}
    @if ($plan_details != null)
        @if ($plan_details['pwa'] == 1)
            @laravelPWA

            <!-- Web Application Manifest -->
            <link rel="manifest" href="{{ $manifest }}">
        @endif
    @endif

    {{-- Limited Text Function --}}
    @php
    if (!function_exists('limit_text')) {
        function limit_text($text)
        {
            $limit = 4;
            if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]) . '...';
            }
            return $text;
        }
    }
    @endphp
</head>

<body dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr' }}">
    <!-- Preloader -->
    <div class="page page-center preloader-wrapper">
        <div class="container container-slim py-4">
            <div class="text-center">
                <div class="mb-3">
                    <img src="{{ url($business_card_details->profile) }}" height="36"
                        alt="{{ $business_card_details->title }}">
                </div>
                <div class="mb-3 h3">{{ __('Loading') }}</div>
                <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-indeterminate"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page --}}
    <div id="wrapper" class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none p-2 pb-2">
            <div class="container-xl">
                <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ url($business_card_details->card_url) }}">
                        <img src="{{ url($business_card_details->profile) }}"
                            alt="{{ $business_card_details->title }}" class="navbar-brand-image logo-height" />
                    </a>
                </div>
                <div class="navbar-nav flex-row order-md-last">
                    {{-- Language switcher --}}
                    @include('templates.includes.bs-language-switcher')

                    <div class="nav-item dropdown">
                        <div class="nav-item">
                            <a class="position-relative cursor-pointer" data-bs-toggle="modal"
                                data-bs-target="#cartItems">
                                <i class="ti ti-shopping-bag"></i>
                                <span class="badge bg-green text-green-fg badge-notification badge-pill"
                                    id="badge">0</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper mt-5">
            <div class="page-body">
                <div class="container-xl">
                    <!-- Banners -->
                    <div class="row">
                        {{-- Greeting message --}}
                        <div class="col-md-12 pt-6 px-1">
                            <h3 class="alert alert-important alert-green">{{ $business_card_details->sub_title }}</h3>
                        </div>

                        <div class="col-md-12">
                            <div id="carousel-captions" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    {{-- Cover --}}
                                    @if (is_array(json_decode($business_card_details->cover)) == true)
                                        @foreach (json_decode($business_card_details->cover) as $cover)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img class="d-block w-100 radius-img"
                                                    alt="{{ $business_card_details->title }}"
                                                    src="{{ url($cover) }}" />
                                                <div class="d-none d-md-block"></div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <img class="d-block w-100 radius-img"
                                                alt="{{ $business_card_details->title }}"
                                                src="{{ url($business_card_details->cover) }}" />
                                            <div class="d-none d-md-block"></div>
                                        </div>
                                    @endif
                                </div>
                                <a class="carousel-control-prev" href="#carousel-captions" role="button"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">{{ __('Previous') }}</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-captions" role="button"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">{{ __('Next') }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="col-md-12 my-3">
                            <h2>{{ __('Categories') }}</h2>
                            <div class="row categories">
                                <div class="col-md-3 col-lg-3 px-1 cursor-pointer">
                                    <a href="{{ url()->current() }}">
                                        <div class="card card-sm custom-card-style">
                                            <div class="card-body">
                                                <div class="ratio ratio-1x1">
                                                    <svg fill="#0154A5" width="800px" height="800px"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11 4h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6h-4v-4h4v4zM17 3c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zM7 13c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <h3 class="card-title text-center mb-1">
                                                                {{ __('All') }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- Foreach -->
                                @foreach ($categories as $category)
                                    <div class="col-md-3 col-lg-3 px-1 cursor-pointer">
                                        <a
                                            href="{{ route('profile', $business_card_details->card_url) . '?category=' . strtolower($category->category_name) }}">
                                            <div class="card card-sm custom-card-style">
                                                <div class="card-body">
                                                    <div class="ratio ratio-1x1">
                                                        <img src="{{ url($category->thumbnail) }}"
                                                            class="rounded object-cover"
                                                            alt="{{ $category->category_name }}" />
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <h3
                                                                    class="card-title text-center mb-1 gobiz-wa-store-cat-text">
                                                                    {{ $category->category_name }}
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Products -->
                        @if (count($products) > 0)
                            <div class="col-md-12 my-3">
                                <h2>{{ __('Products') }}</h2>
                                <div class="row">
                                    <!-- Foreach -->
                                    @foreach ($products as $product)
                                        <div class="col-sm-6 col-lg-3 col-6 py-2">
                                            <div class="card custom-card-style">
                                                @php
                                                    $productImages = explode(',', $product->product_image);
                                                @endphp
                                                <div id="carousel-controls" class="carousel slide mb-3"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @foreach ($productImages as $productImage)
                                                            <div
                                                                class="carousel-item {{ $loop->index == 0 ? 'active' : '' }}">
                                                                <img class="d-block w-100 rounded"
                                                                    id="{{ $product->product_id }}_product_image"
                                                                    style="max-width: max-content; max-height: max-content; object-fit: contain;"
                                                                    alt="{{ $product->product_name }}"
                                                                    src="{{ url($productImage) }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <span class="badge bg-green text-white"
                                                        id="{{ $product->product_id }}_subtitle">{{ $product->category_name }}</span>
                                                    <span
                                                        class="badge bg-green text-white">{{ $product->badge }}</span>
                                                    <h3 id="{{ $product->product_id }}_product_name"
                                                        class="card-title single-product mb-1"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $product->product_name }}">
                                                        {{ $product->product_name }}
                                                    </h3>
                                                    <p class="text-secondary mb-3">{{ $product->product_subtitle }}
                                                    </p>
                                                    <p class="pricing my-2 p-0"><strong><span
                                                                id="{{ $product->product_id }}_currency">{{ $currency }}
                                                                <span
                                                                    id="{{ $product->product_id }}_price">{{ $product->sales_price }}</span></span></strong>
                                                    </p>
                                                    @if ($product->sales_price != $product->regular_price)
                                                        <p class="regular_price"><del
                                                                class="text-green">{{ $currency }}
                                                                {{ $product->regular_price }}</del></strong></p>
                                                    @endif
                                                </div>
                                                @if ($product->product_status == 'instock')
                                                    <div class="card-footer">
                                                        <a onclick="addToCart('{{ $product->product_id }}')"
                                                            class="btn btn-green"><i
                                                                class="ti ti-shopping-bag-plus"></i>
                                                            {{ __('Add') }}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Paginate --}}
                                    <div class="col-md-12 my-3">
                                        @if (request()->has('category'))
                                            {{ $products->appends(['category' => strtolower(request()->category)])->links() }}
                                        @else
                                            {{ $products->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 my-3">
                                <h2>{{ __('Products') }}</h2>
                                <div class="row">
                                    <h3>{{ __('No Products Founds!') }}</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="{{ $shareComponent['facebook'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-facebook-filled text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['twitter'] }}"
                                        class="link-light"><i class="ti ti-brand-twitter-filled text-green"></i></a>
                                </li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['linkedin'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-linkedin text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['telegram'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-telegram text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['whatsapp'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-whatsapp text-green"></i></a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                @if ($plan_details['hide_branding'] == 1)
                                    <li class="list-inline-item">
                                        {{ __('Copyright') }} &copy; <span id="year"></span> <a
                                            href="{{ url($business_card_details->card_url) }}"
                                            class="link-light text-green"><strong>{{ $card_details->title }}</strong></a>.
                                        {{ __('All rights reserved.') }}
                                    </li>
                                @else
                                    <li class="list-inline-item">
                                        {{ __('Copyright') }} &copy; <span id="year"></span> <a
                                            href="{{ url($business_card_details->card_url) }}"
                                            class="link-light text-green"><strong>{{ config('app.name') }}</strong></a>.
                                        {{ __('All rights reserved.') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Cart Items --}}
    <div class="modal modal-blur fade" id="cartItems" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Cart Items') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="row" id="cart_items"></div>
                        <a class="btn btn-green" id="place-order"
                            onclick="placeOrder()">{{ __('Place WhatsApp Order') }}</a>
                    </div>

                    <div id="empty-cart" class="pt-6 p-3">
                        <p class="px-4 py-4 mb-4 text-center">{{ __('Your cart is empty.') }}</p>

                        <a class="btn btn-green d-flex" data-bs-dismiss="modal"
                            aria-label="Close">{{ __('Start Shopping') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Place order --}}
    <div class="modal modal-blur fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Please fill following details:') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required" for="cus_name">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="cus_name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label required" for="cus_mobile">{{ __('Mobile') }}</label>
                        <input type="number" class="form-control" id="cus_mobile" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label required" for="cus_address">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="cus_address" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-green" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-green"
                        onclick="confirmOrder()">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error alert -->
    <div class="alert alert-important alert-danger alert-dismissible alert-float" id="errorAlertContainer"
        role="alert">
        <div id="errorAlertMessage"></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Success alert -->
    <div class="alert alert-important alert-success alert-dismissible alert-float" id="successAlertContainer"
        role="alert">
        <div id="successAlertMessage"></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Include PWA modal -->
    @if ($plan_details != null)
        {{-- Check PWA --}}
        @if ($plan_details['pwa'] == 1)
            @include('vendor.laravelpwa.bs_pwa_modal')
        @endif
    @endif

    <!-- Core -->
    <script type="text/javascript" src="{{ url('js/tabler.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/slick.min.js') }}"></script>
    <script src="{{ url('js/script.js') }}"></script>
    <script src="{{ url('js/data-filter.js') }}"></script>

    {{-- Custom JS --}}
    @yield('custom-js')

    {{-- Check business details --}}
    @if ($business_card_details != null)
        <script>
            {!! $business_card_details->custom_js !!}
        </script>
    @endif

    <script>
        // Global variables
        var cart = [];
        var whatsAppNumber = "{{ $enquiry_button }}";
        var whatsAppMessage = `{!! $whatsapp_msg !!}`;
        var currency = "{{ $currency }}";

        // Function to initialize page
        function initializePage() {
            $('.preloader-wrapper').fadeOut('slow');
            getData();
            initializeSlick();
        }

        // Initialize Slick slider for categories
        function initializeSlick() {
            $(".categories").slick({
                rtl: false,
                dots: false,
                infinite: true,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 700,
                centerMode: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 4
                        }
                    }
                ]
            });
        }

        // Fetch data function
        function getData() {
            var storageKey = "cart_" + "{{ $business_card_details->card_id }}";
            cart = localStorage.getItem(storageKey) ? JSON.parse(localStorage.getItem(storageKey)) : [];
            updateList();
            updateBadge();
        }

        // Add to cart function
        function addToCart(pid) {
            var productName = $("#" + pid + "_product_name").text();
            var price = $("#" + pid + "_price").text();
            var product_image = $("#" + pid + "_product_image").attr("src");
            var subtitle = $("#" + pid + "_subtitle").text();

            var found = cart.findIndex(item => item.product_id == pid);
            if (found !== -1) {
                cart[found].qty++;
                successAlert('{{ __('Cart updated') }}');
            } else {
                cart.push({
                    product_name: $.trim(productName),
                    price: price,
                    product_id: pid,
                    currency: currency,
                    qty: 1,
                    product_image: product_image,
                    subtitle: subtitle
                });
                successAlert("{{ __('Item added to cart') }}");
            }
            updateList();
            updateBadge();
            updateStorage();
        }

        // Update cart list
        function updateList() {
            var cart_items = "";
            var grandTotal = 0;

            cart.forEach((item, index) => {
                const total_price = item.qty * Number(item.price);
                grandTotal += total_price;

                cart_items += `<div class="col-6 mb-2">`;
                cart_items += `<div class="card">`;
                cart_items +=
                    `<div class="img-responsive img-responsive-full card-img-top" style="background-image: url(${item.product_image})"></div>`;
                cart_items += `<div class="card-body">`;
                cart_items += `<h4 class="text-dark mb-1">${item.product_name}</h4>`;
                cart_items += `<small class="text-dark">${item.subtitle}</small>`;
                cart_items +=
                    `<p class="text-dark mt-1"><strong>${currency} ${total_price}</strong></p>`;
                cart_items += `</div>`;
                cart_items += `<div class="card-footer">`;
                cart_items += `<div class="d-flex flex-wrap justify-content-between">`;
                cart_items +=
                    `<a onclick="reduceQty(${index})" class="btn btn-green flex-fill mb-1 ${window.innerWidth <= 768 ? 'btn-sm' : 'mx-2'}">-</a>`;
                cart_items +=
                    `<a class="btn btn-white flex-fill mb-1 ${window.innerWidth <= 768 ? 'btn-sm' : 'mx-2'}">${item.qty}</a>`;
                cart_items +=
                    `<a onclick="addQty(${index})" class="btn btn-green flex-fill mb-1 ${window.innerWidth <= 768 ? 'btn-sm' : 'mx-2'}">+</a>`;
                cart_items +=
                    `<a onclick="removeFromCart(${index})" class="btn btn-green flex-fill mb-1 ${window.innerWidth <= 768 ? 'btn-sm' : 'mx-2'}">x</a>`;
                cart_items += `</div>`;
                cart_items += `</div>`;
                cart_items += `</div>`;
                cart_items += `</div>`;
            });

            if (grandTotal > 0) {
                cart_items +=
                    `<br><h3 class="pl-4 pt-4 pr-4 font-bold">{{ __('Grand total:') }} ${currency} ${grandTotal}</h3>`;
            }

            $("#cart_items").html(cart_items);
        }

        // Update badge function
        function updateBadge() {
            var badgeCount = cart.length;
            if (badgeCount > 0) {
                $("#empty-cart").hide();
                $("#badge").text(badgeCount).show();
                $("#place-order").show().attr("class", "btn btn-green d-flex");
            } else {
                $("#place-order").hide().attr("class", "btn btn-green d-none");
                $("#badge").hide();
                $("#empty-cart").show();
            }
        }

        // Reduce quantity function
        function reduceQty(i) {
            if (cart[i].qty == 1) {
                removeFromCart(i);
            } else {
                cart[i].qty--;
                updateBadge();
                updateList();
            }
            updateStorage();
        }

        // Add quantity function
        function addQty(i) {
            cart[i].qty++;
            updateBadge();
            updateList();
            updateStorage();
        }

        // Remove from cart function
        function removeFromCart(i) {
            cart.splice(i, 1);
            successAlert(`{{ __('Item Removed') }}`);
            updateStorage();
            updateBadge();
            updateList();
        }

        // Place order function
        function placeOrder() {
            var myModal = new bootstrap.Modal(document.getElementById('orderModal'), {
                keyboard: false
            });
            myModal.show();
        }

        // Function to confirm order details
        function confirmOrder() {
            var cusName = document.getElementById('cus_name').value;
            var cusMobile = document.getElementById('cus_mobile').value;
            var cusAddress = document.getElementById('cus_address').value;

            if (!cusName || !cusMobile || !cusAddress) {
                errorAlert('{{ __('Please fill out all fields.') }}');
                return false;
            }

            createWhatsAppLink([cusName, cusMobile, cusAddress]);
            var myModalEl = document.getElementById('orderModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        }

        // Function to create WhatsApp link for order details
        function createWhatsAppLink(cusDetails) {
            "use strict";
            // Check if customer details are valid
            if (cusDetails[0].length >= 3 && cusDetails[1].length >= 4) {
                // Initialize products list and grand total
                let productsList = `\n- - - - - - - - - - - - - - - - - - - -\n📦 *{{ __('Order Details:') }}* \n\n`;
                let grandTotal = 0;

                // Iterate through cart items
                cart.forEach(item => {
                    const itemCost = Number(item.qty) * Number(item.price);
                    const cartPrice = Number(item.price);

                    // Append product details to products list
                    productsList +=
                        `${item.product_name} - ${item.qty} X ${currency} ${cartPrice} = *${currency} ${itemCost}*\n`;
                    grandTotal += itemCost;
                });

                // Add total and customer details to products list
                productsList += `\n- - - - - - - - - - - - - - - - - - - -\n`;
                productsList += `*{{ __('Total') }}* : *${currency} ${grandTotal}*\n\n`;
                productsList += `📞 *{{ __('Customer Details:') }}* \n`;
                productsList += `{{ __('Customer Name') }} : ${cusDetails[0]}\n`;
                productsList += `{{ __('Contact Number') }} : ${cusDetails[1]}\n`;
                productsList += `{{ __('Delivery Address') }} : ${cusDetails[2]}\n\n`;

                // Prepare WhatsApp share content
                let waShareContent = `🎉 *{{ __('New Order') }}* \n`;
                waShareContent += productsList + `*${whatsAppMessage}*`;

                // Construct WhatsApp link and open in new tab
                const link = `https://api.whatsapp.com/send/?phone=${whatsAppNumber}&text=${encodeURI(waShareContent)}`;
                window.open(link, '_blank');

                // Reset cart and update local storage
                cart = [];
                updateStorage();

                // Show success alert
                successAlert('{{ __('Order Placed!') }}');
            } else {
                // If customer details are invalid, prompt to place order
                placeOrder();
            }
        }

        // Update local storage function
        function updateStorage() {
            localStorage.setItem("cart_" + "{{ $business_card_details->card_id }}", JSON.stringify(cart));
        }

        // Show alert function
        function showAlert(containerId, message) {
            const alertContainer = document.getElementById(containerId);
            const alertMessage = alertContainer.querySelector('div');
            alertMessage.innerHTML = message;
            alertContainer.classList.add('show');
            alertContainer.style.display = 'block';
            setTimeout(() => {
                alertContainer.classList.remove('show');
                setTimeout(() => {
                    alertContainer.style.display = 'none';
                }, 1000);
            }, 3000);
        }

        // Error alert function
        function errorAlert(message) {
            showAlert('errorAlertContainer', message);
        }

        // Success alert function
        function successAlert(message) {
            showAlert('successAlertContainer', message);
        }

        // Initial function call
        initializePage();
    </script>
</body>

</html>
