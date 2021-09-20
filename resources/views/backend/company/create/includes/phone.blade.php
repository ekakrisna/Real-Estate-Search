<div id="form-group--phone" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        <span class="bg-danger label-required">@lang('label.required')</span>
        <strong class="field-title">@lang('label.phone')</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <input type="text" id="input-phone" name="phone" class="form-control @error('phone') is-invalid @enderror" 
            value="{{ old( 'phone', $item->phone ) }}" required data-parsley-phone-number />
    </div>
</div>