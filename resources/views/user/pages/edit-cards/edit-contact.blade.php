@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
<script>
window.onload = function() {
    defaultContactForm();
}
</script>
@endsection

@section('content')
<div class="page-wrapper">
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
            
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader">{{ __('Update Business Card') }}</h4>
                            <div class="list-group list-group-transparent">
                                {{-- Nav links --}}
                                @include('user.pages.edit-cards.includes.nav-link', ['link' => 'contact'])
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <form action="{{ route('user.update.contact.form', Request::segment(3)) }}" method="post" enctype="multipart/form-data" id="myForm">
                            @csrf
                            <div class="card-body">
                                <h3 class="card-title mb-4">{{ __('Contact / Inquiry Form') }}</h3>

                                <div class="row">
                                    <div class="col-md-6 col-xl-6">
                                        <div class="mb-3">
                                            <div class="form-label">{{ __('Hide Contact / Inquiry Form') }}
                                            </div>
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    onchange="displayContactForm()" name="contact_form"
                                                    id="contact-form" {{ $business_card->enquiry_email == null ?
                                                'checked' : ''}}>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="contactForm">
                                        <h2 class="page-title mb-3">
                                            {{ __('Contact / Inquiry Form Configuration') }}
                                        </h2>

                                        <!-- Email -->
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Want to receive email') }}</label>
                                                <input type="email" class="form-control" name="receive_email"
                                                    id="receive_email" value="{{ $business_card->enquiry_email }}" placeholder="{{ __('Email Address') }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <a href="{{ route('user.cards') }}" class="btn">
                                        {{ __('Cancel') }}
                                    </a>

                                    {{-- Next link --}}
                                    @php
                                    $route = route('user.cards');

                                    // Check business hours is "ENABLED"
                                    if ($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                                        $route = route('user.edit.advanced.setting', Request::segment(3));
                                    }
                                    @endphp

                                    <a href="{{ $route }}" class="btn btn-primary">
                                        {{ __('Skip') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.includes.footer')
</div>


@push('custom-js')
<script>
function displayContactForm() {
    "use strict";
    var disp = $('input[name="contact_form"]:checked').length;
    console.log(disp);
    if (disp == 0) {
        $("#contactForm").show();
        $('#receive_email').attr('required', 'required');
    } else {
        $("#contactForm").hide();
        $('#receive_email').removeAttr('required', 'required');
    }
}
function defaultContactForm() {
    "use strict";
    var disp = $('input[name="contact_form"]:checked').length;
    console.log(disp);
    if (disp == 0) {
        $("#contactForm").show();
        $('#receive_email').attr('required', 'required');
    } else {
        $("#contactForm").hide();
        $('#receive_email').removeAttr('required', 'required');
    }
}
</script>
@endpush
@endsection