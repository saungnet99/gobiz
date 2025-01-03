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
                        {{ __('Edit Page') }}
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
                <div class="col-sm-12 col-lg-12">
                    <form action="{{ route('admin.update.page', Request::segment(3)) }}" method="post"
                        enctype="multipart/form-data" class="card">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @for ($i = 0; $i < count($sections); $i++) <div class="col-xl-6">
                                    <div id="section{{ $i }}" class="row">
                                        <div class="col-md-12 col-xl-12">
                                            <div class="mb-3">
                                                <label class="form-label required text-capitalize">{{
                                                    $sections[$i]->section_title == "desc" ? 'Description' :
                                                    str_replace('_', ' ', $sections[$i]->section_title) }}</label>
                                                <textarea rows="6" cols="10" class="form-control" name="section{{ $i }}"
                                                    placeholder="{{ $sections[$i]->section_title }}"
                                                    required>{{ $sections[$i]->section_content }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @endfor

                            {{-- Check footer section --}}
                            @if (Request::segment(3) != "footer")
                            <h2 class="mt-3 mb-3 page-title">
                                {{ __('SEO Configuration') }}
                            </h2>

                            {{-- Title --}}
                            <div class="col-md-6 col-xl-6">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Title')
                                        }}</label>
                                    <textarea class="form-control" name="title" rows="3" placeholder="{{ __('Title') }}"
                                        required>{{ $sections[0]->title }}</textarea>
                                </div>
                            </div>

                            {{-- Meta Description --}}
                            <div class="col-md-6 col-xl-6">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Description')
                                        }}</label>
                                    <textarea class="form-control" name="description" rows="3"
                                        placeholder="{{ __('Description') }}"
                                        required>{{ $sections[0]->description }}</textarea>
                                </div>
                            </div>

                            {{-- Keywords --}}
                            <div class="col-md-12 col-xl-12">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Keywords') }}</label>
                                    <textarea class="form-control required" name="keywords" rows="3"
                                        placeholder="{{ __('Keywords (Keyword 1, Keyword 2)') }}"
                                        required>{{ $sections[0]->keywords }}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary btn-md ms-auto">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
</div>
@endsection