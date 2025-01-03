<div class="offcanvas offcanvas-bottom" tabindex="-1" id="pwaModal" aria-labelledby="pwaModalLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="pwaModalLabel">{{ __('Add to Home Screen') }}</h2>
    </div>
    <div class="offcanvas-body">
        <div>
            {{ __('This website can be installed on your device. Add it to your home screen for a better experience.') }}
        </div>
        <div class="mt-3">
            <button id="addToHomeScreenButton" type="button" class="btn btn-primary">{{ __('Install') }}</button>
            <button id="closeModal" type="button" class="btn btn-secondary"
                data-bs-dismiss="offcanvas">{{ __('Cancel') }}</button>
        </div>
    </div>
</div>
