@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

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
        <div class="container-xl mt-3">
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
            
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ __('Choosed Plan') }} : {{ __($plan_details->plan_name) }}</h3>
                        <button id="rzp-button1" class="btn btn-primary">{{ __('Pay Now') }}</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.includes.footer')
</div>
@push('custom-js')
<script type="text/javascript" src="{{ asset('js/razorpay-checkout.js') }}"></script>
<script>
    ! function() {
                    "use strict";
                    var options = {
                        "key": "{{ $config[6]->config_value }}",
                        "amount": "{{ $order->amount }}",
                        "currency": "{{ $order->currency }}",
                        "name": "{{ env('APP_NAME') }}",
                        "description": "Upgrade Package",
                        "image": "{{ asset($settings->favicon) }}",
                        "order_id": "{{ $order->id }}",
                        "handler": function(response) {
                            window.location = "../../razorpay-payment-status/" + response.razorpay_order_id + "/" + response
                                .razorpay_payment_id;
                        },
                        "prefill": {
                            "name": "{{ Auth::user()->name }}",
                            "email": "{{ Auth::user()->email }}",
                            "contact": ""
                        },
                        "notes": {
                            "gobiz_transaction_id": "{{ $gobiz_transaction_id }}"
                        },
                        "theme": {
                            "color": "#613BBB"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed',
                        function(response) {
                            window.location = "../../razorpay-payment-status/" + response.error.metadata.order_id + "/" + response
                                .error
                                .metadata.payment_id;
                        });
                    document.getElementById('rzp-button1').onclick = function(e) {
                        rzp1.open();
                        e.preventDefault();
                    }
                }();
</script>
@endpush
@endsection