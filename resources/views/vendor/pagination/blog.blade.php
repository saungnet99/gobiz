@php
    use Illuminate\Support\Facades\DB;

    $config = DB::table('config')->get();
@endphp

@if ($paginator->hasPages())
    <div class="w-full py-8">
        <nav>
            <ul class="inline-flex">
                <!-- Previous Button -->
                @if ($paginator->onFirstPage())
                    <li>
                        <a
                            class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700 rounded-l">
                            {{ __('Previous') }}
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700 rounded-l">{{ __('Previous') }}</a>
                    </li>
                @endif

                <!-- Page Numbers -->
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled">
                            {{ $element }}
                        </li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <a href="#"
                                        class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700 active my-active">{{ $page }}</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}"
                                        class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                <!-- Next Button -->
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700 rounded-r">{{ __('Next') }}</a>
                    </li>
                @else
                    <li>
                        <a
                            class="px-3 py-2 bg-{{ $config[11]->config_value }}-200 text-{{ $config[11]->config_value }}-700 rounded-r">
                            {{ __('Next') }}
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
