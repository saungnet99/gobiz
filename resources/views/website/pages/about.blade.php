@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true,
'title' => __('About us')])

@section('content')
{{-- About us page --}}
<section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto mb-20">
            <h2 class="mb-4 font-heading font-semibold text-center text-6xl sm:text-7xl text-gray-900">{{ __($aboutPage[0]->section_content) }}</h2>
        </div>
        <div class="flex flex-wrap -m-6">
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($aboutPage[1]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[2]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[3]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[4]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($aboutPage[5]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[6]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[7]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[8]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[9]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[10]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[11]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[12]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[13]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($aboutPage[14]->section_content) }}</p>
                
                </div>
            </div>
        </div>
    </div>
</section>
@endsection