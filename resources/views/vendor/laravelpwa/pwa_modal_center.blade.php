<div id="pwaModal" class="fixed inset-x-0 lg:bottom-0 bottom-14 z-50 p-3 overflow-auto hidden bg-black bg-opacity-50">
    <div class="flex items-end justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 lg:w-2/5">
            <!-- Modal content -->
            <div class="mb-4">
                <h1 class="text-xl font-semibold mb-3">{{ __('Add to Home Screen') }}</h1>
                <p class="text-gray-700 text-sm">
                    {{ __('This website can be installed on your device. Add it to your home screen for a better experience.') }}
                </p>
            </div>
            <div class="flex justify-end">
                <button id="addToHomeScreenButton"
                    class="px-4 py-2 text-white bg-gray-800 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">{{ __('Install') }}</button>
                <button id="closeModal"
                    class="ml-2 px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-500 focus:outline-none focus:bg-gray-500">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>
