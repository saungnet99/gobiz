@php
use Illuminate\Support\Facades\DB;
    
$config = DB::table('config')->get();
@endphp

<footer class="footer footer-transparent d-print-none">
    <div class="container">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                        {{ __('Copyright') }} &copy; <span id="year"></span>
                        <a href="{{ route('admin.dashboard') }}" class="link-secondary">{{ config('app.name') }} - <strong>{{ __('v') }}{{ $config[32]->config_value }}</strong></a>.
                        {{ __('All rights reserved.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
 