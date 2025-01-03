@if ($paginator->hasPages())
<div class="card pagination-card">
    <div class="card-footer pagination-card-footer d-flex align-items-center">
        <p class="m-0 text-muted">{!! __('') !!} <span>{{ $paginator->firstItem() }}</span> {!! __('to') !!}
            <span>{{ $paginator->lastItem() }}</span> {!! __('of') !!} <span>{{ $paginator->total() }}</span> {!!
            __('results') !!}
        </p>
        <nav class="custom-nav">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                <li class="btn btn-sm btn-primary li-link disabled" aria-disabled="true">
                    <span>@lang('pagination.previous')</span>
                </li>
                @else
                <li><a class="btn btn-sm btn-primary li-link" href="{{ $paginator->previousPageUrl() }}"
                        rel="prev">@lang('pagination.previous')</a></li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                <li><a class="btn btn-sm btn-primary li-link" href="{{ $paginator->nextPageUrl() }}"
                        rel="next">@lang('pagination.next')</a></li>
                @else
                <li class="btn btn-sm btn-primary li-link disabled" aria-disabled="true"><span>@lang('pagination.next')</span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif

<style>
    .btn-group-sm>.btn, .btn-sm {
        --tblr-btn-line-height: 1.5;
        --tblr-btn-icon-size: .75rem;
        margin-right: 5px;
        font-size: 12px !important;
        margin: 13px 0 10px 5px !important;
    }

    .li-link {
        padding: 10px;
        margin: 4px;
    }

    .btn.disabled, .btn:disabled, fieldset:disabled .btn {
        border-color: #0000 !important;
    }

    .custom-nav {
        position: absolute;
        right: 5px;
        top: -2px;
    }
</style>