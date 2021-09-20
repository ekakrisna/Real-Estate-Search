<div id="form-group--{{ $name }}" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <p>{{ $label  }}</p>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <div class="field-group clearfix @error($name) is-invalid @enderror">
            @php
                if(!empty($is_bool_value_reverse)){
                    $index=1;
                };
            @endphp
            @foreach($options as $option)
                @php
                    if(!empty($is_indexed_value)){
                        $current_value = $loop->index + 1 ;
                    }elseif(!empty($is_bool_value)){
                        $current_value = $loop->index ;
                    }elseif(!empty($is_bool_value_reverse)){
                        $current_value = $index;
                        $index= $index-1;
                    }else{
                        $current_value = $option;
                    }
                @endphp
                <div class="icheck-cyan d-inline">
                    <input type="radio" value="{{ $current_value }}" id="input-{{ $name }}-{{ $loop->index }}" name="{{ $name }}" {{ !empty($loop->first) && (!empty($value) || $value == "") ? "checked" : "" }} {{ $value == $current_value ? "checked" : "" }} />
                    <label for="input-{{ $name }}-{{ $loop->index }}" class="text-uppercase mr-5">{{ $option }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
