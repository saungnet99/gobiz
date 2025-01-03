@php
use Illuminate\Support\Str;

if (app()->getLocale() == null) {
    Session::put('locale', $business_card_details->card_lang);
    app()->setLocale(Session::get('locale'));
}
@endphp

<div class="fixed top-0 right-0 m-4 absolute">
    <select id="language" name="language" class="block w-full bg-white border border-gray-300 text-gray-700 py-1 px-1 pr-1 rounded-md shadow-sm focus:outline-none sm:text-sm">
        @foreach(config('app.languages') as $langLocale => $langName)
            <option value="{{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}><strong>{{ Str::upper($langLocale) }}</strong></option>
        @endforeach
    </select>
</div>

{{-- Custom JS --}}
@section('custom-js')
<script>
// Language switcher
$(document).ready(function() {
    "use strict";
    // Language switcher
    $('#language').change(function() {
        var selectedLocale = $(this).val();

        $.ajax({
            url: "{{ route('set.locale') }}",  // Use the route name defined earlier
            type: "POST",
            data: {
                locale: selectedLocale,
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(response) {
                console.log(response.message); // Log success message
                // Redirect page
                window.location = ``;
            },
            error: function(xhr) {
                console.error(xhr.responseText); // Log error message
            }
        });
    });
});
</script>
@endsection