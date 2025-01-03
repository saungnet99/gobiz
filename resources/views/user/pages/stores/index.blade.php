@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('content')
<div class="page-wrapper">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('WhatsApp Stores') }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <span class="d-sm-inline">
                        <a href="{{ route('user.categories') }}" class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('All Categories') }}">
                            <svg class="d-lg-none d-inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-category">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h6v6h-6z" />
                                <path d="M14 4h6v6h-6z" />
                                <path d="M4 14h6v6h-6z" />
                                <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            </svg>
                            <span class="d-lg-inline d-none">{{ __('All Categories') }}</span>
                        </a>
                    </span>
                    <a href="{{ route('user.create.store') }}" class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Create new store') }}">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon d-lg-none d-inline" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        <span class="d-lg-inline d-none">{{ __('Create new store') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            {{-- Failed --}}
            @if(Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}} 
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif
            
            <div class="row row-deck row-cards">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="storesTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Created') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="w-1">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.includes.footer')
</div>

<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div>{{ __('If you proceed, you will enabled/disabled this card.')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="plan_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="openQR" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">{{ __('Scan QR')}}</div>
            </div>
            <div class="modal-body text-center qr-code"></div>
        </div>
    </div>
</div>

{{-- Duplicate Store Modal --}}
<div class="modal modal-blur fade" id="duplicateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div id="duplicate_status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="duplicate_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Delete --}}
<div class="modal modal-blur fade" id="forceDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div id="delete_status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="delete_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('scripts')
<script>
    $(document).ready(function() {
        $('#storesTable').DataTable({
            processing: false,
            serverSide: true,
            ajax: '{{ route('user.stores') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'title', name: 'title' },
                { data: 'card_status', name: 'card_status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    // Duplicate store
    function duplicateStore(id, type) {
        "use strict";

        $("#duplicateModal").modal("show");
        var duplicate_status = document.getElementById("duplicate_status");
        duplicate_status.innerHTML = "<?php echo __('If you proceed, you will duplicate this store.') ?>"
        var duplicate_link = document.getElementById("duplicate_id");
        duplicate_link.getAttribute("href");
        duplicate_link.setAttribute("href", "{{ route('user.duplicate') }}?id=" + id + "&type=" + type);
    }

    // Delete store
    function deleteStore(id, action) {
        "use strict";

        $("#forceDeleteModal").modal("show");
        var delete_status = document.getElementById("delete_status");
        delete_status.innerHTML = "<?php echo __('If you proceed, you will') ?> " + action + " <?php echo __('this store.') ?>"
        var delete_link = document.getElementById("delete_id");
        delete_link.getAttribute("href");
        delete_link.setAttribute("href", "{{ route('user.delete.store') }}?id=" + id);
    }
</script>
@endsection
@endsection