@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
    <style>
        #canvasContainer {
            max-width: 100%;
            /* Set maximum width to prevent overflow */
            overflow: hidden;
            /* Hide overflow content */
            width: 100%;
            /* Set width to fill its container */
            height: auto;
            /* Allow height to adjust proportionally */
        }

        #qrCanvas {
            width: 100%;
            /* Set canvas width to fill its container */
            height: 100%;
            /* Set canvas height to fill its container */
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('QR Maker') }}
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

                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <div class="card">
                            <div class="card-body mb-5">
                                <div class="row">
                                    <div class="mb-3 qrtext col-lg-12">
                                        <label class="form-label">{{ __('QR Text') }}</label>
                                        <input type="text" id="qrtext" onkeyup="updateQr()" class="form-control"
                                            name="example-text-input" placeholder="{{ __('Input placeholder') }}">
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="logo" class="form-label">{{ __('Choose Logo') }}</label>
                                        <input type="file" class="form-control" id="logo" name="logo"
                                            onchange="updateQr()" accept="image/*">
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label for="logoSize" class="form-label">{{ __('Logo Size') }}</label>
                                        <select class="form-control logoSize" id="logoSize" onchange="updateQr()">
                                            <option value="extra-small">Extra Small</option>
                                            <option value="small">Small</option>
                                            <option value="medium">Medium</option>
                                            <option value="large">Large</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 textColor col-lg-6">
                                        <label class="form-label">{{ __('Text Color') }}</label>
                                        <input type="color" id="textColor" oninput="updateQr()"
                                            class="form-control form-control-color" value="#000000"
                                            title="{{ __('Choose your color') }}">
                                    </div>

                                    <div class="mb-3 bg col-lg-6">
                                        <label class="form-label">{{ __('Background Color') }}</label>
                                        <input type="color" id="bg" oninput="updateQr()"
                                            class="form-control form-control-color" value="#ffffff"
                                            title="{{ __('Choose your color') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{ __('QR Image') }}</h3>
                                <div class="col-lg-12 align-items-center text-center">
                                    <div id="canvasContainer">
                                        <canvas id="qrCanvas"></canvas>
                                    </div>
                                    <button id="download" class="btn mt-3 btn-primary">{{ __('Download QR') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Custom JS --}}
    @push('custom-js')
        <script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
        <script src="{{ url('js/qrious.min.js') }}"></script>
        <script>
            "use strict";

            function updateQr() {
                var qrParams = {
                    text: $("#qrtext").val(),
                    fontcolor: $("#textColor").val(),
                    background: $("#bg").val()
                };

                var qr = new QRious({
                    element: document.getElementById('qrCanvas'),
                    value: qrParams.text,
                    level: 'H',
                    size: 500,
                    foreground: qrParams.fontcolor,
                    background: qrParams.background
                });

                // Load and overlay the logo onto the QR code canvas
                var logo = document.getElementById('logo');
                if (logo.files && logo.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.src = e.target.result;
                        img.onload = function() {
                            var canvas = document.getElementById('qrCanvas');
                            var ctx = canvas.getContext('2d');
                            var logoSize = getLogoSize();
                            var logoX = (canvas.width - logoSize) / 2;
                            var logoY = (canvas.height - logoSize) / 2;
                            ctx.drawImage(img, logoX, logoY, logoSize, logoSize);
                        };
                    };
                    reader.readAsDataURL(logo.files[0]);
                }

                $("#download").show();
            }

            function getLogoSize() {
                var canvas = document.getElementById('qrCanvas');
                var logoSize = 0;
                var selectedSize = $("#logoSize").val();
                switch (selectedSize) {
                    case "extra-small":
                        logoSize = canvas.width * 0.1; // Adjust multiplier as needed
                        break;
                    case "small":
                        logoSize = canvas.width * 0.2; // Adjust multiplier as needed
                        break;
                    case "medium":
                        logoSize = canvas.width * 0.3; // Adjust multiplier as needed
                        break;
                    case "large":
                        logoSize = canvas.width * 0.4; // Adjust multiplier as needed
                        break;
                    default:
                        logoSize = canvas.width * 0.2; // Default to small size
                        break;
                }
                return logoSize;
            }

            // Function to download the QR code
            function downloadQr() {
                var canvas = document.getElementById("qrCanvas");
                var dataURL = canvas.toDataURL("image/png");
                var a = document.createElement("a");
                a.href = dataURL;
                a.download = "qr_code.png";
                a.click();
            }

            // Call updateQr function when page loads and when input changes
            $(document).ready(updateQr);
            $("#mode, #font, #qrtext, #textColor, #fill, #bg").on("change input", updateQr);

            // Call downloadQr function when download button is clicked
            $("#download").on("click", downloadQr);
        </script>
        
        <script>
            // Array of element selectors
            var elementSelectors = ['.logoSize'];
        
            // Function to initialize TomSelect and enforce the "required" attribute
            function initializeTomSelectWithRequired(el) {
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

            // Loop through each element ID
            elementSelectors.forEach(function(id) {
                // Check if the element exists
                var elements = document.querySelectorAll(id);
                if (elements) {
                    // Apply TomSelect and enforce the "required" attribute
                    elements.forEach(function(el) {
                        initializeTomSelectWithRequired(el);
                    });
                }
            });
        </script>
    @endpush
@endsection
