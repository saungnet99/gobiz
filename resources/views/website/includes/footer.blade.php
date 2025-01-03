@php
    // Settings
    use App\Setting;
    use App\Page;
    $setting = Setting::where('status', 1)->first();
    $pages = Page::get();
@endphp

<section class="relative py-12 lg:px-24 overflow-hidden">
    <img class="absolute bottom-0 left-0" src="{{ asset('app/assets/elements/footers/radial.svg') }}" alt="">
    <div class="relative z-10 container mx-auto px-4">
        <div class="flex flex-wrap -m-6">
            <div class="w-full md:w-1/2 lg:w-5/12 p-6">
                <div class="flex flex-col justify-between h-full">
                    <div>
                        <img class="mb-4" src="{{ asset($settings->site_logo) }}" alt="{{ $settings->site_name }}"
                            width="200">
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">© {{ __('Copyright') }}
                            {{ Carbon\Carbon::now()->format('Y') }}. {{ __('All Rights Reserved by') }} {{ config('app.name') }}.</p>
                    </div>
                </div>
            </div>
            <div
                class="w-full {{ $supportPage[0]->section_content || $supportPage[1]->section_content || $supportPage[2]->section_content || $supportPage[3]->section_content || $supportPage[4]->section_content != '' ? 'md:w-1/2 lg:w-2/12' : 'md:w-1/2 lg:w-2/12' }} p-6">
                <div class="h-full">
                    <h3 class="mb-9 font-heading font-semibold text-xs text-gray-500 uppercase tracking-px">
                        {{ __('Getting Started') }}</h3>
                    <ul>
                        <li class="mb-4"><a
                                class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                href="{{ route('home-locale') }}#how-it-works">{{ __('How it works?') }}</a></li>
                        <li class="mb-4"><a
                                class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                href="{{ route('home-locale') }}#features">{{ __('Features') }}</a></li>
                        <li class="mb-4"><a
                                class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                href="{{ route('home-locale') }}#pricing">{{ __('Pricing') }}</a></li>
                        <li class="mb-4"><a
                            class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                            href="{{ route('blogs') }}">{{ __('Blogs') }}</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="w-full {{ $supportPage[0]->section_content || $supportPage[1]->section_content || $supportPage[2]->section_content || $supportPage[3]->section_content != '' || $supportPage[4]->section_content != '' ? 'md:w-1/2 lg:w-2/12' : 'md:w-1/2 lg:w-2/12' }} p-6">
                <div class="h-full">
                    <h3 class="mb-9 font-heading font-semibold text-xs text-gray-500 uppercase tracking-px">
                        {{ __('My Account') }}</h3>
                    <ul>
                        <li class="mb-4"><a
                                class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                href="{{ route('login') }}">{{ __('Login') }}</a></li>

                        @if (Route::has('register'))
                            <li class="mb-4"><a
                                    class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @endif

                        @if ($pages[195]->page_name == 'contact' && $pages[195]->status == 'active')
                            <li class="mb-4"><a
                                    class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('contact') }}">{{ __('Contact Us') }}</a></li>
                        @endif

                        <li><a class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                href="mailto:{{ $supportPage[10]->section_content }}">{{ __('Customer Support') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full md:w-1/2 lg:w-3/12 p-6">
                <div class="h-full">
                    <h3 class="mb-9 font-heading font-semibold text-xs text-gray-500 uppercase tracking-px">{{ __('Legals') }}</h3>
                    <ul>
                        @if ($pages[49]->page_name == 'faq' && $pages[49]->status == 'active')
                            <li class="mb-4"><a class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('faq') }}">{{ __('FAQs') }}</a></li>
                        @endif
                        @if ($pages[108]->page_name == 'terms' && $pages[108]->status == 'active')
                            <li class="mb-4"><a
                                    class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('terms.and.conditions') }}">{{ __('Terms and Conditions') }}</a>
                            </li>
                        @endif
                        @if ($pages[64]->page_name == 'privacy' && $pages[64]->status == 'active')
                            <li class="mb-4"><a
                                    class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('privacy.policy') }}">{{ __('Privacy Policy') }}</a></li>
                        @endif
                        @if ($pages[156]->page_name == 'refund' && $pages[156]->status == 'active')
                            <li><a class="font-heading font-medium text-base text-gray-900 hover:text-gray-700"
                                    href="{{ route('refund.policy') }}">{{ __('Refund Policy') }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
