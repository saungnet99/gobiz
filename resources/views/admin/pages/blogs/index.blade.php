@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title"> 
                        {{ __('Blogs') }}
                    </h2>
                </div>
                <!-- Add Blog -->
                <div class="col-auto ms-auto d-print-none">
                    <a type="button" href="{{ route('admin.create.blog') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        {{ __('Create') }}
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

                        {{-- Blogs --}}
                        <div class="table-responsive px-2 py-2">
                            <table class="table table-vcenter card-table" id="blogs-table">
                                <thead>
                                    <tr>
                                        <th width="5%">{{ __('S.No') }}</th>
                                        <th width="12%">{{ __('Date') }}</th>
                                        <th width="5%">{{ __('Category') }}</th>
                                        <th width="10%">{{ __('Tags') }}</th>
                                        <th width="20%">{{ __('Title') }}</th>
                                        <th width="23%">{{ __('Short description') }}</th>
                                        <th width="10%">{{ __('Status') }}</th>
                                        <th width="15%" class="w-1">{{ __('Actions') }}</th>
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

{{-- Action modal --}}
<div class="modal modal-blur fade" id="action-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title">{{ __('Are you sure?')}}</div>
                <div id="action_status"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">{{
                    __('Cancel')}}</button>
                <a class="btn btn-danger" id="blogId">{{ __('Yes, proceed')}}</a>
            </div>
        </div>
    </div>
</div>

{{-- Custom JS --}}
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#blogs-table').DataTable({
            processing: false, // Disable processing indicator
            serverSide: true,
            ajax: "{{ route('admin.blogs') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'blogCategory', name: 'blogCategory.blog_category_title' },
                { data: 'tags', name: 'tags', orderable: false, searchable: false },
                { data: 'heading', name: 'heading' },
                { data: 'short_description', name: 'short_description' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[1, 'desc']] // Default order by created_at descending
        });
    });
    
    function getBlog(blogId, blogStatus) {
        "use strict";

        $("#action-modal").modal("show");
        var delete_status = document.getElementById("action_status");
        delete_status.innerHTML = "<?php echo __('If you proceed, you will') ?> " + blogStatus + " <?php echo __('this blog.') ?>"
        var actionLink = document.getElementById("blogId");
        actionLink.getAttribute("href");
        actionLink.setAttribute("href", "{{ route('admin.action.blog') }}?id=" + blogId + "&mode=" + blogStatus);
    }
</script>
@endsection
@endsection