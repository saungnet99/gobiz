@php
use Illuminate\Support\Str;

if (app()->getLocale() == null) {
    Session::put('locale', $business_card_details->card_lang);
    app()->setLocale(Session::get('locale'));
}
@endphp

{{-- Languages --}}
@if(count(config('app.languages')) > 1)
<div class="nav-item dropdown mx-2">
    <div class="lang">
        <select type="text" class="form-select small-btn" placeholder="{{ __('Select a language') }}" id="language" value="">
            @foreach(config('app.languages') as $langLocale => $langName)
            <option value="{{ $langLocale }}" {{ app()->getLocale() == $langLocale ? 'selected' : '' }}><strong>{{ Str::upper($langLocale) }}</strong></option>
            @endforeach
        </select>
    </div>
</div>
@endif

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