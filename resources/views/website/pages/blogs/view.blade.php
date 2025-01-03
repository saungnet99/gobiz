@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true])

{{-- Custom JS --}}
@section('custom-script')
    {{-- AdSense status --}}
    @if ($settings->adsense_code != 'DISABLE')
        {{-- AdSense code --}}
        <script async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->google_adsense_code }}"
            crossorigin="anonymous"></script>
    @endif
@endsection

@section('content')
    {{-- Start View Blog --}}
    <section class="py-5 lg:py-12 overflow-hidden">
        <img class="absolute top-0 left-0 mt-10"
            src="{{ asset('themes/modern-indigo/assets/images/content/stars-left-top.svg') }}" alt="">
        <img class="absolute bottom-0 right-0"
            src="{{ asset('themes/modern-indigo/assets/images/content/indigo-light-right.png') }}" alt="">
        <div class="relative container px-4 mx-auto">
            <div class="text-center mb-12">
                <span
                    class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-indigo-800 bg-{{ $config[11]->config_value }}-50 rounded-full">{{ $blogDetails->blogCategory->blog_category_title }}</span>
                <h1 class="font-heading text-3xl xs:text-4xl md:text-5xl font-bold text-gray-800 mb-12">
                    <span>{{ $blogDetails->heading }}</span>
                </h1>
                <div>
                    <img class="block w-full lg:w-1/2 lg:h-1/2 lg:object-center mx-auto rounded-lg"
                        src="{{ asset($blogDetails->cover_image) }}" alt="{{ $blogDetails->heading }}">
                </div>
            </div>
            <div class="max-w-lg lg:max-w-3xl xl:max-w-5xl mx-auto">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4 mb-12 lg:mb-0">
                        <div class="max-w-full">
                            {!! $blogDetails->long_description !!}
                        </div>

                        <!-- Tags Heading -->
                        <div class="my-8">
                            <h2 class="text-xl font-semibold mb-2">{{ __('Related Tags:') }}</h2>

                            {{-- Tags --}}
                            <div class="flex flex-wrap -m-2">
                                @php
                                    // Tags separated
                                    $tags = explode(',', $blogDetails->tags);
                                    $tags = collect($tags)->all();
                                @endphp

                                @foreach ($tags as $tag)
                                    <div class="w-auto p-2">
                                        <span
                                            class="relative group inline-block cursor-pointer w-full sm:w-auto py-1 px-3 mb-4 text-center text-sm font-semibold text-gray-50 hover:text-gray-50 bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 rounded-md overflow-hidden transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">{{ strtoupper($tag) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Share --}}
                        <div>
                            <h2 class="text-xl font-semibold mb-4">{{ __('Share This Blog Post') }}</h2>
                            <div class="flex flex-wrap -m-2">
                                <div class="w-auto p-2">
                                    <!-- Facebook Share Button -->
                                    <a href="{{ route('sharetofacebook', $blogDetails->slug) }}" target="_blank"
                                        class="flex items-center justify-center p-3 text-white hover:text-gray-50 font-medium tracking-tighter bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="w-auto p-2">
                                    <!-- Twitter Share Button -->
                                    <a href="{{ route('sharetotwitter', $blogDetails->slug) }}" target="_blank"
                                        class="flex items-center justify-center p-3 text-white hover:text-gray-50 font-medium tracking-tighter bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                                            <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="w-auto p-2">
                                    <!-- LinkedIn Share Button -->
                                    <a href="{{ route('sharetolinkedin', $blogDetails->slug) }}" target="_blank"
                                        class="flex items-center justify-center p-3 text-white hover:text-gray-50 font-medium tracking-tighter bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                            <path d="M8 11l0 5" />
                                            <path d="M8 8l0 .01" />
                                            <path d="M12 16l0 -5" />
                                            <path d="M16 16v-3a2 2 0 0 0 -4 0" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="w-auto p-2">
                                    <!-- Instagram Share Button -->
                                    <a href="{{ route('sharetoinstagram', $blogDetails->slug) }}" target="_blank"
                                        class="flex items-center justify-center p-3 text-white hover:text-gray-50 font-medium tracking-tighter bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                            <path d="M16.5 7.5l0 .01" />
                                        </svg>
                                    </a>
                                </div>

                                <div class="w-auto p-2">
                                    <!-- WhatsApp Share Button -->
                                    <a href="{{ route('sharetowhatsapp', $blogDetails->slug) }}" target="_blank"
                                        class="flex items-center justify-center p-3 text-white hover:text-gray-50 font-medium tracking-tighter bg-{{ $config[11]->config_value }}-800 hover:bg-{{ $config[11]->config_value }}-700 border-2 border-white focus:border-green-400 focus:border-opacity-40 focus:ring-4 focus:ring-green-400 focus:ring-opacity-40 rounded-full transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                            <path
                                                d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Recent blogs --}}
                        @if (count($recentBlogs) > 0)
                            <div class="pt-16">
                                <div class="flex flex-wrap -mx-4 lg:mb-8 items-end">
                                    <div class="w-full lg:w-2/3 xl:w-1/2 px-4 mb-8 lg:mb-0">
                                        <div>
                                            <h1 class="font-heading text-3xl xs:text-4xl md:text-5xl font-bold">
                                                <span class="animated-gradient-text">{{ __('Recent Blogs') }}</span>
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-4 -mb-12">
                                    @foreach ($recentBlogs as $blog)
                                        <div class="w-full md:w-1/2 xl:w-1/3 p-4 mb-12 border-r border-gray-100 shadow shadow-lg rounded-lg">
                                            <a class="block group" href="{{ route('view.blog', $blog->slug) }}">
                                                <img class="block w-full h-50 mb-4" src="{{ asset($blog->cover_image) }}"
                                                    alt="{{ $blog->heading }}">
                                                <div class="flex inline">
                                                    <span
                                                        class="block text-gray-500 mb-2">{{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</span>
                                                </div>
                                                <h4
                                                    class="text-xl font-semibold text-gray-800 group-hover:text-indigo-800 mb-4">
                                                    {{ $blog->heading }}</h4>
                                                <p class="text-gray-500">{{ $blog->short_description }}</p>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End View Blog --}}
@endsection
