<div id="form-group--{{ $name }}" class="row form-group{{ !empty($hide) ? ' hide' : '' }}">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) && empty($value) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-content">
        <div class="field-group clearfix @error($name) is-invalid @enderror">
            <input type="file" id="input-{{ $name }}" name="{{ $name }}" accept="{{ $accept ?? '' }}" class="input-file @error($name) is-invalid @enderror" {{ !empty($required) && empty($value) ? 'required' : '' }} />
            @if(!empty($value))
                <br/>
                <a download href="{{ Storage::url($value) }}" class="badge badge-info text-sm mt-1 p-1"><i class="fa fa-file-download"></i> @lang('label.download_file')</a>
            @endif
        </div>
    </div>
</div>
