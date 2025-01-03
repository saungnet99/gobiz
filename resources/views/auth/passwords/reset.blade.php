@php
// Page content
use Illuminate\Support\Facades\DB;

$config = DB::table('config')->get();
$supportPage = DB::table('pages')->where('page_name', 'footer')->orWhere('page_name', 'contact')->get();

// Default
$navbar = true;
$footer = true;

if ($config[38]->config_value == "no") { 
    // $navbar = false;
    $footer = false;
}
@endphp

@extends('layouts.index', ['nav' => $navbar, 'banner' => false, 'footer' => $footer, 'cookie' => false, 'setting' => true,
'title' => true, 'title' => 'Reset Password'])

@section('custom-script')
<link rel="icon" href="{{ asset($settings->favicon) }}" sizes="96x96" type="image/png" />
@endsection

@section('content')
{{-- Reset page --}}
<section class="pt-12 lg:pb-20 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center -m-6">
            <div class="w-full md:w-1/2 p-6 lg:block hidden">
                <div class="p-1 mx-auto max-w-max overflow-hidden rounded-full">
                    <img class="object-cover rounded-full" src="{{ asset($config[13]->config_value) }}" alt="{{ $config[0]->config_value }}">
                </div>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <div class="md:max-w-md">
                    <h2 class="mb-8 font-heading font-bold text-6xl sm:text-7xl">{{ __('Reset Password') }}</h2>

                    {{-- Reset form --}}
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf  
                        <div class="flex flex-wrap -m-2 mb-6">

                            <input type="hidden" name="token" value="{{ $token }}">

                            {{-- Email --}}
                            <div class="w-full p-2">
                                <p class="mb-2.5 font-medium text-base">{{ __('Email address') }}</p>
                                <div
                                    class="p-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 focus-within:ring-4 focus-within:ring-indigo-500 rounded-lg @error('email') is-invalid @enderror">
                                    <input class="w-full px-6 py-4 placeholder-gray-500 text-base outline-none rounded-lg"
                                        type="email" placeholder="{{ __('Your email address') }}" name="email" id="email" value="{{ urldecode(request()->get('email')) }}" required autocomplete="email" autofocus>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback mx-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            {{-- Password --}}
                            <div class="w-full p-2">
                                <p class="mb-2.5 font-medium text-base">{{ __('Password') }}</p>
                                <div
                                    class="p-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 focus-within:ring-4 focus-within:ring-indigo-500 rounded-lg">
                                    <input class="w-full px-6 py-4 placeholder-gray-500 text-base outline-none rounded-lg @error('password') is-invalid @enderror"
                                        type="password" placeholder="********" name="password" id="password" required autocomplete="new-password">
                                </div>
                            </div>
                            {{-- Show password --}}
                            <div class="mb-4">
                                <a class="ml-2 text-xs text-gray-800 font-medium float-right cursor-pointer" title="Show password"
                                    data-bs-toggle="tooltip" onclick="showPassword()">{{ __('Show / Hide Password')}}</a>
                            </div>
                            @error('password')
                            <span class="invalid-feedback mx-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            {{-- Confirm Password --}}
                            <div class="w-full p-2">
                                <p class="mb-2.5 font-medium text-base">{{ __('Confirm Password') }}</p>
                                <div
                                    class="p-px bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 focus-within:ring-4 focus-within:ring-indigo-500 rounded-lg">
                                    <input class="w-full px-6 py-4 placeholder-gray-500 text-base outline-none rounded-lg @error('password') is-invalid @enderror"
                                        type="password" placeholder="********" name="password_confirmation" id="password_confirmation" id="" required autocomplete="new-password">
                                </div>
                            </div>
                            @error('password')
                            <span class="invalid-feedback mx-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="group relative md:max-w-max mb-5">
                            <div
                                class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 opacity-0 group-hover:opacity-50 rounded-full transition ease-out duration-300">
                            </div>
                            <button
                                class="p-1 w-full font-heading font-semibold text-xs text-white uppercase tracking-px overflow-hidden rounded-full">
                                <div class="relative py-5 px-14 bg-gradient-to-r from-{{ $config[11]->config_value }}-400 to-{{ $config[11]->config_value }}-500 overflow-hidden rounded-full">
                                    <div
                                        class="absolute top-0 left-0 transform -translate-y-full group-hover:-translate-y-0 h-full w-full bg-white transition ease-in-out duration-500">
                                    </div>
                                    <p class="relative z-10 group-hover:text-gray-900">{{ __('Reset Password') }}</p>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Custom JS --}}
@section('custom-js')
<script>
function showPassword() {
    "use strict";
    var password = document.getElementById("password");
    var confirmPassword = document.getElementById("password_confirmation");
    if (password.type === "password") {
        password.type = "text";
        confirmPassword.type = "text";
    } else {
        password.type = "password";
        confirmPassword.type = "password";
    }
}
</script>
@endsection
@endsection