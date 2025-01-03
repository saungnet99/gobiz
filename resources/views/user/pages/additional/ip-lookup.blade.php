@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

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
                        {{ __('IP Lookup') }}
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

                {{-- Failed --}}
                @if (Session::has("failed"))
                <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="alert-icon icon icon-tabler icon-tabler-alert-circle" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            {{Session::get('failed')}}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif

                {{-- Success --}}
                @if(Session::has("success"))
                <div class="alert alert-important alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon icon icon-tabler icon-tabler-check"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                        <div>
                            {{Session::get('success')}}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif

                {{-- Search IP Lookup --}}
                <div class="col-sm-6 col-lg-6">
                    <form action="{{ route('user.result.ip-lookup') }}" method="post" class="card">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                {{-- IP Address --}}
                                <div class="col-md-12 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label required">{{ __('IP Address') }}</label>
                                        <input type="text" class="form-control" name="ip"
                                            value="{{ $result['traits']['ip_address'] ?? (old('ip') ?? request()->ip()) }}"
                                            placeholder="{{ __('IP Address') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="10" cy="10" r="7"></circle>
                                    <line x1="21" y1="21" x2="15" y2="15"></line>
                                </svg>
                                {{ __('Search') }}
                            </button>
                            <a href="{{ route('user.ip-lookup') }}" class="btn btn-dark">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Result --}}
                <div class="col-sm-6 col-lg-6">
                    @if(!empty($result))
                    <div class="card border-0 shadow-sm">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col">
                                    <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mb-n3">
                            <div class="form-row">
                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="countryName">{{ __('Country Name') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <span class="avatar avatar-xs"
                                                        style="background-image: url({{ asset('/images/icons/countries/'. mb_strtolower($result['country']['iso_code'] ?? 'not-found')) }}.svg)"></span>
                                                </div>
                                            </div>
                                            <input id="countryName" class="form-control" type="text"
                                                value="{{ __($result['country']['names']['en'] ?? 'Not found') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="cityName">{{ __('City Name') }}</label>
                                        <input id="cityName" class="form-control" type="text"
                                            value="{{ __($result['city']['names']['en'] ?? 'Not found') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="postal-code">{{ __('Postal code') }}</label>
                                        <input id="postal-code" class="form-control" type="text"
                                            value="{{ __($result['postal']['code'] ?? 'Not found') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="latitude">{{ __('Latitude') }}</label>
                                        <input id="latitude" class="form-control" type="text"
                                            value="{{ __($result['location']['latitude'] ?? 'Not found') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="longitude">{{ __('Longtitude') }}</label>
                                        <input id="longitude" class="form-control" type="text"
                                            value="{{ __($result['location']['longitude'] ?? 'Not found') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="timezone">{{ __('Timezone') }}</label>
                                        <input id="timezone" class="form-control" type="text"
                                            value="{{ __($result['location']['time_zone'] ?? 'Not found') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card border-0 shadow-sm">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col">
                                    <div class="font-weight-bold">{{ __('Result') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mb-n3">
                            <div class="form-row">
                                <h2 class="text-center">{{ __('Waiting for result') }}<span class="animated-dots"></span></h2>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Footer --}}
    @include('user.includes.footer')
</div>
@endsection