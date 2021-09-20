<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
    <strong class="field-title">{{ $label }}</strong>
    @if( !empty($required) )
        <span class="label-attach required">@lang('label.required')</span>
    @else
        <span class="label-attach optional">@lang('label.optional')</span>
    @endif
</div>
