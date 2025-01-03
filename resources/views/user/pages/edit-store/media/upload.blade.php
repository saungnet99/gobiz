{{-- Upload multiple images --}}
<div class="col-sm-12 col-lg-12 mb-4">
    <form action="{{ route('user.multiple') }}" class="dropzone" id="dropzone" enctype="multipart/form-data">
        @csrf
        <div class="dz-message">
            {{ __('Drag and Drop Single/Multiple Files Here') }} <br>
        </div>
    </form>
</div>
