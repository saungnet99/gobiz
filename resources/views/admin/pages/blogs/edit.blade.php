@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

{{-- Custom CSS --}}
@section('css')
<!-- Bootstrap Tags Input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
{{-- Tiny MCE --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js" integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
.bootstrap-tagsinput .tag {
    margin-right: 2px;
    color: #ffffff;
    background: #206bc4;
    border-radius: 5px;
    padding: 5px;
    margin-top: 10px;
}

.cover-preview {
    width: 50% !important;
}
</style>
@endsection

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
                            {{ __('Update Blog') }}
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
                    {{-- Save Blog --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.update.blog', $blogDetails->blog_id) }}" method="post" enctype="multipart/form-data" class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">

                                            {{-- Blog Cover --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Cover') }}</div>
                                                    <input type="file" class="form-control" name="blog_cover" id="cover" accept=".jpeg,.jpg,.png,.webp" />
                                                </div>
                                            </div>

                                            {{-- Cover Preview --}}
                                            <div class="col-md-6 col-xl-6 mb-3">
                                                <div class="form-label">{{ __('Cover Image Preview') }}</div>
                                                <a target="_blank" id="divCoverImg">
                                                    <img id="coverPreview" src="{{ asset($blogDetails->cover_image)}}" class="card-img-top cover-preview">
                                                </a>
                                            </div>

                                            {{-- Blog Name --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Name') }}</label>
                                                    <input type="text" class="form-control text-capitalize" name="blog_name" id="blog_name" maxlength="200" value="{{ $blogDetails->heading }}" placeholder="{{ __('Eg: Blog title') }}" required>
                                                </div>
                                            </div>

                                            {{-- Blog Slug --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Slug') }}</label>
                                                    <input type="text" class="form-control" name="blog_slug" id="blog_slug" maxlength="200" value="{{ $blogDetails->slug }}" placeholder="{{ __('Eg: blog-url') }}" required>
                                                </div>
                                            </div>

                                            {{-- Short description --}}
                                            <div class="col-md-12 col-xl-12">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Short description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="short_description" id="short_description" placeholder="{{ __('Eg: Blog title') }}" cols="30" rows="3" required>{{ $blogDetails->short_description }}</textarea>
                                                </div>
                                            </div>

                                            {{-- Long description --}}
                                            <div class="col-md-12 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Description') }}</div>
                                                    <textarea name="long_description" id="long_description" cols="30" rows="5" class="form-control text-capitalize" placeholder="{{ __('Description') }}">{{ $blogDetails->long_description }}</textarea>
                                                </div>
                                            </div>

                                            {{-- Categories --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Categories') }}</label>
                                                    <select class="form-select" placeholder="{{ __('Choose a category') }}" name="category_id" id="category_id" required>
                                                        <option value="" selected disabled>{{ __('Choose a category') }}</option>
                                                        @foreach ($blogsCategories as $blogsCategory)
                                                            <option value="{{ $blogsCategory->blog_category_id }}" {{ $blogsCategory->blog_category_id == $blogDetails->category ? 'selected' : '' }}>{{ __($blogsCategory->blog_category_title) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Tags --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Tags') }}</label>
                                                    <input type="text" class="form-control" name="tags" id="tags" placeholder="{{ __('tag 1, tag 2') }}" value="{{ $blogDetails->tags }}" required>
                                                    <small class="form-text text-muted">{{ __('Press Enter to add a tag.') }}</small>
                                                </div>
                                                <div id="tagsContainer"></div>
                                            </div>

                                            <h2 class="page-title mt-3 mb-3">
                                                {{ __('SEO Configurations') }}
                                            </h2>
    
                                            {{-- Title --}}
                                            <div class="col-md-12 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Title') }}</div>
                                                    <textarea name="seo_title" id="seo_title" cols="30" rows="1"
                                                        class="form-control text-capitalize" placeholder="{{ __('Title') }}"
                                                        required>{{ $blogDetails->title }}</textarea>
                                                </div>
                                            </div>
    
                                            {{-- Description --}}
                                            <div class="col-md-12 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Description') }}</div>
                                                    <textarea name="seo_description" id="seo_description" cols="30" rows="5"
                                                        class="form-control" placeholder="{{ __('Description') }}"
                                                        required>{{ $blogDetails->description }}</textarea>
                                                </div>
                                            </div>
    
                                            {{-- Keywords --}}
                                            <div class="col-md-12 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Keywords') }}</div>
                                                    <input type="text" class="form-control" value="{{ $blogDetails->keywords }}" name="seo_keywords" placeholder="{{ __('Keywords') }}"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="d-flex">
                                    <a href="{{ route('admin.blog.categories') }}"
                                        class="btn btn-outline-primary btn-md">{{ __('Cancel') }}</a>
                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        {{ __('Update') }}
                                    </button>
                                </div>
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
<script src="{{ asset('js/lightgallery.min.js') }}"></script>
<!-- Bootstrap Tags Input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
    // Preview Cover
    $(document).ready(() => {
        "use strict";

        const coverInp = $("#cover");
        let imgURL;

        coverInp.change(function(e) {
            imgURL = URL.createObjectURL(e.target.files[0]);

            var coverImg = document.getElementById("coverPreview");
            coverImg.getAttribute("src");
            coverImg.setAttribute("src", imgURL);

            // New blank
            var divCoverImg = document.getElementById("divCoverImg");
            divCoverImg.getAttribute("href");
            divCoverImg.setAttribute("href", imgURL);
        });
    });

    // Convert slug
    $('#blog_name').on("change keyup paste click", function() {
        "use strict";
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
        $('#blog_slug').val(Text);
    });

    // HTML Editor
    tinymce.init({
        selector: '#long_description',
        plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount charmap quickbars emoticons',
        menubar: 'file edit view insert format tools',
        toolbar: 'code undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview save print | insertfile link anchor | ltr rtl',
        content_style: 'body { font-family:Times New Roman,Arial,sans-serif; font-size:16px }',
        menubar: false,
        statusbar: false,
    });

    // Tags
    $(document).ready(function() {
        "use strict";

        $('#tags').tagsinput({
            confirmKeys: [13, 188] // Allows pressing Enter or comma to add a tag
        });

        $(".bootstrap-tagsinput input").addClass('form-control d-inline');
        $(".bootstrap-tagsinput").addClass('col-md-12 col-xl-12');
    });
</script>
@endsection
@endsection
