@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <style>
        .btn-group-sm>.btn,
        .btn-sm {
            --tblr-btn-line-height: 1.5;
            --tblr-btn-icon-size: .75rem;
            margin-right: 5px;
            font-size: 12px !important;
            margin: 13px 0 10px 5px !important;
        }

        .li-link {
            padding: 10px;
            margin: 4px;
        }

        .btn.disabled,
        .btn:disabled,
        fieldset:disabled .btn {
            border-color: #0000 !important;
        }

        .custom-nav {
            position: absolute;
            right: 5px;
            top: -2px;
        }

        .media-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                            {{ __('Media Library') }}
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="d-flex">
                            <a href="{{ route('user.add.media') }}" class="btn btn-icon btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-photo-up d-lg-none d-inline">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 8h.01" />
                                    <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                                    <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                                    <path d="M19 22v-6" />
                                    <path d="M22 19l-3 -3l-3 3" />
                                </svg>
                                <span class="d-lg-inline d-none">{{ __('Upload') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl" id="mediaIframe">
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

                {{-- Alert message --}}
                <div id="showAlert"></div>

                {{-- Media images --}}
                <div class="row" id="mediaCardsContainer"></div>

                {{-- Empty media --}}
                <div class="row" id="noImagesFound">
                    <div class="col-sm-12 col-lg-12">
                        <div class="container-xl d-flex flex-column justify-content-center">
                            <div class="empty">
                                <div class="empty-img">
                                    <img id="empty" src="{{ asset('img/empty.svg') }}" height="128" alt="">
                                </div>
                                <p class="empty-title">{{ __('No images found') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="card pagination-card" id="showPagination">
                    <div class="card-footer pagination-card-footer d-flex align-items-center" id="paginationLinks">
                        <p class="m-0 text-muted">{{ __('') }} <span id="paginationStartIndex"></span>
                            {{ __('to') }}
                            <span id="paginationEndIndex"></span> {{ __('of') }} <span
                                id="paginationTotalCount"></span> {{ __('results') }}
                        </p>
                        <nav class="custom-nav">
                            <ul class="pagination">
                                <li class="btn btn-sm btn-primary li-link" id="prevPageBtn" onclick="loadPreviousPage()">
                                    {{ __('Previous') }}</li>
                                <li class="btn btn-sm btn-primary li-link" id="nextPageBtn" onclick="loadNextPage()">
                                    {{ __('Next') }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Delete --}}
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div id="delete_status"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary btn-md me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger btn-md" id="delete_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('scripts')
    <script>
        var currentPage = 1;
        var totalPages = 1;

        function loadPreviousPage() {
            "use strict";

            if (currentPage > 1) {
                currentPage--;
                loadMedia(currentPage);
            }
        }

        function loadNextPage() {
            "use strict";

            if (currentPage < totalPages) {
                currentPage++;
                loadMedia(currentPage);
            }
        }

        function loadMedia(page = 1) {
            $.ajax({
                url: '{{ route('user.media') }}',
                method: 'GET',
                data: {
                    page: page
                },
                dataType: 'json',
                success: handleMediaResponse,
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function handleMediaResponse(response) {
            "use strict";

            var mediaData = response.media.data;
            if (mediaData.length > 0) {
                $('#noImagesFound').hide();
                $('#showPagination').removeClass('d-none').addClass('card pagination-card');
                displayMediaCards(mediaData);
                updatePaginationInfo(response.media);
            } else {
                $('#noImagesFound').show();
                $('#showPagination').addClass('d-none');
                $('#mediaCardsContainer').html('');
                updatePaginationInfo(response.media);
            }
        }

        function displayMediaCards(mediaData) {
            "use strict";

            var mediaCardsHtml = '';
            mediaData.forEach(function(media) {
                mediaCardsHtml += `
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="${media.base_url}${media.media_url}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${media.media_name}">
                        <div class="card-body">
                            <h5 class="card-title media-name">${media.media_name}</h5>
                            <p class="card-text"><small>{{ __('Upload on:') }} ${media.formatted_created_at}</small></p>
                            <a class="btn btn-icon btn-primary btn-md copyBoard" data-clipboard-text="${media.media_url}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Copy') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="8" y="8" width="12" height="12" rx="2"></rect>
                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                </svg>
                            </a>
                            <button onclick="deleteMedia('${media.media_id}')" class="btn btn-icon btn-md btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            });
            $('#mediaCardsContainer').html(mediaCardsHtml);
        }

        function updatePaginationInfo(media) {
            "use strict";

            $('#paginationStartIndex').text(media.from);
            $('#paginationEndIndex').text(media.to);
            $('#paginationTotalCount').text(media.total);
            currentPage = media.current_page;
            totalPages = media.last_page;

            $('#prevPageBtn').prop('disabled', currentPage <= 1);
            $('#nextPageBtn').prop('disabled', currentPage >= totalPages);
        }

        $(document).ready(function() {
            "use strict";

            loadMedia(); // Initial load
        });
    </script>

    {{-- Clipboard --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            var clipboard = new ClipboardJS('.copyBoard');

            clipboard.on('success', function(e) {
                "use strict";

                showSuccessAlert('{{ __('Image link copied: ') }}<strong>' + e.text + '</strong>');
            });

            clipboard.on('error', function(e) {
                "use strict";

                showErrorAlert('{{ __('Failed to copy text to clipboard. Please try again.') }}');
            });

            function showSuccessAlert(message) {
                "use strict";

                showAlert(message, 'success');
            }

            function showErrorAlert(message) {
                "use strict";

                showAlert(message, 'danger');
            }

            function showAlert(message, type) {
                "use strict";

                var alertDiv = document.createElement('div');
                alertDiv.classList.add('alert', 'alert-important', 'alert-' + type, 'alert-dismissible');
                alertDiv.setAttribute('role', 'alert');

                var innerContent = '<div class="d-flex">' +
                    '<div>' +
                    message +
                    '</div>' +
                    '</div>' +
                    '<a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>';

                alertDiv.innerHTML = innerContent;
                document.querySelector('#showAlert').appendChild(alertDiv);

                setTimeout(function() {
                    "use strict";

                    alertDiv.remove();
                }, 3000);
            }
        });
    </script>
    <script>
        // Delete media
        function deleteMedia(id) {
            $("#delete-modal").modal("show");
            document.getElementById("delete_status").innerHTML = "Do you want to remove this file?";
            var delete_link = document.getElementById("delete_id");
            delete_link.getAttribute("href");
            delete_link.setAttribute("href", "{{ route('user.media.delete') }}?id=" + id);
        }
    </script>
@endsection
@endsection
