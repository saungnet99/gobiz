@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

{{-- Custom CSS --}}
@section('css')
    <style>
        .input-group {
            box-shadow: none;
            border-radius: var(--tblr-border-radius);
        }

        .btn {
            padding: 0.5rem 0.5rem !important;
            font-size: 0.9rem !important;
        }

        .input-group-text {
            padding: 0;
        }

        .form-control {
            border-radius: 0px !important;
        }

        [data-bs-theme="light"] #empty {
            content: url('../img/empty.svg');
        }

        [data-bs-theme="dark"] #empty {
            content: url('../img/empty-white.svg');
        }
    </style>
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
                            {{ __('Active Themes') }}
                        </h2>
                    </div>
                    <div class="col-auto d-inline">
                        <form action="{{ route('admin.search.theme') }}" method="GET">
                            <div>
                                <div class="input-group input-group-flat">
                                    <input type="hidden" name="view-page" value="active-themes">
                                    <input type="text" name="query" class="form-control"
                                        value="{{ request()->query('query') }}" placeholder="{{ __('Search for...') }}">
                                    <span class="input-group-text">
                                        <button class="input-group-link btn btn-primary btn-icon" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                <path d="M21 21l-6 -6" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    {{-- Themes --}}
                    @if (count($themes) > 0)
                        @foreach ($themes as $theme)
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <a href="{{ asset('img/vCards/' . $theme['theme_thumbnail']) }}"
                                        data-fslightbox="gallery" class="d-block">
                                        <img src="{{ asset('img/vCards/' . $theme['theme_thumbnail']) }}"
                                            class="card-img-top">
                                    </a>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $string = $theme['theme_name'];
                                                $words = explode(' ', $string);
                                                $themeName = implode(' ', array_slice($words, 0, 3));
                                                if (count($words) > 3) {
                                                    $themeName = implode(' ', array_slice($words, 0, 3)) . ' ...';
                                                }
                                            @endphp
                                            <div>
                                                <div data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="{{ __($theme['theme_name']) }}">
                                                    <strong>{{ __($themeName) }}</strong>
                                                </div>
                                                <div class="badge bg-primary text-white mt-2">
                                                    {{ __($theme['theme_description']) }}</div>
                                            </div>
                                            <div class="ms-auto">
                                                <a class="ms-3 btn btn-primary text-white" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="{{ __($theme['count'] . ' customers are using this theme') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-cards" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M3.604 7.197l7.138 -3.109a.96 .96 0 0 1 1.27 .527l4.924 11.902a1 1 0 0 1 -.514 1.304l-7.137 3.109a.96 .96 0 0 1 -1.271 -.527l-4.924 -11.903a1 1 0 0 1 .514 -1.304z" />
                                                        <path d="M15 4h1a1 1 0 0 1 1 1v3.5" />
                                                        <path
                                                            d="M20 6c.264 .112 .52 .217 .768 .315a1 1 0 0 1 .53 1.311l-2.298 5.374" />
                                                    </svg>
                                                    {{ __($theme['count']) }}
                                                </a>
                                                <a href="{{ route('admin.edit.theme', $theme['theme_id']) }}"
                                                    class="btn btn-primary btn-icon text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-edit" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                        </path>
                                                        <path
                                                            d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                        </path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                </a>
                                                <a href="#"
                                                    onclick="updateStatus(`{{ $theme->theme_id }}`, `{{ __('disabled') }}`); return false;"
                                                    class="btn btn-danger btn-icon text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-ban">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                        <path d="M5.7 5.7l12.6 12.6" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-sm-12 col-lg-12">
                            <div class="container-xl d-flex flex-column justify-content-center">
                                <div class="empty">
                                    <div class="empty-img">
                                        <img id="empty" src="{{ asset('img/empty.svg') }}" height="128"
                                            alt="">
                                    </div>
                                    <p class="empty-title">{{ __('No results found') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="my-3">
                    @if (request()->has('query'))
                        {{ $themes->appends(['view-page' => strtolower(request()->query('view-page')), 'query' => strtolower(request()->query('query'))])->links() }}
                    @else
                        {{ $themes->links() }}
                    @endif
                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>

    {{-- Update status --}}
    <div class="modal modal-blur fade" id="status-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="status_message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="themeId">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('scripts')
    <script src="{{ asset('js/fslightbox.js') }}"></script>
    <script>
        function updateStatus(themeId, themeStatus) {
            "use strict";

            $("#status-modal").modal("show");
            var delete_status = document.getElementById("status_message");
            delete_status.innerHTML = "<?php echo __('If you proceed, you will'); ?> " + themeStatus + " <?php echo __('this theme.'); ?>"
            var actionLink = document.getElementById("themeId");
            actionLink.getAttribute("href");
            actionLink.setAttribute("href", "{{ route('admin.update.theme.status') }}?id=" + themeId + "&status=" +
                themeStatus);
        }
    </script>
@endsection
@endsection
