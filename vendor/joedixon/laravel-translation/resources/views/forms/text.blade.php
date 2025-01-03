<div class="mb-3">
    <label class="form-label required">{{ $label }}</label>
    <input type="text" class="form-control @if($errors->has($field)) error @endif" name="{{ $field }}" id="{{ $field }}"
        placeholder="{{ isset($placeholder) ? $placeholder : '' }}" value="{{ old($field) }}">

    {{ isset($required) ? 'required' : '' }}
    @if($errors->has($field))
    @foreach($errors->get($field) as $error)
    <p class="text-danger">{!! $error !!}</p>
    @endforeach
    @endif
</div>