{{-- Show appointment slots in the calendar --}}
@if ($plan_details['appointment'] == 1)
    @if ($appointment_slots != null)
        <div class="bg-black w-full mt-4 mb-4 flex justify-center align-middle py-2">
            <p class="heading font-black text-white text-xl px-4 py-2">
                {{ __('Appointment') }}</p>
        </div>
        <div class="bg-white w-full px-5 py-2">
            <!-- Error Message (hidden by default) -->
            <div id="errorMessage" class="text-red-500 text-sm mt-2 hidden">{{ __('Please select a valid date and time slot.') }}</div>

            {{-- Success Message (hidden by default) --}}
            <div id="successMessage" class="text-green-500 text-sm mt-2 hidden">{{ __('Appointment booked successfully!') }}</div>

            <!-- Error Message (hidden by default) -->
            <div id="errorSubmitMessage" class="text-red-500 text-sm mt-2 hidden">{{ __('Please fill all the fields.') }}</div>

            <div class="w-full mx-auto my-4">
                <input type="text" id="appointment-date"
                    class="flatpickr-input block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="{{ __('Select a date') }}" required>
            </div>

            <div class="w-full mx-auto my-4">
                <select id="time-slot-select"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 mt-2" required>
                    <option value="">{{ __('Select a time slot') }}</option>
                </select>
            </div>

            <div class="w-full mx-auto flex justify-center align-middle my-4">
                <button type="button" id="add-slot-button"
                    class="bg-black text-white px-4 py-2 mt-2 rounded-lg hover:bg-black transition duration-300" onclick="validateAndShowModal()">
                    {{ __('Book Appointment') }}
                </button>
            </div>
        </div>
    @endif
@endif

<!-- Modal (hidden by default) -->
<div id="appointmentModal" class="fixed inset-0 flex items-center px-3 justify-center bg-gray-800 bg-opacity-75 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ __('Book Appointment') }}</h2>
        
        <form id="appointmentForm">
            <!-- Name Field -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                <input type="text" id="name" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input type="email" id="email" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            
            <!-- Phone Field -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                <input type="text" id="phone" class="mt-1 p-2 border border-gray-300 rounded w-full" required>
            </div>
            
            <!-- Notes Field -->
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                <textarea id="notes" class="mt-1 p-2 border border-gray-300 rounded w-full" rows="3"></textarea>
            </div>

            {{-- Show price --}}
            <div class="mb-4 hidden">
                <label for="price" class="block text-sm font-medium text-gray-700">{{ __('Price') }}</label>
                <input type="text" id="price" class="mt-1 p-2 border border-gray-300 rounded w-full" disabled>
            </div>
            
            <!-- Submit and Close Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="validateAndShowModal()">{{ __('Close') }}</button>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
</div>
