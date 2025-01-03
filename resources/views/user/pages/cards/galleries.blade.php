@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
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
                            {{ __('Galleries') }}
                        </h2>
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
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('user.save.galleries', Request::segment(3)) }}" method="post"
                            enctype="multipart/form-data" class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div id="gallery" class="row">
                                            <div id="more-gallery" class="row mt-3"></div>

                                            <div class="col-lg-12">
                                                <button type="button" onclick="addGallery()" class="btn btn-primary">
                                                    {{ __('Add One More Gallery') }}
                                                </button>
                                            </div>
                                            <div class="col-md-4 col-xl-4 my-3">
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-primary">
                                                        {{ __('Submit') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Media Library --}}
    <div class="modal modal-blur fade" id="openMediaModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Media Library') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row row-cards" id="captions">
                        {{-- Upload multiple images --}}
                        @include('user.pages.cards.media.upload')

                        {{-- Upload multiple images --}}
                        @include('user.pages.cards.media.list')

                        {{-- Pagination --}}
                        <div id="pagination"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
    @push('custom-js')
        <script>
            var count = 0;
            var currentSelection = 0;

            function addGallery() {
                "use strict";
                if (count >= {{ $plan_details->no_of_galleries }}) {
                    new swal({
                        title: `{{ __('Oops!') }}`,
                        icon: 'warning',
                        text: `{{ __('You have reached your current plan limit.') }}`,
                        timer: 2000,
                        buttons: false,
                        showConfirmButton: false,
                    });
                } else {
                    count++;
                    var id = getRandomInt();

                    var gallery = "<div class='row' id='" + id + "'>" +
                        "<div class='col-md-6 col-xl-6'>" +
                            "<div class='mb-3'>" +
                                "<label class='form-label required'>" + 
                                    "{{ __('Gallery Image') }} <span class='text-muted'>({{ __('Recommended : 200 x 200 pixels') }})</span>" + 
                                "</label>" +
                                "<div class='input-group mb-2'>" +
                                    "<input type='text' class='image" + id + " media-model form-control' name='gallery_image[]' placeholder='{{ __('Gallery Image') }}' required>" +
                                    "<button class='btn btn-primary btn-md' type='button' onclick='openMedia(" + id + ")'>" +
                                        "{{ __('Choose image') }}" +
                                    "</button>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                        "<div class='col-md-6 col-xl-6'>" +
                            "<div class='mb-3'>" +
                                "<label class='form-label required'>" +
                                    "{{ __('Image Caption') }}" +
                                "</label>" +
                                "<input type='text' class='form-control' name='caption[]' placeholder='{{ __('Image Caption') }}...' required>" +
                                "<a href='#' class='btn mt-3 btn-danger btn-sm' onclick='removeGallery(" + id + ")'>" +
                                    "{{ __('Remove') }}" +
                                "</a>" +
                            "</div>" +
                            "<br>" +
                        "</div>" +
                    "</div>";
                    $("#more-gallery").append(gallery).html();
                }
            }

            // Remove gallery
            function removeGallery(id) {
                "use strict";
                $("#" + id).remove();
            }

            // Generate random number
            function getRandomInt() {
                min = Math.ceil(0);
                max = Math.floor(9999999999);
                return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
            }

            // Open Media modal
            function openMedia(id) {
                "use strict";
                currentSelection = id;
                $('#openMediaModel').modal('show');
            }
        </script>
        {{-- Upload image in dropzone --}}
        <script type="text/javascript">
            Dropzone.options.dropzone = {
                maxFilesize: {{ env('SIZE_LIMIT') / 1024 }},
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                init: function() {
                    this.on("success", function(file, response) {
                        loadMedia();
                    });
                }
            };
        </script>

        {{-- Media with pagination --}}
        <script>
            // Default values
            var currentPage = 1;
            var totalPages = 1;

            // Previous image
            function loadPreviousPage() {
                "use strict";

                if (currentPage > 1) {
                    currentPage--;
                    loadMedia(currentPage);
                }
            }

            // Next page
            function loadNextPage() {
                "use strict";

                if (currentPage < totalPages) {
                    currentPage++;
                    loadMedia(currentPage);
                }
            }

            // Load media images
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

            // Media response
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

            // Display media images in card type
            function displayMediaCards(mediaData) {
                "use strict";

                // Generate media image
                var mediaCardsHtml = '';
                mediaData.forEach(function(media) {
                    mediaCardsHtml += `
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="${media.base_url}${media.media_url}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${media.media_name}">
                        <div class="card-body">
                            <h5 class="card-title media-name">${media.media_name}</h5>
                            <a class="btn btn-icon btn-primary btn-md copyBoard" data-clipboard-text="${media.media_url}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Copy') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <rect x="8" y="8" width="12" height="12" rx="2"></rect>
                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            `;
                });
                $('#mediaCardsContainer').html(mediaCardsHtml);
            }

            // Update pagination
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

            // Load more image in pagination
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

                // Success
                clipboard.on('success', function(e) {
                    "use strict";

                    // Place value in the field
                    $('.image' + currentSelection).val(e.text);

                    // Hide media modal
                    $('#openMediaModel').modal('hide');
                });

                // Error
                clipboard.on('error', function(e) {
                    "use strict";

                    showErrorAlert('{{ __('Failed to copy text to clipboard. Please try again.') }}');
                });

                // Show success message
                function showSuccessAlert(message) {
                    "use strict";

                    showAlert(message, 'success');
                }

                // Show error message
                function showErrorAlert(message) {
                    "use strict";

                    showAlert(message, 'danger');
                }

                // Show alert
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
    @endpush
@endsection
