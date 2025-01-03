@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('content')
    <div class="page-wrapper">
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

                <div class="card">
                    <div class="row g-0">
                        <div class="col-12 col-md-3 border-end">
                            <div class="card-body">
                                <h4 class="subheader">{{ __('Update Appointment') }}</h4>
                                <div class="list-group list-group-transparent">
                                    {{-- Nav links --}}
                                    @include('user.pages.edit-cards.includes.nav-link', [
                                        'link' => 'appointment',
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-9 flex-column">
                            <form action="{{ route('user.update.appointment', Request::segment(3)) }}" method="post"
                                class="card">
                                @csrf
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ __('Set Appointment Timings') }}</h4>
                                        {{-- Slot duration --}}
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Slot duration') }}</label>
                                            <div class="input-group mb-2">
                                                <input type="number" name="slot_duration" id="slot_duration" class="form-control" placeholder="{{ __('Slot duration in minutes. Example: 30') }}" min="10" step="5" max="60" value="{{ $appointmentSlots->first()->slot_duration ?? 30 }}">
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
                                                    <select name="time_slots[{{ $day }}][]" data-day="{{ strtolower($day) }}" class="form-select time-select" multiple></select>
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
                                            <a href="{{ route('user.edit.contact.form', Request::segment(3)) }}"
                                                class="btn btn-primary">
                                                {{ __('Skip') }}
                                            </a>
                                            <button type="submit"
                                                class="btn btn-primary btn-sm">{{ __('Save Slots') }}</button>
                                        </div>
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

    {{-- Password Protected --}}
    @push('custom-js')
        <!-- Tomselect JS -->
        <script src="{{ asset('js/tom-select.base.min.js') }}"></script>

        {{-- Dynamic change the time slots and tom-select --}}
        <script> 
            document.addEventListener('DOMContentLoaded', function() {
                const slotDurationInput = document.getElementById('slot_duration');
                const timeSelects = document.querySelectorAll('.time-select');
        
                // Assuming the PHP outputs the JSON directly
                const timeSlotsByDay = {!! $time_slots !!}; // Use the JSON-encoded variable
        
                // Initialize TomSelect for each select element and store instances
                const tomSelectInstances = Array.from(timeSelects).map(select => {
                    return new TomSelect(select, {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
                        controlInput: '<input>',
                    });
                });
        
                function generateTimeSlots(duration) {
                    if (isNaN(duration) || duration <= 0) return;
        
                    tomSelectInstances.forEach(tomSelectInstance => {
                        tomSelectInstance.clearOptions(); // Clear existing options
        
                        for (let i = 0; i < 24 * 60; i += duration) {
                            const start = String(Math.floor(i / 60)).padStart(2, '0') + ':' + String(i % 60).padStart(2, '0');
                            const endMinutes = i + duration;
                            const end = String(Math.floor(endMinutes / 60)).padStart(2, '0') + ':' + String(endMinutes % 60).padStart(2, '0');
        
                            tomSelectInstance.addOption({
                                value: `${start} - ${end}`, // Corrected string interpolation
                                text: `${start} - ${end}`,  // Corrected string interpolation
                            });
                        }
        
                        tomSelectInstance.refreshOptions(false);
                    });
                }
        
                generateTimeSlots(Math.min(parseInt(slotDurationInput.value), 60));
        
                slotDurationInput.addEventListener('change', function() {
                    let duration = Math.min(parseInt(this.value), 60);
                    tomSelectInstances.forEach(tomSelectInstance => {
                        tomSelectInstance.clear(); // Deselect all values
                    });
                    generateTimeSlots(duration);
                });
        
                tomSelectInstances.forEach(select => {
                    const day = select.input.dataset.day; // Assuming data-day is set correctly
                    
                    if (day && timeSlotsByDay[day]) {
                        try {
                            const valuesToSelect = JSON.parse(timeSlotsByDay[day]); // Convert string to array
        
                            setTimeout(() => {
                                select.setValue(valuesToSelect); // Set values directly
                            }, 100);
                        } catch (e) {
                            console.error(`Error parsing JSON for ${day}:`, e);
                        }
                    } else {
                        console.warn(`No time slots found for ${day}.`);
                    }
                });
            });
        </script>        
    @endpush
@endsection
