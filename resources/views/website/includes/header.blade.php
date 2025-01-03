@php
    // Settings
    use App\Setting;
    use App\Page;
    use Illuminate\Support\Facades\DB;

    $setting = Setting::where('status', 1)->first();
    $pages = Page::get();
    $homePage = DB::table('pages')
        ->where('page_name', 'home')
        ->get();
@endphp

<section
    class="bg-{{ request()->is('/') ? 'gradient-' . $config[11]->config_value . '2' : 'white' }} lg:px-20 background-animate">
    <section>
        <div class="flex items-center justify-between px-8 py-5">
            {{-- Website menu --}}
            <div class="w-auto">
                <div class="flex flex-wrap items-center">
                    <div class="w-auto mr-14">
                        <a href="/">
                            <img src="{{ asset($settings->site_logo) }}" alt="{{ $settings->site_name }}" width="150">
                        </a>
                    </div>
                    @if ($config[38]->config_value != "no")
                        <div class="w-auto">
                            <div class="flex flex-wrap items-center">
                                <div class="w-auto hidden lg:block">
                                    <ul class="flex items-center mr-10">
                                        @if ($pages[204]->page_name == 'about' && $pages[204]->status == 'active')
                                            <li class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg"><a
                                                    href="{{ route('about') }}">{{ __('About') }}</a></li>
                                        @endif
                                        <li class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg"><a
                                                href="{{ route('home-locale') }}#how-it-works">{{ __('How it works?') }}</a>
                                        </li>
                                        <li class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg"><a
                                                href="{{ route('home-locale') }}#features">{{ __('Features') }}</a></li>
                                        <li class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg"><a
                                                href="{{ route('home-locale') }}#pricing">{{ __('Pricing') }}</a></li>
                                        @if ($pages[195]->page_name == 'contact' && $pages[195]->status == 'active')
                                            <li class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg"><a
                                                    href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                                        @endif

                                        {{-- Custom pages --}}
                                        @if ($pages)
                                            @foreach ($pages as $page)
                                                @if ($page->page_name == 'Custom Page' && $page->status == 'active')
                                                    <li
                                                        class="font-heading mr-9 text-gray-900 hover:text-gray-700 text-lg {{ request()->is('p/' . $page->section_title) ? 'font-bold' : '' }}">
                                                        <a
                                                            href="{{ route('custom.page', $page->section_title) }}">{{ __($page->section_name) }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif

                                        {{-- Check webtools --}}
                                        @if ($settings->google_adsense_code != 'DISABLE_BOTH')
                                            <div @click.away="open = false"
                                                class="hidden cursor-pointer lg:inline-block px-2 transition duration-200 relative"
                                                x-data="{ open: false }">
                                                <a @click="open = !open"
                                                    class="font-heading mr-4 text-gray-900 hover:text-gray-700 text-lg">
                                                    <span>{{ __('Web Tools') }}</span>
                                                    <svg fill="currentColor" viewBox="0 0 20 20"
                                                        :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="journal-scroll absolute w-full h-80 overflow-y-scroll mt-2 rounded-lg shadow-lg md:w-40">
                                                    <div class="px-2 py-2 bg-white capitalize rounded-sm shadow">
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.html.beautifier') }}">{{ __('HTML Beautifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.html.minifier') }}">{{ __('HTML Minifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.css.beautifier') }}">{{ __('CSS Beautifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.css.minifier') }}">{{ __('CSS Minifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.js.beautifier') }}">{{ __('JS Beautifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.js.minifier') }}">{{ __('JS Minifier') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.random.password.generator') }}">{{ __('Random Password Generator') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.bcrypt.password.generator') }}">{{ __('Bcrypt Password Generator') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.md5.password.generator') }}">{{ __('MD5 Password Generator') }}</a>
                                                        {{-- <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.random.word.generator') }}">{{ __('Random Word Generator') }}</a> --}}
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.text.counter') }}">{{ __('Text Counter') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.lorem.generator') }}">{{ __('Lorem Generator') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.emojies') }}">{{ __('Emojies') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.dns.lookup') }}">{{ __('DNS Lookup') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.ip.lookup') }}">{{ __('IP Address Lookup') }}</a>
                                                        <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ route('web.whois.lookup') }}">{{ __('WHOIS Lookup') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-auto">
                <div class="flex flex-wrap items-center">
                    <div class="w-auto hidden lg:block">
                        <div class="flex flex-wrap">
                            {{-- Languages --}}
                            <div class="w-auto">
                                @if (count(config('app.languages')) > 1)
                                    <div @click.away="open = false"
                                        class="hidden cursor-pointer lg:inline-block py-2 transition duration-200 relative"
                                        x-data="{ open: false }">
                                        <a @click="open = !open"
                                            class="font-heading mr-4 text-gray-900 hover:text-gray-700 text-lg">
                                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                                            <svg fill="currentColor" viewBox="0 0 20 20"
                                                :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                                class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="journal-scroll absolute w-full max-h-80 overflow-y-scroll mt-2 rounded-lg shadow-lg md:w-40">
                                            <div class="px-2 py-2 bg-white capitalize rounded-sm shadow">
                                                @foreach (config('app.languages') as $langLocale => $langName)
                                                    <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                        href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langName) }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Guest user --}}
                            @if ($config[38]->config_value != "no")
                                @guest
                                    <div class="w-auto">
                                        <a class="font-heading block py-2 px-5 mr-5 text-lg text-gray-900 rounded-10"
                                            href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                    </div>

                                    @if (Route::has('register'))
                                        <div class="w-auto">
                                            <a class="group relative font-heading block py-2 px-5 text-lg text-gray-900 border-2 border-gray-900 overflow-hidden rounded-10"
                                                href="{{ route('register') }}">
                                                <div
                                                    class="absolute top-0 left-0 transform -translate-y-full group-hover:-translate-y-0 h-full w-full bg-gray-900 transition ease-in-out duration-500">
                                                </div>
                                                <p class="relative z-10 group-hover:text-white">{{ __('Sign Up') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-auto">
                                        <a class="group relative font-heading block py-2 px-5 text-lg text-gray-900 border-2 border-gray-900 overflow-hidden rounded-10"
                                            href="{{ route('user.dashboard') }}">
                                            <div
                                                class="absolute top-0 left-0 transform -translate-y-full group-hover:-translate-y-0 h-full w-full bg-gray-900 transition ease-in-out duration-500">
                                            </div>
                                            <p class="relative z-10 group-hover:text-white">{{ __('Dashboard') }}</p>
                                        </a>
                                    </div>
                                @endguest
                            @endif
                        </div>
                    </div>
                    <div class="w-auto lg:hidden">
                        @if ($config[38]->config_value != "no")
                            <a href="#">
                                <svg class="navbar-burger text-gray-800" width="51" height="51"
                                    viewbox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                                    <path d="M37 32H19M37 24H19" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        @else
                            @if (count(config('app.languages')) > 1)
                                <div @click.away="open = false"
                                    class="cursor-pointer lg:inline-block py-2 transition duration-200 relative"
                                    x-data="{ open: false }">
                                    <a @click="open = !open"
                                        class="font-heading mr-4 text-gray-900 hover:text-gray-700 text-lg">
                                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                                        <svg fill="currentColor" viewBox="0 0 20 20"
                                            :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                            class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="journal-scroll absolute max-h-80 overflow-y-scroll mt-2 rounded-lg shadow-lg" style="margin-left: -75px; margin-top: -1px;">
                                        <div class="px-2 py-2 bg-white capitalize rounded-sm shadow">
                                            @foreach (config('app.languages') as $langLocale => $langName)
                                                <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                    href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langName) }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div class="hidden navbar-menu fixed top-0 left-0 bottom-0 w-4/6 sm:max-w-xs z-50">
            <div class="navbar-backdrop fixed inset-0 bg-gray-800 opacity-80"></div>
            <nav class="relative z-10 px-9 py-8 bg-white h-full">
                <div class="flex flex-wrap justify-between h-full">
                    <div class="w-full">
                        <div class="flex items-center justify-between -m-2">
                            <div class="w-auto p-2">
                                <a class="inline-block" href="/">
                                    <img src="{{ asset($settings->site_logo) }}" alt="{{ $settings->site_name }}"
                                        width="100">
                                </a>
                            </div>
                            <div class="w-auto p-2">
                                <a class="navbar-burger" href="#">
                                    <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center py-8 w-full">
                        <ul>
                            @if ($pages[204]->page_name == 'about' && $pages[204]->status == 'active')
                                <li class="mb-6"><a
                                        class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                        href="{{ route('about') }}">{{ __('About') }}</a></li>
                            @endif
                            <li class="mb-6"><a
                                    class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                    href="{{ route('home-locale') }}#how-it-works">{{ __('How it works?') }}</a></li>
                            <li class="mb-6"><a
                                    class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                    href="{{ route('home-locale') }}#features">{{ __('Features') }}</a></li>
                            <li class="mb-6"><a
                                    class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                    href="{{ route('home-locale') }}#pricing">{{ __('Pricing') }}</a></li>
                            @if ($pages[195]->page_name == 'contact' && $pages[195]->status == 'active')
                                <li class="mb-6"><a
                                        class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                        href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                            @endif

                            {{-- Custom pages --}}
                            @if ($pages)
                                @foreach ($pages as $page)
                                    @if ($page->page_name == 'Custom Page' && $page->status == 'active')
                                        <li
                                            class="mb-6 {{ request()->is('p/' . $page->section_title) ? 'font-bold' : '' }}">
                                            <a class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700"
                                                href="{{ route('custom.page', $page->section_title) }}">{{ __($page->section_name) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif

                            {{-- Check webtools --}}
                            @if ($settings->google_adsense_code != 'DISABLE_BOTH')
                                <li @click.away="open = false" class="mb-6 relative" x-data="{ open: false }">
                                    <a @click="open = !open"
                                        class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700">
                                        <span>{{ __('Web Tools') }}</span>
                                        <svg fill="currentColor" viewBox="0 0 20 20"
                                            :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                            class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="journal-scroll absolute w-full h-40 overflow-y-scroll mt-2 rounded-lg shadow-lg md:w-40 z-50">
                                        <div class="px-2 py-2 bg-white capitalize rounded-sm shadow">
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.html.beautifier') }}">{{ __('HTML Beautifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.html.minifier') }}">{{ __('HTML Minifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.css.beautifier') }}">{{ __('CSS Beautifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.css.minifier') }}">{{ __('CSS Minifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.js.beautifier') }}">{{ __('JS Beautifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.js.minifier') }}">{{ __('JS Minifier') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.random.password.generator') }}">{{ __('Random Password Generator') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.bcrypt.password.generator') }}">{{ __('Bcrypt Password Generator') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.md5.password.generator') }}">{{ __('MD5 Password Generator') }}</a>
                                            {{-- <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.random.word.generator') }}">{{ __('Random Word Generator') }}</a> --}}
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.text.counter') }}">{{ __('Text Counter') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.lorem.generator') }}">{{ __('Lorem Generator') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.emojies') }}">{{ __('Emojies') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.dns.lookup') }}">{{ __('DNS Lookup') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.ip.lookup') }}">{{ __('IP Address Lookup') }}</a>
                                            <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ route('web.whois.lookup') }}">{{ __('WHOIS Lookup') }}</a>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            {{-- Languages --}}
                            @if (count(config('app.languages')) > 1)
                                <li @click.away="open = false" class="mb-6 relative" x-data="{ open: false }">
                                    <a @click="open = !open"
                                        class="font-heading font-medium text-lg text-gray-900 hover:text-gray-700">
                                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                                        <svg fill="currentColor" viewBox="0 0 20 20"
                                            :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                            class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="journal-scroll absolute w-full h-40 overflow-y-scroll mt-2 rounded-lg shadow-lg md:w-40 z-50">
                                        <div class="px-2 py-2 bg-white capitalize rounded-sm shadow">
                                            @foreach (config('app.languages') as $langLocale => $langName)
                                                <a class="block px-4 py-2 mt-2 text-lg capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                    href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langName) }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="flex flex-col justify-end w-full">
                        <div class="flex flex-wrap">
                            {{-- Guest user --}}
                            @guest
                                <div class="w-full">
                                    <a class="p-0.5 font-heading block w-full text-lg text-gray-900 font-medium rounded-10"
                                        href="{{ route('login') }}">
                                        <div class="py-2 px-5 rounded-10">
                                            <p>{{ __('Login') }}</p>
                                        </div>
                                    </a>
                                </div>

                                @if (Route::has('register'))
                                    <div class="w-full">
                                        <a href="{{ route('register') }}"
                                            class="group relative p-0.5 font-heading block w-full text-lg text-gray-900 font-medium bg-gradient-cyan overflow-hidden rounded-10">
                                            <div
                                                class="absolute top-0 left-0 transform -translate-y-full group-hover:-translate-y-0 h-full w-full bg-gradient-cyan transition ease-in-out duration-500">
                                            </div>
                                            <div class="py-2 px-5 bg-white rounded-lg">
                                                <p class="relative z-10">{{ __('Try now') }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="w-full">
                                    <a href="{{ route('user.dashboard') }}"
                                        class="group relative p-0.5 font-heading block w-full text-lg text-gray-900 font-medium bg-gradient-cyan overflow-hidden rounded-10">
                                        <div
                                            class="absolute top-0 left-0 transform -translate-y-full group-hover:-translate-y-0 h-full w-full bg-gradient-cyan transition ease-in-out duration-500">
                                        </div>
                                        <div class="py-2 px-5 bg-white rounded-lg">
                                            <p class="relative z-10">{{ __('Dashboard') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </section>

    {{-- Hero slider --}}
    @if (isset($banner) && $banner)
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center -m-4 lg:pt-24 pt-10 pb-36">
                <div class="w-full lg:w-1/2 p-6">
                    <div class="lg:max-w-xl">
                        <h1 class="mb-6 font-heading text-7xl md:text-10xl xl:text-12xl text-gray-900 font-bold">
                            {{ __($homePage[0]->section_content) }}</h1>
                        <p class="mb-9 text-gray-600 text-lg">{{ __($homePage[1]->section_content) }}</p>
                        <button
                            class="group relative font-heading px-10 py-5 my-2 w-full lg:w-auto uppercase text-white text-xs font-semibold tracking-px bg-{{ $config[11]->config_value }}-800 overflow-hidden rounded-md">
                            <a href="{{ url($homePage[5]->section_content) }}" target="_blank">
                                <div
                                    class="absolute top-0 left-0 transform -translate-x-full group-hover:-translate-x-0 h-full w-full transition ease-in-out duration-500 bg-gray-800">
                                </div>
                                <p class="relative z-10">{{ __($homePage[4]->section_content) }}</p>
                            </a>
                        </button>
                        <button
                            class="group relative font-heading px-10 py-5 w-full lg:w-auto uppercase text-white text-xs font-semibold tracking-px bg-gray-900 overflow-hidden rounded-md">
                            <a href="{{ url($homePage[3]->section_content) }}" target="_blank">
                                <div
                                    class="absolute top-0 left-0 transform -translate-x-full group-hover:-translate-x-0 h-full w-full transition ease-in-out duration-500 bg-gray-800">
                                </div>
                                <p class="relative z-10">{{ __($homePage[2]->section_content) }}</p>
                            </a>
                        </button>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 p-6">
                    <img class="block mx-auto" src="{{ asset($config[12]->config_value) }}"
                        alt="{{ $settings->site_name }}">
                </div>
            </div>
        </div>
    @endif
</section>
