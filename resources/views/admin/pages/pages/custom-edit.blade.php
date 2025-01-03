@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

{{-- Custom CSS --}}
@section('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js" integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

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
                        {{ __('Edit Page') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            {{-- Error --}}
            <div class="alert alert-important alert-danger alert-dismissible d-none" role="alert" id="fillError">
                <div class="d-flex">
                    <div>
                        {{ __('Fill the all the required fields') }}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>

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
                {{-- Update page --}}
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.update.custom.page') }}" method="post" enctype="multipart/form-data"
                        class="card" id="customPageForm">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">

                                        <input type="hidden" class="form-control" value="{{ $page->id }}" name="page_id"
                                            required />

                                        {{-- Page Name --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Page Name') }}</div>
                                                <input type="text" class="form-control text-capitalize" name="page_name"
                                                    id="page_name" value="{{ $page->section_name }}"
                                                    placeholder="{{ __('Page Name') }}" required />
                                            </div>
                                        </div>

                                        {{-- Slug --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Slug') }}</div>
                                                <input type="text" class="form-control" name="slug" id="slug"
                                                    value="{{ $page->section_title }}" placeholder="{{ __('Slug') }}"
                                                    required />
                                            </div>
                                        </div>

                                        {{-- Body --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Body') }}</div>
                                                <textarea name="body" id="body" cols="30" rows="5"
                                                    class="form-control text-capitalize" placeholder="{{ __('Body') }}"
                                                    required>{{ $page->section_content }}</textarea>
                                            </div>
                                        </div>

                                        <h2 class="page-title mt-3 mb-3">
                                            {{ __('SEO Configurations') }}
                                        </h2>

                                        {{-- Title --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Title')
                                                    }}</label>
                                                <textarea class="form-control text-capitalize" name="title" rows="2"
                                                    placeholder="{{ __('Title') }}"
                                                    required>{{ $page->title }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Meta Description --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Description') }}</div>
                                                <textarea name="description" id="description" rows="2"
                                                    class="form-control text-capitalize"
                                                    placeholder="{{ __('Description') }}"
                                                    required>{{ $page->description }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Meta Keywords --}}
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <div class="form-label required">{{ __('Keywords') }}</div>
                                                <textarea name="keywords" id="keywords" rows="3"
                                                    class="form-control text-capitalize"
                                                    placeholder="{{ __('Keywords') }}"
                                                    required>{{ $page->keywords }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary btn-md ms-auto">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('admin.includes.footer')
</div>

{{-- Custom JS --}}
@section('scripts')
<script>
    // Convert slug
    $('#page_name').on("change keyup paste click", function() {
    "use strict";
    var Text = $(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
    $('#slug').val(Text);
});

tinymce.init({
    selector: 'textarea#body',
    plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount charmap quickbars emoticons',
    menubar: 'file edit view insert format tools',
    toolbar: 'code undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview save print | insertfile link anchor | ltr rtl',
    content_style: 'body { font-family:Times New Roman,Arial,sans-serif; font-size:16px }',
    menubar: false,
    statusbar: false,
});

$('#customPageForm').on('submit', function(e) {
    "use strict";
    if($('#body').summernote('isEmpty')) {
        $('#fillError').attr("class", "alert alert-important alert-danger alert-dismissible");
        e.preventDefault();
    }
});
</script>
@endsection
@endsection