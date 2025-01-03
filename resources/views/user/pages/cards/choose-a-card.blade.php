@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
@endsection

@section('content')
<div class="page-wrapper">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Choose a Card Type') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            {{-- Failed --}}
            @if(Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif
            
            <div class="row row-deck row-cards">
                <div class="col-6 col-md-2 col-xl-2 col-lg-2">

                    <div class="card">

                        <label class="form-imagecheck mb-2">
                            <input name="card-type" type="radio" value="business" class="form-imagecheck-input"
                                onclick="chooseCardTpe(this)">
                            <span class="shadow-none">
                                <img src="{{ asset("img/vCards/business.png") }}" alt="{{ __('Business') }}"
                                    class="form-imagecheck-image p-2">
                            </span>
                            <h4 class="text-center mt-4">{{ __('Business') }}</h4>
                        </label>

                    </div>

                </div>
                <div class="col-6 col-md-2 col-xl-2 col-lg-2">

                    <div class="card">

                        <label class="form-imagecheck mb-2">
                            <input name="card-type" type="radio" value="personal" class="form-imagecheck-input"
                                onclick="chooseCardTpe(this)">
                            <span class="shadow-none">
                                <img src="{{ asset("img/vCards/personal.png") }}" alt="{{ __('Personal') }}"
                                    class="form-imagecheck-image p-2">
                            </span>
                            <h4 class="text-center mt-4">{{ __('Personal') }}</h4>
                        </label>

                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('user.includes.footer')
</div>

{{-- Custom JS --}}
@push('custom-js')
<script>
    function chooseCardTpe(selectedCard) {
    var selectedCardValue = selectedCard.value;
    if (selectedCardValue == "business") {
        window.location = `{{ route('user.create.card', 'type=business') }}`;
    } else {
        window.location = `{{ route('user.create.card', 'type=personal') }}`;
    }
}
</script>
@endpush
@endsection
