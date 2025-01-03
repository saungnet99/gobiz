@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
<style>
.form-control {
    border-radius: 2px !important;
}
.code {
    background: #333;
    color: #fff;
    font-family: monospace;
}
</style>
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
                                @include('user.pages.edit-cards.includes.nav-link', ['link' => 'advanced'])
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <form action="{{ route('user.update.advanced.setting', Request::segment(3)) }}" method="post" id="myForm">
                            @csrf
                            <div class="card-body">
                                <h3 class="card-title mb-4">{{ __('Advanced Settings') }}</h3>
        
                                <div class="row">

                                    {{-- Password protected --}}
                                    @if ($plan_details->password_protected == 1)
                                    <div class="row mb-3">
                                        <div class="col-md-3 col-xl-3">
                                            <div class="mb-2">
                                                <div class="form-label">{{ __('Disable Password Protection') }}
                                                </div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        onchange="displayPasswordProtected()" {{ $business_card->password == null ? 'checked' : '' }}
                                                        name="password_protected" id="password-protected">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="{{ $business_card->password == null ? 'd-none' : '' }}" id="passwordProtectedForm">
                                        <h2 class="page-title mb-3">
                                            {{ __('Set Password Protection') }}
                                        </h2>

                                        <!-- Password -->
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-4">
                                                <label class="form-label">{{ __('Password') }}</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="password" class="form-control" name="password"
                                                        id="password" value="{{ $business_card->password }}" minlength="3" maxlength="20"
                                                        placeholder="{{ __('Password') }}">

                                                    {{-- Show password --}}
                                                    <span class="input-group-text">
                                                        <a class="input-group-link" onclick="showPassword()"><kbd>{{ __('Show Password') }}</kbd></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Advanced settings --}}
                                    @if ($plan_details->advanced_settings == 1)
                                    <h2 class="page-title mb-3">
                                        {{ __('Custom CSS / JS') }}
                                    </h2>
                                    
                                    <div class="col-md-6 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Custom CSS') }}</label>
                                            <textarea class="form-control code" name="custom_css" rows="4"
                                                data-bs-toggle="autosize" maxlength="25000"
                                                placeholder="{{ __('Custom CSS') }}">{{ $business_card->custom_css }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Custom JS') }}</label>
                                            <textarea class="form-control code" name="custom_js" rows="4"
                                                data-bs-toggle="autosize" maxlength="25000"
                                                placeholder="{{ __('Custom JS') }}">{{ $business_card->custom_js }}</textarea>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <a href="{{ route('user.cards') }}" class="btn">
                                        {{ __('Cancel') }}
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

{{-- Password Protected --}}
@push('custom-js')
<script>
function displayPasswordProtected() {
    "use strict";
    var disp = $('input[name="password_protected"]:checked').length;
    if (disp == 0) {
        $("#passwordProtectedForm").removeAttr('class', 'd-none');;
        $('#password').attr('required', 'required');
    } else {
        $("#passwordProtectedForm").attr('class', 'd-none');;
        $('#password').removeAttr('required', 'required');
    }
}

function showPassword() {
    "use strict";
    var password = document.getElementById("password");
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
</script>
@endpush
@endsection