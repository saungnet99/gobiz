@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

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
                            {{ __('Checkout') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            @if ($selected_plan == null)
                <div class="container-xl mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{ __('No Plan Found') }}</h3>
                                <a href="{{ route('user.pages.checkout', Request::segment(3)) }}"
                                    class="btn btn-primary">{{ __('Back') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="container-xl mt-3">
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
                        <div class="col-lg-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">{{ __('Upgrade/Renewal Plan') }}</h3>
                                    <div class="card-table table-responsive">
                                        <table class="table table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">{{ __('Description') }}</th>
                                                    <th class="w-1">{{ __('Price') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            {{ __($selected_plan->plan_name) }} -
                                                            {{ __($selected_plan->validity) }}
                                                            {{ __('Days') }}
                                                        </div>
                                                    </td>
                                                    <td class="text-bold text-end">
                                                        {{ $currency->symbol }}{{ $selected_plan->plan_price == '0' ? 0 : number_format($selected_plan->plan_price, 2) }}
                                                    </td>
                                                </tr>
                                                @if ($config[25]->config_value > 0)
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                {{ __($config[24]->config_value) }}
                                                                ({{ $config[25]->config_value }}%)
                                                            </div>
                                                        </td>
                                                        <td class="text-bold text-end">
                                                            {{ $currency->symbol }}{{ number_format(((float) $selected_plan->plan_price * (float) $config[25]->config_value) / 100, 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="d-none" id="appliedCoupon"></tr>
                                                <tr>
                                                    <td class="h5 text-bold"> {{ __('Total Payable Amount') }} </td>
                                                    <td class="w-1 text-bold h3 text-end" id="total">
                                                        {{ $currency->symbol }}{{ number_format($total, 2) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Coupon Code -->
                                    <form id="couponForm" class="my-3">
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control text-uppercase"
                                                placeholder="{{ __('Coupon Code') }}"
                                                value="{{ old('coupon_code') ?? $coupon_code }}" name="coupon_code" id="coupon_code">
                                            <button type="submit" class="btn btn-primary mx-2" id="applyCoupon">{{ __('Apply') }}</button>
                                        </div>
                                        <p class="fw-bold mt-2" id="discountMessage"></p>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <form action="{{ route('prepare.payment.gateway', $selected_plan->plan_id) }}" method="post">
                                @csrf
                                <div class="col-lg-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="row">
                                                    <h3 class="card-title text-muted mb-3">{{ __('Billing Details') }}</h1>
                                                    <input type="hidden" name="applied_coupon" id="applied_coupon" class="form-control">
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('Name') }}</label>
                                                            <input type="text" class="form-control" name="billing_name" placeholder="{{ __('Name') }}" value="{{ Auth::user()->billing_name == null ? Auth::user()->name : Auth::user()->billing_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('Email') }}</label>
                                                            <input type="email" class="form-control"
                                                                name="billing_email" placeholder="{{ __('Email') }}"
                                                                value="{{ Auth::user()->billing_email == null ? Auth::user()->email : Auth::user()->billing_email }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('Phone') }}</label>
                                                            <input type="tel" class="form-control"
                                                                name="billing_phone" placeholder="{{ __('Phone') }}"
                                                                value="{{ Auth::user()->billing_phone }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('Address') }}</label>
                                                            <input type="tel" class="form-control"
                                                                name="billing_address" id="billing_address"
                                                                placeholder="{{ __('Address') }}"
                                                                value="{{ Auth::user()->billing_address }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('City') }}</label>
                                                            <input type="text" class="form-control"
                                                                name="billing_city" placeholder="{{ __('City') }}"
                                                                value="{{ Auth::user()->billing_city }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xl-4">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('State/Province') }}</label>
                                                            <input type="text" class="form-control"
                                                                name="billing_state"
                                                                placeholder="{{ __('State/Province') }}"
                                                                value="{{ Auth::user()->billing_state }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xl-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">{{ __('Zip Code') }}</label>
                                                            <input type="text" class="form-control"
                                                                name="billing_zipcode"
                                                                placeholder="{{ __('Zip Code') }}"
                                                                value="{{ Auth::user()->billing_zipcode }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xl-6">
                                                        {{-- Include countries --}}
                                                        @include('user.pages.checkout.includes.countries')
                                                    </div>
                                                    <div class="col-md-6 col-xl-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required" for="type">{{ __('Type') }}</label>
                                                            <select name="type" id="type"
                                                                class="form-control" required>
                                                                <option value="personal"
                                                                    {{ Auth::user()->type == 'personal' ? 'selected' : '' }}>
                                                                    {{ __('Personal') }}</option>
                                                                <option value="business"
                                                                    {{ Auth::user()->type == 'business' ? 'selected' : '' }}>
                                                                    {{ __('Business') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xl-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('Tax Number') }} </label>
                                                            <input type="text" class="form-control"
                                                                name="vat_number"
                                                                placeholder="{{ __('Tax Number') }}"
                                                                value="{{ Auth::user()->vat_number }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title text-muted">{{ __('Payment method') }}</h3>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            @foreach ($gateways as $gateway)
                                                                <div class="col-lg-4 mb-3">
                                                                    <div
                                                                        class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                                                        <label class="form-selectgroup-item flex-fill">
                                                                            <input type="radio"
                                                                                name="payment_gateway_id"
                                                                                value="{{ $gateway->payment_gateway_id }}"
                                                                                class="form-selectgroup-input">
                                                                            <div
                                                                                class="form-selectgroup-label d-flex align-items-center p-3">
                                                                                <div class="me-3">
                                                                                    <span
                                                                                        class="form-selectgroup-check"></span>
                                                                                </div>
                                                                                <span class="avatar me-3"
                                                                                    style="background-image: url({{ asset($gateway->payment_gateway_logo) }})"></span>
                                                                                <div>
                                                                                    <div class="font-weight-medium h4">
                                                                                        {{ __($gateway->display_name) }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <input type="submit" value="{{ __('Continue for payment') }}"
                                                            class="btn btn-primary">
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
            @endif
        </div>
        @include('user.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/tom-select.base.min.js') }}"></script>
    <script src="{{ asset('js/confetti.browser.min.js') }}"></script>
    <script>
        // Array of element IDs
        var elementIds = ['billing_country', 'type'];

        // Loop through each element ID
        elementIds.forEach(function(id) {
            // Check if the element exists
            var el = document.getElementById(id);
            if (el) {
                // Apply TomSelect to the element
                new TomSelect(el, {
                    copyClassesToDropdown: false,
                    dropdownClass: 'dropdown-menu ts-dropdown',
                    optionClass: 'dropdown-item',
                    controlInput: '<input>',
                    maxOptions: null,
                    render: {
                        item: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                    },
                });
            }
        });
    </script>
@endsection
<script>
    document.getElementById('couponForm').addEventListener('submit', function (e) {
        "use strict";
        e.preventDefault();  // Prevent form from submitting the traditional way
        
        let form = this;
        let formData = new FormData(form);
        let couponCodeInput = document.getElementById('coupon_code');
        let appliedCoupon = document.getElementById('appliedCoupon');
        let discountMessage = document.getElementById('discountMessage');
        let applied_coupon = document.getElementById('applied_coupon');
        let applyCoupon = document.getElementById('applyCoupon');

        fetch('{{ route('user.checkout.coupon', $selected_plan->plan_id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // trigger confetti
                confetti({
                    particleCount: 200,
                    spread: 900,
                    colors: ['#f1c40f', '#f39c12', '#e67e22', '#e74c3c', '#9b59b6', '#8e44ad', '#7f8c8d'],
                });

                couponCodeInput.classList.remove('is-invalid');
                couponCodeInput.classList.add('is-valid');
                appliedCoupon.classList.remove('d-none');

                // Update the table with coupon code and discount
                let newRow = `
                    <tr>
                        <td>{{ __('Coupon Code') }} : <strong>${data.coupon_code}</strong></td>
                        <td class="text-bold text-end">- {{ $currency->symbol }}${parseFloat(data.discountPrice).toFixed(2)}</td>
                    </tr>
                `;
                appliedCoupon.innerHTML = newRow;  // Replace the existing table with the new one

                // Display discount message
                discountMessage.innerHTML = '{{ __('Coupon applied!') }}';

                // Update the total
                document.getElementById('total').innerHTML = '{{ $currency->symbol }}' + data.total.toFixed(2);

                // Update the coupon code input
                applied_coupon.value = data.coupon_id;
            } else {
                couponCodeInput.classList.remove('is-valid');
                couponCodeInput.classList.add('is-invalid');
                appliedCoupon.classList.add('d-none');
                appliedCoupon.innerHTML = "";  // Replace the existing table with the new one
                discountMessage.innerHTML = data.message;
                // Update the total
                document.getElementById('total').innerHTML = '{{ $currency->symbol }}{{ number_format($total, 2) }}';

                // Update the total
                applied_coupon.value = "";
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
