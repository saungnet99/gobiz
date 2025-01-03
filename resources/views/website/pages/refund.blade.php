@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true,
'title' => __('Refund Policy')])

@section('content')
{{-- Refund policy page --}}
<section class="pt-12 lg:pb-20 lg:px-24 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto mb-20">
            <h2 class="mb-4 font-heading font-semibold text-center text-6xl sm:text-7xl text-gray-900">{{ __($refundPage[0]->section_content) }}</h2>
        </div>
        <div class="flex flex-wrap -m-6 mb-12">
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-5 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[1]->section_content) }}</h2>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <p class="text-base text-black mb-3">{{ __($refundPage[2]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[3]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[4]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[5]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[6]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[7]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[8]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($refundPage[9]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[10]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[11]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[12]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[13]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[14]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[15]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[16]->section_content) }}</h2>
                    <p class="text-base text-black mb-3">{{ __($refundPage[17]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[18]->section_content) }}</p>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[19]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[20]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[21]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[22]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[23]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[24]->section_content) }}</p>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[25]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[26]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[27]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[28]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[29]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[30]->section_content) }}</p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[31]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[32]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[33]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[34]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-3">{{ __($refundPage[35]->section_content) }}</p>
                </div>
            </div>
            <div class="w-full p-6">
                <div class="md:max-w-full">
                    <h2 class="mb-4 font-heading font-medium text-2xl text-gray-900">{{ __($refundPage[36]->section_content) }}</h2>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[37]->section_content) }}</strong></p>
                    <p class="text-base text-black mb-4"><strong>{{ __($refundPage[38]->section_content) }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
