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
        <div class="row">
            <div class="col-md-10">
                <input type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }} />
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-default" id="flag_btn" href="{{ route('admin.changeCustomerFlag', $id) }}">
                    <i class="{{ $value2=='True' ? "fas fa-flag" : "far fa-flag" }}"></i>
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// change flag and it's value
    $("#flag_btn").on("click", function(){
        event.preventDefault();
        let link = $(this).attr('href');
        let icon = $(this).children();

        $.get( link, function(data){
            if(data.flag == 1) {
                icon.removeClass('far fa-flag').addClass('fas fa-flag');
            } else if(data.flag == 0) {
                icon.removeClass('fas fa-flag').addClass('far fa-flag');
            }
        });


    });
</script>
@endpush