@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true,
'title' => __('Privacy Policy')])

@section('content')
{{-- Privacy policy page --}}
<section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto mb-20">
            <h2 class="mb-4 font-heading font-semibold text-center text-6xl sm:text-7xl text-gray-900">{{ __($privacyPage[0]->section_content) }}</h2>
        </div>
        <div class="flex flex-wrap -m-6 mb-12">
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <p class="text-base text-black mb-3">{{ __($privacyPage[1]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[2]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[3]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[4]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[5]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[6]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[7]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[8]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[9]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[10]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[11]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[12]->section_content) }}</p>
                
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[13]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[14]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[15]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[16]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[17]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[18]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[19]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[20]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[21]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[22]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[23]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[24]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[25]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[26]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($privacyPage[27]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[28]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[29]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[30]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[31]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[32]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($privacyPage[33]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[34]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[35]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[36]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[37]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[38]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[39]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[40]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($privacyPage[41]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[42]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($privacyPage[43]->section_content) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
