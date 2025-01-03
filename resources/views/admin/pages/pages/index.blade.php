@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

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
                        {{ __('Pages') }}
                    </h2>
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
                <div class="col">
                    <h2 class="page-title">
                        {{ __('Static Pages') }}
                    </h2>
                </div>
                <span class="text-muted font-weight-bold">{{ __("Note: Static pages are doesn't have HTML editor. You can able to change the contents only.")}}</span>

                {{-- Static Pages --}}
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="pages-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Page') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="w-1">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Custom Pages --}}
                <div class="col mt-4">
                    <h2 class="page-title">
                        {{ __('Custom Pages') }}
                    </h2>
                </div>
                <!-- Add page -->
                <div class="col-auto ms-auto d-print-none mt-4">
                    <a type="button" href="{{ route('admin.add.page') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ __('Add New Page') }}
                    </a>
                </div>
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="custom-pages-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Page') }}</th>
                                        <th>{{ __('Slug') }}</th>
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

    {{-- Footer --}}
    @include('admin.includes.footer')
</div>

{{-- Enable/Disable Page Modal --}}
<div class="modal modal-blur fade" id="status-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div>{{ __('If you proceed, you will enable/disable this page.')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="page_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Delete Page Modal --}}
<div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div id="delete_status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="delete_qr_code_id">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('scripts')
{{-- Static pages --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#pages-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: "{{ route('admin.pages') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'page_name', name: 'page_name' },
                { data: 'url', name: 'url', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
    
    function getDisablePage(pageName) {
        "use strict";

        $("#status-modal").modal("show");
        var link = document.getElementById("page_id");
        link.getAttribute("href");
        link.setAttribute("href", "{{ route('admin.disable.page') }}?id=" + pageName);
    }
</script>

{{-- Custom pages --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('#custom-pages-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: "{{ route('admin.custom.pages') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'section_name', name: 'section_name' },
                { data: 'url', name: 'url', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
    
    function getPage(pageId) {
        "use strict";

        $("#status-modal").modal("show");
        var link = document.getElementById("page_id");
        link.getAttribute("href");
        link.setAttribute("href", "{{ route('admin.status.page') }}?id=" + pageId);
    }
    
    function deletePage(pageId, action) {
        "use strict";

        $("#delete-modal").modal("show");
        var delete_status = document.getElementById("delete_status");
        delete_status.innerHTML = "<?php echo __('If you proceed, you will') ?> " + action + " <?php echo __('this page.') ?>"
        var delete_link = document.getElementById("delete_qr_code_id");
        delete_link.getAttribute("href");
        delete_link.setAttribute("href", "{{ route('admin.delete.page') }}?id=" + pageId);
    }
</script>
@endsection
@endsection