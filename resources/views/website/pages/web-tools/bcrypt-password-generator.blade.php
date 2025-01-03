@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true,
'title' => __('Bcrypt Password Generator - Web Tools')])

{{-- Check Google Adsense is "enabled" --}}
@section('custom-script')
<script src="{{ asset('js/clipboard.min.js') }}"></script>
@if ($settings->google_adsense_code != 'DISABLE_ADSENSE_ONLY')
{{-- AdSense code --}}
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $settings->google_adsense_code }}" crossorigin="anonymous"></script>
@endif
@endsection

@section('content')
<div>
    <section class="text-gray-700">
        <div class="container px-5 py-12 mx-auto">
            {{-- Page title --}}
            <div class="mb-2">
                <h1 class="text-3xl font-bold font-large title-font text-gray-900 mb-4">
                    {{ __('Bcrypt Password Generator') }}
                </h1>
            </div>

            {{-- Form --}}
            <form action="{{ route('web.result.bcrypt.password.generator')}}" method="post">
                @csrf
                {{-- Password --}}
                <div class='mb-3 space-y-2 w-full'>
                    <label class='font-bold text-gray-600 py-2'>{{ __('Password') }} <abbr title='required'>*</abbr></label>
                    <input type="text" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter font-mono text-base text-black rounded-lg h-10 px-4" name="password" id="password" placeholder="{{ __('Password')}}" value="@isset($password){{ $password }}@endisset">
                </div>

                <button type="submit" class="group relative font-heading px-10 py-5 mb-8 w-full lg:w-auto uppercase text-white text-xs font-semibold tracking-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 overflow-hidden rounded-md" onclick="convert()">{{ __('Generate') }}</button>
            </form>

            {{-- Result --}}
            @if (isset($results))
                <div class='mb-3 space-y-2 w-full'>
                    <label class='font-bold text-gray-600 py-2'>{{ __('Output') }} <abbr title='required'>*</abbr></label>
                    <input type="text" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter font-mono text-base text-black rounded-lg h-10 px-4" name="result" id="result" placeholder="{{ __('Result')}}" value="{{ $results }}">
                </div>

                <button class="copyBtn group relative font-heading px-10 py-5 w-full lg:w-auto uppercase text-white text-xs font-semibold tracking-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 overflow-hidden rounded-md" data-clipboard-action="copy" data-clipboard-target="#result">{{ __('Copy to clipboard') }}</button>
            @endif
        </div>
    </section>
</div>

{{-- Custom JS --}}
@section('custom-js')
<script>
new ClipboardJS('.copyBtn');
</script>
@endsection
@endsection