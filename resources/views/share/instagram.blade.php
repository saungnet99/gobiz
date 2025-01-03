@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true, 'title' => __('Share this Post on Instagram')])

@section('content')
    <section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="max-w-full mx-auto mb-20">
                <h2 class="mb-4 font-heading font-semibold text-center text-6xl sm:text-7xl text-gray-900">{{ __('Share this Post on Instagram') }}</h2>

                <p class="text-base text-black mb-3">{{ __('Right-click and save the image below to share on Instagram:') }}</p>
                <img src="{{ $image }}" alt="Blog Cover Image" style="max-width: 100%; height: auto;">

                <p class="text-base text-black mb-3">{{ __('Copy the caption below to use with your Instagram post:') }}</p>
                <textarea class="bg-gray-200 rounded p-3" rows="5" cols="100" readonly>{{ $caption }}</textarea>

                <p class="text-base text-black mb-3">{{ __('Don\'t forget to include the link to your blog in your bio!') }}</p>
            </div>
        </div>
    </section>
@endsection
