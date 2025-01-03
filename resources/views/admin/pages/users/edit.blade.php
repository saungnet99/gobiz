@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

{{-- Custom CSS --}}
@section('css')
<style>
    .ts-control>input {
        display: contents !important;
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
                        {{ __('Update User') }}
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
                    <form action="{{ route('admin.update.user') }}" method="post" class="card">
                        @csrf
                        <div class="card-header">
                            <h4 class="page-title">{{ __('User Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        <input type="hidden" class="form-control" name="user_id" value="{{ $user_details->user_id }}">
                                        {{-- Role --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Role') }}</div>
                                                <select class="form-select role" name="role" required>
                                                    <option value="">{{ __('Choose a role') }}</option>
                                                    <option value="3" {{ $user_details->role_id == 3 ? "selected" : "" }}>{{ __('Administrator') }}</option>
                                                    <option value="4" {{ $user_details->role_id == 4 ? "selected" : "" }}>{{ __('Manager') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Name --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Name') }}</label>
                                                <input type="text" class="form-control" name="name" value="{{ $user_details->name }}"
                                                    placeholder="{{ __('Name') }}" required>
                                            </div>
                                        </div>
                                        {{-- Email --}}
                                        <div class="col-md-6 col-xl-4">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Email') }}</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="{{ __('Email') }}" value="{{ $user_details->email }}" required>

                                            </div>
                                        </div>
                                        {{-- Password --}}
                                        <div class="col-md-6 col-xl-3">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Password') }}</label>
                                                <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}">
                                            </div>
                                        </div>
                                        
                                        {{-- Permissions --}}
                                        <h2 class="page-title my-3">
                                            {{ __('Permissions') }}
                                        </h2>
                                        {{-- Themes --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">
                                                    {{ __('Themes') }} 
                                                </div>
                                                <select class="form-select themes" name="themes">
                                                    <option value="on" {{ json_decode($user_details->permissions)->themes == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->themes == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Plans --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Plans') }}</div>
                                                <select class="form-select plans" name="plans">
                                                    <option value="on" {{ json_decode($user_details->permissions)->plans == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->plans == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Customers --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Customers') }}</div>
                                                <select class="form-select customers" name="customers">
                                                    <option value="on" {{ json_decode($user_details->permissions)->customers == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->customers == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Payment Methods --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Payment Methods') }}</div>
                                                <select class="form-select payment_methods" name="payment_methods">
                                                    <option value="on" {{ json_decode($user_details->permissions)->payment_methods == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->payment_methods == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Coupons --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Coupons') }}</div>
                                                <select class="form-select coupons" name="coupons">
                                                    <option value="on" {{ json_decode($user_details->permissions)->coupons == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->coupons == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Transactions --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Transactions') }}</div>
                                                <select class="form-select transactions" name="transactions">
                                                    <option value="on" {{ json_decode($user_details->permissions)->transactions == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->transactions == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Pages --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Pages') }}</div>
                                                <select class="form-select pages" name="pages">
                                                    <option value="on" {{ json_decode($user_details->permissions)->pages == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->pages == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Blogs --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Blogs') }}</div>
                                                <select class="form-select blogs" name="blogs">
                                                    <option value="on" {{ json_decode($user_details->permissions)->blogs == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->blogs == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Users --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Users') }}</div>
                                                <select class="form-select users" name="users">
                                                    <option value="on" {{ json_decode($user_details->permissions)->users == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->users == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- General Settings --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('General Settings') }}</div>
                                                <select class="form-select general_settings" name="general_settings">
                                                    <option value="on" {{ json_decode($user_details->permissions)->general_settings == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->general_settings == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Translations --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Translations') }}</div>
                                                <select class="form-select translations" name="translations">
                                                    <option value="on" {{ json_decode($user_details->permissions)->translations == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->translations == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Generate Sitemap --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Generate Sitemap') }}</div>
                                                <select class="form-select sitemap" name="sitemap">
                                                    <option value="on" {{ json_decode($user_details->permissions)->sitemap == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->sitemap == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Invoice & Tax --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Invoice & Tax') }}</div>
                                                <select class="form-select invoice_tax" name="invoice_tax">
                                                    <option value="on" {{ json_decode($user_details->permissions)->invoice_tax == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->invoice_tax == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Software Update --}}
                                        <div class="col-md-6 col-xl-2">
                                            <div class="mb-3">
                                                <div class="form-label">{{ __('Software Update') }}</div>
                                                <select class="form-select software_update" name="software_update">
                                                    <option value="on" {{ json_decode($user_details->permissions)->software_update == 1 ? "selected" : "" }}>{{ __('Yes') }}</option>
                                                    <option value="off" {{ json_decode($user_details->permissions)->software_update == 0 ? "selected" : "" }}>{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
</div>

{{-- Custom JS --}}
@section('scripts')
<script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
<script>
    // Array of element selectors
    var elementSelectors = ['.role'];

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
@endsection
@endsection