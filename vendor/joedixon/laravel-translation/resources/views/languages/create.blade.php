@extends('translation::layout')

@section('body')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                @include('translation::notifications')

                <div class="col">
                    <h2 class="page-title">
                        {{ __('translation::translation.add_language') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('languages.store') }}" method="POST">
                            <div class="card-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="col-md-6 col-xl-4">
                                    @include('translation::forms.text', ['field' => 'name', 'label' =>
                                    __('translation::translation.language_name'), ])
                                </div>

                                <div class="col-md-6 col-xl-4">
                                    @include('translation::forms.text', ['field' => 'locale', 'label' =>
                                    __('translation::translation.locale'), ])
                                </div>
                            </div>

                            <div class="card-footer">
                                <button class="btn btn-primary">
                                    {{ __('translation::translation.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection