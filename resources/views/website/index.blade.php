@extends('layouts.index', ['nav' => true, 'banner' => true, 'footer' => true, 'cookie' => true, 'setting' => true, 'config' => $config])

@section('content')
    {{-- How to works --}}
    <section class="pt-32 pb-12 overflow-hidden" id="how-it-works">
        <div class="container mx-auto px-4">
            <p class="mb-5 font-heading font-semibold text-xs text-gray-400 text-center uppercase tracking-px">
                {{ __($homePage[6]->section_content) }}</p>
            <h2 class="mb-7 font-heading font-semibold text-6xl sm:text-7xl text-gray-900 text-center">
                {{ __($homePage[7]->section_content) }}</h2>
            <p class="mb-20 font-heading font-semibold text-xs text-gray-400 text-center uppercase tracking-px">
                {{ __($homePage[8]->section_content) }}</p>
            <div class="flex flex-wrap -m-4">
                <div class="w-full md:w-1/3 p-4 mb-3"> 
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-album p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __($homePage[12]->section_content) }}</h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[13]->section_content) }}</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 p-4 mb-3">
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-shopping-cart p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __($homePage[14]->section_content) }}</h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[15]->section_content) }}</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 p-4 mb-3">
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-id p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __($homePage[16]->section_content) }}</h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[17]->section_content) }}</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 p-4">
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-users-group p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __('Contact Information') }}
                        </h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[9]->section_content) }}</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 p-4">
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-social p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __($homePage[10]->section_content) }}</h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[11]->section_content) }}</p>
                    </div>
                </div>
                <div class="w-full md:w-1/3 p-4">
                    <div class="md:max-w-xs mx-auto text-center">
                        <i class="ti ti-briefcase p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                        <h2 class="font-heading font-medium text-xl text-gray-900 mt-8 mb-2">
                            {{ __($homePage[18]->section_content) }}</h2>
                        <p class="text-gray-500 leading-loose">{{ __($homePage[19]->section_content) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="relative pt-24 pb-12 lg:px-24 overflow-hidden" id="features">
        <div class="container mx-auto px-4 mb-12">
            <p class="mb-5 font-heading text-xs text-gray-600 font-semibold text-center uppercase tracking-px">
                {{ __($homePage[20]->section_content) }}</p>
            <h2 class="mb-20 max-w-2xl mx-auto font-heading font-bold text-center text-6xl sm:text-7xl text-gray-900">
                {{ __($homePage[21]->section_content) }}</h2>
            <div class="flex flex-wrap -m-7">
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i
                                class="ti ti-brand-whatsapp p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[22]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[23]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-aperture p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[24]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[25]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-briefcase p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[26]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[27]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-credit-card p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[28]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[29]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-clock p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[30]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[31]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-briefcase p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[32]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[33]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-map-pin p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[34]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[35]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-share p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[36]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[37]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-brush p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[38]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[39]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-circle-check p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[40]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[41]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-bolt p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[42]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[43]->section_content) }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/4 p-7">
                    <div
                        class="h-full p-0.5 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 rounded-10 transform hover:-translate-y-3 transition ease-out duration-1000">
                        <div class="h-full p-8 bg-white rounded-lg">
                            <i class="ti ti-link p-3 rounded-lg bg-{{ $config[11]->config_value }}-100 text-{{ $config[11]->config_value }}-600"></i>
                            <h3 class="mt-16 mb-5 font-heading font-bold text-gray-900 text-xl">
                                {{ __($homePage[44]->section_content) }}</h3>
                            <p class="text-gray-600">{{ __($homePage[45]->section_content) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="pt-12 pb-32 lg:px-24 overflow-hidden" id="pricing">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto">
                <p class="text-{{ $config[11]->config_value }}-600 font-bold text-center">
                    {{ __($homePage[46]->section_content) }}</p>
                <h2 class="mb-5 font-heading font-bold text-center text-6xl sm:text-7xl text-gray-900">
                    {{ __($homePage[47]->section_content) }}</h2>
                <p class="mb-20 font-heading text-xs text-gray-600 font-semibold text-center uppercase tracking-px">
                    {{ __($homePage[48]->section_content) }}</p>
            </div>
            <div class="flex flex-wrap -m-3">
                {{-- Plans --}}
                @if (count($plans) > 0)
                    @foreach ($plans as $plan)
                        <div class="w-full md:w-1/2 xl:w-1/3 p-3">
                            <div
                                class="p-10 bg-{{ $plan->recommended == '1' ? $config[11]->config_value . '-600' : 'white' }} border border-gray-200 rounded-10">
                                <span
                                    class="{{ $plan->recommended == '1' ? 'bg-gray-50 text-dark' : 'bg-' . $config[11]->config_value . '-600 text-white' }} mb-2 inline-block rounded py-1 px-2 text-sm font-semibold">{{ __($plan->plan_type == 'BOTH' ? 'VCARD & STORE' : $plan->plan_type) }}</span>
                                <h3
                                    class="mb-5 font-heading font-medium text-base text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                    {{ __($plan->plan_name) }}</h3>
                                <div class="pb-6 flex">
                                    <span
                                        class="font-heading font-bold text-6xl sm:text-7xl text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">{{ $plan->plan_price == '0' ? '' : $currency->symbol }}{{ $plan->plan_price == '0' ? __('Free') : $plan->plan_price }}</span>
                                    @if ($plan->validity == '9999')
                                        <span
                                            class="text-sm text-{{ $plan->recommended == '1' ? 'white' : 'black' }} self-end">{{ $plan->plan_price != '0' ? '/' . __('Forever') : '' }}</span>
                                    @endif
                                    @if ($plan->validity == '31')
                                        <span
                                            class="text-sm text-{{ $plan->recommended == '1' ? 'white' : 'black' }} self-end">{{ $plan->plan_price != '0' ? '/' . __('Per Month') : '' }}</span>
                                    @endif
                                    @if ($plan->validity == '365' || $plan->validity == '366')
                                        <span
                                            class="text-sm text-{{ $plan->recommended == '1' ? 'white' : 'black' }} self-end">{{ $plan->plan_price != '0' ? '/' . __('Per Year') : '' }}</span>
                                    @endif
                                    @if (
                                        $plan->validity > '1' &&
                                            $plan->validity != '31' &&
                                            $plan->validity != '365' &&
                                            $plan->validity != '366' &&
                                            $plan->validity != '9999')
                                        <span
                                            class="text-sm text-{{ $plan->recommended == '1' ? 'white' : 'black' }} self-end">{{ $plan->plan_price != '0' ? '/' . __('Per ') . $plan->validity . __(' Days') : '' }}</span>
                                    @endif
                                </div>
                                {{-- Description --}}
                                <p
                                    class="pb-6 text-{{ $plan->recommended == '1' ? 'white' : 'black' }} leading-loose border-b border-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                    {{ __($plan->plan_description) }}</p>

                                <ul class="my-6">
                                    {{-- Check Card type is "Both" or "VCARD" --}}
                                    @if ($plan->plan_type == 'BOTH' || $plan->plan_type == 'VCARD')
                                        <h3
                                            class="mb-5 font-heading font-large text-base text-{{ $plan->recommended == '1' ? 'white' : $config[11]->config_value . '-600' }}">
                                            {{ __('vCard Features') }}</h3>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_vcards > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_vcards == '999' ? 'Unlimited' : $plan->no_of_vcards) }}
                                                {{ __('vCards') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_services > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_services == '999' ? 'Unlimited' : $plan->no_of_services) }}
                                                {{ __('Services') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_vcard_products > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_vcard_products == '999' ? 'Unlimited' : $plan->no_of_vcard_products) }}
                                                {{ __('Products') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_links > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_links == '999' ? 'Unlimited' : $plan->no_of_links) }}
                                                {{ __('Links') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_payments > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_payments == '999' ? 'Unlimited' : $plan->no_of_payments) }}
                                                {{ __('Payment Listed') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_galleries > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_galleries == '999' ? 'Unlimited' : $plan->no_of_galleries) }}
                                                {{ __('Galleries') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_testimonials > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_testimonials == '999' ? 'Unlimited' : $plan->no_testimonials) }}
                                                {{ __('Testimonials') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->business_hours == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->business_hours == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __('Business Hours') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->appointment == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->appointment == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __('Appointments') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->contact_form == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->contact_form == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __('Contact Form') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_enquires > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_enquires == '999' ? 'Unlimited' : $plan->no_of_enquires) }}
                                                {{ __('Enquiries') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->password_protected == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->password_protected == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __('Password Protected') }}</p>
                                        </li>
                                    @endif

                                    {{-- Check Card type is "Both" or "STORE" --}}
                                    @if ($plan->plan_type == 'BOTH' || $plan->plan_type == 'STORE')
                                        <h3
                                            class="mt-5 mb-5 font-heading font-large text-base text-{{ $plan->recommended == '1' ? 'white' : $config[11]->config_value . '-600' }}">
                                            {{ __('Store Features') }}</h3>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_stores > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_stores == '999' ? 'Unlimited' : $plan->no_of_stores) }}
                                                {{ __('Stores') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_categories > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_categories == '999' ? 'Unlimited' : $plan->no_of_categories) }}
                                                {{ __('Categories') }}</p>
                                        </li>

                                        <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                            <i class="ti ti-{{ __($plan->no_of_store_products > 0 ? 'check' : 'x') }} mr-2.5 text-{{ $plan->recommended == '1' ? 'green-500' : 'green-500' }}"
                                                style="font-size: 20px;"></i>
                                            <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                                {{ __($plan->no_of_store_products == '999' ? 'Unlimited' : $plan->no_of_store_products) }}
                                                {{ __('Products') }}</p>
                                        </li>
                                    @endif

                                    {{-- Additional Features --}}
                                    <h3
                                        class="mt-5 mb-5 font-heading font-large text-base text-{{ $plan->recommended == '1' ? 'white' : $config[11]->config_value . '-600' }}">
                                        {{ __('Additional features') }}</h3>

                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->advanced_settings == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->advanced_settings == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Advanced Settings') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->pwa == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->pwa == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Progressive Web App (PWA)') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->personalized_link == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->personalized_link == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Personalized Link') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->additional_tools == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->additional_tools == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Additional Tools') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->hide_branding == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->hide_branding == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Hide Branding') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->free_setup == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->free_setup == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Free Setup') }}</p>
                                    </li>
                                    <li class="flex items-center font-heading font-medium text-base text-gray-400">
                                        <i class="ti ti-{{ __($plan->free_support == '1' ? 'check' : 'x') }} mr-2.5 text-{{ $plan->free_support == '1' || $plan->recommended == '1' ? 'green-500' : 'gray-400' }}"
                                            style="font-size: 20px;"></i>
                                        <p class="text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                            {{ __('Free Support') }}</p>
                                    </li>
                                </ul>

                                {{-- Disable register --}}
                                @if(Route::has('register'))
                                <button class="mb-3 w-full font-heading font-bold text-base overflow-hidden rounded-md">
                                    <a href="{{ route('register') }}"
                                        class="text-{{ $plan->recommended == '1' ? 'black' : 'white' }} hover:text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                        <div
                                            class="px-9 py-4 border bg-{{ $plan->recommended == '1' ? 'white' : 'black' }} hover:bg-{{ $plan->recommended == '1' ? 'black' : 'white' }} overflow-hidden rounded-md">
                                            {{ __('Sign up. It’s Free') }}
                                        </div>
                                    </a>
                                </button>
                                @else
                                <button class="mb-3 w-full font-heading font-bold text-base overflow-hidden rounded-md">
                                    <a href="{{ route('login') }}"
                                        class="text-{{ $plan->recommended == '1' ? 'black' : 'white' }} hover:text-{{ $plan->recommended == '1' ? 'white' : 'black' }}">
                                        <div
                                            class="px-9 py-4 border bg-{{ $plan->recommended == '1' ? 'white' : 'black' }} hover:bg-{{ $plan->recommended == '1' ? 'black' : 'white' }} overflow-hidden rounded-md">
                                            {{ __('Sign up. It’s Free') }}
                                        </div>
                                    </a>
                                </button>
                                @endif
                            </div>
                        </div>
                    @endforeach 
                @else
                    <div class="w-full p-3">
                        <div class="py-5 px-3 rounded-10 text-center">
                            <h3 class="mb-5 font-heading font-medium text-4xl text-black">{{ __('No plans are currently active') }}</h3>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Custom JS --}}
    @section('custom-js')

    @endsection
@endsection
