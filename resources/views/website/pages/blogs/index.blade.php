@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true])

{{-- Custom JS --}}
@section('custom-script')
    {{-- AdSense status --}}
    @if ($settings->adsense_code != 'DISABLE')
        {{-- AdSense code --}}
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->google_adsense_code }}"
            crossorigin="anonymous"></script>
    @endif
@endsection

@section('content')
    {{-- Start Blog Section --}}
    <section class="py-10 overflow-hidden">
        <div class="container px-4 mx-auto">
            <div class="max-w-xs md:max-w-2xl xl:max-w-7xl mx-auto">
                <div class="md:max-w-full mb-10 md:mb-10">
                    <h2 class="mb-5 font-heading font-semibold text-6xl sm:text-7xl text-gray-900">{{
                        __('Blogs') }}</h2>
                    <p class="text-gray-600 text-md">{{ __('Discover the All-in-One Solution to Manage Contacts, Share Business Info, and Boost Sales - Start Growing Your Business Today!') }}</p>
                </div>
                <div class="flex flex-wrap -mx-4 -mb-12">
                    {{-- Blogs --}}
                    @if (count($blogs) > 0)
                        @foreach ($blogs as $blog)
                            <div class="w-full md:w-1/2 xl:w-1/3 p-4 mb-12 border-r border-gray-100 shadow shadow-lg rounded-lg">
                                <a class="block group" href="{{ route('view.blog', $blog->slug) }}">
                                    <img class="block w-full h-50 mb-4" src="{{ asset($blog->cover_image) }}"
                                        alt="{{ $blog->heading }}">
                                    <div class="flex inline">
                                        <span
                                            class="block text-gray-500 mb-2">{{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</span>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900 group-hover:text-orange-900 mb-4">
                                        {{ $blog->heading }}</h4>
                                    <p class="text-gray-500">{{ $blog->short_description }}</p>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full px-4 py-12">
                            <h3
                                class="mb-4 text-3xl md:text-4xl leading-tight text-darkCoolGray-900 font-bold tracking-tighter">
                                {{ __('No blog posts found!') }}</h3>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                <div class="text-center">
                    {{ $blogs->links('vendor.pagination.blog') }}
                </div>
            </div>
        </div>
    </section>
    {{-- End Blog Section --}}
@endsection
