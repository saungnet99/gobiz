@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
<link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">
<style>
    .section-theme {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .theme-image {
        width: 100% !important;
        /* height: 90% !important; */
        border-radius: 32px;
        margin-bottom: 1rem;
    }
    .border-curve{
        border-radius: 16px;
    }

    .btn-choose-theme {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .avatar {
        --tblr-avatar-bg: #f6f8fb00;
    }

    .avatar-xl {
        --tblr-avatar-size: 10rem !important;
    }
</style>
@endsection

@php
$defaultImage = "";

foreach ($themes as $theme) {
    if ($theme->theme_id == $business_card->theme_id) {
        $defaultImage = url("img/vCards/".$theme->theme_thumbnail);
    }
}
@endphp

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
                        {{ __('Edit WhatsApp Store') }}
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
                    <form action="{{ route('user.update.store', Request::segment(3)) }}" method="post"
                        enctype="multipart/form-data" class="card">
                        @csrf
                        {{-- Create Card --}}
                        <div class="card-body">
                            <div class="row">

                            {{-- Themes --}}
                            <div class="col-md-4 col-xl-4 mb-5">
                                <img src="{{ $defaultImage }}" class="object-contain theme-image"
                                data-bs-toggle="modal"
                                data-bs-target="#themeModal"
                                alt="">

                                <a href="#" class="btn btn-primary btn-choose-theme"
                                    data-bs-toggle="modal" data-bs-target="#themeModal">
                                    {{ __('Choose a theme') }}
                                </a>
                            </div>

                                {{-- Card details --}}
                                <div class="col-md-8 col-xl-8">
                                    <div class="row">
                                        <input type="hidden" class="form-control" name="theme_id" id="choosedTheme"
                                            value="{{ $business_card->theme_id }}">
                                            
                                        {{-- Language --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required" for="card_lang">{{ __('Language') }}</label>
                                                <select name="card_lang" id="card_lang" class="form-control card_lang" required>
                                                    @foreach(config('app.languages') as $langLocale => $langName)
                                                    <option class="dropdown-item" value="{{ $langLocale }}" {{
                                                        strtolower($business_card->card_lang) == $langLocale ?
                                                        'selected' : '' }}>
                                                        {{ $langName }} ({{ strtoupper($langLocale) }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Profile image and banner images preview --}}
                                        <div class="col-md-12 col-xl-12 d-none" id="previewSection">
                                            <div class="row mb-3">
                                                <div class="col-md-6 col-xl-6 mb-3" id="previewCoverContainer"></div>
                                                <div class="col-md-6 col-xl-6" id="logoPreview"></div>
                                            </div>
                                        </div>

                                        {{-- Banners --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Banner') }}</div>
                                                <input type="file" class="form-control" id="coverInput"
                                                    placeholder="{{ __('Banner') }}"
                                                    value="{{ $business_card->cover }}"
                                                    accept=".jpeg,.jpg,.png,.gif,.svg" />
                                                <input type="hidden" class="form-control" name="banner">
                                                <small class="fw-bold"><span class="text-danger">*</span> {{ __('Upload banner images one after the other') }}</small>
                                            </div>
                                        </div>

                                        {{-- Logo --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Logo') }}</div>
                                                <input type="file" class="form-control" id="logo"
                                                    placeholder="{{ __('Logo') }}" value="{{ $business_card->logo }}"
                                                    accept=".jpeg,.jpg,.png,.gif,.svg" />
                                                <input type="hidden" class="form-control" name="logo">
                                            </div>
                                        </div>

                                        {{-- Store name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Store name') }}</label>
                                                <input type="text" class="form-control" name="title"
                                                    onload="convertToLink(this.value); checkLink()"
                                                    onkeyup="convertToLink(this.value); checkLink()"
                                                    placeholder="{{ __('Store name') }}"
                                                    value="{{ $business_card->title }}" required>
                                            </div>
                                        </div>

                                        {{-- Personalized Link --}}
                                        @if ($plan_details->personalized_link)
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Personalized Link') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{ URL::to('/') }}
                                                    </span>
                                                    <input type="text" class="form-control" name="link"
                                                        placeholder="{{ __('Personalized Link') }}" autocomplete="off"
                                                        id="plink" onkeyup="checkLink()" minlength="3"
                                                        value="{{ $business_card->card_url }}" required>
                                                </div>
                                                <p id="status"></p>
                                            </div>
                                        </div>
                                        @endif

                                        {{-- Store greeting --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Store greeting') }}</label>
                                                <input type="text" class="form-control" name="subtitle"
                                                    placeholder="{{ __('Store greeting') }}"
                                                    value="{{ $business_card->sub_title }}" required>
                                            </div>
                                        </div>

                                        {{-- Currency --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required" for="currency">{{ __('Currency') }}</label>
                                                <select name="currency" id="currency" class="form-control currency">
                                                    @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->iso_code }}" {{ $store_details->
                                                        currency
                                                        == $currency->iso_code || $store_details->currency ==
                                                        $currency->symbol ? 'selected' : '' }}>
                                                        {{ $currency->name }} ({{ $currency->symbol }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Country Code --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Country Code') }}</label>
                                                @include('user.pages.edit-store.include.country-code')
                                            </div>
                                        </div>

                                        {{-- WhatsApp Number --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('WhatsApp Number') }}</label>
                                                @include('user.pages.edit-store.include.whatsapp-no')
                                            </div>
                                        </div>

                                        {{-- WhatsApp Footer Text --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('WhatsApp Footer Text') }}</label>
                                                <textarea class="form-control" name="whatsapp_msg" id="whatsapp_msg"
                                                    cols="10" rows="3"
                                                    placeholder="{{ __('Thanks note') }}">{{ $store_details->whatsapp_msg }}</textarea>
                                            </div>
                                        </div>
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

{{-- Choose a theme modal --}}
<div class="modal modal-blur fade" id="themeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-flat">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="{{ __('Search') }}">
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <div class="row" id="results">
                    @foreach ($themes as $theme)
                        <div class="col-lg-3 col-sm-3 col-md-3 col-6">
                            <label class="form-imagecheck mb-2">
                                <input type="radio" id="theme_id" value="{{ $theme->theme_id }}"
                                    onclick="chooseTheme(this, `{{ asset('img/vCards/' . $theme->theme_thumbnail) }}`)"
                                    class="form-imagecheck-input theme_id"
                                    {{ $theme->theme_id == $business_card->theme_id ? 'checked' : '' }} required />
                                <span class="text-center font-weight-bold">
                                    <img src="{{ asset('img/vCards/' . $theme->theme_thumbnail) }}"
                                        class="object-cover border-curve" alt="{{ __($theme->theme_name) }}">
                                        <div class="text-center m-1">
                                            <p class="badge bg-primary text-white ">{{ __($theme->theme_name) }}</p>
                                        </div> 
                                </span>
                            </label>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Profile Image Cropping -->
<div id="cropModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Crop Image') }}</h4>
            </div>
            <div class="modal-body">
                <div class="cropper-container" style="width: 100%;">
                    <img id="croppedImage" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="crop">{{ __('Crop') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Crop Cover Image Modal -->
<div id="cropCoverModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Crop Cover Image') }}</h4>
            </div>
            <div class="modal-body">
                <div class="cropper-container" style="width: 100%;">
                    <img id="croppedCoverImage" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="coverCrop">{{ __('Crop') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@push('custom-js')
<script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
<script src="{{ asset('js/cropper.min.js') }}"></script>
 {{-- Profile image cropping --}}
 <script>
    $(document).ready(function () {
        var cropper;
        var uploadedImageURL;

        // Initialize cropper when modal is shown
        $('#cropModal').on('shown.bs.modal', function () {
            cropper = new Cropper(document.getElementById('croppedImage'), {
                aspectRatio: 3, // Aspect ratio of 1:1
                viewMode: 1, // Set view mode to 3 (restrict the crop box to fit within the container, then scale the result image to fit exactly 512x512 pixels)
                autoCropArea: 1, // Auto crop the entire image
                cropBoxResizable: false, // Disable crop box resizing
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
        });

        // Handle image upload
        $('#logo').change(function (e) {
            var files = e.target.files;
            var reader = new FileReader();

            reader.onload = function (event) {
                uploadedImageURL = event.target.result;
                $('#croppedImage').attr('src', uploadedImageURL);
                $('#cropModal').modal('show');
            };

            reader.readAsDataURL(files[0]);
        });

        // Handle crop button click
        $('#crop').click(function () {
            var canvas = cropper.getCroppedCanvas({
                width: 512,
                height: 512,
            });

            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob);

                // Include CSRF token in the AJAX request
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route("user.vcard.cropped.image") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Optionally, close the modal after successful upload
                        $('#cropModal').modal('hide');

                        // Set the imageUrl in the #logo input field
                        $('input[name="logo"]').val(response.imageUrl);
                    },
                    error: function () {
                        console.log('Upload error');
                    }
                });
            });

            // Display cropped image preview in #logoPreview
            var croppedImageURL = cropper.getCroppedCanvas().toDataURL();
            var previewSection = $('#previewSection').removeClass('d-none');
            var previewLogoContainer = $('#logoPreview');
            var previewHtml = '<div class="col-md-4"><img src="' + croppedImageURL + '" class="img-fluid rounded"></div>';
            previewLogoContainer.append(previewHtml);
        });
    });
</script>
<script>
$(document).ready(function () {
    var cropper;
    var uploadedImageURL;
    var currentFileIndex;
    var files = [];
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#coverInput').change(function (e) {
        files = e.target.files;
        if (files.length > 0) {
            currentFileIndex = 0;
            loadNextImage();
        }
    });

    function loadNextImage() {
        if (currentFileIndex < files.length) {
            var reader = new FileReader();
            reader.onload = function (event) {
                uploadedImageURL = event.target.result;
                $('#croppedCoverImage').attr('src', uploadedImageURL);
                $('#cropCoverModal').modal('show');
            };
            reader.readAsDataURL(files[currentFileIndex]);
        }
    }

    $('#cropCoverModal').on('shown.bs.modal', function () {
        cropper = new Cropper(document.getElementById('croppedCoverImage'), {
            aspectRatio: 1288 / 408,
            viewMode: 3,
            autoCropArea: 1,
            cropBoxResizable: false,
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
    });

    $('#coverCrop').click(function () {
        var canvas = cropper.getCroppedCanvas({
            width: 1288,
            height: 408,
        });

        canvas.toBlob(function (coverBlob) {
            var formData = new FormData();
            var imageName = 'cropped_image_' + currentFileIndex + '.png';
            formData.append('croppedImage', coverBlob, imageName);

            $.ajax({
                url: '{{ route("user.store.cropped.images") }}', // Ensure this route is defined in your web.php
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    addPreview(response.imageUrl);
                    currentFileIndex++;
                    if (currentFileIndex < files.length) {
                        loadNextImage();
                    } else {
                        $('#cropCoverModal').modal('hide');
                    }
                },
                error: function () {
                    console.log('Upload error');
                }
            });
        });
    });

    var pushedCoverImages = [];
    function addPreview(imageUrl) {
        var previewCoverContainer = $('#previewCoverContainer');
        var previewSection = $('#previewSection').removeClass('d-none');
        var previewHtml = '<div class="col-md-6"><img src="' + imageUrl + '" class="img-fluid rounded"></div>';
        previewCoverContainer.append(previewHtml);
        // Set the imageUrl in the #banner input field
        pushedCoverImages.push(imageUrl);
        $('input[name="banner"]').val(pushedCoverImages);
    }
});
</script>
<script>
    function checkLink(){
    "use strict";
    var plink = $('#plink').val();

    if(plink.length > 2){
        $.ajax({
        url: "{{ route('user.check.link') }}",
        method: 'POST',
        data:{_token: "{{ csrf_token() }}", link: plink},
        }).done(function(res) {
            if(res.status == 'success') {
                $('#status').html("<span class='badge mt-2 bg-green text-white'>{{ __('Available') }}</span>");
            }else{
                $('#status').html("<span class='badge mt-2 bg-red text-white'>{{ __('Not available') }}</span>");
            }
        });
    }else{
        $('#status').html("");
    }
}

$(document).ready(function() {
   $(".modal").on("hidden.bs.modal", function() {
    $(".theme_id").prop('checked', false);
   });
});


function chooseTheme(selectedTheme, thumbnail) {

    $("input[name='theme_id']").val(selectedTheme.value);

    $(".theme-image").attr("src", thumbnail);

    $("#themeModal").modal("hide");

    $(".submitBtn").attr("class", "btn btn-primary submitBtn");
}


/* Encode string to link */
function convertToLink( str ) {
    "use strict";
    //replace all special characters | symbols with a space
    str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
             .toLowerCase();

    // trim spaces at start and end of string
    str = str.replace(/^\s+|\s+$/gm,'');

    // replace space with dash/hyphen
    str = str.replace(/\s+/g, '-');
    document.getElementById("plink").value = str;
    //return str;
}

var APP_URL = '{{ config('app.url') }}';

$(document).ready(function() {
"use strict";

$('#searchInput').on('keyup', function() {
    "use strict";

    let query = $(this).val();
    let type = 'WhatsApp Store';

    $.ajax({
        url: '{{ route('user.search.theme') }}',
        type: 'GET',
        data: {
            query: query, type: type
        },
        dataType: 'json',
        success: function(response) {
            let output = '';
            if (response.length === 0) {
                output = '<div class="alert alert-warning">{{ __("No themes found.") }}</div>';
            } else {
                $.each(response, function(index, card) {
                        output += `<div class="col-lg-3 col-sm-3 col-md-3 col-6">
                                        <label class="form-imagecheck mb-2">
                                            <input type="radio" id="theme_id" value="${card.theme_id}" onclick="chooseTheme(this, '${APP_URL}/img/vCards/${card.theme_thumbnail}')" class="form-imagecheck-input theme_id" required />
                                            <span class="form-imagecheck-figure text-center font-weight-bold">
                                                <img src="${APP_URL}/img/vCards/${card.theme_thumbnail}"
                                                    class="object-cover" alt="${card.theme_name}">
                                            </span>
                                        </label>
                                        <div class="text-center">
                                            <h2 class="badge bg-primary text-white mt-2">${card.theme_name}</h2>
                                        </div>
                                    </div>`;
                    });
                }
                $('#results').html(output);
            }
        });
    });
});
</script>
<script>
    // Array of element selectors
    var elementSelectors = ['.card_lang', '.country_code', '.currency'];

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
