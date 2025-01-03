@php
use App\User;
use App\Plan;
use App\BusinessCard;
use Carbon\Carbon;

// Card details
$business_card = BusinessCard::where('card_id', Request::segment(3))->first();

// Fetch the user plan
$plan = User::where('id', Auth::user()->id)->where('status', 1)->first();
$planData = json_decode($plan->plan_details, true);

if ($planData) {
    // Fetch the default plan details only once if necessary
    if (!$planData || !isset($planData['appointment'])) {
        $planDefaults = Plan::where('plan_id', $plan->plan_id)->first();
    }

    // Check and assign missing plan details
    $planData['appointment'] = $planData['appointment'] ?? $planDefaults->appointment;

    // Update plan details if necessary
    if ($planData !== json_decode($plan->plan_details, true)) {
        $plan->plan_details = json_encode($planData);
        $plan->updated_at = Carbon::now();
        $plan->save();
    }

    // Fetch the updated plan details
    $plan_details = json_decode($plan->plan_details, true);
}
@endphp

<a href="{{ route("user.edit.card", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'basic' ? 'active' : '' }}">{{
    __('Basic Details') }}</a>

<a href="{{ route("user.edit.social.links", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'links' ? 'active' : '' }}">{{
    __('Social Links') }}</a>

@if ($business_card->type == "business")
<a href="{{ route("user.edit.payment.links", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'payments' ? 'active' : '' }}">{{
    __('Payment Links') }}</a>

<a href="{{ route("user.edit.services", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'services' ? 'active' : '' }}">{{
    __('Services') }}</a>

<a href="{{ route("user.edit.vproducts", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'products' ? 'active' : '' }}">{{
    __('Products') }}</a>

<a href="{{ route("user.edit.galleries", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'galleries' ? 'active' : '' }}">{{
    __('Galleries') }}</a>

<a href="{{ route("user.edit.testimonials", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'testimonial' ? 'active' : '' }}">{{
    __('Testimonials') }}</a>

@if ($plan_details['business_hours'] == 1)
<a href="{{ route("user.edit.business.hours", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'hours' ? 'active' : '' }}">{{
    __('Business Hours') }}</a>
@endif

@if ($plan_details['appointment'] == 1)
<a href="{{ route("user.edit.appointment", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'appointment' ? 'active' : '' }}">{{
    __('Appointment') }}</a>
@endif

@if ($plan_details['contact_form'] == 1)
<a href="{{ route("user.edit.contact.form", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'contact' ? 'active' : '' }}">{{
    __('Contact Form') }}</a>
@endif

@if ($plan_details['password_protected'] == 1 || $plan_details['advanced_settings'] == 1)
<a href="{{ route("user.edit.advanced.setting", Request::segment(3)) }}"
    class="list-group-item list-group-item-action d-flex align-items-center {{ $link == 'advanced' ? 'active' : '' }}">{{
    __('Advanced Settings') }}</a>
@endif
@endif