@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('css')
<link href="{{ asset('css/dropzone.min.css')}}" rel="stylesheet">
<script src="{{ asset('js/dropzone.min.js')}}"></script>
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
                        {{ __('Products') }}
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
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('user.save.products', Request::segment(3)) }}" method="post"
                        enctype="multipart/form-data" class="card">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div id="more-products" class="row"></div>

                                    <div class="col-lg-12">
                                        <button type="button" onclick="addProduct()" class="btn btn-primary">
                                            {{ __('Add One More Product') }}
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
                <h5 class="modal-title">{{ __('Media Library')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row row-cards" id="captions">
                    {{-- Upload multiple images --}}
                    @include('user.pages.edit-store.media.upload')

                    {{-- Upload multiple images --}}
                    @include('user.pages.edit-store.media.list')

                    {{-- Pagination --}}
                    <div id="pagination"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Select') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@push('custom-js')
<script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
<script>
    var count = 0;
    var currentSelection = 0;
    function addProduct() {
	"use strict";
    if (count>={{ $plan_details->no_of_store_products }}) {
        new swal({
            title: `{{ __('Oops!') }}`,
            icon: 'warning',
            text: `{{ __('You have reached your current plan limit.') }}`,
            timer: 2000,
            buttons: false,
            showConfirmButton: false,
        });
    }
    else {
        count++;
        var id = getRandomInt();
        var products = `<div class='row' id=`+ id +`>
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required' for='product_status'>{{ __('Categories') }}</label>
                                    <select name='categories[]' id='categories' class='form-control categories' required>
                                        @foreach ($categories as $category)
                                        <option value='{{ $category->category_id }}'>{{ __($category->category_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required'>{{ __('Product Badge') }}</label>
                                    <input type='text' class='form-control' name='badge[]'placeholder='{{ __('Product Badge') }}...' required>
                                </div>
                            </div>
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required'>{{ __('Product Image') }} <span class='text-muted'>({{ __('Recommended : 500 x 500 pixels') }})</span></label>
                                    <div class='input-group mb-2'>
                                        <input type='text' class='image`+ id +` media-model form-control' name='product_image[]' placeholder='{{ __('Product Image') }}' required>
                                        <button class='btn btn-primary btn-md' type='button' onclick='openMedia(`+ id +`)'>{{ __('Choose image') }}</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'> 
                                    <label class='form-label required'>{{ __('Product Name') }}</label> 
                                    <input type='text' class='form-control' name='product_name[]' placeholder='{{ __('Product Name') }}' required> 
                                </div>
                            </div>
                            
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required'>{{ __('Product Sub Title') }}</label>
                                    <textarea class='form-control' name='product_subtitle[]' data-bs-toggle='autosize' placeholder='{{ __('Product Sub Title') }}...' required></textarea>
                                </div>
                            </div>
                            
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required'>{{ __('Regular Price') }}</label>
                                    <input type='number' class='form-control' name='regular_price[]' placeholder='{{ __('Regular Price') }}...' min='1' step='.001' required>
                                </div>
                            </div>
                            
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required'>{{ __('Sales Price') }}</label>
                                    <input type='number' class='form-control' name='sales_price[]' min='1' step='.001' placeholder='{{ __('Sales Price') }}...' required>
                                </div>
                            </div>
                            
                            <div class='col-md-6 col-xl-6'>
                                <div class='mb-3'>
                                    <label class='form-label required' for='product_status'>{{ __('Status') }}</label>
                                    <select name='product_status[]' id='product_status' class='form-control product_status' required>
                                        <option value='instock'>{{ __('In Stock') }}</option>
                                        <option value='outstock'>{{ __('Out of Stock') }}</option>
                                    </select>
                                    <a href='#' class='btn mt-3 btn-danger btn-sm' onclick='removeProduct(`+id+`)'>{{ __('Remove') }}</a>
                                </div>
                            </div>
                        </div> 
                        <br>`;
        $("#more-products").append(products).html();
    }
    }

    // Remove 
    function removeProduct(id) {
	"use strict";
        $("#"+id).remove();
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

        loadMedia(); // Initial load

        // Array to store selected media IDs
        var selectedMediaIds = [];

        // Handle individual select
        $(document).on('change', '.select-media', function() {
            var mediaId = $(this).data('id');
            if (this.checked) {
                $(`.media-card[data-id="${mediaId}"]`).addClass('selected');
                selectedMediaIds.push(mediaId);
            } else {
                $(`.media-card[data-id="${mediaId}"]`).removeClass('selected');
                $('#selectAllMedia').prop('checked', false);
                // Remove mediaId from the array
                selectedMediaIds = selectedMediaIds.filter(id => id !== mediaId);
            }

            console.log(selectedMediaIds);
            // Set in image url to input field
            $('.image'+currentSelection).val(selectedMediaIds);
        });
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

    function displayMediaCards(mediaData) {
        "use strict";

        // Generate media image
        var mediaCardsHtml = '';
        mediaData.forEach(function(media) {
            mediaCardsHtml += `
                <div class="col-3 col-sm-3">
                    <label class="form-imagecheck mb-2 media-card" data-id="${media.id}">
                        <input name="form-imagecheck" type="checkbox" value="3" class="form-imagecheck-input select-media" id="select-media-${media.media_url}" data-id="${media.media_url}">
                            <span class="form-imagecheck-figure">
                            <img src="${media.base_url}${media.media_url}" alt="${media.media_name}" class="form-imagecheck-image" style="height: 200px; object-fit: cover;">
                        </span>
                    </label>
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
<script>
    // Array of element selectors
    var elementSelectors = ['.categories', '.product_status'];

    // Function to initialize TomSelect on an element
    function initializeTomSelect(el) {
        new TomSelect(el, {
            copyClassesToDropdown: false,
            dropdownClass: 'dropdown-menu ts-dropdown',
            optionClass: 'dropdown-item',
            controlInput: '<input>',
            maxOptions: null,
            render: {
                item: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
        });

        // Ensure the "required" attribute is enforced
        el.addEventListener('change', function() {
            if (el.value) {
                el.setCustomValidity('');
            } else {
                el.setCustomValidity('This field is required');
            }
        });
    
        // Trigger validation on load
        el.dispatchEvent(new Event('change'));
    }

    // Initialize TomSelect on existing elements
    elementSelectors.forEach(function(selector) {
        var elements = document.querySelectorAll(selector);
        elements.forEach(function(el) {
            initializeTomSelect(el);
        });
    });

    // Observe the document for dynamically added elements
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Ensure it's an element node
                    elementSelectors.forEach(function(selector) {
                        if (node.matches(selector)) {
                            initializeTomSelect(node);
                        }
                        // Also check if new nodes have children that match
                        var childElements = node.querySelectorAll(selector);
                        childElements.forEach(function(childEl) {
                            initializeTomSelect(childEl);
                        });
                    });
                }
            });
        });
    });

    // Configure the observer
    observer.observe(document.body, { childList: true, subtree: true });
</script>
@endpush
@endsection