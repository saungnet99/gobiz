@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => false, 'setting' => false, 'title' => true, 'title' => __('429 - Too Many Requests')])

@php
// Page content
use Illuminate\Support\Facades\DB;
use App\Setting;

$supportPage = DB::table('pages')->where('page_name', 'footer')->orWhere('page_name', 'contact')->get();
$config = DB::table('config')->get();
$settings = Setting::where('status', 1)->first();
@endphp

@section('content')
{{-- Too Many Requests Page --}}
<section class="pt-12 lg:pb-20 overflow-hidden">
    <div class="relative container mx-auto lg:px-24 px-4">
        <div class="relative z-10">
            <h2 class="lg:mb-12 mb-12 max-w-max font-heading font-bold text-15xl text-transparent bg-clip-text bg-gradient-{{ $config[11]->config_value }}">
                {{ __('429') }}
            </h2>
            <div class="md:max-w-xl">
                <h2 class="mb-11 font-heading font-bold text-6xl sm:text-7xl text-gray-900">{{ __('Page Forbidden') }}</h2>
                <button class="group relative font-heading py-5 px-9 block w-full md:w-auto text-xs text-white font-semibold uppercase bg-gray-900 overflow-hidden rounded">
                    <a href="/">
                        <div class="absolute top-0 left-0 transform -translate-x-full group-hover:-translate-x-0 h-full w-full transition ease-in-out duration-500 bg-gray-800">
                        </div>
                        <p class="relative z-10">{{ __('Go back to Homepage') }}</p>
                    </a>
                </button>
            </div>
        </div>
    </div>
</section>
@endsection