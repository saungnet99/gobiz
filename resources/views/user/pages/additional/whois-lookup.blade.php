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
                        {{ __('WHOIS Lookup') }}
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
                {{-- Search Whois Lookup --}}
                <div class="col-sm-6 col-lg-6">
                    <form action="{{ route('user.result.whois-lookup') }}" method="post" class="card">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                {{-- Domain --}}
                                <div class="col-md-12 col-xl-12">
                                    <div class="mb-3">
                                        <label class="form-label required">{{ __('Domain') }}</label>
                                        <input type="text" class="form-control" name="domain"
                                            value="{{ $domain ?? (old('domain') ?? '') }}"
                                            placeholder="{{ __('Eg: https://domain.com') }}" required>
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
                            <a href="{{ route('user.whois-lookup') }}" class="btn btn-dark">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Result --}}
                <div class="col-sm-6 col-lg-6">
                    @if(isset($results))
                    <div class="card border-0 shadow-sm">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col">
                                    <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(empty($results))
                            {{ __('No results found.') }}
                            @else
                            <div class="list-group list-group-flush my-n3">
                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain') }}</div>
                                        <div class="col-12 col-lg-8 text-break d-flex align-items-center">
                                            <img class="avatar avatar-sm"
                                                src="https://icons.duckduckgo.com/ip3/{{ $results->domainName }}.ico"
                                                rel="noreferrer" class="rounded">
                                            <span>{{ $results->domainName }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Registrar Name') }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">{{ $results->registrar }}</div>
                                    </div>
                                </div>

                                @if($results->owner)
                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Registrant Name') }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">{{ $results->owner }}</div>
                                    </div>
                                </div>
                                @endif

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain Created date')
                                            }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">
                                            {{ __(':date at :time (UTC :offset)', ['date' =>
                                            \Carbon\Carbon::createFromTimestamp($results->creationDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                            \Carbon\Carbon::createFromTimestamp($results->creationDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                            \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                            config('app.timezone')))->toOffsetName()]) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain Updated date')
                                            }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">
                                            {{ __(':date at :time (UTC :offset)', ['date' =>
                                            \Carbon\Carbon::createFromTimestamp($results->updatedDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                            \Carbon\Carbon::createFromTimestamp($results->updatedDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                            \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                            config('app.timezone')))->toOffsetName()]) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain Expiration
                                            date')
                                            }}</div>
                                        <div class="col-12 col-lg-8 text-break">
                                            {{ __(':date at :time (UTC :offset)', ['date' =>
                                            \Carbon\Carbon::createFromTimestamp($results->expirationDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('Y-m-d')), 'time' =>
                                            \Carbon\Carbon::createFromTimestamp($results->expirationDate)->tz(Auth::user()->timezone
                                            ?? config('app.timezone'))->format(__('H:i:s')), 'offset' =>
                                            \Carbon\CarbonTimeZone::create((Auth::user()->timezone ??
                                            config('app.timezone')))->toOffsetName()]) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Domain Name servers')
                                            }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">
                                            @foreach($results->nameServers as $serverName)
                                            <div class="text-break {{ !$loop->first ? 'mt-1' : '' }}">
                                                {{ $serverName }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('States') }}</div>
                                        <div class="col-12 col-lg-8 text-break">
                                            @foreach($results->states as $stateName)
                                            <div class="text-break {{ !$loop->first ? 'mt-1' : '' }}">
                                                {{ $stateName }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                @if($results->whoisServer)
                                <div class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('WHOIS server') }}
                                        </div>
                                        <div class="col-12 col-lg-8 text-break">{{ $results->whoisServer }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
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