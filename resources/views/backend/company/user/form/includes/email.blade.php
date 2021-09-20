<div id="form-group--email" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        <span class="bg-danger label-required">@lang('label.required')</span>
        <strong class="field-title">@lang('label.e-mail')</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <input type="email" id="input-email" name="email" class="form-control @error('email') is-invalid @enderror" 
            value="{{ !empty( $item->email ) ? $item->email : old('email') }}" required data-parsley-trigger="focusout"
            data-parsley-email-exists="[{{ route( 'api.email.exists' )}},{{ 'edit' === $page_type ? $item->id : 0 }}]"/>
    </div>
</div>