@extends('translation::layout')

@section('body')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                @include('translation::notifications')

                <div class="col">
                    <h2 class="page-title">
                        {{ __('translation::translation.translations') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('languages.translations.create', $language) }}"
                            class="btn btn-primary d-none d-sm-inline-block">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            {{ __('Add') }}
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
                        <form action="{{ route('languages.translations.index', ['language' => $language]) }}"
                            method="get">

                            <div class="row px-3 mt-5">
                                <div class="col-lg-3">
                                    @include('translation::forms.select', ['name' => 'language', 'items' => $languages,
                                    'submit' => true, 'selected' => $language])
                                </div>

                                <div class="col-lg-3">
                                    @include('translation::forms.select', ['name' => 'group', 'items' => $groups,
                                    'submit' => true, 'selected' => Request::get('group'), 'optional' => true])
                                </div>

                                <div class="col-lg-6">
                                    {{-- @include('translation::forms.search', ['name' => 'filter', 'value' =>
                                    Request::get('filter')]) --}}
                                </div>
                            </div>

                            @if(count($translations))
                            <div class="table-responsive">

                                <table class="table card-table table-vcenter text-nowrap datatable" id="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">{{ __('translation::translation.group_single') }}</th>
                                            <th style="width: 20%">{{ __('translation::translation.key') }}</th>
                                            <th class="text-uppercase" style="width: 35%">{{ config('app.locale') }}</th>
                                            <th class="text-uppercase" style="width: 40%">{{ $language }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($translations as $type => $items)
                                            @foreach($items as $group => $translations)
                                                @foreach($translations as $key => $value)
                                                    @if(!is_array($value[config('app.locale')] ?? ''))
                                                    <tr>
                                                        <td style="width: 10%; word-wrap: break-word">{{ $group }}</td>
                                                        <td style="width: 20%; word-wrap: break-word">{{ $key }}</td>
                                                        <td style="width: 35%; word-wrap: break-word">{{ $value[config('app.locale')] ?? '' }}</td>
                                                        <td style="width: 35%; word-wrap: break-word">
                                                            <translation-input
                                                                initial-translation="{{ $value[$language] }}"
                                                                language="{{ $language }}"
                                                                group="{{ $group }}"
                                                                translation-key="{{ $key }}"
                                                                route="{{ config('translation.ui_url') }}">
                                                            </translation-input>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection