@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('css')
<link rel="stylesheet" href="{{ asset('css/all.css') }}" />
<link rel="stylesheet" href="{{ asset('css/bootstrap-iconpicker.min.css') }}" />
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
                                @include('user.pages.edit-cards.includes.nav-link', ['link' => 'payments'])
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 d-flex flex-column">
                        <form action="{{ route('user.update.payment.links', Request::segment(3)) }}" method="post" id="myForm">
                            @csrf
                            <div class="card-body">
                                <h3 class="card-title mb-4">{{ __('Payment Links') }}</h3>

                                <div class="row">
                                    {{-- Dynamic fields --}}
                                    @for ($i = 0; $i < count($payments); $i++) 
                                    <div class="row" id="{{ $i }}">
                                        <div class='col-lg-6 col-md-6'>
                                            <div class='mb-3 mt-2'>
                                                <label class='form-label required'>{{ __('Icon') }}</label>
                                                <div class='input-group'>
                                                    <input type='text' class='form-control'
                                                        placeholder='{{ __('Choose Icon') }}' id='iconpick{{ $i }}'
                                                        onclick='openPicker({{ $i }})' name='icon[]'
                                                        value="{{ $payments[$i]->icon }}" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-lg-6 col-md-6'>
                                            <div class='mb-3 mt-2'>
                                                <label class='form-label required'
                                                    for='type'>{{ __('Display type') }}</label>
                                                <select name='type[]' id='type'
                                                    class='type{{ $payments[$i]->id }} form-control type'
                                                    onchange='changeLabel({{ $payments[$i]->id }})' required>
                                                    <option value='' disabled selected>{{ __('Choose Type') }}
                                                    </option>
                                                    <option value='text'
                                                        {{ $payments[$i]->type == 'text' || $payments[$i]->type == 'textarea' ? 'selected' : ''}}>
                                                        {{ __('Default') }}</option>
                                                    <option value='upi'
                                                        {{ $payments[$i]->type == 'upi' ? 'selected' : ''}}>
                                                        {{ __('UPI') }}</option>
                                                    <option value='url'
                                                        {{ $payments[$i]->type == 'url' ? 'selected' : ''}}>
                                                        {{ __('Link') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-lg-6 col-md-6'>
                                            <div class='mb-3 mt-2'>
                                                <label class='form-label required'>{{ __('Label') }}</label>
                                                <input type='text' class='lbl{{ $payments[$i]->id }} form-control'
                                                    name='label[]' placeholder='{{ __('Label') }}'
                                                    value="{{ $payments[$i]->label }}" required>
                                            </div>
                                        </div>
                                        <div class='col-lg-5 col-md-5'>
                                            <div class='mb-3 mt-2'>
                                                <label class='form-label required'>{{ __('Content') }}</label>
                                                <textarea type="text"
                                                    class='textlbl{{ $payments[$i]->id }} form-control'
                                                    name='value[]' placeholder='{{ __('Type something') }}' cols="30" rows="2" required>{{ $payments[$i]->content }}</textarea>
                                            </div>
                                        </div>
                                        <div class='col-lg-1 col-md-1'>
                                            <div class='mb-3 pt-1 mt-5'>
                                                <button class='btn btn-danger btn-sm'
                                                    onclick='removePayment({{ $i }})'>
                                                    <i class='fa fa-times text-whte'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor

                                    {{-- Add new payments --}}
                                    <div id="more-payments" class="row"></div>

                                    {{-- Add button --}}
                                    <div class="col-lg-12 mb-5">
                                        <button type="button" onclick="addPayment()" class="btn btn-primary">
                                            {{ __('Add One More Payments') }}
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

{{-- Custom JS --}}
@push('custom-js')
<script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/fontawesome-iconpicker.min.js') }}"></script>
<script>
    var count = {{ count($payments) }};

    function addPayment() {
	"use strict";
    if (count>={{ $plan_details->no_of_payments}}) {
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
        var payments = `
        <div class='row' id="${id}">
            <div class='col-lg-6 col-md-6'>
                <div class='mb-3 mt-2'>
                    <label class='form-label required'>{{ __('Icon') }}</label>
                    <div class='input-group'>
                        <input type='text' class='form-control' placeholder='{{ __('Choose Icon') }}' id='iconpick${id}' onclick='openPicker(${id})' name='icon[]' required readonly>
                    </div>
                </div>
            </div>
            <div class='col-lg-6 col-md-6'>
                <div class='mb-3 mt-2'>
                    <label class='form-label required' for='type'>{{ __('Display type') }}</label>
                    <select name='type[]' id='type' class='type${id} form-control type' onchange='changeLabel(${id})' required>
                        <option value='' disabled selected>{{ __('Choose Type') }}</option>
                        <option value='text'>{{ __('Default') }}</option>
                        <option value='upi'>{{ __('UPI') }}</option>
                        <option value='url'>{{ __('Link') }}</option>
                    </select>
                </div>
            </div>
            <div class='col-lg-6 col-md-6'>
                <div class='mb-3 mt-2'>
                    <label class='form-label required'>{{ __('Label') }}</label>
                    <input type='text' class='lbl${id} form-control' name='label[]' placeholder='{{ __('Label') }}' required>
                </div>
            </div>
            <div class='col-md-5 mt-1 col-lg-5'>
                <div class='mb-3'>
                    <label class='form-label required'>{{ __('Content') }}</label>
                    <textarea class='textlbl${id} form-control' name='value[]' cols='30' rows='2' placeholder='{{ __('Type something') }}' required></textarea>
                </div>
            </div>
            <div class='col-lg-1 col-md-1'>
                <div class='mb-2 pt-1 mt-5'>
                    <button class='btn btn-danger btn-sm' onclick='removePayment(${id})'>
                        <i class='fa fa-times text-white'></i>
                    </button>
                </div>
            </div>
        </div>`;
        $("#more-payments").append(payments).html();
    }
    }

    function removePayment(id) {
	"use strict";
        $("#"+id).remove();
        count--;
    }

    function getRandomInt() {
        min = Math.ceil(0);
        max = Math.floor(9999999999);
        return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
    }

    function openPicker(id){
        "use strict";
        $("#iconpick"+id).iconpicker({ animation:true,hideOnSelect:true, placement: "inline",  templates: {    popover: '<div class="iconpicker-popover popover position-absolute"><div class="arrow"></div>' +
            '<div class="popover-title"></div><div class="popover-content"></div></div>', iconpickerItem: '<a role="button" class="iconpicker-item"><i></i></a>' } });
    }

    function changeLabel(id) {
        "use strict";
        var label = 'Label';
        var textlabel = 'Type Something';
        let lbl = document.querySelector('.lbl'+id);
        let textlbl = document.querySelector('.textlbl'+id);
        let type = document.querySelector('.type'+id).value;
        console.log(type);
        if(type == 'text') {
            label = `{{ __('Bank')}}`;
            textlabel = `{{ __('Your bank account details') }}`;
        } else if(type == 'upi') {
            label = `{{ __('UPI') }}`;
            textlabel = `{{ __('For ex: YOUR UPI ID') }}`;
        } else if(type == 'url') {
            label = `{{ __('Payment Link') }}`;
            textlabel = `{{ __('For ex: https://rzp.io/i/nxrHnLJ') }}`;
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
</script>
<script>
    // Array of element selectors
    var elementSelectors = ['.type'];

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
