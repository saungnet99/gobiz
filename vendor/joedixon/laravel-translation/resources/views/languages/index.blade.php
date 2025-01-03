@extends('translation::layout')

@section('body')

@if(count($languages))
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                @include('translation::notifications')

                <div class="col">
                    <h2 class="page-title">
                        {{ __('translation::translation.languages') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">

                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <a href="{{ route('languages.index') }}" class="btn">
                                {{ __('translation::translation.languages') }}
                            </a>
                        </span>
                        <a href="{{ route('languages.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            {{ __('Add') }}
                        </a>
                        <a href="{{ route('languages.translations.index', config('app.locale')) }}"
                            class="btn btn-primary d-none d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-language"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 5h7"></path>
                                <path d="M9 3v2c0 4.418 -2.239 8 -5 8"></path>
                                <path d="M5 9c0 2.144 2.952 3.908 6.7 4"></path>
                                <path d="M12 20l4 -9l4 9"></path>
                                <path d="M19.1 18h-6.2"></path>
                            </svg>
                            {{ __('translation::translation.translations') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable" id="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('translation::translation.language_name') }}</th>
                                        <th>{{ __('translation::translation.locale') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($languages as $language => $name)
                                    <tr>
                                        <td>
                                            {{ $name }}
                                        </td>
                                        <td>
                                            <a href="{{ route('languages.translations.index', $language) }}">
                                                {{ $language }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection