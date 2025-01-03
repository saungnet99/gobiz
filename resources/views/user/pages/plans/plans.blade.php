@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
    <style>
        .icon-color {
            color: forestgreen;
            font-size: 20px;
            display: inline;
        }

        .ti {
            font-size: 16px !important;
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
                            {{ __('Plans') }}
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
            
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">{{ __('My plan') }}</h3>

                            @if (isset($active_plan))

                                @if ($active_plan->plan_price == 0)
                                    <p class="text-uppercase"><b>{{ __($active_plan->plan_name) }}</b></p>
                                    <p>{{ __('FREE PLAN') }}</p>
                                @else
                                    <p class="text-uppercase"><b>{{ __($active_plan->plan_name) }}</b></p>
                                    @if ($active_plan->validity == 9999)
                                        <p>{{ __('Lifetime') }}</p>
                                    @else
                                        <p>{{ $remaining_days > 0 ? __('Remaining Days') . ' : ' . $remaining_days : __('Plan Expired!') }}
                                        </p>
                                    @endif
                                @endif

                                <div class="card-text">
                                    @if ($free_plan == 0 || $active_plan->plan_price != 0)
                                        <a href="{{ route('user.checkout', $active_plan->plan_id) }}" class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-rotate" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19.95 11a8 8 0 1 0 -.5 4m.5 5v-5h-5"></path>
                                            </svg>
                                            {{ __('Renew') }}
                                        </a>
                                    @endif
                                    <a href="#plans" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-circle-arrow-up-filled" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-4.98 3.66l-.163 .01l-.086 .016l-.142 .045l-.113 .054l-.07 .043l-.095 .071l-.058 .054l-4 4l-.083 .094a1 1 0 0 0 1.497 1.32l2.293 -2.293v5.586l.007 .117a1 1 0 0 0 1.993 -.117v-5.585l2.293 2.292l.094 .083a1 1 0 0 0 1.32 -1.497l-4 -4l-.082 -.073l-.089 -.064l-.113 -.062l-.081 -.034l-.113 -.034l-.112 -.02l-.098 -.006z"
                                                stroke-width="0" fill="currentColor"></path>
                                        </svg>
                                        {{ __('Upgrade') }}
                                    </a>
                                </div>
                            @else
                                <p>{{ __('No active plans!') }}</p>

                                <div class="card-text">
                                    <a href="#plans" class="btn btn-primary">{{ __('Choose plan') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="plans" class="page-body">
                <div class="container-xl">

                    <div class="row">

                        @foreach ($plans as $plan)
                            <div class="col-sm-6 col-lg-4 mb-3">
                                <div class="card card-md">

                                    @if ($plan->recommended == '1')
                                        <div class="ribbon ribbon-top ribbon-bookmark bg-green">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        <span
                                            class="badge bg-primary text-white">{{ __($plan->plan_type == 'BOTH' ? 'VCARD & STORE' : $plan->plan_type) }}</span>
                                        <div class="text-capitalize font-weight-bold h2">{{ __($plan->plan_name) }}</div>
                                        <div class="my-3">
                                            <h3 class="display-4">
                                                <strong>
                                                    {{ $plan->plan_price == '0' ? '' : $currency->symbol }}{{ $plan->plan_price == '0' ? __('FREE') : $plan->plan_price }}
                                                </strong>
                                            </h3>

                                            <small class="text-capitalize">
                                                @if ($plan->validity == '9999')
                                                    {{ __('Per') }} {{ __('Forever') }}
                                                @endif
                                                @if ($plan->validity == '31')
                                                    {{ __('Per') }} {{ __('Month') }}</span>
                                                @endif
                                                @if ($plan->validity == '366')
                                                    {{ __('Per') }} {{ __('Year') }}</span>
                                                @endif
                                                @if ($plan->validity > '1' && $plan->validity != '31' && $plan->validity != '366' && $plan->validity != '9999')
                                                    {{ __('Per') . ' ' . $plan->validity . ' ' . __('Days') }}
                                                @endif
                                            </small>
                                        </div>
                                        <hr>
                                        <p class="mt-3">{{ __($plan->plan_description) }}</p>
                                        <ul class="list-unstyled lh-lg">
                                            {{-- Check Card type is "Both" or "VCARD" --}}
                                            @if ($plan->plan_type == 'BOTH' || $plan->plan_type == 'VCARD')
                                                <h4 class="mb-3 text-primary">{{ __('vCard Features') }}</h4>

                                                <li><i class="ti ti-{{ __($plan->no_of_vcards > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_vcards == 999 ? __('Unlimited') : $plan->no_of_vcards }}
                                                        {{ __('vCards') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_services > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_services == 999 ? __('Unlimited') : $plan->no_of_services }}
                                                        {{ __('Services') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_vcard_products > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_vcard_products == 999 ? __('Unlimited') : $plan->no_of_vcard_products }}
                                                        {{ __('Products') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_links > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_links == 999 ? __('Unlimited') : $plan->no_of_links }}
                                                        {{ __('Links') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_payments > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_payments == 999 ? __('Unlimited') : $plan->no_of_payments }}
                                                        {{ __('Payment Listed') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_galleries > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_galleries == 999 ? __('Unlimited') : $plan->no_of_galleries }}
                                                        {{ __('Galleries') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_testimonials > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_testimonials == 999 ? __('Unlimited') : $plan->no_testimonials }}
                                                        {{ __('Testimonials') }}</strong></li>
                                                <li><i
                                                        class="ti ti-{{ __($plan->business_hours == 1 ? 'check' : 'x') }} text-{{ $plan->business_hours == 1 ? 'green' : 'red' }}"></i>
                                                    <strong>{{ __('Business Hours') }}</strong></li>
                                                <li><i
                                                        class="ti ti-{{ __($plan->appointment == 1 ? 'check' : 'x') }} text-{{ $plan->appointment == 1 ? 'green' : 'red' }}"></i>
                                                    <strong>{{ __('Appointments') }}</strong></li>
                                                <li><i
                                                        class="ti ti-{{ __($plan->contact_form == 1 ? 'check' : 'x') }} text-{{ $plan->contact_form == 1 ? 'green' : 'red' }}"></i>
                                                    <strong>{{ __('Contact Form') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_enquires > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_enquires == 999 ? __('Unlimited') : $plan->no_of_enquires }}
                                                        <strong>{{ __('Enquiries') }}</strong></li>
                                                <li><i
                                                        class="ti ti-{{ __($plan->password_protected == 1 ? 'check text-green' : 'x text-red') }}"></i>
                                                    {{ __('Password Protected') }}</strong></li>
                                            @endif

                                            {{-- Check Card type is "Both" or "STORE" --}}
                                            @if ($plan->plan_type == 'BOTH' || $plan->plan_type == 'STORE')
                                                <h4 class="mt-3 mb-3 text-primary">{{ __('Store Features') }}</h4>

                                                <li><i class="ti ti-{{ __($plan->no_of_stores > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_stores == '999' ? __('Unlimited') : $plan->no_of_stores }}
                                                        {{ __('Stores') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_categories > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_categories == '999' ? __('Unlimited') : $plan->no_of_categories }}
                                                        {{ __('Categories') }}</strong></li>
                                                <li><i class="ti ti-{{ __($plan->no_of_store_products > 0 ? 'check text-green' : 'x text-red') }} me-1"></i><strong>{{ $plan->no_of_store_products == '999' ? __('Unlimited') : $plan->no_of_store_products }}
                                                        {{ __('Products') }}</strong></li>
                                            @endif

                                            {{-- Additional Features --}}
                                            <h4 class="mt-3 mb-3 text-primary">{{ __('Additional Features') }}</h4>

                                            <li><i
                                                    class="ti ti-{{ __($plan->advanced_settings == 1 ? 'check' : 'x') }} text-{{ $plan->advanced_settings == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Advanced Settings') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->pwa == 1 ? 'check' : 'x') }} text-{{ $plan->pwa == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Progressive Web App (PWA)') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->personalized_link == 1 ? 'check' : 'x') }} text-{{ $plan->personalized_link == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Personalized Link') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->additional_tools == 1 ? 'check' : 'x') }} text-{{ $plan->additional_tools == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Additional Tools') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->hide_branding == 1 ? 'check' : 'x') }} text-{{ $plan->hide_branding == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Hide Branding') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->free_setup == 1 ? 'check' : 'x') }} text-{{ $plan->free_setup == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Free Setup') }}</strong></li>
                                            <li><i
                                                    class="ti ti-{{ __($plan->free_support == 1 ? 'check' : 'x') }} text-{{ $plan->free_support == 1 ? 'green' : 'red' }}"></i>
                                                <strong>{{ __('Free Support') }}</strong></li>
                                        </ul>
                                        <div class="text-center mt-4">
                                            @if ($free_plan == 0 || $plan->plan_price != '0')
                                                <a class="open-plan-model btn btn-outline-primary w-100"
                                                    data-id="{{ $plan->plan_id }}"
                                                    href="#openPlanModel">{{ __('Choose plan') }}</a>
                                            @else
                                                <a class="down-plan-model btn btn-outline-primary w-100"
                                                    data-id="{{ $plan->plan_id }}"
                                                    href="#downPlanModel">{{ __('Choose plan') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        @include('user.includes.footer')
    </div>

    <div class="modal modal-blur fade" id="planModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>
                        {{ __('If you upgrade or downgrade from your current plan, It will temporarily disable your old cards. You need to enable it manually.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="plan_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="downPlanModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title text-muted">{{ __('UNABLE TO DOWNGRADE') }}</div>
                    <div class="mb-2">{{ __("Because you are already activated the 'Free' plan.") }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
