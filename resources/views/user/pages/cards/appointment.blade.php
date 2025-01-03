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
                            {{ __('Appointment') }}
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

                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('user.save.appointment', Request::segment(3)) }}" method="post"
                            class="card">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">{{ __('Set Appointment Timings') }}</h4>
                                    {{-- Slot duration --}}
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Slot duration') }}</label>
                                        <div class="input-group mb-2">
                                            <input type="number" name="slot_duration" id="slot_duration" class="form-control" placeholder="{{ __('Slot duration in minutes. Example: 30') }}" min="10" step="5" max="60" value="30">
                                            <span class="input-group-text">
                                              {{ __('minutes') }}
                                            </span>
                                        </div>
                                        <small>{{ __('Max duration is 60 minutes.') }}</small>
                                    </div>

                                    {{-- Days of the week --}}
                                    <div class="mb-3">
                                        <h3>{{ __('Days of the week') }}</h3>
                                    </div>
                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <div class="mb-3">
                                            <div class="form-label">{{ __($day) }}</div>
                                            <div class="input-group mb-2">
                                                {{-- Appointment timings --}}
                                                <select name="time_slots[{{ $day }}][]" class="form-select time-select" multiple>
                                                    <option value="" disabled selected>{{ __('Choose your time slots (multiple)') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Price --}}
                                    <div class="mb-3">
                                        <h3>{{ __('Price') }}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">{{ __('Fee') }}</label>
                                        <div class="input-group mb-2">
                                            <input type="number" name="price" id="price" class="form-control" placeholder="{{ __('Price') }}" value="0" min="0" step=".001" required>
                                            <span class="input-group-text">
                                              {{ __('per slot') }}
                                            </span>
                                        </div>
                                        <small>{{ __('Set the price 0 for free') }}</small>
                                    </div>
                                </div>
                                {{-- Buttons --}}
                                <div class="card-footer bg-transparent mt-auto">
                                    <div class="btn-list justify-content-end">
                                        <a href="{{ route('user.contact.form', Request::segment(3)) }}"
                                            class="btn btn-primary">
                                            {{ __('Skip') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Slots') }}</button>
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
@endsection

{{-- Custom JS --}}
@push('custom-js')
    <!-- Tomselect JS -->
    <script src="{{ asset('js/tom-select.base.min.js') }}"></script>

    {{-- Dynamic change the time slots and tom-select --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slotDurationInput = document.getElementById('slot_duration');
            const timeSelects = document.querySelectorAll('.time-select');
    
            // Initialize TomSelect for each select element and store instances
            const tomSelectInstances = Array.from(timeSelects).map(select => {
                return new TomSelect(select, {
                    copyClassesToDropdown: false,
                    dropdownParent: 'body',
                    controlInput: '<input>',
                });
            });
    
            function generateTimeSlots(duration) {
                // Check if the duration is valid
                if (isNaN(duration) || duration <= 0) {
                    return;
                }
    
                tomSelectInstances.forEach(tomSelectInstance => {
                    // Clear existing options
                    tomSelectInstance.clearOptions();
    
                    // Generate new time slots based on the duration
                    for (let i = 0; i < 24 * 60; i += duration) {
                        const start = String(Math.floor(i / 60)).padStart(2, '0') + ':' + String(i % 60).padStart(2, '0');
                        const endMinutes = i + duration;
                        const end = String(Math.floor(endMinutes / 60)).padStart(2, '0') + ':' + String(endMinutes % 60).padStart(2, '0');
    
                        tomSelectInstance.addOption({
                            value: `${start} - ${end}`,
                            text: `${start} - ${end}`,
                        });
                    }
    
                    // Refresh the dropdown to display the new options
                    tomSelectInstance.refreshOptions(false);
                });
            }
    
            // Generate initial time slots with the default value.
            generateTimeSlots(Math.min(parseInt(slotDurationInput.value), 60));
    
            // Update time slots whenever the slot duration changes.
            slotDurationInput.addEventListener('change', function() {
                let duration = parseInt(this.value);
                duration = Math.min(duration, 60); // Ensure the duration does not exceed 60 minutes.
                generateTimeSlots(duration);
            });
        });
    </script>
@endpush

{{-- Custom CSS --}}
@section('css')
    
@endsection
