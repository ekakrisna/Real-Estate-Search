<div id="form-group--{{ $name }}-{{$index}}" class="row form-group{{ !empty($hide) ? ' hide' : '' }}">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) && empty($value) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }} {{ $index }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-content">
        <div class="field-group clearfix @error($name) is-invalid @enderror">
            <input type="file" id="input-{{ $name }}-{{ $index }}" name="{{$name}}[{{ $index }}]" accept="image/gif,image/jpeg,image/jpg,image/png" class="input-image @error($name . ' ' . $index ) is-invalid @enderror" {{ !empty($required) && empty($value) ? 'required' : '' }} />

            <div id="image-preview-{{$name}}-{{ $index }}" class="image-preview">
                @if(!empty($value) && empty($required))
                    <a id="remove-image-{{$name}}-{{ $index }}" class="remove-image btn btn-xs btn-default">
                        <i class="fa fa-trash"></i>
                    </a>
                @endif
                <input type="hidden" class="input-remove-image" name="{{ $name }}_removable_image[{{$index}}]" value="false" />
                <img src="{{ !empty($value) ? asset('uploads/' . $value ) : asset('img/backend/default.jpg') }}" data-default="{{ !empty($value) ? asset($value) : asset('img/backend/noimage.png') }}" data-empty="{{ asset('img/backend/noimage.png') }}" class="img-thumbnail">
            </div>
        </div>
    </div>
</div>
