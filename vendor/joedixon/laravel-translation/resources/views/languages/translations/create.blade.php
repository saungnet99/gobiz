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
                        {{ __('translation::translation.add_translation') }}
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
                        <form action="{{ route('languages.translations.store', $language) }}" method="POST">
                            <div class="card-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="col-lg-3">
                                    @include('translation::forms.text', ['field' => 'group', 'label' =>
                                    __('translation::translation.group_label'), 'placeholder' =>
                                    __('translation::translation.group_placeholder')])

                                </div>

                                <div class="col-lg-3">
                                    @include('translation::forms.text', ['field' => 'key', 'label' =>
                                    __('translation::translation.key_label'), 'placeholder' =>
                                    __('translation::translation.key_placeholder')])
                                </div>

                                <div class="col-lg-3">
                                    @include('translation::forms.text', ['field' => 'value', 'label' =>
                                    __('translation::translation.value_label'), 'placeholder' =>
                                    __('translation::translation.value_placeholder')])
                                </div>

                                <div class="col-lg-3 mt-3">
                                    <button v-on:click="toggleAdvancedOptions" class="btn btn-primary btn-md">{{
                                        __('Advanced options') }}</button>
                                </div>

                                <div class="col-lg-3 mt-3" v-show="showAdvancedOptions">
                                    @include('translation::forms.text', ['field' => 'namespace', 'label' =>
                                    __('translation::translation.namespace_label'), 'placeholder' =>
                                    __('translation::translation.namespace_placeholder')])
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