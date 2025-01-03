@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true, 'title' => __($page->section_name) ])

@section('content')
{{-- Custom JS --}}
@section('custom-script')
{{-- AdSense status --}}
@if ($settings->adsense_code != "DISABLE")
@if ($settings->adsense_code != "")
{{-- AdSense code --}}
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->adsense_code }}"
    crossorigin="anonymous"></script>
@endif
@endif

<style>
h1, h2, h3, h4, h5, h6 {
    margin-bottom: 1rem !important;
}
p {
    margin-bottom: 1.5rem !important;
}
</style>
@endsection

<section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
    <div class="container mx-auto px-4" data-aos="fade-up">
        {{-- Page content --}}
        {{-- @php
        dd(preg_split("/<[^>]*>/", $page->section_content, -1, PREG_SPLIT_NO_EMPTY));
        @endphp --}}
        @if (!empty($page->section_content))
            @foreach (preg_split("/(<[^>]*>)/", $page->section_content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
                @if (strpos($part, '<') === 0)
                    {!! __($part) !!}
                @else
                    {{ __($part) }}
                @endif
            @endforeach
        @endif
    </div>
</section>
@endsection