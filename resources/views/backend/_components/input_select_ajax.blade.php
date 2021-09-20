<div id="form-group--{{ $name }}" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        @php
            if( !empty( old($name . '_label') ) ){
                $options    = [old($name . '_label')];
                $value      = old($name);
            }
        @endphp
        <select data-url="{{ $url }}" type="text" id="input-{{ $name }}" name="{{ $name }}" class="select2ajax form-control @error($name) is-invalid @enderror" {{ !empty($required) ? 'required' : '' }}>
            @foreach($options as $id => $label)
                <option value="{{ $value }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="hidden" name="{{ $name }}_label" id="input-{{ $name }}-selected-label" class="selected-label" />
    </div>
</div>
