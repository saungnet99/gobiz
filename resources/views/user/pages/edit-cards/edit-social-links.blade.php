@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('css')
    <link rel="stylesheet" href="{{ asset('css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-iconpicker.min.css') }}" />
    <style>
        .ts-control>input {
            display: contents !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
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
            
                <div class="card">
                    <div class="row g-0">
                        <div class="col-12 col-md-3 border-end">
                            <div class="card-body">
                                <h4 class="subheader">{{ __('Update Business Card') }}</h4>
                                <div class="list-group list-group-transparent">
                                    {{-- Nav links --}}
                                    @include('user.pages.edit-cards.includes.nav-link', ['link' => 'links'])
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-9 d-flex flex-column">
                            <form action="{{ route('user.update.social.links', Request::segment(3)) }}" method="post"
                                id="myForm">
                                @csrf
                                <div class="card-body">
                                    <h3 class="card-title mb-4">{{ __('Social Links') }}</h3>

                                    <div>
                                        {{-- Dynamic fields --}}
                                        @for ($i = 0; $i < count($features); $i++)
                                            <div class="row" id="{{ $i }}">
                                                <div class='col-lg-2 col-md-2'>
                                                    <div class='mb-3 mt-2'>
                                                        <label class='form-label required'
                                                            for='type'>{{ __('Display type') }}</label>
                                                        <select class="type{{ $features[$i]->id }} defaultType form-select"
                                                            id="type{{ $features[$i]->id }}" name="type[]"
                                                            onchange='changeLabel({{ $features[$i]->id }})' required>
                                                            <optgroup label="{{ __('Social Links') }}">
                                                                <option value='facebook'
                                                                    {{ $features[$i]->type == 'facebook' ? 'selected' : '' }}>
                                                                    {{ __('Facebook') }}</option>
                                                                <option value='instagram' {{ $features[$i]->type == 'instagram' ? 'selected' : '' }}>{{ __('Instagram') }}</option>
                                                                <option value='x-twitter'
                                                                    {{ $features[$i]->type == 'x-twitter' ? 'selected' : '' }}>
                                                                    {{ __('Twitter') }}</option>
                                                                <option value='linkedin'
                                                                    {{ $features[$i]->type == 'linkedin' ? 'selected' : '' }}>
                                                                    {{ __('LinkedIn') }}</option>
                                                                <option value='pinterest'
                                                                    {{ $features[$i]->type == 'pinterest' ? 'selected' : '' }}>
                                                                    {{ __('Pinterest') }}</option>
                                                                <option value='reddit'
                                                                    {{ $features[$i]->type == 'reddit' ? 'selected' : '' }}>
                                                                    {{ __('Reddit') }}</option>
                                                                <option value='tiktok'
                                                                    {{ $features[$i]->type == 'tiktok' ? 'selected' : '' }}>
                                                                    {{ __('Tiktok') }}</option>
                                                                <option value='threads'
                                                                    {{ $features[$i]->type == 'threads' ? 'selected' : '' }}>
                                                                    {{ __('Threads') }}</option>
                                                                <option value='snapchat'
                                                                    {{ $features[$i]->type == 'snapchat' ? 'selected' : '' }}>
                                                                    {{ __('Snapchat') }}</option>
                                                                <option value='wechat'
                                                                    {{ $features[$i]->type == 'wechat' ? 'selected' : '' }}>
                                                                    {{ __('WeChat') }}</option>
                                                                <option value='telegram'
                                                                    {{ $features[$i]->type == 'telegram' ? 'selected' : '' }}>
                                                                    {{ __('Telegram') }}</option>
                                                                <option value='tumblr'
                                                                    {{ $features[$i]->type == 'tumblr' ? 'selected' : '' }}>
                                                                    {{ __('Tumblr') }}</option>
                                                                <option value='qq'
                                                                    {{ $features[$i]->type == 'qq' ? 'selected' : '' }}>
                                                                    {{ __('QQ') }}</option>
                                                                <option value='discord'
                                                                    {{ $features[$i]->type == 'discord' ? 'selected' : '' }}>
                                                                    {{ __('Discord') }}</option>
                                                                <option value='quora'
                                                                    {{ $features[$i]->type == 'quora' ? 'selected' : '' }}>
                                                                    {{ __('Quora') }}</option>
                                                                <option value='wa'
                                                                    {{ $features[$i]->type == 'wa' ? 'selected' : '' }}>
                                                                    {{ __('WhatsApp') }}</option>
                                                                <option value='address'
                                                                    {{ $features[$i]->type == 'address' ? 'selected' : '' }}>
                                                                    {{ __('Address') }}</option>
                                                                <option value='email'
                                                                    {{ $features[$i]->type == 'email' ? 'selected' : '' }}>
                                                                    {{ __('Email') }}</option>
                                                                <option value='tel'
                                                                    {{ $features[$i]->type == 'tel' ? 'selected' : '' }}>
                                                                    {{ __('Phone') }}</option>
                                                                <option value='url'
                                                                    {{ $features[$i]->type == 'url' ? 'selected' : '' }}>
                                                                    {{ __('Link') }}</option>
                                                                <option value='text'
                                                                    {{ $features[$i]->type == 'text' || $features[$i]->type == 'textarea' ? 'selected' : '' }}>
                                                                    {{ __('Custom Text') }}</option>
                                                            </optgroup>
                                                            <optgroup label="{{ __('Sections') }}">
                                                                <option value='map'
                                                                    {{ $features[$i]->type == 'map' ? 'selected' : '' }}>
                                                                    {{ __('Google Map') }}</option>
                                                                <option value='youtube'
                                                                    {{ $features[$i]->type == 'youtube' ? 'selected' : '' }}>
                                                                    {{ __('Youtube') }}</option>
                                                                <option value='iframe'
                                                                    {{ $features[$i]->type == 'iframe' ? 'selected' : '' }}>
                                                                    {{ __('iframe') }}</option>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class='col-lg-1 col-md-1'>
                                                    <div class='mb-3 mt-2'>
                                                        <label class='form-label required'>{{ __('Icon') }}</label>
                                                        <button type="button" id='iconpick{{ $features[$i]->id }}'
                                                            onclick='openPicker({{ $features[$i]->id }})'
                                                            class="btn btn-primary btn-icon text-white btn-md"><i
                                                                id="displayIcon{{ $features[$i]->id }}"
                                                                class="{{ $features[$i]->icon }}"></i></button>
                                                        <input type='hidden'
                                                            class='icon{{ $features[$i]->id }} form-control'
                                                            value="{{ $features[$i]->icon }}"
                                                            placeholder='{{ __('Choose Icon') }}' name='icon[]' required
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class='col-lg-3 col-md-3'>
                                                    <div class='mb-3 mt-2'>
                                                        <label class='form-label required'>{{ __('Label') }}</label>
                                                        <input type='text'
                                                            class='lbl{{ $features[$i]->id }} form-control' name='label[]'
                                                            placeholder='{{ __(' Label') }}'
                                                            value="{{ $features[$i]->label }}" required>
                                                    </div>
                                                </div>
                                                <div class='col-lg-4 col-md-4'>
                                                    <div class='mb-3 mt-2'>
                                                        <label class='form-label required'>{{ __('Content') }}</label>
                                                        <input type="{{ $features[$i]->type == 'iframe' ? 'url' : 'text' }}"
                                                            class='textlbl{{ $features[$i]->id }} form-control'
                                                            name='value[]' placeholder='{{ __(' Content') }}'
                                                            value="{{ $features[$i]->content }}" required>
                                                    </div>
                                                </div>
                                                <div class='col-lg-2 col-md-2'>
                                                    <div class='mb-3 pt-1 mt-5'>
                                                        <button type="button" class='btn btn-danger btn-sm'
                                                            onclick='removeFeature({{ $i }})'>
                                                            <i class='fa fa-times text-white'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor

                                        {{-- Add new social links --}}
                                        <div id="more-features"></div>

                                        {{-- Add button --}}
                                        <div class="col-lg-12 mb-5">
                                            <button type="button" onclick="addFeature()" class="btn btn-primary">
                                                {{ __('Add One More Features') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent mt-auto">
                                    <div class="btn-list justify-content-end">
                                        <a href="{{ route('user.cards') }}" class="btn">
                                            {{ __('Cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    @push('custom-js')
        <script type="text/javascript" src="{{ asset('js/fontawesome-iconpicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
        <script>
            var count = {{ count($features) }};

            function addFeature() {
                "use strict";
                if (count >= {{ $plan_details->no_of_links }}) {
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
                    var features = `<div class='row' id=` + id + `>
                                <div class='col-lg-2 col-md-2'>
                                    <div class='mb-3 mt-2'>
                                        <label class='form-label required' for='type'>{{ __('Display type') }}</label>
                                        <select class="type` + id + ` form-select" id="type` + id +`" name="type[]" onchange='changeLabel(` + id + `)' required>
                                            <optgroup label="{{ __('Social Links') }}">
                                                <option value='facebook'>{{ __('Facebook') }}</option>
                                                <option value='instagram'>{{ __('Instagram') }}</option>
                                                <option value='x-twitter'>{{ __('Twitter') }}</option>
                                                <option value='linkedin'>{{ __('LinkedIn') }}</option>
                                                <option value='pinterest'>{{ __('Pinterest') }}</option>
                                                <option value='reddit'>{{ __('Reddit') }}</option>
                                                <option value='tiktok'>{{ __('Tiktok') }}</option>
                                                <option value='threads'>{{ __('Threads') }}</option>
                                                <option value='snapchat'>{{ __('Snapchat') }}</option>
                                                <option value='wechat'>{{ __('WeChat') }}</option>
                                                <option value='telegram'>{{ __('Telegram') }}</option>
                                                <option value='tumblr'>{{ __('Tumblr') }}</option>
                                                <option value='qq'>{{ __('QQ') }}</option>
                                                <option value='discord'>{{ __('Discord') }}</option>
                                                <option value='quora'>{{ __('Quora') }}</option>
                                                <option value='wa'>{{ __('WhatsApp') }}</option>
                                                <option value='address'>{{ __('Address') }}</option>
                                                <option value='email'>{{ __('Email') }}</option>
                                                <option value='tel'>{{ __('Phone') }}</option>
                                                <option value='url'>{{ __('Link') }}</option>
                                                <option value='text'>{{ __('Custom Text') }}</option>
                                            </optgroup>
                                            <optgroup label="{{ __('Sections') }}">
                                                <option value='map'>{{ __('Google Map') }}</option>
                                                <option value='youtube'>{{ __('Youtube') }}</option>
                                                <option value='iframe'>{{ __('iframe') }}</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class='col-lg-1 col-md-1'>
                                    <div class='mb-3 mt-2'>
                                        <label class='form-label required'>{{ __('Icon') }}</label>
                                        <button type="button" id='iconpick` + id + `' onclick='openPicker(` + id +`)' class="btn btn-primary btn-icon text-white btn-md"><i id="displayIcon` + id + `" class="fab fa-facebook"></i></button>
                                        <input type='hidden' class='icon` + id + ` form-control' placeholder='{{ __('Choose Icon') }}' value="fab fa-facebook-f" name='icon[]' required readonly>
                                    </div>
                                </div>

                                <div class='col-lg-3 col-md-3'>
                                    <div class='mb-3 mt-2'>
                                        <label class='form-label required'>{{ __('Label / Title') }}</label>
                                        <input type='text' class='lbl` + id + ` form-control' name='label[]' placeholder='{{ __('Label') }}' required>
                                    </div>
                                </div>

                                <div class='col-lg-4 col-md-4'>
                                    <div class='mb-3 mt-2'>
                                        <label class='form-label required'>{{ __('Content') }}</label>
                                        <input type='text' class='textlbl` + id +` form-control' name='value[]' placeholder='{{ __('Type something') }}' required>
                                    </div>
                                </div>

                                <div class='col-lg-2 col-md-2'>
                                    <div class='my-3 py-4'>
                                        <button type="button" class='btn btn-danger btn-icon' onclick='removeFeature(` + id + `)'>
                                            <i class='fa fa-times text-white'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                    $("#more-features").append(features).html();

                    dynamicSelect('type' + id);
                }
            }

            function removeFeature(id) {
                "use strict";
                $("#" + id).remove();
                count--;
            }

            function getRandomInt() {
                min = Math.ceil(0);
                max = Math.floor(9999999999);
                return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
            }

            function openPicker(id) {
                "use strict";
                $("#iconpick" + id).iconpicker({
                    animation: true,
                    hideOnSelect: true,
                    input: "#iconpick" + id + ", .icon" + id + "",
                    placement: "inline",
                    templates: {
                        popover: '<div class="iconpicker-popover popover position-absolute"><div class="arrow"></div>' +
                            '<div class="popover-title"></div><div class="popover-content"></div></div>',
                        iconpickerItem: '<a role="button" class="iconpicker-item"><i></i></a>'
                    }
                });

                $("#iconpick" + id).on('iconpickerSelected', function(event) {
                    $("#displayIcon" + id).attr("class", event.iconpickerValue);
                });
            }





            function changeLabel(id) {
                "use strict";
                var label = `{{ __('Label') }}`;
                var textlabel = `{{ __('Type something') }}`;
                let icon = document.querySelector('.icon' + id);
                let lbl = document.querySelector('.lbl' + id);
                let textlbl = document.querySelector('.textlbl' + id);
                let type = document.querySelector('.type' + id).value;

                if (type == 'address') {
                    label = `{{ __('Address') }}`;
                    textlabel = `{{ __('For ex: Chennai, Tamilnadu, India') }}`;

                    icon.value = "fas fa-home";
                    lbl.value = `{{ __('Address') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-home");
                    textlbl.type = 'text';

                } else if (type == 'text') {
                    label = `{{ __('About us') }}`;
                    textlabel = `{{ __('For ex: Lorem Ipsum is simply dummy text of') }}`;

                    icon.value = "fas fa-info-circle";
                    lbl.value = `{{ __('Content') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-info-circle");
                    textlbl.type = 'text';

                } else if (type == 'email') {
                    label = `{{ __('Email Address') }}`;
                    textlabel = `{{ __('For ex: support@website.com') }}`;

                    icon.value = "fas fa-at";
                    lbl.value = `{{ __('Email') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-at");
                    textlbl.type = 'email';

                } else if (type == 'tel') {
                    label = `{{ __('Phone Number') }}`;
                    textlabel = `{{ __('For ex: +919876543210') }}`;

                    icon.value = "fas fa-phone";
                    lbl.value = `{{ __('Phone') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-phone");
                    textlbl.type = 'number';

                } else if (type == 'wa') {
                    label = `{{ __('WhatsApp') }}`;
                    textlabel = `{{ __('For ex: 919876543210') }}`;

                    icon.value = "fab fa-whatsapp";
                    lbl.value = `{{ __('WhatsApp') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-whatsapp");
                    textlbl.type = 'number';

                } else if (type == 'url') {
                    label = `{{ __('Website') }}`;
                    textlabel = `{{ __('For ex: website.com') }}`;

                    icon.value = "fas fa-link";
                    lbl.value = `{{ __('Website') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-link");
                    textlbl.type = 'url';

                } else if (type == 'youtube') {
                    label = `{{ __('Video Title') }}`;
                    textlabel = `{{ __('For ex: https://www.youtube.com/watch?v=li5ths352') }}`;

                    icon.value = "fab fa-youtube";
                    lbl.value = `{{ __('Youtube') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-youtube");
                    textlbl.type = 'url';

                } else if (type == 'map') {
                    label = `{{ __('California') }}`;
                    textlabel =
                        `{{ __("For ex: <iframe src='https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3924.7798234706065!2d77.98194106479716!3d10.359482142605264!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b00ab9baf4b4101%3A0x9d6d57a812be5cc6!2sCapsimint%20Technologies%20-%20Web%2C%20Mobile%20App%20and%20Software%20Development%20Company!5e0!3m2!1sen!2sin!4v1638283593135!5m2!1sen!2sin'></iframe>") }}`;

                    icon.value = "fas fa-location-arrow";
                    lbl.value = `{{ __('Location') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-location-arrow");
                    textlbl.type = 'text';

                } else if (type == 'facebook') {
                    label = `{{ __('Facebook') }}`;
                    textlabel = `{{ __('For ex: https://facebook.com') }}`;

                    icon.value = "fab fa-facebook-f";
                    lbl.value = `{{ __('Facebook') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-facebook-f");
                    textlbl.type = 'url';

                } else if (type == 'instagram') {
                    label = `{{ __('Instagram') }}`;
                    textlabel = `{{ __('For ex: https://instagram.com') }}`;

                    icon.value = "fab fa-instagram";
                    lbl.value = `{{ __('Instagram') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-instagram");
                    textlbl.type = 'url';

                } else if (type == 'x-twitter') {
                    label = `{{ __('Twitter') }}`;
                    textlabel = `{{ __('For ex: https://twitter.com') }}`;

                    icon.value = "fab fa-x-twitter";
                    lbl.value = `{{ __('Twitter') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-x-twitter");
                    textlbl.type = 'url';

                } else if (type == 'linkedin') {
                    label = `{{ __('LinkedIn') }}`;
                    textlabel = `{{ __('For ex: https://linkedin.com') }}`;

                    icon.value = "fab fa-linkedin-in";
                    lbl.value = `{{ __('LinkedIn') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-linkedin-in");
                    textlbl.type = 'url';

                } else if (type == 'pinterest') {
                    label = `{{ __('Pinterest') }}`;
                    textlabel = `{{ __('For ex: https://pinterest.com') }}`;

                    icon.value = "fab fa-pinterest";
                    lbl.value = `{{ __('Pinterest') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-pinterest");
                    textlbl.type = 'url';

                } else if (type == 'reddit') {
                    label = `{{ __('Reddit') }}`;
                    textlabel = `{{ __('For ex: https://reddit.com') }}`;

                    icon.value = "fab fa-reddit";
                    lbl.value = `{{ __('Reddit') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-reddit");
                    textlbl.type = 'url';

                } else if (type == 'tiktok') {
                    label = `{{ __('Tiktok') }}`;
                    textlabel = `{{ __('For ex: https://tiktok.com') }}`;

                    icon.value = "fab fa-tiktok";
                    lbl.value = `{{ __('Tiktok') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-tiktok");
                    textlbl.type = 'url';

                } else if (type == 'threads') {
                    label = `{{ __('Threads') }}`;
                    textlabel = `{{ __('For ex: https://threads.net') }}`;

                    icon.value = "fab fa-threads";
                    lbl.value = `{{ __('Threads') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-threads");
                    textlbl.type = 'url';

                } else if (type == 'snapchat') {
                    label = `{{ __('Snapchat') }}`;
                    textlabel = `{{ __('For ex: https://snapchat.com') }}`;

                    icon.value = "fab fa-snapchat";
                    lbl.value = `{{ __('Snapchat') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-snapchat");
                    textlbl.type = 'url';

                } else if (type == 'wechat') {
                    label = `{{ __('WeChat') }}`;
                    textlabel = `{{ __('For ex: https://wechat.com') }}`;

                    icon.value = "fab fa-weixin";
                    lbl.value = `{{ __('WeChat') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-weixin");
                    textlbl.type = 'url';

                } else if (type == 'telegram') {
                    label = `{{ __('Telegram') }}`;
                    textlabel = `{{ __('For ex: https://telegram.org') }}`;

                    icon.value = "fab fa-telegram";
                    lbl.value = `{{ __('Telegram') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-telegram");
                    textlbl.type = 'url';

                } else if (type == 'tumblr') {
                    label = `{{ __('Tumblr') }}`;
                    textlabel = `{{ __('For ex: https://tumblr.com') }}`;

                    icon.value = "fab fa-tumblr";
                    lbl.value = `{{ __('Tumblr') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-tumblr");
                    textlbl.type = 'url';

                } else if (type == 'qq') {
                    label = `{{ __('QQ') }}`;
                    textlabel = `{{ __('For ex: https://qq.com') }}`;

                    icon.value = "fab fa-qq";
                    lbl.value = `{{ __('QQ') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-qq");
                    textlbl.type = 'url';

                } else if (type == 'discord') {
                    label = `{{ __('Discord') }}`;
                    textlabel = `{{ __('For ex: https://discord.com') }}`;

                    icon.value = "fab fa-discord";
                    lbl.value = `{{ __('Discord') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-discord");
                    textlbl.type = 'url';

                } else if (type == 'quora') {
                    label = `{{ __('Quora') }}`;
                    textlabel = `{{ __('For ex: https://quora.com') }}`;

                    icon.value = "fab fa-quora";
                    lbl.value = `{{ __('Quora') }}`;
                    $("#displayIcon" + id).attr("class", "fab fa-quora");
                    textlbl.type = 'url';

                } else if (type == 'iframe') {
                    label = `{{ __('src Link') }}`;
                    textlabel = `{{ __('For ex: https://domain.com?feed=152856') }}`;

                    icon.value = "fas fa-rss";
                    lbl.value = `{{ __('iframe') }}`;
                    $("#displayIcon" + id).attr("class", "fas fa-rss");
                    textlbl.type = 'url';
                }

                lbl.placeholder = label;
                textlbl.placeholder = textlabel;
            }

            document.getElementById("myForm").onkeypress = function(e) {
                var key = e.charCode || e.keyCode || 0;
                if (key == 13) {
                    e.preventDefault();
                }
            }

            // Call the function on all elements with the class 'form-select' when the page loads
            $(document).ready(function() {
                // Select all elements with the class 'form-select'
                $('.defaultType').each(function() {
                    // Assuming you want to call the function 'yourFunction' on each element
                    var id = $(this).attr('id');
                    dynamicSelect(id);
                });
            });

            function dynamicSelect(id) {
                "use strict";
                var el;
                window.TomSelect && (new TomSelect(el = document.getElementById(id), {
                    copyClassesToDropdown: false,
                    dropdownClass: 'dropdown-menu ts-dropdown',
                    optionClass: 'dropdown-item',
                    controlInput: '<input>',
                    maxOptions: null,
                    render: {
                        item: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data.customProperties +
                                    '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data.customProperties +
                                    '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                    },
                }));
            }
        </script>
    @endpush
@endsection
