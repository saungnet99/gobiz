@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true,
'title' => __('FAQs')])

@section('content')
{{-- FAQs Page --}}
<section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto mb-20">
            <h2 class="mb-4 font-heading font-semibold text-center text-6xl sm:text-7xl text-gray-900">{{ __($faqPage[0]->section_content) }}</h2>
            <p class="text-lg text-gray-600 text-center">{{ __($faqPage[1]->section_content) }}</p>
        </div>
        <div class="flex flex-wrap -m-6 mb-12">
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[2]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[3]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[4]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[5]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[6]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[7]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[8]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[9]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[10]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[11]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-xl">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($faqPage[12]->section_content) }}</h2>
                    <p class="text-base text-black">{{ __($faqPage[13]->section_content) }}</p>
                </div>
            </div>
        </div>
        <a class="group block max-w-max mx-auto font-heading font-medium text-gray-900 hover:text-gray-800 text-base overflow-hidden"
            href="{{ route('contact') }}">
            <p class="mb-1">{{ __('Didn’t find the answer? Contact us here') }}</p>
            <div
                class="w-full transform -translate-x-0 group-hover:translate-x-full h-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 transition ease-in-out duration-500">
            </div>
        </a>
    </div>
</section>
@endsection