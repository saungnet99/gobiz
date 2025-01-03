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
                        {{ __('Business Hours') }}
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
                    <form action="{{ route('user.save.business.hours', Request::segment(3)) }}" method="post"
                        class="card">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">

                                        <h2 class="page-title my-3">
                                            {{ __('Always Open') }}
                                        </h2>

                                        <div class="row">
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('24 Hours') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input class="form-check-input" id="always-open" type="checkbox"
                                                            name="always_open"> 
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-xl-3">

                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Hide Business Hours') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input id="display-hrs" class="form-check-input" type="checkbox"
                                                            onchange="displayBusiness()" name="is_display">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="business-hrs" class="row">

                                            <!-- Monday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Monday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="MondayBusiness()" name="monday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="monday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="monday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="monday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Tuesday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Tuesday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="TuesdayBusiness()" name="tuesday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="tuesday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="tuesday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="tuesday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Wednesday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Wednesday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="WednesdayBusiness()" name="wednesday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="wednesday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="wednesday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="wednesday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Thursday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Thursday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="ThursdayBusiness()" name="thursday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="thursday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="thursday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="thursday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Friday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Friday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="FridayBusiness()" name="friday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="friday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="friday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="friday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Saturday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Saturday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="SaturdayBusiness()" name="saturday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="saturday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="saturday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="saturday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sunday -->
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Sunday') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Closed') }}</div>
                                                        <label class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                onchange="SundayBusiness()" name="sunday_closed">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="sunday-business">
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="sunday_open"
                                                            placeholder="{{ __('Opening Time') }}" value="09:00">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="time" class="form-control" name="sunday_closing"
                                                            placeholder="{{ __('Closing Time') }}" value="18:30">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-12 my-3 row">
                                            <div class="mb-3">
                                                <a href="{{ route('user.appointment', Request::segment(3)) }}"
                                                    class="btn btn-primary">
                                                    {{ __('Skip') }}
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Submit') }}
                                                </button>
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
    </div>
    @include('user.includes.footer')
</div>

{{-- Custom JS --}}
@push('custom-js')
<script>
    "use strict";

    function displayBusiness() {
        var disp = $('input[name="is_display"]:checked').length;
        $("#business-hrs").toggle(disp === 0);
    }

    function toggleBusiness(day, elementID) {
        var closed = $(`input[name="${day}_closed"]:checked`).length;
        $(`#${elementID}`).toggle(closed === 0);
    }

    // Example usage:
    $(document).ready(function() {
        // Bind displayBusiness function to change event of is_display checkbox
        $('input[name="is_display"]').on('change', displayBusiness);

        // Bind toggleBusiness function to change event of each day's closed checkbox
        $('input[name$="_closed"]').on('change', function() {
            var day = $(this).attr('name').split('_')[0]; // Extract day from checkbox name
            toggleBusiness(day, `${day}-business`);
        });

        // Initial execution of displayBusiness and toggleBusiness for each day
        displayBusiness();
        ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
            toggleBusiness(day, `${day}-business`);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        "use strict";
        
        const alwaysOpenCheckbox = document.getElementById('always-open');
        const displayHrsCheckbox = document.getElementById('display-hrs');

        alwaysOpenCheckbox.addEventListener('change', function () {
            if (alwaysOpenCheckbox.checked) {
                displayHrsCheckbox.checked = false;

                // Get the business hours element
                var businessHours = document.getElementById('business-hrs');
                
                // Hide the business hours element
                businessHours.style.display = 'none';
            } else {
                // Get the business hours element
                var businessHours = document.getElementById('business-hrs');
                
                // Hide the business hours element
                businessHours.style.display = 'flex';
            }
        });

        displayHrsCheckbox.addEventListener('change', function () {
            if (displayHrsCheckbox.checked) {
                alwaysOpenCheckbox.checked = false;
            }
        });
    });

    function toggleDisplay(inputName, elementId) {
        "use strict";
        var isChecked = $('input[name="' + inputName + '"]:checked').length;
        if (isChecked == 0) {
            $(elementId).show();
        } else {
            $(elementId).hide();
        }
    }
</script>
@endpush
@endsection