@php
use Illuminate\Support\Facades\DB;

// Queries
$config = DB::table('config')->get();
@endphp

<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2" style="z-index: 9999;">
    <div class="max-w-7xl mx-auto px-2">
        <div class="p-2 rounded-lg bg-{{ $config[11]->config_value }}-200">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 items-center table">
                    <p class="ml-3 text-black cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-2 p-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto flex space-x-2">
                    <!-- Agree Button -->
                    <button class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-gray-50 bg-{{ $config[11]->config_value }}-600 hover:bg-{{ $config[11]->config_value }}-500">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                    <!-- Deny Button -->
                    <button class="js-cookie-consent-deny cookie-consent__deny cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-gray-50 bg-gray-800 hover:bg-gray-700">
                        {{ __('cookie-consent::texts.deny', [], 'Deny') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
