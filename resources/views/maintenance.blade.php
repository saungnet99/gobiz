@extends('layouts.index', ['nav' => true, 'banner' => false, 'footer' => true, 'cookie' => true, 'setting' => true])

@section('content')
    <section class="pt-56 pb-56 pb-12 overflow-hidden">
        <div class="container mx-auto px-4 py-5">
            @guest
                <h2 class="mb-7 font-heading font-semibold text-6xl sm:text-7xl text-gray-900 text-center">
                    {{ __('Something went wrong. Please contact a website administrator') }}</h2>
            @else
                <h2 class="mb-7 font-heading font-semibold text-6xl sm:text-7xl text-gray-900 text-center">
                    {{ __('As many changes are made to the plan. So, you need to re-config the all plan again.') }}</h2>
            @endguest
        </div>
    </section>
@endsection
